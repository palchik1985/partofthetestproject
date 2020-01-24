<?php namespace Tests\Repositories;

use App\Models\RestaurantSpace;
use App\Repositories\RestaurantSpaceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class RestaurantSpaceRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var RestaurantSpaceRepository
     */
    protected $restaurantSpaceRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->restaurantSpaceRepo = \App::make(RestaurantSpaceRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->make()->toArray();

        $createdRestaurantSpace = $this->restaurantSpaceRepo->create($restaurantSpace);

        $createdRestaurantSpace = $createdRestaurantSpace->toArray();
        $this->assertArrayHasKey('id', $createdRestaurantSpace);
        $this->assertNotNull($createdRestaurantSpace['id'], 'Created RestaurantSpace must have id specified');
        $this->assertNotNull(RestaurantSpace::find($createdRestaurantSpace['id']), 'RestaurantSpace with given id must be in DB');
        $this->assertModelData($restaurantSpace, $createdRestaurantSpace);
    }

    /**
     * @test read
     */
    public function test_read_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->create();

        $dbRestaurantSpace = $this->restaurantSpaceRepo->find($restaurantSpace->id);

        $dbRestaurantSpace = $dbRestaurantSpace->toArray();
        $this->assertModelData($restaurantSpace->toArray(), $dbRestaurantSpace);
    }

    /**
     * @test update
     */
    public function test_update_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->create();
        $fakeRestaurantSpace = factory(RestaurantSpace::class)->make()->toArray();

        $updatedRestaurantSpace = $this->restaurantSpaceRepo->update($fakeRestaurantSpace, $restaurantSpace->id);

        $this->assertModelData($fakeRestaurantSpace, $updatedRestaurantSpace->toArray());
        $dbRestaurantSpace = $this->restaurantSpaceRepo->find($restaurantSpace->id);
        $this->assertModelData($fakeRestaurantSpace, $dbRestaurantSpace->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_restaurant_space()
    {
        $restaurantSpace = factory(RestaurantSpace::class)->create();

        $resp = $this->restaurantSpaceRepo->delete($restaurantSpace->id);

        $this->assertTrue($resp);
        $this->assertNull(RestaurantSpace::find($restaurantSpace->id), 'RestaurantSpace should not exist in DB');
    }
}
