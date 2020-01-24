<?php

namespace App\Views\Composers;

use App\Models\Table;
use Illuminate\View\View;

class TablesComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        
        $view->with('tables', Table::pluck('name', 'id')->toArray());
    }
}
