<?php
    function pph21(){
        echo "\n";
        echo "Berapa pendapatan (gaji dan lainnya) Anda per bulan? ";
        $handle = fopen ("php://stdin","r");
        $gaji = fgets($handle);
        echo "Berapa pengeluaran (asuransi, jamsostek, dll) Anda per bulan? ";
        $handle = fopen ("php://stdin","r");
        $pengurangan = fgets($handle) ? : 0;
        echo "Berapa bonus dan THR Anda peroleh dalam setahun? ";
        $handle = fopen ("php://stdin","r");
        $thrbonus = fgets($handle) ? : 0;
        echo "Anda sudah menikah (Y/T)? ";
        $handle = fopen ("php://stdin","r");
        $nikah = fgets($handle);
        if(trim($nikah) === 'Y' || trim($nikah) === 'y'){
            $nikah = true;
        } else {
            $nikah = false;
        }
        echo "Jumlah tanggungan? ";
        $handle = fopen ("php://stdin","r");
        $tanggungan = fgets($handle);

        if ($nikah) {
            if ($tanggungan >= 3) {
                $ptkp = "30375000";
            } elseif ($tanggungan === 2) {
                $ptkp = "28350000";
            } elseif ($tanggungan === 1) {
                $ptkp = "26325000";
            } else {
                $ptkp = "24300000";
            }
        } else { 
            if ($tanggungan >= 3) {
                $ptkp = "32400000";
            } elseif ($tanggungan === 2) {
                $ptkp = "30375000";
            } elseif ($tanggungan === 1) {
                $ptkp = "28350000";
            } else {
                $ptkp = "26325000";
            }
        }

        $gajiTahunan = (12 * ($gaji - $pengurangan)) + $thrbonus;
        $biayaJabatan = 0.05 * $gajiTahunan;
        $gajiKenaPajak = $gajiTahunan - $biayaJabatan - $ptkp;

        $pajak = 0;
        if ($gajiKenaPajak > 0) {
            if ($gajiKenaPajak > 500000000) {
                $tier1 = 0.05 * 50000000;
                $tier2 = 0.1 * 200000000;
                $tier3 = 0.25 * 250000000;
                $tier4 = 0.3 * ($gajiKenaPajak - 500000000);
                $pajak = $tier1 + $tier2 + $tier3 + $tier4;
            } elseif ($gajiKenaPajak > 250000000) {
                $tier1 = 0.05 * 50000000;
                $tier2 = 0.1 * 200000000;
                $tier3 = 0.25 * ($gajiKenaPajak - 250000000);
                $pajak = $tier1 + $tier2 + $tier3;
            } elseif ($gajiKenaPajak > 50000000) {
                $tier1 = 0.05 * 50000000;
                $tier2 = 0.1 * ($gajiKenaPajak - 50000000);
                $pajak = $tier1 + $tier2;
            } else {
                $tier1 = 0.05 * $gajiKenaPajak;
                $pajak = $tier1;
            }
        }

        // echo "\n";
        // echo "==========================================\n";
        // echo "= Pajak per tahun : ".str_pad(number_format($pajak), 20, " ", STR_PAD_LEFT)." =\n";
        // echo "= Pajak per bulan : ".str_pad(number_format($pajak/12), 20, " ", STR_PAD_LEFT)." =\n";
        // echo "==========================================\n";

        return $pajak;
    }

    function pph21_bulanan(){
        $pajak = pph21();
        $total = str_pad(number_format($pajak/12), 20, " ", STR_PAD_LEFT);
        return $total;
    }