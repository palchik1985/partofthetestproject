<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * // toDo: set only fillable fields "EventSet" block. And all other in EventGet block.
 * @SWG\Definition(
 *      definition="EventSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="restaurant_id",
 *          description="restaurant_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="restaurant_space_id",
 *          description="restaurant_space_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="date_time",
 *          description="date_time",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="ticket_price",
 *          description="ticket_price",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="min_deposit",
 *          description="min_deposit",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="EventGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/EventSet"),
 *    @SWG\Schema(
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="image_url",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 *    ),
 *  }
 * ),
 */
class Event extends Model
{
    
    public $table = 'events';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    
    protected $appends = [ 'image_url' ];
    
    public $fillable = [
        'restaurant_id',
        'restaurant_space_id',
        'title',
        'description',
        'date_time',
        'ticket_price',
        'min_deposit',
        'image'
    ];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'restaurant_id' => 'integer',
        'title'         => 'string',
        'description'   => 'string',
        'date_time'     => 'datetime',
        'ticket_price'  => 'integer',
        'min_deposit'   => 'integer',
        'image'         => 'string'
    ];
    
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'title'        => 'required',
        'date_time'    => 'required',
        'ticket_price' => 'required',
        'min_deposit'  => 'required'
    ];
    
    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
    
    ];
    
    public function getImageUrlAttribute()
    {
        
        if(!empty($this->image)) {
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/events/' . $this->image);
        }
        
        return null;
    }
    
    public function restaurant()
    {
        
        return $this->belongsTo('App\Models\Restaurant');
        
    }
    
    public function restaurant_space()
    {
        
        return $this->belongsTo('App\Models\RestaurantSpace');
    }
    
    public function medias()
    {
        
        return $this->morphMany('App\Models\Media', 'mediable');
    }
    
    //    public static function getItems($date, $restaurant_space_id)
    //    {
    //
    //        // todo вынести время старта дня в таблицу рестораны
    //        // определить период
    //        $date            = Carbon::parse($date . ' 00:00:00');
    //        $date_start_from = Carbon::parse($date)->startOfDay()->addHours(env('START_OPERATIONAL_DAY_HOURS'))
    //                                 ->format('Y-m-d H:i:s');
    //
    //        $date_start_to = Carbon::parse($date)->startOfDay()->addHours(env('START_OPERATIONAL_DAY_HOURS'))
    //                               ->addHours(24)->format('Y-m-d H:i:s');
    //
    //
    //
    //        $events = self::where('date_time', '>', $date_start_from)->where('date_time', '<', $date_start_to)
    //                    ->where(['restaurant_space_id' => $restaurant_space_id])
    //                   ->orderBy('date_time')->get();
    //
    //        $events
    //    }
}
