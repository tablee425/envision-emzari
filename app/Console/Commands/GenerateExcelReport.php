<?php

namespace Arrow\Console\Commands;

use Illuminate\Console\Command;

use Excel;
use Arrow\Field;
use Arrow\DeliveryTicket;
use Arrow\DeliveryTicketItem;
use DB;
use Carbon\Carbon;

class GenerateExcelReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:generate-reports {field_id} {prior_year}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Excel Reports used for php 7.0.';

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
        // Authenticate the field here. Make sure company is permitted
        // to see it.
        $field = Field::find($this->argument('field_id'));
        $this->info(Excel::create($field->name, function($excel) use ($field) {
            if(!(int)$this->argument('prior_year')) // (!(int)$request->prior_year)
            {
                // return dd('current year.');
                $date = new Carbon('first day of January '.date('Y'), 'America/Vancouver');
                $untilDate = Carbon::now()->subMonth();
            } else {
                // return dd('prior year.');
                $date = new Carbon('first day of January '.(date('Y') - 1), 'America/Vancouver');
                $untilDate = new Carbon('first day of January '.date('Y'), 'America/Vancouver');
            }
            $oldDate = $date;
            while($date < $untilDate)
            {
                $continuousInjections = $field->injections()
                                    ->where('type','CONTINUOUS')
                                    ->where( DB::raw('MONTH(date)'), $date->month )
                                    ->where( DB::raw('YEAR(date)'), $date->year )
                                    ->get();
                $batchInjections = $field->injections()
                                    ->where('type','BATCH')
                                    ->where('scheduled_batches','>',0)
                                    ->where( DB::raw('MONTH(date)'), $date->month )
                                    ->where( DB::raw('YEAR(date)'), $date->year )
                                    ->get();


                if (! ($batchInjections->isEmpty() && $continuousInjections->isEmpty()))
                {
                    $excel->sheet($date->format('F Y'), function($sheet) use ($field, $batchInjections, $continuousInjections, $date) {
                        $sheet->loadView('reports.excel.field', compact('batchInjections', 'continuousInjections', 'field','date'))
                              ->setAutoSize(true)
                              ->mergeCells('A1:D3')
                              ->mergeCells('E1:T3')
                              ->mergeCells('A4:T4')
                              ->mergeCells('A5:T5')
                              // ->setWidth('A',5)
                              ->setColumnFormat(array(
                                    'P' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                    'Q' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                    'R' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                    'S' => \PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
                                    )
                              );
                    });
                }
                $oldDate = $date;
                $date = $date->addMonth();
            }
        })->store('xls', false, true)['full']);
    }
}
