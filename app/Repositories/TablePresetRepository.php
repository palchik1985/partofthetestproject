<?php

namespace App\Repositories;

use App\Models\TablePreset;

/**
 * Class TablePresetRepository
 * @package App\Repositories
*/

class TablePresetRepository extends BaseAPIRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'restaurant_space_id',
        'name' => 'like',
        'comment' => 'like',
        'scale'
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
        return TablePreset::class;
    }
}
