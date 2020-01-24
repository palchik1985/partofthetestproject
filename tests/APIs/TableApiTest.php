<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Table;

class TableApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_table()
    {
        $table = factory(Table::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tables', $table
        );

        $this->assertApiResponse($table);
    }

    /**
     * @test
     */
    public function test_read_table()
    {
        $table = factory(Table::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/tables/'.$table->id
        );

        $this->assertApiResponse($table->toArray());
    }

    /**
     * @test
     */
    public function test_update_table()
    {
        $table = factory(Table::class)->create();
        $editedTable = factory(Table::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tables/'.$table->id,
            $editedTable
        );

        $this->assertApiResponse($editedTable);
    }

    /**
     * @test
     */
    public function test_delete_table()
    {
        $table = factory(Table::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tables/'.$table->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tables/'.$table->id
        );

        $this->response->assertStatus(404);
    }
}
