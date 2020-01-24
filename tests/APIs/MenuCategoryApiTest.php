<?php namespace Tests\APIs;

use App\Models\Menu\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;
use Tests\TestCase;

class MenuCategoryApiTest extends TestCase
{
    
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    
    /**
     * @test
     */
    public function test_create_menu_category()
    {
        
        $menuCategory = factory(Category::class)->make()->toArray();
        
        $this->response = $this->json(
            'POST',
            '/api/menu_categories', $menuCategory
        );
        
        $this->assertApiResponse($menuCategory);
    }
    
    /**
     * @test
     */
    public function test_read_menu_category()
    {
        
        $menuCategory = factory(Category::class)->create();
        
        $this->response = $this->json(
            'GET',
            '/api/menu_categories/' . $menuCategory->id
        );
        
        $this->assertApiResponse($menuCategory->toArray());
    }
    
    /**
     * @test
     */
    public function test_update_menu_category()
    {
        
        $menuCategory       = factory(Category::class)->create();
        $editedMenuCategory = factory(Category::class)->make()->toArray();
        
        $this->response = $this->json(
            'PUT',
            '/api/menu_categories/' . $menuCategory->id,
            $editedMenuCategory
        );
        
        $this->assertApiResponse($editedMenuCategory);
    }
    
    /**
     * @test
     */
    public function test_delete_menu_category()
    {
        
        $menuCategory = factory(Category::class)->create();
        
        $this->response = $this->json(
            'DELETE',
            '/api/menu_categories/' . $menuCategory->id
        );
        
        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/menu_categories/' . $menuCategory->id
        );
        
        $this->response->assertStatus(404);
    }
}
