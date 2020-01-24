<?php

namespace App\Models;

use Eloquent as Model;

/**
 * // toDo: set only fillable fields "TablePresetSet" block. And all other in TablePresetGet block.
 * @SWG\Definition(
 *      definition="TablePresetSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="restaurant_space_id",
 *          description="restaurant_space_id",
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
 *          property="scale",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="default",
 *          type="boolean"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="TablePresetGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/TablePresetSet"),
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

class TablePreset extends Model
{

    public $table = 'table_presets';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'restaurant_space_id',
        'name',
        'comment',
        'scale',
        'default',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'restaurant_space_id' => 'integer',
        'name' => 'string',
        'comment' => 'string',
        'scale' => 'float'
    ];

    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'restaurant_space_id' => 'required',
        'name' => 'required'
    ];

    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'restaurant_space_id' => 'required',
        'name' => 'required'
    ];
    
    public function tables()
    {
        
        return $this->belongsToMany('App\Models\Table')->withPivot('coord_x', 'coord_y');
    }
    
    public function restaurant_space()
    {
        
        return $this->belongsTo('App\Models\RestaurantSpace');
    }
    
    public static function getDefault($restaurant_space_id)
    {
        
        return self::where('restaurant_space_id', '=',
            $restaurant_space_id)->whereDefault(true)->with('tables')->first();
        
    }
    
}
