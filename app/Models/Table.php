<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * @SWG\Definition(
 *      definition="TableSet",
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
 *          property="busy_now",
 *          description="name",
 *          type="string",
 *          enum={"busy", "draft"},
 *      ),
 *      @SWG\Property(
 *          property="seats_count",
 *          description="seats_count",
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
 *  definition="TableGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/TableSet"),
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

class Table extends Model
{

    public $table = 'tables';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $appends = [ 'image_url' ];
    protected $hidden  = [
        'created_at',
        'updated_at',
    ];

    public $fillable = [
        'restaurant_id',
        'name',
        'seats_count',
        'image',
        'busy_now',
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
        'seats_count'   => 'integer',
        'image'         => 'string',
        'busy_now'      => 'string',
    ];

    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'name' => 'required',
        'seats_count' => 'required'
    ];

    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'name' => 'required',
    ];
    
    public function getImageUrlAttribute()
    {
        if(!empty($this->image)){
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/tables/' . $this->image);
        }
        return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/tables/table_' . $this->seats_count . '.png');
        
    }
    
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
        
    }
    
    public function presets()
    {
        
        return $this->belongsToMany('App\Models\TablePreset');
    }
    
}
