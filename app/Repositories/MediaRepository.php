<?php

namespace App\Repositories;

use App\Models\Media;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class MediaRepository
 * @package App\Repositories
 */
class MediaRepository extends BaseRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'type',
        'path',
        'mediable_id',
        'mediable_type',
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
        
        return Media::class;
    }
}
