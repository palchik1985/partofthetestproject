<?php

namespace App\Repositories;

use App\Models\Client;
use App\Models\Reserve;
use Carbon\Carbon;

/**
 * Class ClientRepository
 * @package App\Repositories
*/

class ClientRepository extends BaseAPIRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'      => 'like',
        'last_name' => 'like',
        'email'     => 'like',
        'phone'     => 'like',
        'comment'   => 'like',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Client::class;
    }
    
    
    /*
     * Get reserve info in client array: last reserve and reserves count (without deleted reserves)
     */
    public function addReserveInfo(array $client)
    {
        
        $last_reserve_at = Reserve::where(['client_id' => $client[ 'id' ]])->orderBy('date_start', 'desc')->first();
        $reserves_count  = Reserve::where(['client_id' => $client[ 'id' ]])->count();
        
        $client[ 'last_reserve_at' ] = ! empty($last_reserve_at) ? Carbon::parse($last_reserve_at->date_start)->format('Y-m-d') : null;
        $client[ 'reserves_count' ]  = $reserves_count;
        
        return $client;
    }
}
