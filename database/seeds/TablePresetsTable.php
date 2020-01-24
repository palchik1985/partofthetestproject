<?php

use Illuminate\Database\Seeder;

class TablePresetsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\TablePreset::class, 2)->create();
    }
}
