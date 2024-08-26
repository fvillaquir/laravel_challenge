<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\Jobs\SendOrderEmail;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;


/**
 * @OA\Info(title="Api de Ordenes", version="1.0.0")
 */
class OrderController extends Controller
{
    /**
    * @OA\Post(
    *     path="/compra",
    *     summary="Crear una nueva orden de compra",
    *     description="Este endpoint permite crear una nueva orden de compra, validando los datos ingresados y asociando productos a la orden. También envía un correo electrónico de confirmación al usuario.",
    *     operationId="createOrder",
    *     tags={"Ordenes"},
    *     @OA\RequestBody(
    *         required=true,
    *         @OA\JsonContent(
    *             required={"user_id", "domicilio_entrega", "domicilio_facturacion", "products"},
    *             @OA\Property(property="user_id", type="integer", example=1, description="ID del usuario que realiza la compra"),
    *             @OA\Property(property="domicilio_entrega", type="string", example="123 Calle Prueba, Buenos Aires", description="Domicilio de entrega"),
    *             @OA\Property(property="domicilio_facturacion", type="string", example="123 Calle Prueba, Buenos Aires", description="Domicilio de facturación"),
    *             @OA\Property(
    *                 property="products",
    *                 type="array",
    *                 @OA\Items(
    *                     type="object",
    *                     @OA\Property(property="id", type="integer", example=1, description="ID del producto"),
    *                     @OA\Property(property="cantidad", type="integer", example=2, description="Cantidad de unidades del producto")
    *                 )
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=201,
    *         description="Orden creada exitosamente",
    *         @OA\JsonContent(ref="/app/Swagger/Schemas/Order")
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Error de validación",
    *         @OA\JsonContent(
    *             @OA\Property(property="errors", type="object", example={"user_id": {"El campo user_id es obligatorio."}})
    *         )
    *     ),
    *     @OA\Response(
    *         response=500,
    *         description="Error interno del servidor",
    *         @OA\JsonContent(
    *             @OA\Property(property="error", type="string", example="Error al crear la orden")
    *         )
    *     )
    * )
    */

    public function compra(Request $request)
    {    
        // Validamos los valores ingresados a la api y en caso de que no supere la validacion retornamos un error
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'domicilio_entrega' => 'required|string',
                'domicilio_facturacion' => 'required|string',
                'products' => 'required|array',
                'products.*.id' => 'required|exists:products,id',
                'products.*.cantidad' => 'required|integer|min:1',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }

        DB::beginTransaction();

        try {

            // Creamos la orden e inicializamos los valores
            $order = Order::create([
                'user_id' => $validatedData['user_id'],
                'domicilio_entrega' => $validatedData['domicilio_entrega'],
                'domicilio_facturacion' => $validatedData['domicilio_facturacion'],
                'total' => 0, // Esto se calculará luego
                'status' => 'pendiente',
            ]);

            $total = 0;

            // Calculamos el precio actual del total de la orden y actualziamos los valores dependientes entre la relacion de uno a muchos de ambas tablas
            foreach ($validatedData['products'] as $productData) {
                $product = Product::find($productData['id']);
                $order->products()->attach($product->id, [
                    'cantidad' => $productData['cantidad'],
                    'precio' => $product->precio,
                ]);

                $total += $product->precio * $productData['cantidad'];
            }

            $order->update(['total' => $total]);
            
            // Despachar el Job para enviar el correo electrónico
            SendOrderEmail::dispatch($order);

            DB::commit();

            return response()->json($order->load('products'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear la orden'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/orden/{id}",
     *     summary="Obtener detalles de una orden por ID",
     *     description="Este endpoint permite obtener los detalles de una orden específica, incluyendo los productos asociados, utilizando el ID de la orden.",
     *     operationId="getOrderById",
     *     tags={"Ordenes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la orden"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la orden",
     *         @OA\JsonContent(ref="/app/Swagger/Schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Orden no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No existe")
     *         )
     *     )
     * )
     */

    public function orden($id)
    {
        // Definir una clave única para la cache
        $cacheKey = "order_{$id}";

        // Intentamos obtener la orden desde la memoria cache, si no existe buscamos la orden por el ID en nuestra bd, incluyendo los productos relacionados
        $order = Cache::remember($cacheKey, 3600, function () use ($id) {
            return Order::with('products')->find($id);
        });

        // Verificamos si la orden existe
        if (!$order) {
            return response()->json(['message' => 'No existe'], Response::HTTP_NOT_FOUND);
        }

        // Devolvemos la orden como JSON
        return response()->json($order);
    }
}
