<?php

namespace App\Criteria;

use App\Models\TablePreset;
use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class FindTablesForDateCriteria.
 *
 * @package namespace App\Criteria;
 */
class FindTablesForDateCriteria implements CriteriaInterface
{
    
    private $date;
    private $restaurant_space_id;
    
    public function __construct($restaurant_space_id, $date)
    {
        
        $this->restaurant_space_id = $restaurant_space_id;
        $this->date                = $date;
        
    }
    
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        
        // получить допустимые пресеты для поиска
        $preset_ids = TablePreset::where('restaurant_space_id', '=', $this->restaurant_space_id)->pluck('id');
        
        $date = $this->date ?? Carbon::today()->format('Y-m-d');
        
        $model = $model->whereIn('table_preset_id', $preset_ids)->where('date', '=', $date);
        
        return $model;
    }
}
