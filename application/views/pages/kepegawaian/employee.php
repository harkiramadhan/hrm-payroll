<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Pegawai</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="<?= site_url('kepegawaian/employee/add') ?>" class="btn btn-sm btn-round bg-gradient-dark mb-0"><i class="fas fa-plus me-2"></i> Pegawai</a>
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
                        <th class="text-center w-5px">NIP</th>
                        <th>Nama</th>
                        <th class="text-center w-10p">Cabang</th>
                        <th class="text-center w-10p">Divisi/Jabatan</th>
                        <th class="text-center">Departemen/Unit</th>
                        <th class="text-center">Template Tunjangan</th>
                        <th class="text-center w-5px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>