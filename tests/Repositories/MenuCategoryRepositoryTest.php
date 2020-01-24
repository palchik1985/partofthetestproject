<?php namespace Tests\Repositories;

use App\Models\Menu\Category;
use App\Repositories\MenuCategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;

class MenuCategoryRepositoryTest extends TestCase
{
    
    use ApiTestTrait, DatabaseTransactions;
    
    /**
     * @var MenuCategoryRepository
     */
    protected $menuCategoryRepo;
    
    public function setUp(): void
    {
        
        parent::setUp();
        $this->menuCategoryRepo = \App::make(MenuCategoryRepository::class);
    }
    
    /**
     * @test create
     */
    public function test_create_menu_category()
    {
        
        $menuCategory = factory(Category::class)->make()->toArray();
        
        $createdMenuCategory = $this->menuCategoryRepo->create($menuCategory);
        
        $createdMenuCategory = $createdMenuCategory->toArray();
        $this->assertArrayHasKey('id', $createdMenuCategory);
        $this->assertNotNull($createdMenuCategory[ 'id' ], 'Created MenuCategory must have id specified');
        $this->assertNotNull(Category::find($createdMenuCategory[ 'id' ]), 'MenuCategory with given id must be in DB');
        $this->assertModelData($menuCategory, $createdMenuCategory);
    }
    
    /**
     * @test read
     */
    public function test_read_menu_category()
    {
        
        $menuCategory = factory(Category::class)->create();
        
        $dbMenuCategory = $this->menuCategoryRepo->find($menuCategory->id);
        
        $dbMenuCategory = $dbMenuCategory->toArray();
        $this->assertModelData($menuCategory->toArray(), $dbMenuCategory);
    }
    
    /**
     * @test update
     */
    public function test_update_menu_category()
    {
        
        $menuCategory     = factory(Category::class)->create();
        $fakeMenuCategory = factory(Category::class)->make()->toArray();
        
        $updatedMenuCategory = $this->menuCategoryRepo->update($fakeMenuCategory, $menuCategory->id);
        
        $this->assertModelData($fakeMenuCategory, $updatedMenuCategory->toArray());
        $dbMenuCategory = $this->menuCategoryRepo->find($menuCategory->id);
        $this->assertModelData($fakeMenuCategory, $dbMenuCategory->toArray());
    }
    
    /**
     * @test delete
     */
    public function test_delete_menu_category()
    {
        
        $menuCategory = factory(Category::class)->create();
        
        $resp = $this->menuCategoryRepo->delete($menuCategory->id);
        
        $this->assertTrue($resp);
        $this->assertNull(Category::find($menuCategory->id), 'MenuCategory should not exist in DB');
    }
}
