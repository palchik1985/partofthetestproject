<?php

namespace App\Views\Composers;

use App\Models\Menu\Subcategory;
use Illuminate\View\View;

class MenuSubcategoriesComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
    
        $subcategories = Subcategory::with('category')->get();
        $items         = [];
        foreach ($subcategories as $subcategory) {
            $items[ $subcategory->id ] = sprintf('%s (%s)', $subcategory->name, $subcategory->category->name);
        }
    
        $view->with('menuSubcategories', $items);
    }
}
