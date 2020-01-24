<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\RestaurantSpace;

class RestaurantSpaceApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/restaurant_spaces', $restaurantSpace
        );

        $this->assertApiResponse($restaurantSpace);
    }

    /**
     * @test
     */
    public function test_read_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/restaurant_spaces/'.$restaurantSpace->id
        );

        $this->assertApiResponse($restaurantSpace->toArray());
    }

    /**
     * @test
     */
    public function test_update_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->create();
        $editedRestaurantSpace = factory(RestaurantSpace::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/restaurant_spaces/'.$restaurantSpace->id,
            $editedRestaurantSpace
        );

        $this->assertApiResponse($editedRestaurantSpace);
    }

    /**
     * @test
     */
    public function test_delete_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/restaurant_spaces/'.$restaurantSpace->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/restaurant_spaces/'.$restaurantSpace->id
        );

        $this->response->assertStatus(404);
    }
}
