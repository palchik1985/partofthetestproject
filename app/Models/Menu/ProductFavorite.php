<?php

namespace App\Models\Menu;

use Eloquent as Model;

/**
 * @SWG\Definition(
 *      definition="MenuProductFavoriteSet",
 *      type="object",
 *      required={"menu_product_id", "user_id"},
 *      @SWG\Property(
 *          property="menu_product_id",
 *          description="menu_product_id",
 *          type="integer"
 *      ),
 *      @SWG\Property(
 *          property="user_id",
 *          description="user_id",
 *          type="integer"
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="MenuProductFavoriteGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/MenuProductSet"),
 *  }
 * ),
 */
class ProductFavorite extends Model
{
    
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'menu_product_id' => 'required|exists:menu_products,id',
        'client_id'       => 'required|exists:clients,id',
    ];
    
    public $table = 'menu_product_favorite';
    
    public $fillable = [
        'menu_product_id',
        'client_id',
    ];
    
    protected $dates = false;
    
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'menu_product_id' => 'integer',
        'client_id'       => 'integer',
    ];
    
    public function client()
    {
        
        return $this->belongsTo('App\Models\Client');
        
    }
    
    public function product()
    {
        
        return $this->belongsTo('App\Models\Menu\Product', 'menu_product_id');
    }
}
