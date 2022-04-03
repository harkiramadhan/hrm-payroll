<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Pegawai</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <a href="<?= site_url('master/employee/add') ?>" class="btn btn-sm btn-round bg-gradient-dark mb-0"><i class="fas fa-plus me-2"></i> Pegawai</a>
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
                        <th class="text-center">NIK</th>
                        <th>Nama</th>
                        <th class="text-center">E-Ktp</th>
                        <th class="text-center">Tanggal Lahir</th>
                        <th class="text-center">Nikah</th>
                        <th class="text-center">Agama</th>
                        <th class="text-center">Pendidikan</th>
                        <th class="text-center">Company</th>
                        <th class="text-center">Jabatan</th>
                        <th class="text-center">Divisi</th>
                        <th class="text-center">Departemen</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Last Update</th>
                        <th class="text-center w-5px">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>