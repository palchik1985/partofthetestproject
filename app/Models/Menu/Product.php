<?php

namespace App\Models\Menu;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * @SWG\Definition(
 *      definition="MenuProductSet",
 *      type="object",
 *      required={"menu_subcategory_id", "title", "position_order"},
 *      @SWG\Property(
 *          property="menu_subcategory_id",
 *          description="menu_subcategory_id",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="r_keeper_id",
 *          description="r_keeper_id",
 *          type="integer"
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
 *          property="price",
 *          description="price",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="weight_gram",
 *          description="weight_gram",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="position_order",
 *          description="position_order",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="MenuProductGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/MenuProductSet"),
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
class Product extends Model
{
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'title'       => 'required',
        'price'       => 'required',
        'weight_gram' => 'required',
    ];
    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'title' => 'required',
    ];
    public        $table        = 'menu_products';
    public        $fillable     = [
        'menu_subcategory_id',
        'r_keeper_id',
        'title',
        'description',
        'price',
        'weight_gram',
        'position_order',
        'image',
    ];
    protected     $appends      = ['image_url'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'                  => 'integer',
        'menu_subcategory_id' => 'integer',
        'r_keeper_id'         => 'integer',
        'title'               => 'string',
        'description'         => 'string',
        'price'               => 'integer',
        'weight_gram'         => 'integer',
        'position_order'      => 'integer',
        'image'               => 'string',
    ];
    
    public function getImageUrlAttribute()
    {
        
        if ( ! empty($this->image)) {
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/products/' . $this->image);
        }
        
        return null;
    }
    
    public function subcategory()
    {
        
        return $this->belongsTo('App\Models\Menu\Subcategory', 'menu_subcategory_id');
    }
    
    public function types()
    {
        
        return $this->belongsToMany('App\Models\Menu\ProductType', 'menu_product_product_type');
    }
}
