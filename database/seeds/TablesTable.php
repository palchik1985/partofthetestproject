<?php

use Illuminate\Database\Seeder;

class TablesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Table::class, 30)->create();
    }
}
