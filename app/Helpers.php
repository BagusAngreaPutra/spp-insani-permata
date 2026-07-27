<?php

if (!function_exists('terbilang')) {
    function terbilang($angka) {
        $bilangan = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');
        
        if ($angka < 12) {
            return $bilangan[$angka];
        } elseif ($angka < 20) {
            return $bilangan[$angka - 10] . ' belas';
        } elseif ($angka < 100) {
            $puluhan = intval($angka / 10);
            $satuan = $angka % 10;
            return $bilangan[$puluhan] . ' puluh ' . $bilangan[$satuan];
        } elseif ($angka < 200) {
            return 'seratus ' . terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $ratusan = intval($angka / 100);
            return $bilangan[$ratusan] . ' ratus ' . terbilang($angka % 100);
        } elseif ($angka < 2000) {
            return 'seribu ' . terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $ribuan = intval($angka / 1000);
            return terbilang($ribuan) . ' ribu ' . terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $jutaan = intval($angka / 1000000);
            return terbilang($jutaan) . ' juta ' . terbilang($angka % 1000000);
        }
        
        return '';
    }
}

if (!function_exists('convertBulanToIndonesia')) {
    function convertBulanToIndonesia($namaTagihan) {
        // Daftar mapping bulan dalam bahasa Inggris ke bahasa Indonesia
        $bulanMap = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        
        // Ganti nama bulan dalam bahasa Inggris dengan bahasa Indonesia
        foreach ($bulanMap as $bulanInggris => $bulanIndo) {
            $namaTagihan = str_replace($bulanInggris, $bulanIndo, $namaTagihan);
        }
        
        return $namaTagihan;
    }
}