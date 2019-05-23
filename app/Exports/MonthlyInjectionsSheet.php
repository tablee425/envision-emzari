<?php
namespace Arrow\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;

class MonthlyInjectionsSheet implements WithColumnFormatting, FromView, WithTitle, WithEvents, WithDrawings
{
    use Exportable, RegistersEventListeners;

    private $month;
    private $year;
    private $field;
    private $batchInjections;
    private $continuousInjections;

    public function __construct($field, $batchInjections, $continuousInjections, $date)
    {
        $this->date = $date;
        $this->batchInjections = $batchInjections;
        $this->continuousInjections = $continuousInjections;
        $this->field = $field;
    }

    public function view(): View
    {
        return view('reports.excel.field', [
            'batchInjections' => $this->batchInjections,
            'continuousInjections' => $this->continuousInjections,
            'field' => $this->field,
            'date' => $this->date,
        ]);

    }

    public function columnFormats(): array
    {
        return array(
            'P' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'Q' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'R' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        );
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->date->format('F') . ' '. $this->date->year;
    }

    public static function afterSheet(AfterSheet $event)
    {
        // Merge Cells
        $event->sheet->getDelegate()->mergeCells('A1:D3');
        $event->sheet->getDelegate()->mergeCells('E1:T3');
        $event->sheet->getDelegate()->mergeCells('A4:T4');
        $event->sheet->getDelegate()->mergeCells('A5:T5');

        // Cell Height
        $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(220); // row 1
        $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(40);  // row 4
        $event->sheet->getDelegate()->getRowDimension('6')->setRowHeight(60);  // row 6

        // Column Width
        $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
        $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(26);
        $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(26);
        $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('H')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('I')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('J')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('K')->setWidth(20);
        $event->sheet->getDelegate()->getColumnDimension('L')->setWidth(12);
        $event->sheet->getDelegate()->getColumnDimension('M')->setWidth(10);
        $event->sheet->getDelegate()->getColumnDimension('N')->setWidth(22);
        $event->sheet->getDelegate()->getColumnDimension('O')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('P')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('Q')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('R')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('S')->setWidth(16);
        $event->sheet->getDelegate()->getColumnDimension('T')->setWidth(50);

        // Cell Colors
        $style = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $event->sheet->styleCells('A4:T4', array_merge($style,['font' => ['size' => 18, 'underline' => true, 'bold' => true,
            'color' => ['argb' => \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE]]]));

            $event->sheet->styleCells('A6:T6', $style);

        $event->sheet->styleCells('E1', [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'font' => [
                'bold' => true,
                'size' => 18
            ]
        ]);
    }

    public function drawings()
    {
        $logo = 'logos/'.$this->field->area->company_id.'.'.$this->field->area->company->logo_extension;
        if(file_exists(public_path($logo)))
        {
            $path = public_path($logo);
        } else {
            $path = public_path('logos/sterling.jpg');
        }
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setPath($path);
        $drawing->setHeight(200);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(100);
        $drawing->setOffsetY(40);

        return $drawing;
    }
}