<?php

namespace App\Repositories;

use App\Models\Restaurant;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RestaurantRepository
 * @package App\Repositories
*/

class RestaurantRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'image',
        'full_name',
        'address',
        'description',
        'lng',
        'lat'
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
        return Restaurant::class;
    }
}
