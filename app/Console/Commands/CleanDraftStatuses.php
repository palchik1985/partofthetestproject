<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanDraftStatuses extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean_draft_statuses';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command cleans draft statuses on temporary unavailabled tables (when hosteses change something)';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        // todo добавить параметр, который запускает данный крон либо нет.
        // чтоб команда запускалась не постоянно, а только если кто-то меняет столы
        DB::connection('mysql')->table('tables')->where(
            'busy_now',
            '=',
            'draft'
        )->update(['busy_now' => null]);
    }
}
