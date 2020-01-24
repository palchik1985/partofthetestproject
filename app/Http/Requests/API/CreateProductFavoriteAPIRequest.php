<?php

namespace App\Http\Requests\API;

use App\Models\Menu\ProductFavorite;
use InfyOm\Generator\Request\APIRequest;

class CreateProductFavoriteAPIRequest extends APIRequest
{
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        
        return true;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return ProductFavorite::$create_rules;
    }
}
