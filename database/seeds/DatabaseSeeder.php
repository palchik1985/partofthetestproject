<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ClientsTable::class);
         $this->call(EventsTable::class);
         $this->call(ReservesTable::class);
         $this->call(RestaurantSpacesTable::class);
         $this->call(RestaurantsTable::class);
         $this->call(TablePresetsTable::class);
         $this->call(TablesForDateTable::class);
         $this->call(TablesTable::class);
         $this->call(UsersTable::class);
    }
}
