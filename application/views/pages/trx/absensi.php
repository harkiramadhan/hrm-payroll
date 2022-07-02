<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Transaksi Absensi</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-upload me-2"></i> Absensi</button>
                        <a href="<?= site_url('trx/absensi/download') ?>" class="btn btn-sm btn-round text-white bg-secondary mb-0 mx-1"><i class="fas fa-download me-2"></i><i class="fas fa-file-excel me-2"></i> Format</a>
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
                        <th class="text-center">File</th>
                        <th class="text-center w-5px">Total Row Gagal</th>
                        <th class="text-center w-5px">Total Row Berhasil</th>
                        <th class="text-center w-5px">Total Row</th>
                        <th>Timestamp</th>
                        <th class="text-center w-15p">Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
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
                            <div class="col-lg-12 mb-2">
                                <div class="d-grid">
                                    <a href="<?= site_url('trx/absensi/download') ?>" class="btn btn-sm btn-round text-white bg-secondary mb-0 mx-1"><i class="fas fa-download me-2"></i><i class="fas fa-file-excel me-2"></i> Format</a>
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