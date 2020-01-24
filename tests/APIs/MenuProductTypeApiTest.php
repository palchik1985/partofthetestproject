<?php namespace Tests\APIs;

use App\Models\MenuProductType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;
use Tests\TestCase;

class MenuProductTypeApiTest extends TestCase
{
    
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;
    
    /**
     * @test
     */
    public function test_create_menu_product_type()
    {
        
        $menuProductType = factory(MenuProductType::class)->make()->toArray();
        
        $this->response = $this->json(
            'POST',
            '/api/menu_product_types', $menuProductType
        );
        
        $this->assertApiResponse($menuProductType);
    }
    
    /**
     * @test
     */
    public function test_read_menu_product_type()
    {
        
        $menuProductType = factory(MenuProductType::class)->create();
        
        $this->response = $this->json(
            'GET',
            '/api/menu_product_types/' . $menuProductType->id
        );
        
        $this->assertApiResponse($menuProductType->toArray());
    }
    
    /**
     * @test
     */
    public function test_update_menu_product_type()
    {
        
        $menuProductType       = factory(MenuProductType::class)->create();
        $editedMenuProductType = factory(MenuProductType::class)->make()->toArray();
        
        $this->response = $this->json(
            'PUT',
            '/api/menu_product_types/' . $menuProductType->id,
            $editedMenuProductType
        );
        
        $this->assertApiResponse($editedMenuProductType);
    }
    
    /**
     * @test
     */
    public function test_delete_menu_product_type()
    {
        
        $menuProductType = factory(MenuProductType::class)->create();
        
        $this->response = $this->json(
            'DELETE',
            '/api/menu_product_types/' . $menuProductType->id
        );
        
        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/menu_product_types/' . $menuProductType->id
        );
        
        $this->response->assertStatus(404);
    }
}
