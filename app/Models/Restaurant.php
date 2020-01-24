<?php

namespace App\Models;

use Eloquent as Model;

/**
 * // toDo: set only fillable fields "RestaurantSet" block. And all other in RestaurantGet block.
 * @SWG\Definition(
 *      definition="RestaurantSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="full_name",
 *          description="full_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="address",
 *          description="address",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="description",
 *          description="description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="lng",
 *          description="lng",
 *          type="float",
 *          format="float"
 *      ),
 *      @SWG\Property(
 *          property="lat",
 *          description="lat",
 *          type="float",
 *          format="float"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="RestaurantGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/RestaurantSet"),
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

class Restaurant extends Model
{

    public $table = 'restaurants';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';



    public $fillable = [
        'name',
        'image',
        'full_name',
        'address',
        'description',
        'lng',
        'lat'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'image' => 'string',
        'full_name' => 'string',
        'address' => 'string',
        'description' => 'string',
        'lng' => 'float',
        'lat' => 'float'
    ];

    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'name' => 'required',
        'image' => 'required',
        'full_name' => 'required',
        'address' => 'required',
        'description' => 'required'
    ];

    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'name' => 'required',
        'image' => 'required',
        'full_name' => 'required',
        'address' => 'required',
        'description' => 'required'
    ];

    
}
