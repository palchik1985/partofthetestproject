<?php namespace Tests\Repositories;

use App\Models\TableGroup;
use App\Repositories\TableGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TableGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TableGroupRepository
     */
    protected $tableGroupRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tableGroupRepo = \App::make(TableGroupRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_table_group()
    {
        $tableGroup = factory(TableGroup::class)->make()->toArray();

        $createdTableGroup = $this->tableGroupRepo->create($tableGroup);

        $createdTableGroup = $createdTableGroup->toArray();
        $this->assertArrayHasKey('id', $createdTableGroup);
        $this->assertNotNull($createdTableGroup['id'], 'Created TableGroup must have id specified');
        $this->assertNotNull(TableGroup::find($createdTableGroup['id']), 'TableGroup with given id must be in DB');
        $this->assertModelData($tableGroup, $createdTableGroup);
    }

    /**
     * @test read
     */
    public function test_read_table_group()
    {
        $tableGroup = factory(TableGroup::class)->create();

        $dbTableGroup = $this->tableGroupRepo->find($tableGroup->id);

        $dbTableGroup = $dbTableGroup->toArray();
        $this->assertModelData($tableGroup->toArray(), $dbTableGroup);
    }

    /**
     * @test update
     */
    public function test_update_table_group()
    {
        $tableGroup = factory(TableGroup::class)->create();
        $fakeTableGroup = factory(TableGroup::class)->make()->toArray();

        $updatedTableGroup = $this->tableGroupRepo->update($fakeTableGroup, $tableGroup->id);

        $this->assertModelData($fakeTableGroup, $updatedTableGroup->toArray());
        $dbTableGroup = $this->tableGroupRepo->find($tableGroup->id);
        $this->assertModelData($fakeTableGroup, $dbTableGroup->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_table_group()
    {
        $tableGroup = factory(TableGroup::class)->create();

        $resp = $this->tableGroupRepo->delete($tableGroup->id);

        $this->assertTrue($resp);
        $this->assertNull(TableGroup::find($tableGroup->id), 'TableGroup should not exist in DB');
    }
}
