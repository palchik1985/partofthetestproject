<?php

namespace App\Repositories;

use App\Models\Reserve;

/**
 * Class ReserveRepository
 * @package App\Repositories
*/

class ReserveRepository extends BaseAPIRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'restaurant_id',
        'client_id',
        //        'table_id',
        'date_start' => 'like',
        'persons_count',
        'comment' => 'like',
        'event_id',
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
        return Reserve::class;
    }
}
