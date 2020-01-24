<?php

namespace App\Criteria;

use App\Models\Client;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Criteria\RequestCriteria;

/**
 * Class ByClientCriteria.
 *
 * @package namespace App\Criteria;
 */
class ByClientCriteria extends RequestCriteria implements CriteriaInterface
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
        
        // search by client_id
        if ( ! empty($client_id = $this->request->get('client_id'))) {
            $client = Client::getById($client_id);
            
            return $model->where(['client_id' => $client->id]);
        }
        
        // search by client api_key
        if ( ! empty($api_key = $this->request->get('api_key'))) {
            $client = Client::getByKey($api_key);
            
            return $model->where(['client_id' => $client->id]);
        }
        
        return $model;
    }
}
