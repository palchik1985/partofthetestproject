<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class PaginateCriteria.
 *
 * @package namespace App\Criteria;
 */
class PaginateCriteria extends RequestCriteria implements CriteriaInterface
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
        
        $page = $this->request->get('page');
        if (empty($page)) {
            return $model;
        }
        
        $per_page = $this->request->get('per_page') ?? 20;
        
        $model = $model->forPage($page, $per_page);
        
        return $model;
    }
}
