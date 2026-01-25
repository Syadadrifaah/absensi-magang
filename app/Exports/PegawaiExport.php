<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;


class PegawaiExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
   public function collection()
    {
        return Employee::select(
            'name',
            'nip',
            'position_id',
            'department',
            'email',
            'phone',
        )->get();
    }


    public function headings(): array
    {
        return [
            'Nama',
            'NIP',
            'Jabatan',
            'Departemen',
            'Email',
            'No. HP',
        ];
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function registerEvents(): array
    {
        $startCell = $this->startCell();
        preg_match('/\d+/', $startCell, $matches);
        $startRow = $matches[0] ?? 1;

        return [
            AfterSheet::class => function (AfterSheet $event) use ($startRow) {

                $sheet = $event->sheet;

                /* ================= PAGE SETUP ================= */
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                /* ================= KOLOM ================= */
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(20);
                $sheet->getColumnDimension('E')->setWidth(30);
                $sheet->getColumnDimension('F')->setWidth(18);

                /* ================= KOP SURAT ================= */
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A3:F3');

                $sheet->setCellValue('A1', 'BALAI PENGAWAS OBAT DAN MAKANAN DI KENDARI');
                $sheet->setCellValue('A2', 'Kompleks Bumi Praja Anduonohu Kota Kendari Sulawesi Tenggara 93232');
                $sheet->setCellValue('A3', 'Telp : (0401) 3195855 | Email : bpom_kendari@pom.go.id');

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');

                /* ================= LOGO ================= */
                $logo = new Drawing();
                $logo->setPath(public_path('logo/logo-bpom.png'));
                $logo->setHeight(80);
                $logo->setCoordinates('A1');
                $logo->setWorksheet($sheet->getDelegate());

                /* ================= GARIS BAWAH KOP ================= */
                $sheet->getStyle('A4:F4')->getBorders()->getBottom()
                    ->setBorderStyle(Border::BORDER_THICK);

                $sheet->mergeCells('A6:F6');
                $sheet->setCellValue('A6', 'LAPORAN DATA PEGAWAI BALAI PENGAWAS OBAT DAN MAKANAN KOTA KENDARI 2026');
                $sheet->getStyle('A6')->getFont()->setSize(12);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');

                /* ================= HEADER TABEL ================= */
                $sheet->getStyle("A{$startRow}:F{$startRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$startRow}:F{$startRow}")->getAlignment()->setHorizontal('center');

                /* ================= BORDER TABEL ================= */
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$startRow}:F{$lastRow}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /* ================= REPEAT HEADER SAAT PRINT ================= */
                $sheet->getPageSetup()->setRowsToRepeatAtTop([$startRow, $startRow]);
            }
        ];
    }
}
