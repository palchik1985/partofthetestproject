<?php

namespace App\Repositories;

use App\Models\SMS;

/**
 * Class ClientRepository
 * @package App\Repositories
 */
class SmsRepository extends BaseAPIRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'number'    => 'like',
        'send_time' => 'like',
        'added'     => 'like',
        'sended'    => 'like',
        'received'  => 'like',
        'status'    => 'like',
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
        
        return SMS::class;
    }
}
