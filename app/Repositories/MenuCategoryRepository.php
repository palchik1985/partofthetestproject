<?php

namespace App\Repositories;

use App\Models\Menu\Category;

/**
 * Class MenuCategoryRepository
 * @package App\Repositories
 */
class MenuCategoryRepository extends BaseAPIRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'restaurant_id',
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
        
        return Category::class;
    }
}
