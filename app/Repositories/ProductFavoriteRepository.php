<?php

namespace App\Repositories;

use App\Models\Menu\ProductFavorite;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProductFavoriteRepository
 * @package App\Repositories
 */
class ProductFavoriteRepository extends BaseRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'client_id',
        'menu_product_id',
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
        
        return ProductFavorite::class;
    }
}
