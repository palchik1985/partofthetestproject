<?php

namespace App\Repositories;

use App\Models\TableGroup;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TableGroupRepository
 * @package App\Repositories
*/

class TableGroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'restaurant_id',
        'name' => 'like',
        'comment' => 'like',
        'start' => 'like',
        'finish' => 'like'
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
        return TableGroup::class;
    }
}
