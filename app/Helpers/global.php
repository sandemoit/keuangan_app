<?php

use App\Models\Laporan;
use App\Models\Transaction;
use Carbon\Carbon;

if (! function_exists('saldo_sum')) {
    function saldo_sum($jenisTotal, $date = null)
    {
        $dateNow = date('Y-m');
        $now = Carbon::now();

        $datePattern = $date == Carbon::now()->format('Y-m-d') ? '%Y-%m-%d' : '%Y-%m';

        $pemasukan = Transaction::query()
            ->when($date, function ($query, $date) use ($dateNow, $datePattern) {
                if ($date === $dateNow) {
                    return $query->whereRaw("DATE_FORMAT(date_trx, '$datePattern') = ?", [$dateNow]);
                }
                return $query->whereRaw("DATE_FORMAT(date_trx, '$datePattern') = ?", [$date]);
            })
            ->where('type', 'income')
            ->sum('amount');

        $pengeluaran = Transaction::query()
            ->when($date, function ($query, $date) use ($dateNow, $datePattern) {
                if ($date === $dateNow) {
                    return $query->whereRaw("DATE_FORMAT(date_trx, '$datePattern') = ?", [$dateNow]);
                }
                return $query->whereRaw("DATE_FORMAT(date_trx, '$datePattern') = ?", [$date]);
            })
            ->where('type', 'expense')
            ->sum('amount');

        $saldoBulanLalu = Laporan::where('bulan', Carbon::now()->subMonth()->format('m'))->where('tahun', $now->year)->value('saldo') ?? 0;

        switch ($jenisTotal) {
            case 'income':
                return $pemasukan;
            case 'expense':
                return $pengeluaran;
            case 'saldo':
                return $pemasukan + $saldoBulanLalu - $pengeluaran;
            case 'selisih':
                return $pemasukan - $pengeluaran;
            default:
                return 0;
        }
    }
}

if (! function_exists('rupiah')) {
    function rupiah($angka)
    {
        return number_format($angka, 0, ',', '.');
    }
}

/**
 * Helper untuk format tanggal Indonesia
 * 
 * @param string|DateTime $tanggal Tanggal yang akan diformat
 * @param bool $withTime Apakah waktu juga ditampilkan
 * @return string
 */
function tanggal($tanggal, $withTime = false)
{
    if (!$tanggal) {
        return '-';
    }

    // Jika input berupa string, konversi ke DateTime
    if (is_string($tanggal)) {
        $tanggal = new DateTime($tanggal);
    }

    // Array nama bulan dalam bahasa Indonesia
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    // Array nama hari dalam bahasa Indonesia
    $hari = [
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    ];

    $nama_hari = $hari[$tanggal->format('w')];
    $nama_bulan = $bulan[$tanggal->format('n')];
    $tahun = $tanggal->format('Y');
    $tanggal_hari = $tanggal->format('d');

    $hasil = "$tanggal_hari $nama_bulan $tahun";

    // Jika withTime true, tambahkan format waktu 
    if ($withTime) {
        $waktu = $tanggal->format('H:i');
        $hasil .= " pukul $waktu WIB";
    }

    return $hasil;
}

/**
 * Helper untuk format tanggal Indonesia dengan hari
 * 
 * @param string|DateTime $tanggal Tanggal yang akan diformat
 * @param bool $withTime Apakah waktu juga ditampilkan
 * @return string
 */
function tanggal_hari($tanggal, $withTime = false)
{
    if (!$tanggal) {
        return '-';
    }

    // Jika input berupa string, konversi ke DateTime
    if (is_string($tanggal)) {
        $tanggal = new DateTime($tanggal);
    }

    // Array nama bulan dalam bahasa Indonesia
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    // Array nama hari dalam bahasa Indonesia
    $hari = [
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    ];

    $nama_hari = $hari[$tanggal->format('w')];
    $nama_bulan = $bulan[$tanggal->format('n')];
    $tahun = $tanggal->format('Y');
    $tanggal_hari = $tanggal->format('d');

    $hasil = "$nama_hari, $tanggal_hari $nama_bulan $tahun";

    // Jika withTime true, tambahkan format waktu 
    if ($withTime) {
        $waktu = $tanggal->format('H:i');
        $hasil .= " pukul $waktu WIB";
    }

    return $hasil;
}
