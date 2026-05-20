<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CustomerRouteStopsExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    protected $stops;
    protected $routeName;

    public function __construct($stops, $routeName)
    {
        $this->stops = $stops;
        $this->routeName = $routeName;
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
        
        // Add a few empty rows to serve as a template if it's empty
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
            'DURAK ADI',
            'SAAT (SS:DD)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        
        // Header Style
        $sheet->getStyle('A1:B1')->applyFromArray([
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
        if ($highestRow > 1) {
            $sheet->getStyle('A2:B' . $highestRow)->applyFromArray([
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
            $sheet->getStyle('B2:B' . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ]);
            
            // Zebra striping
            for ($row = 2; $row <= $highestRow; $row++) {
                if ($row % 2 == 0) {
                    $sheet->getStyle('A'.$row.':B'.$row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFF9FAFB'); // Gray-50
                }
            }
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Set row height for content
        for ($row = 2; $row <= $highestRow; $row++) {
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
}
