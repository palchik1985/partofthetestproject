<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * // toDo: set only fillable fields "ReserveSet" block. And all other in ReserveGet block.
 * @SWG\Definition(
 *      definition="ReserveSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="restaurant_id",
 *          description="restaurant_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="client_id",
 *          description="client_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="table_id",
 *          description="table_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="date_start",
 *          description="date_start",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="date_finish",
 *          description="date_finish",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="persons_count",
 *          description="persons_count",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="comment",
 *          description="comment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="prepayment",
 *          description="prepayment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="comment_status",
 *          description="statuses: ПО, ПЗ, ПС",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="status",
 *          description="Status of reserve: active, done, cancelled etc.",
 *          type="string"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="ReserveGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/ReserveSet"),
 *    @SWG\Schema(
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="from_mobile",
 *          description="is reserve registered from mobile",
 *          type="boolean",
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
class Reserve extends Model
{
    
    use SoftDeletes;
    
    public const START_TIME_OF_DAY_HOURS = 6;
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'client_id'     => 'required',
        'date_start'    => 'required',
        'date_finish'   => 'required',
        'persons_count' => 'required',
    ];
    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'client_id'     => 'required',
        'date_start'    => 'required',
        'date_finish'   => 'required',
        'persons_count' => 'required',
    ];
    public        $table        = 'reserves';
    public        $fillable     = [
        'restaurant_id',
        'client_id',
        'date_start',
        'date_finish',
        'persons_count',
        'comment',
        'prepayment',
        'image',
        'status',
        'options',
        'event_id',
        'from_mobile',
    ];
    protected     $appends      = ['image_url'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'restaurant_id' => 'integer',
        'client_id'     => 'integer',
        'date_start'    => 'datetime',
        'date_finish'   => 'datetime',
        'persons_count' => 'integer',
        'comment'       => 'string',
        'prepayment'    => 'string',
        'image'         => 'string',
        'status'        => 'string',
        'options'       => 'string',
        'from_mobile'   => 'boolean',
    ];
    
    public static function getItems($date, array $table_ids, $overbooking = false)
    {
        
        // todo вынести время старта дня в таблицу рестораны
        // определить период
        $date            = Carbon::parse($date . ' 00:00:00');
        $date_start_from = Carbon::parse($date->format('Y-m-d H:i:s'))->addHours(env('START_OPERATIONAL_DAY_HOURS'))
                                 ->format('Y-m-d H:i:s');
        $date_start_to   = Carbon::parse($date_start_from)->addHours(24)->format('Y-m-d H:i:s');
        //        dd($date->format('Y-m-d H:i:s'), $date_start_from, $date_start_to);
        // получить резервы по столам за период
    
        if ($overbooking === false) {
            $reserves = self::where('date_start', '>', $date_start_from)->where('date_start', '<', $date_start_to)
                            ->whereHas('tables', function ($query) use ($table_ids) {
            
                                $query->whereIn('table_id', $table_ids);
                            })
                            ->with(['client', 'tables:tables.id,tables.name'])->orderBy('date_start')->get();
            //            dd($reserves);
        } else {
            $reserves = self::where('date_start', '>', $date_start_from)->where('date_start', '<', $date_start_to)
                            ->doesntHave('tables')
                            ->with(['client'])->orderBy('date_start')->get();
        }
    
        foreach ($reserves as $i => $reserve) {
            $reserves[ $i ][ 'smses' ] = $reserve->smses;
        }
        
        return $reserves;
        
    }
    
    
    private function setSmsesToReserves($reserves)
    {
        
        foreach ($reserves as $reserve) {
        
        }
    }
    
    public function getImageUrlAttribute()
    {
        
        if ( ! empty($this->image)) {
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/reserves/' . $this->image);
        }
        
        return null;
    }
    
    public function restaurant()
    {
        
        return $this->belongsTo('App\Models\Restaurant');
        
    }
    
    public function client()
    {
        
        return $this->belongsTo('App\Models\Client');
    }
    
    public function tables()
    {
    
        return $this->belongsToMany('App\Models\Table', 'table_reserve');
    }
    
    public function event()
    {
        
        return $this->belongsTo('App\Models\Event');
    }
    
    public function sms_ids()
    {
    
        return $this->hasMany('App\Models\ReserveSmsId');
    }
    
    public function getSmsesAttribute()
    {
        
        $sms_ids = $this->sms_ids->pluck('sms_id')->toArray();
        
        return ! empty($sms_ids) ? SMS::whereIn('id', $sms_ids)->get() : [];
    }
}
