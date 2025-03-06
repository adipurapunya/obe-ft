<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateExport implements FromCollection, WithHeadings, WithStyles
{
    protected $mahasiswas;
    protected $inpnil;

    public function __construct($mahasiswas, $inpnil)
    {
        $this->mahasiswas = $mahasiswas;
        $this->inpnil = $inpnil;
    }

    public function collection()
    {
        // Ambil data mahasiswa dan tambahkan kolom untuk komponen penilaian
        return collect($this->mahasiswas)->map(function($mahasiswa, $index) {
            $row = [
                $index + 1, // Menambahkan nomor urut
                $mahasiswa->nim,
                $mahasiswa->nama_mahasiswa,
            ];

            foreach ($this->inpnil as $in) {
                // Menambahkan kolom kosong untuk setiap komponen penilaian
                $row[] = '';
            }

            // Menambahkan kolom kosong untuk Absolut dan Relatif
            $row[] = ''; // Absolut
            $row[] = ''; // Relatif

            return $row;
        });
    }

    public function headings(): array
    {
        $headings = [
            'No.', // Header untuk kolom nomor urut
            'NIM',
            'Nama Mahasiswa',
        ];

        foreach ($this->inpnil as $in) {
            //$headings[] = $in->jen_penilaian . ' - ' . $in->label . ' - ' . $in->kode_cpmk;
            $headings[] =  $in->label;
        }

        // Menambahkan header untuk Absolut dan Relatif
        $headings[] = 'Absolut';
        $headings[] = 'Relatif';

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        // Styling header agar tampil lebih menarik
        $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '5895BD'],
            ],
        ]);

        // Set auto size untuk setiap kolom
        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }
}
