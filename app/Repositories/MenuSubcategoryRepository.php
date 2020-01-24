<?php

namespace App\Repositories;

use App\Models\Menu\Subcategory;

/**
 * Class MenuCategoryRepository
 * @package App\Repositories
 */
class MenuSubcategoryRepository extends BaseAPIRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'menu_category_id',
        'name',
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
        
        return Subcategory::class;
    }
}
