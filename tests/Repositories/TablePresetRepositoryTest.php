<?php namespace Tests\Repositories;

use App\Models\TablePreset;
use App\Repositories\TablePresetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TablePresetRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TablePresetRepository
     */
    protected $tablePresetRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tablePresetRepo = \App::make(TablePresetRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->make()->toArray();

        $createdTablePreset = $this->tablePresetRepo->create($tablePreset);

        $createdTablePreset = $createdTablePreset->toArray();
        $this->assertArrayHasKey('id', $createdTablePreset);
        $this->assertNotNull($createdTablePreset['id'], 'Created TablePreset must have id specified');
        $this->assertNotNull(TablePreset::find($createdTablePreset['id']), 'TablePreset with given id must be in DB');
        $this->assertModelData($tablePreset, $createdTablePreset);
    }

    /**
     * @test read
     */
    public function test_read_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->create();

        $dbTablePreset = $this->tablePresetRepo->find($tablePreset->id);

        $dbTablePreset = $dbTablePreset->toArray();
        $this->assertModelData($tablePreset->toArray(), $dbTablePreset);
    }

    /**
     * @test update
     */
    public function test_update_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->create();
        $fakeTablePreset = factory(TablePreset::class)->make()->toArray();

        $updatedTablePreset = $this->tablePresetRepo->update($fakeTablePreset, $tablePreset->id);

        $this->assertModelData($fakeTablePreset, $updatedTablePreset->toArray());
        $dbTablePreset = $this->tablePresetRepo->find($tablePreset->id);
        $this->assertModelData($fakeTablePreset, $dbTablePreset->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->create();

        $resp = $this->tablePresetRepo->delete($tablePreset->id);

        $this->assertTrue($resp);
        $this->assertNull(TablePreset::find($tablePreset->id), 'TablePreset should not exist in DB');
    }
}
