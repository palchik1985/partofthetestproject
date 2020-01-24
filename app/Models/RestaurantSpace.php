<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * // toDo: set only fillable fields "RestaurantSpaceSet" block. And all other in RestaurantSpaceGet block.
 * @SWG\Definition(
 *      definition="RestaurantSpaceSet",
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
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="size_x",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="size_y",
 *          type="integer",
 *          format="int32"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="RestaurantSpaceGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/RestaurantSpaceSet"),
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

class RestaurantSpace extends Model
{

    public $table = 'restaurant_spaces';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $appends = [ 'image_url' ];

    public $fillable = [
        'restaurant_id',
        'name',
        'description',
        'image',
        'size_x',
        'size_y',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'restaurant_id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'image' => 'string'
    ];

    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'name' => 'required'
    ];

    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'name' => 'required'
    ];
    
    public function getImageUrlAttribute()
    {
        if(!empty($this->image)) {
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/spaces/' . $this->image);
        }
        return null;
    }
    
    
    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
        
    }
}
