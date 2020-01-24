<?php namespace Tests\APIs;

use App\Models\ProductFavorite;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;
use Tests\TestCase;

class ProductFavoriteApiTest extends TestCase
{
    
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    
    /**
     * @test
     */
    public function test_create_product_favorite()
    {
        
        $productFavorite = factory(ProductFavorite::class)->make()->toArray();
        
        $this->response = $this->json(
            'POST',
            '/api/product_favorites', $productFavorite
        );
        
        $this->assertApiResponse($productFavorite);
    }
    
    /**
     * @test
     */
    public function test_read_product_favorite()
    {
        
        $productFavorite = factory(ProductFavorite::class)->create();
        
        $this->response = $this->json(
            'GET',
            '/api/product_favorites/' . $productFavorite->id
        );
        
        $this->assertApiResponse($productFavorite->toArray());
    }
    
    /**
     * @test
     */
    public function test_update_product_favorite()
    {
        
        $productFavorite       = factory(ProductFavorite::class)->create();
        $editedProductFavorite = factory(ProductFavorite::class)->make()->toArray();
        
        $this->response = $this->json(
            'PUT',
            '/api/product_favorites/' . $productFavorite->id,
            $editedProductFavorite
        );
        
        $this->assertApiResponse($editedProductFavorite);
    }
    
    /**
     * @test
     */
    public function test_delete_product_favorite()
    {
        
        $productFavorite = factory(ProductFavorite::class)->create();
        
        $this->response = $this->json(
            'DELETE',
            '/api/product_favorites/' . $productFavorite->id
        );
        
        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/product_favorites/' . $productFavorite->id
        );
        
        $this->response->assertStatus(404);
    }
}
