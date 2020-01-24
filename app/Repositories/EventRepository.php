<?php

namespace App\Repositories;

use App\Models\Event;
use Carbon\Carbon;

/**
 * Class EventRepository
 * @package App\Repositories
 */
class EventRepository extends BaseAPIRepository
{
    
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'restaurant_id',
        'title'       => 'like',
        'description' => 'like',
        'date_time'   => 'like',
        'restaurant_space_id',
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
    
        return Event::class;
    }
    
    public function todayEvents($date, int $restaurant_space_id)
    {
        
        // todo вынести время старта дня в таблицу рестораны
        // определить период
        $date            = Carbon::parse($date . ' 00:00:00');
        $date_start_from = Carbon::parse($date)->startOfDay()->addHours(env('START_OPERATIONAL_DAY_HOURS'))
                                 ->format('Y-m-d H:i:s');
        
        $date_start_to = Carbon::parse($date)->startOfDay()->addHours(env('START_OPERATIONAL_DAY_HOURS'))
                               ->addHours(24)->format('Y-m-d H:i:s');
        
        $events = $this->model->where('date_time', '>', $date_start_from)
                              ->where('date_time', '<', $date_start_to)
                              ->where(['restaurant_space_id' => $restaurant_space_id])
                              ->orderBy('date_time')->get();
        
        if ($events->count() == 0) {
            $default_event = $this->defaultEvent($restaurant_space_id);
            if ( ! empty($default_event)) {
                $events[] = $this->defaultEvent($restaurant_space_id);
            }
        }
        
        return $events;
    }
    
    public function defaultEvent($restaurant_space_id)
    {
        
        return $this->findWhere([
            'title'               => 'no event',
            'restaurant_space_id' => $restaurant_space_id,
        ])->first();
        //
        //        if (empty($event)) {
        //            return $this->sendError('Event not found');
        //        }
        //
        //        return $this->sendResponse($event->toArray(), 'Event retrieved successfully');
    }
}
