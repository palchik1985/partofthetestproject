<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Restaurant;

class RestaurantApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_restaurant()
    {
        $restaurant = factory(Restaurant::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/restaurants', $restaurant
        );

        $this->assertApiResponse($restaurant);
    }

    /**
     * @test
     */
    public function test_read_restaurant()
    {
        $restaurant = factory(Restaurant::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/restaurants/'.$restaurant->id
        );

        $this->assertApiResponse($restaurant->toArray());
    }

    /**
     * @test
     */
    public function test_update_restaurant()
    {
        $restaurant = factory(Restaurant::class)->create();
        $editedRestaurant = factory(Restaurant::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/restaurants/'.$restaurant->id,
            $editedRestaurant
        );

        $this->assertApiResponse($editedRestaurant);
    }

    /**
     * @test
     */
    public function test_delete_restaurant()
    {
        $restaurant = factory(Restaurant::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/restaurants/'.$restaurant->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/restaurants/'.$restaurant->id
        );

        $this->response->assertStatus(404);
    }
}
