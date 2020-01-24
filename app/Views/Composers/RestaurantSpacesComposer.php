<?php

namespace App\Views\Composers;

use App\Models\RestaurantSpace;
use Illuminate\View\View;

class RestaurantSpacesComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        
        $view->with('restaurantSpaces', RestaurantSpace::pluck('name', 'id')->toArray());
    }
}
