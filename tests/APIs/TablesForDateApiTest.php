<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TablesForDate;

class TablesForDateApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tables_for_dates', $tablesForDate
        );

        $this->assertApiResponse($tablesForDate);
    }

    /**
     * @test
     */
    public function test_read_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/tables_for_dates/'.$tablesForDate->id
        );

        $this->assertApiResponse($tablesForDate->toArray());
    }

    /**
     * @test
     */
    public function test_update_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->create();
        $editedTablesForDate = factory(TablesForDate::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tables_for_dates/'.$tablesForDate->id,
            $editedTablesForDate
        );

        $this->assertApiResponse($editedTablesForDate);
    }

    /**
     * @test
     */
    public function test_delete_tables_for_date()
    {
        $tablesForDate = factory(TablesForDate::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tables_for_dates/'.$tablesForDate->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tables_for_dates/'.$tablesForDate->id
        );

        $this->response->assertStatus(404);
    }
}
