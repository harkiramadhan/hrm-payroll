<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Review PPH21</strong></h5>
                </div>
            </div>
            <!-- <p class="text-sm mb-0">
            A lightweight, extendable, dependency-free javascript HTML table plugin.
            </p> -->
        </div>
        <div class="table-responsive p-4">
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th >No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Cabang</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th>PTKP</th>
                        <th>Total Pendapatan <br> Take Home Pay Sebelum PPH</th>
                        <th>Potongan Tunjangan Jabatan <br> 5% Dari Total Pendapatan, Max. 500rb</th>
                        <th>Penghasilan Neto Sebulan <br> Tot. Pendapatan - Pot. Tunj. Jabatan</th>
                        <th>Penghasilan Neto Setahun <br> Penghasilan Neto * 12 </th>
                        <th>PTKP <br> Sesuai Dengan Ketentutan UU </th>
                        <th>PKP Setahun <br> Gaji KP - PTKP</th>
                        <?php foreach($pkp->result() as $row){ ?>
                        <th><?= $row->persentase ?>% <br> PKP Setahun <?= $row->text ?></th>
                        <?php } ?>
                        <th>PKP<br> Setahun</th>
                        <th>PKP<br> Sebulan</th>
                        <th>THP<br> Setelah PPH21</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>