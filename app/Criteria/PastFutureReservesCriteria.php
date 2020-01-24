<?php

namespace App\Criteria;

use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class GetMyReservesCriteria.
 *
 * @package namespace App\Criteria;
 */
class PastFutureReservesCriteria extends RequestCriteria implements CriteriaInterface
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
        
        if (empty($this->request->get('past'))) {
            return $model;
        }
        
        if ($this->request->get('past') == 'true') {
            return $model->where('date_start', '<', Carbon::now())->orderBy('date_start', 'desc');
        }
        
        return $model->where('date_start', '>', Carbon::now())->orderBy('date_start');
    }
}
