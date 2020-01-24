<?php

namespace App\Views\Composers;

use App\Models\TablePreset;
use Illuminate\View\View;

class TablePresetsComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        
        $view->with('tablePresets', TablePreset::pluck('name', 'id')->toArray());
    }
}
