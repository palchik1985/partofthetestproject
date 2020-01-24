<?php

namespace App\Models\Menu;

use Eloquent as Model;

/**
 * @SWG\Definition(
 *      definition="MenuProductTypeSet",
 *      type="object",
 *      required={"name"},
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
 * ),
 * @SWG\Definition(
 *  definition="MenuProductTypeGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/MenuProductTypeSet"),
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
class ProductType extends Model
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
    public        $table        = 'menu_product_types';
    public        $fillable     = [
        'name',
        'description',
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'          => 'integer',
        'name'        => 'string',
        'description' => 'string',
    ];
}
