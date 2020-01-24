<?php

namespace App\Models\Menu;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * @SWG\Definition(
 *      definition="MenuSubcategorySet",
 *      type="object",
 *      required={"menu_category_id", "name", "order"},
 *      @SWG\Property(
 *          property="menu_category_id",
 *          description="menu_category_id",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="name",
 *          description="name",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="order",
 *          description="order",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="MenuSubcategoryGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/MenuSubcategorySet"),
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
class Subcategory extends Model
{
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'name'             => 'required',
        'menu_category_id' => 'required',
    ];
    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'name'             => 'required',
        'menu_category_id' => 'required',
    ];
    public        $table        = 'menu_subcategories';
    public        $fillable     = [
        'menu_category_id',
        'name',
        'order',
        'image',
    ];
    protected     $appends      = ['image_url'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'               => 'integer',
        'menu_category_id' => 'integer',
        'name'             => 'string',
        'order'            => 'integer',
        'image'            => 'string',
    ];
    
    public function getImageUrlAttribute()
    {
        
        if ( ! empty($this->image)) {
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/subcategories/' . $this->image);
        }
        
        return null;
    }
    
    public function category()
    {
        
        return $this->belongsTo('App\Models\Menu\Category', 'menu_category_id');
    }
}
