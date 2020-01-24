<?php namespace Tests\Repositories;

use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class RestaurantRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var RestaurantRepository
     */
    protected $restaurantRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->restaurantRepo = \App::make(RestaurantRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_restaurant()
    {
        $restaurant = factory(Restaurant::class)->make()->toArray();

        $createdRestaurant = $this->restaurantRepo->create($restaurant);

        $createdRestaurant = $createdRestaurant->toArray();
        $this->assertArrayHasKey('id', $createdRestaurant);
        $this->assertNotNull($createdRestaurant['id'], 'Created Restaurant must have id specified');
        $this->assertNotNull(Restaurant::find($createdRestaurant['id']), 'Restaurant with given id must be in DB');
        $this->assertModelData($restaurant, $createdRestaurant);
    }

    /**
     * @test read
     */
    public function test_read_restaurant()
    {
        $restaurant = factory(Restaurant::class)->create();

        $dbRestaurant = $this->restaurantRepo->find($restaurant->id);

        $dbRestaurant = $dbRestaurant->toArray();
        $this->assertModelData($restaurant->toArray(), $dbRestaurant);
    }

    /**
     * @test update
     */
    public function test_update_restaurant()
    {
        $restaurant = factory(Restaurant::class)->create();
        $fakeRestaurant = factory(Restaurant::class)->make()->toArray();

        $updatedRestaurant = $this->restaurantRepo->update($fakeRestaurant, $restaurant->id);

        $this->assertModelData($fakeRestaurant, $updatedRestaurant->toArray());
        $dbRestaurant = $this->restaurantRepo->find($restaurant->id);
        $this->assertModelData($fakeRestaurant, $dbRestaurant->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_restaurant()
    {
        $restaurant = factory(Restaurant::class)->create();

        $resp = $this->restaurantRepo->delete($restaurant->id);

        $this->assertTrue($resp);
        $this->assertNull(Restaurant::find($restaurant->id), 'Restaurant should not exist in DB');
    }
}
