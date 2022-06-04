<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Jam Kerja</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-plus me-2"></i> Jam Kerja</button>
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
                        <th class="text-left w-5px">Kode</th>
                        <th class="text-left w-5px">Keterangan</th>
                        <th>Hari Kerja</th>
                        <th class="text-left w-10p">Jam (In)</th>
                        <th class="text-left w-10p">Jam (Out)</th>
                        <th class="text-left w-5px">Status</th>
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
                        <h5 class="font-weight-bolder">Tambah Jam Kerja</h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('master/working/create') ?>" role="form text-left" method="post">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Kode / Shift <small class="text-danger">*</small></label>
                                        <select name="shift_id" class="form-control" id="exampleFormControlSelect1" required="">
                                            <option value="" selected="" disabled="">- Pilih Kode / Shift</option>
                                            <?php foreach($shift->result() as $sr){ ?>
                                                <option value="<?= $sr->id ?>"><?= $sr->kode." - ".$sr->keterangan ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Hari Kerja <small class="text-danger">*</small></label>
                                        <select name="hari_kerja" class="form-control" id="exampleFormControlSelect1" required="">
                                            <option value="" selected="" disabled="">- Pilih Hari Kerja</option>
                                            <option value="Senin">Senin</option>
                                            <option value="Selasa">Selasa</option>
                                            <option value="Rabu">Rabu</option>
                                            <option value="Kamis">Kamis</option>
                                            <option value="Jum'at">Jum</option>
                                            <option value="Sabtu">Sabtu</option>
                                            <option value="Minggu">Minggu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Jam (In) <small class="text-danger">*) Format HH:MM</small></label>
                                    <div class="input-group mb-3">
                                        <input type="time" class="form-control" placeholder="Jam (In)" aria-label="Jam (In)" name="jam_in" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Jam (Out) <small class="text-danger">*) Format HH:MM</small></label>
                                    <div class="input-group mb-3">
                                        <input type="time" class="form-control" placeholder="Jam (Out)" aria-label="Jam (Out)" name="jam_out" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Status<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="t" required="">
                                            <label class="form-check-label" for="inlineRadio1">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="f" required="">
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