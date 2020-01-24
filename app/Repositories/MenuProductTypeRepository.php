<?php

namespace App\Repositories;

use App\Models\Menu\ProductType;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MenuProductTypeRepository
 * @package App\Repositories
 */
class MenuProductTypeRepository extends BaseRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
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
        
        return ProductType::class;
    }
}
