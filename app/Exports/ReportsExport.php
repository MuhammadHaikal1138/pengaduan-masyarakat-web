<?php

namespace App\Exports;

use App\Models\Report;
use App\Models\StaffProvince;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $reports;

    public function __construct($reports = null)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        // Jika laporan sudah diteruskan melalui constructor
        if ($this->reports) {
            return $this->reports;
        }

        // Jika tidak, ambil semua laporan
        return Report::with('response', 'responseProgress')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Email Pelapor',
            'Dilaporkan Pada Tanggal',
            'Deskripsi Pengaduan',
            'URL Gambar',
            'Lokasi',
            'Jumlah Voting',
            'Status Pengaduan',
            'Progres Tanggapan',
            'Staff Terkait',
        ];
    }

    public function map($report): array
    {
        $responseProgressHistories = $report->response?->responseProgress?->pluck('histories')->toArray();
        $staffProvince = StaffProvince::with('user')
            ->where('user_id', $report->response?->pluck('staff_id')->first())
            ->first()?->user->email ?? 'Belum Ditanggapi';

        return [
            $report->id,
            optional($report->user)->email,
            $report->created_at ? $report->created_at->format('d F Y') : '-',
            $report->description,
            $report->image,
            implode(', ', array_filter([
                $report->province,
                $report->regency,
                $report->subdistrict,
                $report->village,
            ])),
            $report->voting ? count(json_decode($report->voting)) : '0',
            $report->response?->pluck('response_status')->first() ?? 'Belum Ditanggapi',
            implode(', ', $responseProgressHistories),
            $staffProvince,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling untuk Heading
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFill()->setFillType('solid')->getStartColor()->setRGB('4CAF50');
        $sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('FFFFFF');

        // Set auto width for columns
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Styling untuk Deskripsi Pengaduan (kolom D)
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getStyle('D')->getAlignment()->setHorizontal('left');

        // Styling untuk seluruh kolom data agar rata kiri
        $sheet->getStyle('A2:J' . $sheet->getHighestRow())
            ->getAlignment()->setHorizontal('left')
            ->setVertical('center');

        return [];
    }
}