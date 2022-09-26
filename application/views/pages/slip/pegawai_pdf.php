<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji Pegawai </title>
    <!-- Invoice styling -->
		<style>
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
				max-width: 1000px;
				margin: auto;
				/* padding: 30px; */
				/* border: 1px solid #eee; */
				/* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
				font-size: 12px;
				line-height: 24px;
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

    <div class="container">
        <div class="row">
            <div class="col-8 offset-2">
                <!-- Content to capture -->
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
                    <table >
                        <tr>
                            <td width="30%"><h5 style="margin: 0 0 0 0">
                                Total Hari Kerja
                            </h5></td>
                            <td width="70%"><h5 style="margin: 0 0 0 0">
                                : &nbsp;&nbsp;<?= $summary->hari_efektif ?> Hari Kerja
                            </h5></td>
                        </tr>
                        <tr>
                            <td width="30%"><h5 style="margin: 0 0 0 0">
                                Absensi
                            </h5></td>
                            <td width="70%"><h5 style="margin: 0 0 0 0">
                                : &nbsp;&nbsp;<?= $summary->total_hadir ?> Hadir / (Finger Print)
                            </h5></td>
                        </tr>
                        <tr>
                            <td width="30%"><h5 style="margin: 0 0 0 0">
                                Gaji Pokok
                            </h5></td>
                            <td width="70%"><h5 style="margin: 0 0 0 0">
                                : &nbsp;&nbsp;Rp. <?= rupiah($summary->nominal_gapok) ?>,-
                            </h5></td>
                        </tr>
                    </table>
                    
                    <table style="margin-top: 10px;">
                        <tr class="information">
                            <td style="padding-bottom:5px;" align="left">
                                <h4>Tunjangan</h4>
                            </td>
                        </tr>
                        <?php 
                            $totalTunjangan = [];
                            foreach($tunjangan->result() as $tr){ 
                                $nominalTunjangan = '';
                                $jumlah =  rupiah($tr->nominal)." * X";
                                $getTunjangan = $this->db->get_where('summary_tunjangan', [
                                    'pegawai_id' => $summary->pegawai_id,
                                    'tunjangan_id' => $tr->id,
                                    'review_cutoff_id' => $summary->review_cutoff_id
                                ]);

                                if($getTunjangan->num_rows() > 0) {
                                    $nominalTunjangan = (int)str_replace(['.', ','], '', $getTunjangan->row()->nominal);
                                    $jumlah = $getTunjangan->row()->jumlah;
                                }
                                array_push($totalTunjangan, $nominalTunjangan);
                        ?>
                        <tr class="">
                            <td><?= $tr->tunjangan ?></td>
                            <td style="text-align: center!important; text-align: center; vertical-align: middle;">:</td>
                            <td><?= $jumlah ?></td>
                            <td> = Rp.&nbsp;<?= rupiah($nominalTunjangan) ?>,-</td>
                        </tr>
                        <?php } ?>
                        <tr class="heading">
                            <td align="right" style="padding-right: 20px;" colspan="3">Total Pendapatan (Gross)</td>
                            <td>Rp. <?= rupiah(array_sum($totalTunjangan) + $summary->nominal_gapok) ?>,-</td>
                        </tr>
                    </table>

                    <br>
                    
                    <table id="ttd" style="page-break-inside: avoid;">
                        <tr>
                            <td width="" class="">
                                Diterima Oleh,
                                <br>
                                <br>
                                <br>    
                                <br>
                                <br>
                                <br>
                                ( <?= $summary->nama ?> )
                            </td>
                            <td width="" class="" style="text-align: right;">
                                Disetujui Oleh,
                                <br>
                                <br>
                                <br>    
                                <br>
                                <br>
                                <br>
                                ( Chosyi Udin )
                            </td>
                        </tr>
                        <tr>
                            <td class="">
                            </td>
                            <td class="">
                            </td>
                        </tr>
                        <tr>
                            <td class="">
                            </td>
                            <td class="">
                            </td>
                        </tr>
                    </table>
                </div> 
            </div>
        </div>
    </div>
</body>
</html>