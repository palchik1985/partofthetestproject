<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class WithTrashedCriteria.
 *
 * @package namespace App\Criteria;
 */
class WithTrashedCriteria implements CriteriaInterface
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
        
        return $model->withTrashed();
    }
}
