<?php namespace Tests\Repositories;

use App\Models\ProductFavorite;
use App\Repositories\ProductFavoriteRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;

class ProductFavoriteRepositoryTest extends TestCase
{
    
    use ApiTestTrait, DatabaseTransactions;
    
    /**
     * @var ProductFavoriteRepository
     */
    protected $productFavoriteRepo;
    
    public function setUp(): void
    {
        
        parent::setUp();
        $this->productFavoriteRepo = \App::make(ProductFavoriteRepository::class);
    }
    
    /**
     * @test create
     */
    public function test_create_product_favorite()
    {
        
        $productFavorite = factory(ProductFavorite::class)->make()->toArray();
        
        $createdProductFavorite = $this->productFavoriteRepo->create($productFavorite);
        
        $createdProductFavorite = $createdProductFavorite->toArray();
        $this->assertArrayHasKey('id', $createdProductFavorite);
        $this->assertNotNull($createdProductFavorite[ 'id' ], 'Created ProductFavorite must have id specified');
        $this->assertNotNull(ProductFavorite::find($createdProductFavorite[ 'id' ]),
            'ProductFavorite with given id must be in DB');
        $this->assertModelData($productFavorite, $createdProductFavorite);
    }
    
    /**
     * @test read
     */
    public function test_read_product_favorite()
    {
        
        $productFavorite = factory(ProductFavorite::class)->create();
        
        $dbProductFavorite = $this->productFavoriteRepo->find($productFavorite->id);
        
        $dbProductFavorite = $dbProductFavorite->toArray();
        $this->assertModelData($productFavorite->toArray(), $dbProductFavorite);
    }
    
    /**
     * @test update
     */
    public function test_update_product_favorite()
    {
        
        $productFavorite     = factory(ProductFavorite::class)->create();
        $fakeProductFavorite = factory(ProductFavorite::class)->make()->toArray();
        
        $updatedProductFavorite = $this->productFavoriteRepo->update($fakeProductFavorite, $productFavorite->id);
        
        $this->assertModelData($fakeProductFavorite, $updatedProductFavorite->toArray());
        $dbProductFavorite = $this->productFavoriteRepo->find($productFavorite->id);
        $this->assertModelData($fakeProductFavorite, $dbProductFavorite->toArray());
    }
    
    /**
     * @test delete
     */
    public function test_delete_product_favorite()
    {
        
        $productFavorite = factory(ProductFavorite::class)->create();
        
        $resp = $this->productFavoriteRepo->delete($productFavorite->id);
        
        $this->assertTrue($resp);
        $this->assertNull(ProductFavorite::find($productFavorite->id), 'ProductFavorite should not exist in DB');
    }
}
