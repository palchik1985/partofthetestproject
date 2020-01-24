<?php

namespace App\Criteria;

use App\Models\TablePreset;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class FindTableDatesBySpaceCriteria.
 *
 * @package namespace App\Criteria;
 */
class FindTableDatesBySpaceCriteria extends RequestCriteria implements CriteriaInterface
{
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
        $space_id = $this->request->get('restaurant_space_id');
        
        if(!empty($space_id)){
            
            $preset_ids = TablePreset::where('restaurant_space_id', '=', $space_id)->pluck('id');
            
            $model = $model->whereIn('table_preset_id', $preset_ids);
        }
        
        return $model;
    }
}
