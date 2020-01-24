<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ReservesFromMobileCriteria.
 *
 * @package namespace App\Criteria;
 */
class ReservesFromMobileCriteria extends RequestCriteria implements CriteriaInterface
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
    
        $from_mobile = $this->request->get('from_mobile');
    
        if ($from_mobile == null) {
            return $model;
        }
        
        $from_mobile = (bool)$from_mobile;
        
        return $model->where(['from_mobile' => $from_mobile]);
        
    }
}
