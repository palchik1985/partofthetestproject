<?php

namespace App\Repositories;

use App\Models\Table;

/**
 * Class TableRepository
 * @package App\Repositories
*/

class TableRepository extends BaseAPIRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'restaurant_id',
        'name' => 'like',
        'seats_count',
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
        return Table::class;
    }
}
