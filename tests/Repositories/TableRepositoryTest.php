<?php namespace Tests\Repositories;

use App\Models\Table;
use App\Repositories\TableRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TableRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TableRepository
     */
    protected $tableRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tableRepo = \App::make(TableRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_table()
    {
        $table = factory(Table::class)->make()->toArray();

        $createdTable = $this->tableRepo->create($table);

        $createdTable = $createdTable->toArray();
        $this->assertArrayHasKey('id', $createdTable);
        $this->assertNotNull($createdTable['id'], 'Created Table must have id specified');
        $this->assertNotNull(Table::find($createdTable['id']), 'Table with given id must be in DB');
        $this->assertModelData($table, $createdTable);
    }

    /**
     * @test read
     */
    public function test_read_table()
    {
        $table = factory(Table::class)->create();

        $dbTable = $this->tableRepo->find($table->id);

        $dbTable = $dbTable->toArray();
        $this->assertModelData($table->toArray(), $dbTable);
    }

    /**
     * @test update
     */
    public function test_update_table()
    {
        $table = factory(Table::class)->create();
        $fakeTable = factory(Table::class)->make()->toArray();

        $updatedTable = $this->tableRepo->update($fakeTable, $table->id);

        $this->assertModelData($fakeTable, $updatedTable->toArray());
        $dbTable = $this->tableRepo->find($table->id);
        $this->assertModelData($fakeTable, $dbTable->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_table()
    {
        $table = factory(Table::class)->create();

        $resp = $this->tableRepo->delete($table->id);

        $this->assertTrue($resp);
        $this->assertNull(Table::find($table->id), 'Table should not exist in DB');
    }
}
