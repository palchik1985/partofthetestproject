<?php

namespace App\Views\Composers;

use App\Models\Restaurant;
use Illuminate\View\View;

class RestaurantsComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        
        $view->with('restaurants', Restaurant::pluck('name', 'id')->toArray());
    }
}
