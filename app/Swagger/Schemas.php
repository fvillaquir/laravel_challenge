// app/Swagger/Schemas.php
namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="domicilio_entrega", type="string", example="123 Calle Prueba"),
 *     @OA\Property(property="domicilio_facturacion", type="string", example="123 Calle Prueba"),
 *     @OA\Property(property="total", type="number", format="float", example=100.50),
 *     @OA\Property(property="status", type="string", example="pendiente"),
 *     @OA\Property(
 *         property="products",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Product")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-25T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-25T12:34:56Z")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Producto A"),
 *     @OA\Property(property="description", type="string", example="Descripción del producto A"),
 *     @OA\Property(property="price", type="number", format="float", example=10.50),
 *     @OA\Property(property="stock", type="integer", example=50),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-08-25T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-08-25T12:34:56Z")
 * )
 */
