<?php namespace Tests\Repositories;

use App\Models\TablesForDate;
use App\Repositories\TablesForDateRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TablesForDateRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TablesForDateRepository
     */
    protected $tablesForDateRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tablesForDateRepo = \App::make(TablesForDateRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->make()->toArray();

        $createdTablesForDate = $this->tablesForDateRepo->create($tablesForDate);

        $createdTablesForDate = $createdTablesForDate->toArray();
        $this->assertArrayHasKey('id', $createdTablesForDate);
        $this->assertNotNull($createdTablesForDate['id'], 'Created TablesForDate must have id specified');
        $this->assertNotNull(TablesForDate::find($createdTablesForDate['id']), 'TablesForDate with given id must be in DB');
        $this->assertModelData($tablesForDate, $createdTablesForDate);
    }

    /**
     * @test read
     */
    public function test_read_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->create();

        $dbTablesForDate = $this->tablesForDateRepo->find($tablesForDate->id);

        $dbTablesForDate = $dbTablesForDate->toArray();
        $this->assertModelData($tablesForDate->toArray(), $dbTablesForDate);
    }

    /**
     * @test update
     */
    public function test_update_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->create();
        $fakeTablesForDate = factory(TablesForDate::class)->make()->toArray();

        $updatedTablesForDate = $this->tablesForDateRepo->update($fakeTablesForDate, $tablesForDate->id);

        $this->assertModelData($fakeTablesForDate, $updatedTablesForDate->toArray());
        $dbTablesForDate = $this->tablesForDateRepo->find($tablesForDate->id);
        $this->assertModelData($fakeTablesForDate, $dbTablesForDate->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->create();

        $resp = $this->tablesForDateRepo->delete($tablesForDate->id);

        $this->assertTrue($resp);
        $this->assertNull(TablesForDate::find($tablesForDate->id), 'TablesForDate should not exist in DB');
    }
}
