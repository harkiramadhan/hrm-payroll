<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Invoice styling -->
		<style>
            @page *{
                margin-top: 0cm;
            }
            *{
                box-shadow: unset !important;
            }

            td{

                font-size: 12px !important;
            }

			body {
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				text-align: center;
				/* color: #777; */
			}

			body h1 {
				font-weight: 300; 
				margin-bottom: 0px;
				padding-bottom: 0px;
				color: #000;
			}

			body h3 {
				font-weight: 300; 
				font-style: italic;
				color: #555;
			}

			body a {
				color: #06f;
			}

			.invoice-box {
				margin: auto;
				/* padding: 30px; */
				/* border: 1px solid #eee; */
				/* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
				font-size: 10px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
				border-collapse: collapse;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(3) {
				/* text-align: right; */
			}

			.invoice-box table tr.top table td {
				padding-bottom: 10px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 12px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border: 1px solid #eee;
				/* border: 1px solid #eee; */
			}

			.invoice-box table tr.item.last td {
				/* border-bottom: none; */
			}

			.invoice-box table tr.total td:nth-child(2) {
				/* border-top: 2px solid #eee; */
				font-weight: bold;
			}
		</style>
</head>
<body>
    <div class="invoice-box" id="content" style="background-color: white;">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h1 style="margin: 0 0 0 0"><strong>Access Logistik</strong></h1> 
                                <h3 style="margin: 0 0 0 0"><strong>Gaji Bulan : <?= bulan($cutoff->bulan)." ".$cutoff->tahun ?> - Periode : <?= date('d/m/Y', strtotime($cutoff->start_date))." s/d ".date('d/m/Y', strtotime($cutoff->end_date)) ?></strong></h3>
                                <hr style="margin: 0 0 0 0">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Header Pegawai -->
        <table>
            <tr>
                <td width="10%"><h5 style="margin: 0 0 0 0">
                    <strong>Nama</strong>
                </h5></td>
                <td width="25%"><h5 style="margin: 0 0 0 0">
                    <strong>: &nbsp;&nbsp;<?= $summary->nama ?></strong>
                </h5></td>

                <td width="25%">&nbsp;</td>

                <td width="10%"><h5 style="margin: 0 0 0 0">
                    <strong>Departement</strong>
                </h5></td>
                <td width="25%"><h5 style="margin: 0 0 0 0">
                    <strong>: &nbsp;&nbsp;<?= $summary->departement ?></strong>
                </h5></td>
            </tr>
            <tr>
                <td width="10%"><h5 style="margin: 0 0 0 0">
                    <strong>Jabatan</strong>
                </h5></td>
                <td width="25%"><h5 style="margin: 0 0 0 0">
                    <strong>: &nbsp;&nbsp;<?= $summary->jabatan ?></strong>
                </h5></td>

                <td width="25%">&nbsp;</td>
                
                <td width="10%"><h5 style="margin: 0 0 0 0">
                    <strong>Status</strong>
                </h5></td>
                <td width="25%"><h5 style="margin: 0 0 0 0">
                    <strong>: &nbsp;&nbsp;</strong>
                </h5></td>
            </tr>
            <tr>
                <td>&nbsp;</td><td>&nbsp;</td>

                <td width="25%">&nbsp;</td>

                <td width="10%"><h5 style="margin: 0 0 0 0">
                    <strong>No. Rekening</strong>
                </h5></td>
                <td width="25%"><h5 style="margin: 0 0 0 0">
                    <strong>: &nbsp;&nbsp;<?= $summary->no_rekening ?></strong>
                </h5></td>
            </tr>
        </table>
        <br>

        <!-- Summary -->
        <table style="width: 60%">
            <tr>
                <td width="30%"><h5 style="margin: 0 0 0 0">
                    Total Hari Kerja
                </h5></td>
                <td width="70%"><h5 style="margin: 0 0 0 0">
                    : &nbsp;&nbsp;0 Hari Kerja
                </h5></td>
            </tr>
            <tr>
                <td width="30%"><h5 style="margin: 0 0 0 0">
                    Absensi
                </h5></td>
                <td width="70%"><h5 style="margin: 0 0 0 0">
                    : &nbsp;&nbsp;<?= @$kehadiran->row()->rit ?> Hadir / (Finger Print)
                </h5></td>
            </tr>
            <tr>
                <td width="30%"><h5 style="margin: 0 0 0 0">
                    Gaji Pokok
                </h5></td>
                <td width="70%"><h5 style="margin: 0 0 0 0">
                    : &nbsp;&nbsp;Rp. <?= rupiah(($summary->nominal_gapok != 0) ? $summary->nominal_gapok : 0) ?>,-
                </h5></td>
            </tr>
        </table>
        
        <table style="margin-top: 10px">
            <tr>
                <td width="50%">
                    <table width="50%"> 
                            <tr class="information">
                                <td style="padding-bottom:5px;" align="left" >
                                    <h4>Tunjangan</h4>
                                </td>
                            </tr>
                            <?php 
                                $totalTunjangan = [];
                                foreach($tunjangan->result() as $tr){ 
                                    $nominalTunjangan = (int)str_replace(['.', ','], '', $tr->nominal);

                                    if($tr->type == 1){
                                        array_push($totalTunjangan, $nominalTunjangan);
                                    }
                            ?>
                            <tr class="">
                                <td><?= $tr->tunjangan ?></td>
                                <td style="text-align: center!important; text-align: center; vertical-align: middle;">:</td>
                                <td><?= $tr->jumlah ?></td>
                                <td align="right">Rp.</td>
                                <td align="right">&nbsp;<?= rupiah($nominalTunjangan) ?>,-</td>
                            </tr>
                            <?php } ?>
                            <?php if($summary->nominal_insentif == TRUE && $summary->nominal_insentif != 0): ?>
                            <tr class="">
                                <td>Insentif</td>
                                <td style="text-align: center!important; text-align: center; vertical-align: middle;">:</td>
                                <td></td>
                                <td align="right">Rp.</td>
                                <td align="right">&nbsp;<?= rupiah($summary->nominal_insentif) ?> ,-</td>
                            </tr>
                            <?php endif; ?>
                            <tr class="heading">
                                <td align="right" style="padding-right: 20px; border: 0!important;" colspan="3">Total Pendapatan (Gross)</td>
                                <td align="right" style="border: 0!important;">Rp.</td>
                                <td align="right" style="border: 0!important;"><?= rupiah(array_sum($totalTunjangan) + $summary->nominal_gapok + $summary->nominal_insentif) ?> ,-</td>
                            </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="50%"> 
                            <tr class="information">
                                <td style="padding-bottom:5px;" align="left" >
                                    <h4>Potongan Atas Gaji</h4>
                                </td>
                            </tr>
                            <?php 
                                $totalTunjanganPotongan = [];
                                foreach($tunjanganPotongan->result() as $tpr){ 
                                    $nominalTunjanganPotongan = (int)str_replace(['.', ','], '', $tpr->nominal);
                                    array_push($totalTunjanganPotongan, $nominalTunjanganPotongan);
                            ?>
                            <tr class="">
                                <td><?= $tpr->tunjangan ?></td>
                                <td style="text-align: center!important; text-align: center; vertical-align: middle;">:</td>
                                <td><?= $tpr->jumlah ?></td>
                                <td align="right">Rp.</td>
                                <td align="right">&nbsp;<?= rupiah($nominalTunjanganPotongan) ?> ,-</td>
                            </tr>
                            <?php } ?>
                            <tr class="heading">
                                <td align="right" style="padding-right: 20px; border:0!important" colspan="3">Total Potongan</td>
                                <td style="border: 0!important;" align="right">Rp.</td>
                                <td style="border: 0!important;" align="right"><?= rupiah(array_sum($totalTunjanganPotongan)) ?> ,-</td>
                            </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 20px;"></td>
                <td align="center" rowspan="2" class="heading"><h1>
                    Take Home Pay<br> Rp. <?= rupiah($summary->thp) ?> ,-
                </h1></td>
            </tr>
        </table>
        
        <table style="page-break-inside: avoid; margin-top: 10px">
            <tr>
                <td width="50%" class="" style="text-align: center;">
                    Diterima Oleh,
                    <br>
                    <br>
                    <br>    
                    <br>
                    <br>
                    <h5>( <?= $summary->nama ?> )</h5>
                </td>
                <td width="50%" class="" style="text-align: center;">
                    Disetujui Oleh,
                    <br>
                    <br>
                    <br>    
                    <br>
                    <br>
                    <h5>( Chosyi Udin )</h5>
                </td>
            </tr>
        </table>
    </div> 
</body>
</html>