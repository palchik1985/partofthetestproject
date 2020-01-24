<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class FindReservesByRelatedFieldsCriteria.
 *
 * @package namespace App\Criteria;
 */
class FindReservesByRelatedFieldsCriteria extends RequestCriteria implements CriteriaInterface
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
        $client_name = $this->request->get('client_name');
        $table_name = $this->request->get('table_name');
        $event_name = $this->request->get('event_name');
        
        if(!empty($client_name)){
            $model = $model->whereHas('client', function ($query) use ($client_name) {
    
                $query->where('clients.name', 'like', '%' . $client_name . '%')
                      ->orWhere('clients.last_name', 'like', '%' . $client_name . '%');
            });
        }
    
        if(!empty($table_name)){
            $model = $model->whereHas('tables', function ($query) use ($table_name) {
        
                $query->where('tables.name', 'like', '%' . $table_name . '%');
            });
        }
    
        if(!empty($event_name)){
            $model = $model->whereHas('event', function ($query) use ($event_name) {
        
                $query->where('events.title', 'like', '%' . $event_name . '%');
            });
        }
        
        return $model;
    }
}
