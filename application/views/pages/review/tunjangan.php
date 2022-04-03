<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Review Tunjangan</strong></h5>
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
                        <th class="text-center w-5px">No</th>
                        <th>Nama</th>
                        <th class="text-center w-5px">Jabatan</th>
                        <th class="text-center w-5px">Total Hadir</th>
                        <?php foreach($tunjangan->result_array() as $row){ ?>
                            <th class="text-center w-5px"><?= $row['tunjangan'] ?></th>
                        <?php } ?>
                        <th class="text-center w-5px">Take Home Pay</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>