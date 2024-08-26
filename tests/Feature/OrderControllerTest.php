<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Order;
use App\User;
use App\Product;
use Illuminate\Support\Facades\Bus;
use App\Jobs\SendOrderEmail;

class OrderControllerTest extends TestCase
{
    /** @test */
    public function puede_crear_una_orden()
    {
        // Crear la orden con datos válidos
        $response = $this->postJson('/api/compra', [
            'user_id' => 3,
            'domicilio_entrega' => '123 Calle Test',
            'domicilio_facturacion' => '123 Calle Test',
            'products' => [
                [
                    'id' => 2,
                    'cantidad' => 2,
                ]
            ],
        ]);

        // Verificar que la respuesta es correcta
        $response->assertStatus(201);
        $response->assertJson([
            'user_id' => 3,
            'domicilio_entrega' => '123 Calle Test',
            'domicilio_facturacion' => '123 Calle Test',
            'total' => 59.98,
        ]);

        // Verificar que la orden fue creada en la base de datos
        $this->assertDatabaseHas('orders', [
            'user_id' => 3,
            'domicilio_entrega' => '123 Calle Test',
            'domicilio_facturacion' => '123 Calle Test',
        ]);
    }

    /** @test */
    public function puede_recuperar_una_orden()
    {
        // Realizar la solicitud GET para obtener la orden
        $response = $this->getJson("/api/orden/1");

        // Verificar que la respuesta es correcta
        $response->assertStatus(200);
        $response->assertJson([
            'id' => 1,
            'user_id' => 3,
            'domicilio_entrega' => 'Rosario 2263',
            'domicilio_facturacion' => 'Rosario 2263',
            'total' => 179.93,
            'estado' => 'pendiente',
        ]);
    }

    /** @test */
    public function retorna_404_si_no_existe_la_orden()
    {
        // Realizar la solicitud GET para obtener una orden que no existe
        $response = $this->getJson('/api/orden/999');

        // Verificar que la respuesta es correcta
        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'No existe',
        ]);
    }
}
