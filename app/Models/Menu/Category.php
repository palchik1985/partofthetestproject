<?php

namespace App\Models\Menu;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * @SWG\Definition(
 *      definition="MenuCategorySet",
 *      type="object",
 *      required={"name", "order"},
 *      @SWG\Property(
 *          property="restaurant_id",
 *          description="restaurant_id",
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
 *          property="with_images",
 *          description="with_images",
 *          type="boolean",
 *          default="false",
 *      ),
 *      @SWG\Property(
 *          property="image",
 *          description="image",
 *          type="string"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="MenuCategoryGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/MenuCategorySet"),
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
class Category extends Model
{
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'name' => 'required',
    ];
    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        'name' => 'required',
    ];
    public        $table        = 'menu_categories';
    public        $fillable     = [
        'restaurant_id',
        'name',
        'order',
        'image',
        'with_images',
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
        'name'          => 'string',
        'order'         => 'integer',
        'image'         => 'string',
        'with_images'   => 'boolean',
    ];
    
    public function getImageUrlAttribute()
    {
        
        if ( ! empty($this->image)) {
            return $this->attributes[ 'image_url' ] = Storage::disk('local')->url('img/categories/' . $this->image);
        }
        
        return null;
    }
}
