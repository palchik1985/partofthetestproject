<?php namespace Tests\Repositories;

use App\Models\MenuProductType;
use App\Repositories\MenuProductTypeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\ApiTestTrait;
use Tests\TestCase;

class MenuProductTypeRepositoryTest extends TestCase
{
    
    use ApiTestTrait, DatabaseTransactions;
    
    /**
     * @var MenuProductTypeRepository
     */
    protected $menuProductTypeRepo;
    
    public function setUp(): void
    {
        
        parent::setUp();
        $this->menuProductTypeRepo = \App::make(MenuProductTypeRepository::class);
    }
    
    /**
     * @test create
     */
    public function test_create_menu_product_type()
    {
        
        $menuProductType = factory(MenuProductType::class)->make()->toArray();
        
        $createdMenuProductType = $this->menuProductTypeRepo->create($menuProductType);
        
        $createdMenuProductType = $createdMenuProductType->toArray();
        $this->assertArrayHasKey('id', $createdMenuProductType);
        $this->assertNotNull($createdMenuProductType[ 'id' ], 'Created MenuProductType must have id specified');
        $this->assertNotNull(MenuProductType::find($createdMenuProductType[ 'id' ]),
            'MenuProductType with given id must be in DB');
        $this->assertModelData($menuProductType, $createdMenuProductType);
    }
    
    /**
     * @test read
     */
    public function test_read_menu_product_type()
    {
        
        $menuProductType = factory(MenuProductType::class)->create();
        
        $dbMenuProductType = $this->menuProductTypeRepo->find($menuProductType->id);
        
        $dbMenuProductType = $dbMenuProductType->toArray();
        $this->assertModelData($menuProductType->toArray(), $dbMenuProductType);
    }
    
    /**
     * @test update
     */
    public function test_update_menu_product_type()
    {
        
        $menuProductType     = factory(MenuProductType::class)->create();
        $fakeMenuProductType = factory(MenuProductType::class)->make()->toArray();
        
        $updatedMenuProductType = $this->menuProductTypeRepo->update($fakeMenuProductType, $menuProductType->id);
        
        $this->assertModelData($fakeMenuProductType, $updatedMenuProductType->toArray());
        $dbMenuProductType = $this->menuProductTypeRepo->find($menuProductType->id);
        $this->assertModelData($fakeMenuProductType, $dbMenuProductType->toArray());
    }
    
    /**
     * @test delete
     */
    public function test_delete_menu_product_type()
    {
        
        $menuProductType = factory(MenuProductType::class)->create();
        
        $resp = $this->menuProductTypeRepo->delete($menuProductType->id);
        
        $this->assertTrue($resp);
        $this->assertNull(MenuProductType::find($menuProductType->id), 'MenuProductType should not exist in DB');
    }
}
