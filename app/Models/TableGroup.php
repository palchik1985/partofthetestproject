<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;

/**
 * // toDo: set only fillable fields "TableGroupSet" block. And all other in TableGroupGet block.
 * @SWG\Definition(
 *      definition="TableGroupSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="restaurant_id",
 *          description="restaurant_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="comment",
 *          description="comment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="start",
 *          description="start",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="finish",
 *          description="finish",
 *          type="string",
 *          format="date-time"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="TableGroupGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/TableGroupSet"),
 *    @SWG\Schema(
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
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
class TableGroup extends Model
{
    
    public $table = 'table_groups';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    
    public $fillable = [
        'restaurant_id',
        'name',
        'comment',
        'start',
        'finish'
    ];
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'            => 'integer',
        'restaurant_id' => 'integer',
        'name'          => 'string',
        'comment'       => 'string',
        'start'         => 'datetime',
        'finish'        => 'datetime'
    ];
    
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'name'   => 'required',
        'start'  => 'required',
        'finish' => 'required'
    ];
    
    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'name'   => 'required',
        'start'  => 'required',
        'finish' => 'required'
    ];
    
    public function tables()
    {
        
        return $this->belongsToMany('App\Models\Table');
        
    }
    
    public static function getItems($date)
    {
        
        // todo вынести время старта дня в таблицу рестораны
        // определить период
        $date            = Carbon::parse($date . ' 00:00:00');
        $date_start_from = Carbon::parse($date)->startOfDay()->addHours(env('START_OPERATIONAL_DAY_HOURS'))
                                 ->format('Y-m-d H:i:s');
        
        $date_start_to = Carbon::parse($date)->startOfDay()->addHours(env('START_OPERATIONAL_DAY_HOURS'))
                               ->addHours(24)->format('Y-m-d H:i:s');
    
        return self::where('start', '>', $date_start_from)->where('start', '<', $date_start_to)
                   ->with('tables:tables.id,name')->orderBy('start')->get();
    }
    
    
}
