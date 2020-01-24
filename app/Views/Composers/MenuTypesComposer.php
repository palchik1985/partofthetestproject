<?php

namespace App\Views\Composers;

use App\Models\Menu\ProductType;
use Illuminate\View\View;

class MenuTypesComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        
        $view->with('menuTypes', ProductType::pluck('name', 'id')->toArray());
    }
}
