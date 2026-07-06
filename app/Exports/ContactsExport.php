<?php

namespace App\Exports;

use Modules\Common\Entities\Contact;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ContactsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Contact::query()->latest();

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('name',    'like', '%' . $this->filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Full Name',
            'Email',
            'Phone',
            'Subject',
            'Message',
            'Status',
            'Admin Reply',
            'Replied At',
            'Received At',
        ];
    }

    public function map($contact): array
    {
        static $index = 0;
        $index++;

        return [
            $index,
            $contact->name,
            $contact->email,
            $contact->phone     ?? '—',
            $contact->subject   ?? '—',
            $contact->message,
            ucfirst($contact->status),
            $contact->admin_reply  ?? '—',
            $contact->replied_at   ? $contact->replied_at->format('d M Y, h:i A') : '—',
            $contact->created_at->format('d M Y, h:i A'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header row style
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size'  => 11,
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'FFFFFF'],
                ],
            ],
        ]);

        // Data rows — alternate row colors
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $color = ($row % 2 === 0) ? 'F8F9FF' : 'FFFFFF';
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $color],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'E5E7EB'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(30);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 25,
            'D' => 15,
            'E' => 25,
            'F' => 40,
            'G' => 12,
            'H' => 40,
            'I' => 20,
            'J' => 20,
        ];
    }

    public function title(): string
    {
        return 'Contact Messages';
    }
}
