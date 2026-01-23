<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class AbsensiHarianExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    protected $tanggal;

    public function __construct($tanggal)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        return Absensi::with(['user.employee', 'lokasi'])
            ->whereDate('tanggal', $this->tanggal)
            ->orderBy('jam_masuk')
            ->get()
            ->map(function ($a) {
                return [
                    'nama'       => $a->user->employee->name ?? $a->user->name ?? '-',
                    'lokasi'     => $a->lokasi->nama_lokasi ?? '-',
                    'jam_masuk'  => $a->jam_masuk ?? '-',
                    'jam_pulang' => $a->jam_pulang ?? '-',
                    'status'     => strtoupper($a->status),
                    'keterangan' => $a->keterangan ?? '-',
                    'koordinat'   => $a->koordinat_masuk ?? $a->koordinat_pulang ?? '-',
                    'foto_masuk'  => $a->foto_masuk ?? '-',
                    'foto_pulang' => $a->foto_pulang ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Lokasi',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Keterangan',
            'Koordinat',
            'Foto Masuk',
            'Foto Pulang',
        ];
    }

    public function startCell(): string
    {
        return 'A8';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet;

                $startCell = $this->startCell();
                preg_match('/\d+/', $startCell, $matches);
                $startRow = $matches[0] ?? 1;

                /* ================= PAGE ================= */
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                /* ================= WIDTH ================= */
                $sheet->getColumnDimension('A')->setWidth(25);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getColumnDimension('G')->setWidth(20);
                $sheet->getColumnDimension('H')->setWidth(25); // Foto Masuk
                $sheet->getColumnDimension('I')->setWidth(25); // Foto Pulang

                /* ================= KOP SURAT (sama dengan PegawaiExport) ================= */
                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');

                $sheet->setCellValue('A1', 'BALAI PENGAWAS OBAT DAN MAKANAN KOTA KENDARI');
                $sheet->setCellValue('A2', 'Kompleks Bumi Praja Anduonohu Kota Kendari Sulawesi Tenggara 93232');
                $sheet->setCellValue('A3', 'Telp : (0401) 3195855 | Email : bpom_kendari@pom.go.id');

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');

                /* ================= LOGO ================= */
                if (file_exists(public_path('logo/logo-bpom.png'))) {
                    $logo = new Drawing();
                    $logo->setPath(public_path('logo/logo-bpom.png'));
                    $logo->setHeight(80);
                    $logo->setCoordinates('A1');
                    $logo->setWorksheet($sheet->getDelegate());
                }

                /* ================= GARIS BAWAH KOP ================= */
                $sheet->getStyle('A4:I4')->getBorders()->getBottom()
                    ->setBorderStyle(Border::BORDER_THICK);
                $sheet->mergeCells('A6:I6');
                $sheet->setCellValue('A6', 'LAPORAN ABSENSI HARIAN - Tanggal : ' . date('d F Y', strtotime($this->tanggal)));
                $sheet->getStyle('A6')->getFont()->setSize(12);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');

                /* ================= HEADER TABLE ================= */
                $sheet->getStyle("A{$startRow}:I{$startRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$startRow}:I{$startRow}")->getAlignment()->setHorizontal('center');

                /* ================= BORDER ================= */
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$startRow}:I{$lastRow}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /* ================= EMBED PHOTOS ================= */
                for ($row = $startRow + 1; $row <= $lastRow; $row++) {
                    // Foto Masuk in column H
                    $fotoMasuk = $sheet->getCell("H{$row}")->getValue();
                    $fotoPulang = $sheet->getCell("I{$row}")->getValue();

                    $sheet->getRowDimension($row)->setRowHeight(100);

                    // helper to resolve existing file path or download URL to temp file
                    $resolvePath = function ($p) {
                        if (!$p) return false;
                        $p = trim($p);

                        // if it's a URL, try to download into temp file
                        if (preg_match('#^https?://#i', $p)) {
                            try {
                                $contents = @file_get_contents($p);
                                if ($contents === false) return false;
                                $ext = pathinfo(parse_url($p, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                                $tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'excel_img_' . uniqid() . '.' . $ext;
                                @file_put_contents($tmp, $contents);
                                return $tmp;
                            } catch (\Exception $e) {
                                return false;
                            }
                        }

                        // try direct path
                        if (file_exists($p)) return $p;

                        // try public path variants
                        $candidates = [
                            public_path($p),
                            public_path('storage/' . ltrim($p, '/')),
                            storage_path('app/public/' . ltrim($p, '/')),
                            storage_path('app/' . ltrim($p, '/')),
                        ];
                        foreach ($candidates as $c) {
                            if ($c && file_exists($c)) return $c;
                        }

                        return false;
                    };

                    $pathMasuk = $resolvePath($fotoMasuk);
                    if ($pathMasuk) {
                        try {
                            $drawing = new Drawing();
                            $drawing->setPath($pathMasuk);
                            $drawing->setHeight(100);
                            $drawing->setWidth(150);
                            $drawing->setCoordinates("H{$row}");
                            $drawing->setWorksheet($sheet->getDelegate());
                            $sheet->setCellValue("H{$row}", '');
                        } catch (\Exception $e) {
                            // leave path text if embedding fails
                        }
                    }

                    $pathPulang = $resolvePath($fotoPulang);
                    if ($pathPulang) {
                        try {
                            $drawing = new Drawing();
                            $drawing->setPath($pathPulang);
                            $drawing->setHeight(100);
                            $drawing->setWidth(150);
                            $drawing->setCoordinates("I{$row}");
                            $drawing->setWorksheet($sheet->getDelegate());
                            $sheet->setCellValue("I{$row}", '');
                        } catch (\Exception $e) {
                            // leave path text if embedding fails
                        }
                    }
                }

                /* ================= REPEAT HEADER SAAT PRINT ================= */
                $sheet->getPageSetup()->setRowsToRepeatAtTop([$startRow, $startRow]);
            }
        ];
    }
}
