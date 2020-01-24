<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * // toDo: set only fillable fields "ClientSet" block. And all other in ClientGet block.
 * @SWG\Definition(
 *      definition="ClientSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="last_name",
 *          description="last_name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="email",
 *          description="email",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="birthday",
 *          description="birthday",
 *          type="string",
 *          format="date"
 *      ),
 *      @SWG\Property(
 *          property="phone",
 *          description="phone",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="comment",
 *          description="comment",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="bio",
 *          description="bio",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="ClientGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/ClientSet"),
 *    @SWG\Schema(
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="avg_check",
 *          description="avg_check",
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

class Client extends Model
{

    public $table = 'clients';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $appends = [ 'image_url' ];

    public $fillable = [
        'name',
        'last_name',
        'email',
        'birthday',
        'phone',
        'avg_check',
        'comment',
        'bio',
        'image'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer',
        'name'      => 'string',
        'last_name' => 'string',
        'email'     => 'string',
        'birthday'  => 'date',
        'phone'     => 'string',
        'avg_check' => 'integer',
        'comment'   => 'string',
        'bio'       => 'string',
        'image'     => 'string'
    ];

    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'name'  => 'required',
        'phone' => 'required',
        'image' => 'image',
    ];

    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'name'  => 'required',
        'phone' => 'required',
        'image' => 'image',
    ];
    
    
    public function getImageUrlAttribute()
    {
        if(!empty($this->image)){
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/clients/' . $this->image);
        }
        return null;
    }
    
    public function reserves()
    {
        
        return $this->hasMany('App\Models\Reserve');
    }
    
    public function menu_product_favorites()
    {
        
        return $this->belongsToMany('App\Models\Menu\Product', 'menu_product_favorite', 'client_id', 'menu_product_id');
    }
    
    public static function getByPhone($phone)
    {
        
        return self::where('phone', '=', $phone)->first();
    }
    
    public static function getByKey($api_key)
    {
    
        return self::where('api_token', '=', $api_key)->first();
    }
    
    public static function getById($client_id)
    {
    
        return self::find($client_id);
    }
}
