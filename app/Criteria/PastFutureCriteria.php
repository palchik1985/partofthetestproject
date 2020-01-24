<?php

namespace App\Criteria;

use App\Models\Event;
use App\Models\Reserve;
use Carbon\Carbon;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class EventsByDateCriteria.
 *
 * @package namespace App\Criteria;
 */
class PastFutureCriteria extends RequestCriteria implements CriteriaInterface
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
            return $model->with('medias');
        }
        
        switch (true) {
            
            case $model instanceOf Event:
                $fieldName = 'date_time';
                break;
            case $model instanceOf Reserve:
                $fieldName = 'date_start';
                break;
            default:
                return $model;
        }
        if ($this->request->get('past') == 'true') {
            return $model->where($fieldName, '<', Carbon::now())->with('medias')->orderBy($fieldName, 'desc');
        }
    
        return $model->where($fieldName, '>', Carbon::now())->orderBy($fieldName);

    }
}
