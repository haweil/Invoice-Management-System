<?php

namespace App\Exports;

use App\Models\invoices;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return invoices::all();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        // Get the columns from the database table
        $columns = \Schema::getColumnListing((new invoices())->getTable());

        // Format the headers as needed, e.g., capitalize or replace underscores
        $formattedHeaders = array_map(function($column) {
        // Example: Replace underscores with spaces and capitalize each word
            return ucwords(str_replace('_', ' ', $column));
        }, $columns);

        return $formattedHeaders;
    }

    /**
    * @param Worksheet $sheet
    * @return array
    */
    public function styles(Worksheet $sheet)
    {
        // Apply styling to the header row
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'rotation' => 90,
                'startColor' => ['argb' => 'FFA0A0A0'],
                'endColor' => ['argb' => 'FFFFFFFF'],
            ],
        ]);

        // Apply general styling to the entire sheet
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'font' => [
                'size' => 12,
            ],
        ]);

        return [];
    }
}