<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WithoutDefaultEventsCriteria.
 *
 * @package namespace App\Criteria;
 */
class WithoutDefaultEventsCriteria implements CriteriaInterface
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
        
        $model = $model->where('title', '!=', 'no event');
        
        return $model;
    }
}
