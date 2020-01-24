<?php

use Illuminate\Database\Seeder;

class ReservesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Reserve::class, 10)->create();
    }
}
