<div class="container-fluid py-4">
    <div class="card">
        <?php if(@$cutoff->start_date == TRUE): ?>
            <!-- Card header -->
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="mb-0"><strong>Transaksi Absensi</strong></h5>
                        <h5 class="mb-0"><strong><?= @$cutoff->periode." : ".date_indo(date('Y-m-d', strtotime(@$cutoff->start_date)))." - ".date_indo(date('Y-m-d', strtotime(@$cutoff->end_date))) ?></strong></h5>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-upload me-2"></i> Absensi</button>
                            <a href="<?= base_url('assets/Format_Import_Absensi.xlsx') ?>" class="btn btn-sm btn-round text-white bg-secondary mb-0 mx-1" download><i class="fas fa-download me-1"></i><i class="fas fa-file-excel me-2"></i> Format .Xlsx</a>
                            <a href="<?= base_url('assets/Format_Import_Absensi.csv') ?>" class="btn btn-sm btn-round text-white bg-secondary mb-0" download><i class="fas fa-download me-1"></i><i class="fas fa-file-csv me-2"></i> Format .CSV</a>
                        </div>
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
                            <th class="text-center w-5px">Total Row</th>
                            <th>Timestamp</th>
                            <th class="text-center w-15p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card-body bg-warning rounded text-center">
                <h5 class="mb-0 text-white"><strong>Cutoff Belum Aktif, Silahkan Menuju Ke Menu Master/Cutoff Untuk Menambahkan/Mengaktifkan Cutoff Yang Digunakan</strong></h5>
                <h5 class="text-white"><strong>Klik Tombol Di Bawah Untuk Menuju Menu Cutoff</strong></h5>
                <a href="<?= site_url('master/cutoff') ?>" class="btn btn-sm btn-secondary"><i class="fas fa-link me-2"></i> Cutoff</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h5 class="font-weight-bolder">Import Absensi</h5>
                    </div>
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="alert bg-danger text-white text-center p-2" role="alert">
                                    <strong>Pastikan Anda Meng - Import File Sesuai Dengan Format Yang Sudah Di Sediakan!</strong>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-grid">
                                    <a href="<?= base_url('assets/Format_Import_Absensi.xlsx') ?>" class="btn btn-sm btn-round text-white bg-secondary mb-0 mx-1" download><i class="fas fa-download me-1"></i><i class="fas fa-file-excel me-2"></i> Download Format .Xlsx</a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-grid">
                                    <a href="<?= base_url('assets/Format_Import_Absensi.csv') ?>" class="btn btn-sm btn-round text-white bg-secondary mb-0" download><i class="fas fa-download me-1"></i><i class="fas fa-file-csv me-2"></i> Download Format .CSV</a>
                                </div>
                            </div>
                        </div>
                        <form action="<?= site_url('trx/absensi/import') ?>" role="form text-left" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Pilih File <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" placeholder="File" aria-label="File" name="file" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 mb-0 text-white">Import</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                        <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 data-edit">
              
            </div>
        </div>
    </div>
</div>