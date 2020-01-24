<?php

namespace App\Repositories;

use App\Models\TablesForDate;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TablesForDateRepository
 * @package App\Repositories
*/

class TablesForDateRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date' => 'like',
        'table_preset_id',
        'day_description' => 'like'
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
        return TablesForDate::class;
    }
}
