<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class ClientsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        DB::table('clients')->insert([
//            [
//                'id' => 1,
//                'name' => 'testClientName',
//                'email' => 'test_client@client.client',
//                'last_name' => 'testClientLastName',
//                'phone' => '+380501234567',
//                'avg_check' => '10000',
//                'comment' => 'Это просто тестовый клиент',
////                'image' => (new Faker)->image('public/img/clients', 48, 48, null, false)
//            ]
//        ]);
        factory(App\Models\Client::class, 10)->create();
        
    }
}
