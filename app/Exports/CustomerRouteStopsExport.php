<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CustomerRouteStopsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $stops;
    protected $routeName;
    protected $customerName;

    public function __construct($stops, $routeName, $customerName)
    {
        $this->stops = $stops;
        $this->routeName = $routeName;
        $this->customerName = $customerName;
    }

    public function collection()
    {
        $data = collect();
        
        foreach ($this->stops as $stop) {
            $data->push([
                'Durak Adı' => $stop->stop_name,
                'Saat'      => $stop->stop_time ? \Carbon\Carbon::parse($stop->stop_time)->format('H:i') : '-',
            ]);
        }
        
        if ($data->isEmpty()) {
            for ($i = 1; $i <= 10; $i++) {
                $data->push(['', '']);
            }
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [
            [mb_strtoupper($this->customerName . ' / ' . $this->routeName . ' DURAK BİLGİSİ')],
            [''],
            ['DURAK ADI', 'SAAT (SS:DD)']
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // Merge title row
        $sheet->mergeCells('A1:B1');
        
        // Title Style (Row 1)
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF1E293B'], // Slate-800
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFF1F5F9'], // Slate-100
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFCBD5E1'], // Slate-300
                ],
            ],
        ]);

        // Header Style (Row 3)
        $sheet->getStyle('A3:B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4F46E5'], // Indigo-600
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF3730A3'], // Indigo-800
                ],
            ],
        ]);
        
        // Row Styles
        if ($highestRow > 3) {
            $sheet->getStyle('A4:B' . $highestRow)->applyFromArray([
                'font' => [
                    'size' => 11,
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFE5E7EB'], // Gray-200
                    ],
                ],
            ]);
            
            // Center align the time column
            $sheet->getStyle('B4:B' . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
            
            // Zebra striping
            for ($row = 4; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A'.$row.':B'.$row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF9FAFB'); // Gray-50
                }
            }
        }

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(40);
        $sheet->getRowDimension(2)->setRowHeight(10);
        $sheet->getRowDimension(3)->setRowHeight(30);
        
        for ($row = 4; $row <= $highestRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(25);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 60, // Durak Adı
            'B' => 20, // Saat
        ];
    }
    
    public function title(): string
    {
        // Title cannot exceed 31 chars and cannot contain certain chars
        $safeTitle = substr(str_replace(['*', ':', '/', '\\', '?', '[', ']'], '', $this->routeName), 0, 31);
        return $safeTitle ?: 'Duraklar';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setFitToPage(true)
                    ->setFitToWidth(1)
                    ->setFitToHeight(1);
                    
                $event->sheet->getDelegate()->getPageMargins()
                    ->setTop(0.5)
                    ->setRight(0.5)
                    ->setLeft(0.5)
                    ->setBottom(0.5);
            },
        ];
    }
}
