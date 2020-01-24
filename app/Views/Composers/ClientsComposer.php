<?php

namespace App\Views\Composers;

use App\Models\Client;
use Illuminate\View\View;

class ClientsComposer
{
    
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        
        $clients           = Client::select('id', 'name', 'last_name', 'phone', 'comment')->orderBy('name')->get();
        $formatted_clients = [];
        foreach ($clients as $client) {
            $formatted_clients[ $client->id ] = sprintf('%s %s. Tel: %s (%s)',
                $client->name, $client->last_name, $client->phone, $client->comment);
        }
        $view->with('clients', $formatted_clients);
    }
}
