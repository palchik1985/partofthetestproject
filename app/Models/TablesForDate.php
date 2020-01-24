<?php

namespace App\Models;

use Eloquent as Model;

/**
 * // toDo: set only fillable fields "TablesForDateSet" block. And all other in TablesForDateGet block.
 * @SWG\Definition(
 *      definition="TablesForDateSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="date",
 *          description="date",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="table_preset_id",
 *          description="table_preset_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="day_description",
 *          description="day_description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="no_reserves_from_mobile",
 *          description="no_reserves_from_mobile",
 *          type="boolean",
 *          default="false"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="TablesForDateGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/TablesForDateSet"),
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

class TablesForDate extends Model
{

    public $table = 'tables_for_date';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $dates = ['date'];

//    protected $dateFormat = 'U';

    public $fillable = [
        'date',
        'table_preset_id',
        'restaurant_space_id',
        'day_description',
        'no_reserves_from_mobile',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                      => 'integer',
        'restaurant_space_id'     => 'integer',
        'date'                    => 'date:Y-m-d',
        'table_preset_id'         => 'integer',
        'day_description'         => 'string',
        'no_reserves_from_mobile' => 'boolean',
    ];

    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'date'                => 'required',
        'table_preset_id'     => 'required',
        'restaurant_space_id' => 'required',
    ];

    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'date' => 'required',
        'table_preset_id' => 'required',
    ];
    
    public function table_preset()
    {
        
        return $this->belongsTo('App\Models\TablePreset');
    }
    
    public function additional_tables()
    {
        
        return $this->belongsToMany('App\Models\Table', 'additional_tables_for_date')->withPivot('coord_x', 'coord_y');
    }
    
}
