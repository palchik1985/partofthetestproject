<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TableGroup;

class TableGroupApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_table_group()
    {
        $tableGroup = factory(TableGroup::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/table_groups', $tableGroup
        );

        $this->assertApiResponse($tableGroup);
    }

    /**
     * @test
     */
    public function test_read_table_group()
    {
        $tableGroup = factory(TableGroup::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/table_groups/'.$tableGroup->id
        );

        $this->assertApiResponse($tableGroup->toArray());
    }

    /**
     * @test
     */
    public function test_update_table_group()
    {
        $tableGroup = factory(TableGroup::class)->create();
        $editedTableGroup = factory(TableGroup::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/table_groups/'.$tableGroup->id,
            $editedTableGroup
        );

        $this->assertApiResponse($editedTableGroup);
    }

    /**
     * @test
     */
    public function test_delete_table_group()
    {
        $tableGroup = factory(TableGroup::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/table_groups/'.$tableGroup->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/table_groups/'.$tableGroup->id
        );

        $this->response->assertStatus(404);
    }
}
