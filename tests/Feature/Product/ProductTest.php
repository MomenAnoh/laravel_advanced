<?php
namespace Tests\Feature\Product;
use App\Models\product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function testProductIndex()
    {
        $this->getJson('/api/product')->assertStatus(200);
    }
    public function testProductStore()
    {
        $payload=[
            'name'=>'test',
            'des'=>'test',
            'image'=>'test',
            'quantity'=>1,
            'price'=>5,
        ];
       $response= $this->postJson('/api/product',$payload)
           ->assertStatus(200)
           ->assertJsonFragment($payload);
          $this->assertDatabaseHas('products',$payload);

    }
    public function testProductUpdate()
    {
        $data=Product::create([
            'name'=>'ddddddd',
            'des'=>'dddd',
            'image'=>'fffffff',
            'quantity'=>1,
            'price'=>5,
        ]);
        $payload=[
            'name'=>'Momen',
            'des'=>'Momen'
        ];

      $this->postJson("/api/product/{$data->id}",
          $payload)
          ->assertStatus(200)
          ->assertJsonFragment($payload);
      $this->assertDatabaseHas('products', $payload);

    }
    public function testProductDestroy()
    {
        $data=Product::create([
            'name'=>'ddddddd',
            'des'=>'dddd',
            'image'=>'fffffff',
            'quantity'=>1,
            'price'=>5,
        ]);
        $this->deleteJson("/api/product/{$data->id}")
            ->assertStatus(200);
        $this->assertDatabaseMissing('products', $data->toArray());
    }
}

