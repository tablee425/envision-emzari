<?php
namespace Arrow\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Sheet;
use Arrow\Field;
use Carbon\Carbon;
use DB;


class InjectionsExport implements WithMultipleSheets, ShouldAutoSize, WithEvents
{
    use Exportable, RegistersEventListeners;

    protected $year;
    protected $field;

    public function __construct(Field $field, bool $prior_year)
    {
        $this->prior_year = $prior_year;
        $this->field = $field;
        $this->__registerMacro();
    }

    public function sheets(): array
    {
        $sheets = [];
        if(!$this->prior_year)
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
            $continuousInjections = $this->field->injections()
                ->join('production', function($join) {
                    $join->on('production.location_id', '=', 'injections.location_id');
                    $join->on(DB::raw("DATE_FORMAT(production.date, '%Y-%m')"),'=', DB::raw("DATE_FORMAT(injections.date, '%Y-%m')"));
                })
                ->groupBy('injections.id')
                ->where('type','CONTINUOUS')
                ->where( DB::raw('MONTH(injections.date)'), $date->month )
                ->where( DB::raw('YEAR(injections.date)'), $date->year )
                ->select('injections.*', 'production.id as production_id')
                ->get();
            $continuousInjections->load('production');
            $batchInjections = $this->field->injections()
                ->join('production', function($join) {
                    $join->on('production.location_id', '=', 'injections.location_id');
                    $join->on(DB::raw("DATE_FORMAT(production.date, '%Y-%m')"),'=', DB::raw("DATE_FORMAT(injections.date, '%Y-%m')"));
                })
                ->groupBy('injections.id')
                ->where('type','BATCH')
                ->where('scheduled_batches','>',0)
                ->where( DB::raw('MONTH(injections.date)'), $date->month )
                ->where( DB::raw('YEAR(injections.date)'), $date->year )
                ->select('injections.*', 'production.id as production_id')
                ->get();
            $batchInjections->load('production');

            if (! ($batchInjections->isEmpty() && $continuousInjections->isEmpty()))
            {
                \Log::info($date);
                $sheets[] = new MonthlyInjectionsSheet($this->field, $batchInjections,
                                                       $continuousInjections, $date);
            }
            $oldDate = $date;
            $date = $date->copy()->addMonth();
        }
        return $sheets;
    }

    private function __registerMacro()
    {
        Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
            $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
        });
    }
}