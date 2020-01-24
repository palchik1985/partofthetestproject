<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TablePreset;

class TablePresetApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/table_presets', $tablePreset
        );

        $this->assertApiResponse($tablePreset);
    }

    /**
     * @test
     */
    public function test_read_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/table_presets/'.$tablePreset->id
        );

        $this->assertApiResponse($tablePreset->toArray());
    }

    /**
     * @test
     */
    public function test_update_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->create();
        $editedTablePreset = factory(TablePreset::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/table_presets/'.$tablePreset->id,
            $editedTablePreset
        );

        $this->assertApiResponse($editedTablePreset);
    }

    /**
     * @test
     */
    public function test_delete_table_preset()
    {
        $tablePreset = factory(TablePreset::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/table_presets/'.$tablePreset->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/table_presets/'.$tablePreset->id
        );

        $this->response->assertStatus(404);
    }
}
