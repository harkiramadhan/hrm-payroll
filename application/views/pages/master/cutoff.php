<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Cutoff</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-plus me-2"></i> Cutoff</button>
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
                        <th class="">Periode</th>
                        <th class="w-15p">Start Date</th>
                        <th class="w-15p">End Date</th>
                        <th class="w-15p">Status</th>
                        <th class="text-center w-5px">Action</th>
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
                        <h5 class="font-weight-bolder">Tambah Cutoff</h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('master/cutoff/create') ?>" role="form text-left" method="post">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>Bulan <small class="text-danger">*</small></label>
                                    <select name="bulan" class="form-control" id="exampleFormControlSelect1" required>
                                        <option value="" selected="" disabled="">- Pilih Bulan</option>
                                        <?php foreach(range(1,12) as $row){ ?>
                                            <option value="<?= $row ?>"><?= bulan($row) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label>Tahun <small class="text-danger">*</small></label>
                                    <select name="tahun" class="form-control" id="exampleFormControlSelect2" required>
                                        <option value="" selected="" disabled="">- Pilih Tahun</option>
                                        <?php foreach(range(date('Y') - 2,date('Y') + 2) as $y){ ?>
                                            <option value="<?= $y ?>"><?= $y ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Tanggal Mulai<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" placeholder="Tanggal Mulai" aria-label="Tanggal Mulai" name="start_date" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Tanggal Selesai<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" placeholder="Tanggal Selesai" aria-label="Tanggal Selesai" name="end_date" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Status<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="inlineRadio1" value="1" required="">
                                            <label class="form-check-label" for="inlineRadio1">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_active" id="inlineRadio2" value="2" required="">
                                            <label class="form-check-label" for="inlineRadio2">Non Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 mb-0 text-white">Tambahkan</button>
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