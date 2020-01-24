<?php

namespace App\Repositories;

use App\Models\Menu\Product;

/**
 * Class EventRepository
 * @package App\Repositories
 */
class MenuProductRepository extends BaseAPIRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'menu_subcategory_id',
        'r_keeper_id',
        'title'       => 'like',
        'description' => 'like',
        'price',
        'weight_gram',
    ];
    
    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        
        return $this->fieldSearchable;
    }
    
    /**
     * Configure the Model
     **/
    public function model()
    {
        
        return Product::class;
    }
}
