<?php namespace Tests\Repositories;

use App\Models\Reserve;
use App\Repositories\ReserveRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ReserveRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var ReserveRepository
     */
    protected $reserveRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->reserveRepo = \App::make(ReserveRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_reserve()
    {
        $reserve = factory(Reserve::class)->make()->toArray();

        $createdReserve = $this->reserveRepo->create($reserve);

        $createdReserve = $createdReserve->toArray();
        $this->assertArrayHasKey('id', $createdReserve);
        $this->assertNotNull($createdReserve['id'], 'Created Reserve must have id specified');
        $this->assertNotNull(Reserve::find($createdReserve['id']), 'Reserve with given id must be in DB');
        $this->assertModelData($reserve, $createdReserve);
    }

    /**
     * @test read
     */
    public function test_read_reserve()
    {
        $reserve = factory(Reserve::class)->create();

        $dbReserve = $this->reserveRepo->find($reserve->id);

        $dbReserve = $dbReserve->toArray();
        $this->assertModelData($reserve->toArray(), $dbReserve);
    }

    /**
     * @test update
     */
    public function test_update_reserve()
    {
        $reserve = factory(Reserve::class)->create();
        $fakeReserve = factory(Reserve::class)->make()->toArray();

        $updatedReserve = $this->reserveRepo->update($fakeReserve, $reserve->id);

        $this->assertModelData($fakeReserve, $updatedReserve->toArray());
        $dbReserve = $this->reserveRepo->find($reserve->id);
        $this->assertModelData($fakeReserve, $dbReserve->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_reserve()
    {
        $reserve = factory(Reserve::class)->create();

        $resp = $this->reserveRepo->delete($reserve->id);

        $this->assertTrue($resp);
        $this->assertNull(Reserve::find($reserve->id), 'Reserve should not exist in DB');
    }
}
