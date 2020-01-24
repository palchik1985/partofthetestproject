<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Reserve;

class ReserveApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_reserve()
    {
        $reserve = factory(Reserve::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/reserves', $reserve
        );

        $this->assertApiResponse($reserve);
    }

    /**
     * @test
     */
    public function test_read_reserve()
    {
        $reserve = factory(Reserve::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/reserves/'.$reserve->id
        );

        $this->assertApiResponse($reserve->toArray());
    }

    /**
     * @test
     */
    public function test_update_reserve()
    {
        $reserve = factory(Reserve::class)->create();
        $editedReserve = factory(Reserve::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/reserves/'.$reserve->id,
            $editedReserve
        );

        $this->assertApiResponse($editedReserve);
    }

    /**
     * @test
     */
    public function test_delete_reserve()
    {
        $reserve = factory(Reserve::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/reserves/'.$reserve->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/reserves/'.$reserve->id
        );

        $this->response->assertStatus(404);
    }
}
