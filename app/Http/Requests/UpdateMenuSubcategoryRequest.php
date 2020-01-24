<?php

namespace App\Http\Requests;

use App\Models\Menu\Subcategory;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuSubcategoryRequest extends FormRequest
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
        
        return Subcategory::$update_rules;
    }
}
