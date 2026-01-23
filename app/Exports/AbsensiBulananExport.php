<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class AbsensiBulananExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Absensi::with(['user.employee', 'lokasi'])
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->orderBy('tanggal')
            ->orderBy('jam_masuk')
            ->get()
            ->map(function ($a) {
                return [
                    'tanggal'    => date('d-m-Y', strtotime($a->tanggal)),
                    'nama'       => $a->user->employee->name ?? $a->user->name ?? '-',
                    'lokasi'     => $a->lokasi->nama_lokasi ?? '-',
                    'jam_masuk'  => $a->jam_masuk ?? '-',
                    'jam_pulang' => $a->jam_pulang ?? '-',
                    'status'     => strtoupper($a->status),
                    'keterangan' => $a->keterangan ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Pegawai',
            'Lokasi',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Keterangan',
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

                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_PORTRAIT)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                $sheet->getColumnDimension('A')->setWidth(20);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(20);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(25);

                // Kop surat (sama dengan PegawaiExport)
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                $sheet->mergeCells('A3:G3');

                $sheet->setCellValue('A1', 'BALAI PENGAWAS OBAT DAN MAKANAN KOTA KENDARI');
                $sheet->setCellValue('A2', 'Kompleks Bumi Praja Anduonohu Kota Kendari Sulawesi Tenggara 93232');
                $sheet->setCellValue('A3', 'Telp : (0401) 3195855 | Email : bpom_kendari@pom.go.id');

                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');

                if (file_exists(public_path('logo/logo-bpom.png'))) {
                    $logo = new Drawing();
                    $logo->setPath(public_path('logo/logo-bpom.png'));
                    $logo->setHeight(80);
                    $logo->setCoordinates('A1');
                    $logo->setWorksheet($sheet->getDelegate());
                }

                $sheet->getStyle('A4:G4')->getBorders()->getBottom()
                    ->setBorderStyle(Border::BORDER_THICK);

                $sheet->mergeCells('A6:G6');
                    $monthNum = intval($this->bulan);
                    $months = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    $monthName = $months[$monthNum] ?? $this->bulan;
                    $sheet->setCellValue('A6', 'LAPORAN ABSENSI BULANAN - Bulan: ' . $monthName . ' ' . $this->tahun);
                $sheet->getStyle('A6')->getFont()->setSize(12);
                $sheet->getStyle('A6')->getAlignment()->setHorizontal('center');

                $sheet->getStyle("A{$startRow}:G{$startRow}")->getFont()->setBold(true);
                $sheet->getStyle("A{$startRow}:G{$startRow}")->getAlignment()->setHorizontal('center');

                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A{$startRow}:G{$lastRow}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // Tambah area tanda tangan Kepala BPOM di bawah tabel (kanan)
                $sigRow = $lastRow + 3;

                // cari user dengan role yang mengindikasikan kepala balai
                $kepalaUser = User::whereHas('role', function ($q) {
                    $q->whereRaw('LOWER(name) LIKE ?', ['%kepala%']);
                })->with('employee')->first();

                $title = 'Kepala BPOM Kota Kendari';
                $namaKepala = 'Nama Kepala BPOM';
                $nipKepala = 'NIP. 0000000000';

                if ($kepalaUser && $kepalaUser->employee) {
                    $namaKepala = $kepalaUser->employee->name ?? $kepalaUser->name;
                    $nipKepala = 'NIP. ' . ($kepalaUser->employee->nip ?? '');
                }

                $sheet->mergeCells("E{$sigRow}:G{$sigRow}");
                $sheet->setCellValue("E{$sigRow}", $title);
                $sheet->getStyle("E{$sigRow}:G".($sigRow+6))->getAlignment()->setHorizontal('center');

                // ruang untuk tanda tangan: nama di atas garis, NIP di bawah garis
                $sheet->mergeCells("E".($sigRow+2).":G".($sigRow+2));
                $sheet->setCellValue("E".($sigRow+2), '');

                // nama di atas garis
                $sheet->mergeCells("E".($sigRow+4).":G".($sigRow+4));
                $sheet->setCellValue("E".($sigRow+4), $namaKepala);

                // garis tanda tangan
                $sheet->mergeCells("E".($sigRow+5).":G".($sigRow+5));
                $sheet->setCellValue("E".($sigRow+5), '_____________________________');

                // NIP di bawah garis
                $sheet->mergeCells("E".($sigRow+6).":G".($sigRow+6));
                $sheet->setCellValue("E".($sigRow+6), $nipKepala);

                $sheet->getPageSetup()->setRowsToRepeatAtTop([$startRow, $startRow]);
            }
        ];
    }
}
