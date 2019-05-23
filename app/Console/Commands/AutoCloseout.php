<?php

namespace Arrow\Console\Commands;

use Illuminate\Console\Command;
use Arrow\Injection;
use Carbon\Carbon;

class AutoCloseout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closeout:old {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close out injections for specific date.';

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
        $company_id = $this->argument('company_id'); // Corex Resources LTD
        $fields = \Arrow\Company::find($company_id)->fields;
        
        $month = $this->ask('What month do you want to close (01-12) [TWO DIGITS]:');
        $year = $this->ask('What year (eg: 2017) [XXXX]:');
        
        $fields->each(function($field) use ($year, $month) {
            $injectionsContinous = $field->injections()->where(
                \DB::raw("DATE_FORMAT(date, '%Y-%m')"), "$year-$month")
                ->where('status', '=', Injection::STATUS_ENABLED)
                ->where('type', '=', Injection::TYPE_CONTINUOUS)
                ->get();
            // return dd($injectionsContinous);
            $injectionsBatch = $field->injections()->where(
                \DB::raw("DATE_FORMAT(date, '%Y-%m')"), "$year-$month")
                ->where('status', '=', Injection::STATUS_ENABLED)
                ->where('type', '=', Injection::TYPE_BATCH)
                ->get();
            Injection::closeOut($injectionsContinous, $injectionsBatch);
        });
        
        // $dateString = ($this->option('date') == 'current') ? date('Y-m').'-01' : $this->option('date').'-01'; 
        
        // $date = Carbon::createFromFormat('Y-m-d', $dateString)->subMonths(2)->toDateString();
       
        // Injection::whereDate('date', '<', $date)->update(['status' => 1]);
        // $this->line('Injection records updated.');
    }
}
