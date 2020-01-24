<?php

namespace App\Providers;

use App\Views\Composers\ClientsComposer;
use App\Views\Composers\MenuCategoriesComposer;
use App\Views\Composers\MenuSubcategoriesComposer;
use App\Views\Composers\MenuTypesComposer;
use App\Views\Composers\RestaurantsComposer;
use App\Views\Composers\RestaurantSpacesComposer;
use App\Views\Composers\TablePresetsComposer;
use App\Views\Composers\TablesComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    
    public function register()
    {
        //
        
    }
    
    
    public function boot()
    {
        
        View::composer(['partials.table_presets.*'], TablePresetsComposer::class);
        View::composer(['partials.restaurants.*'], RestaurantsComposer::class);
        View::composer(['partials.restaurant_spaces.*'], RestaurantSpacesComposer::class);
        View::composer(['partials.clients.*'], ClientsComposer::class);
        View::composer(['partials.tables.*'], TablesComposer::class);
        View::composer(['partials.menu_categories.*'], MenuCategoriesComposer::class);
        View::composer(['partials.menu_subcategories.*'], MenuSubcategoriesComposer::class);
        View::composer(['partials.menu_product_types.*'], MenuTypesComposer::class);
        
    }
}
