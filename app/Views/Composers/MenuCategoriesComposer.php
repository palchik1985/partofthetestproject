<?php

namespace App\Views\Composers;

use App\Models\Menu\Category;
use Illuminate\View\View;

class MenuCategoriesComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        
        $view->with('menuCategories', Category::pluck('name', 'id')->toArray());
    }
}
