<?php

namespace App\Repositories;

use App\Models\RestaurantSpace;

/**
 * Class RestaurantSpaceRepository
 * @package App\Repositories
 */
class RestaurantSpaceRepository extends BaseAPIRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'restaurant_id',
        'name' => 'like',
        'description' => 'like',
        'image',
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
        
        return RestaurantSpace::class;
    }
}
