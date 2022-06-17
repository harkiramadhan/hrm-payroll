<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Detail Template Tunjangan <?= $template_tunjangan->nama ?></strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-plus me-2"></i> Tunjangan</button>
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
                        <th class="">Tunjangan</th>
                        <th class="w-15p">Role</th>
                        <th class="w-5px">Nominal</th>
                        <th class="text-center w-15p">Tipe</th>
                        <th class="text-center w-5px">Status</th>
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
                        <h5 class="font-weight-bolder">Tambah Tunjangan</h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('master/template_tunjangan/createDetail') ?>" role="form text-left" method="post">
                            <input type="hidden" name="template_id" value="<?= $this->uri->segment(3) ?>">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Tunjangan <small class="text-danger">*</small></label>
                                    <select name="tunjangan_id" class="form-control" id="exampleFormControlSelect1">
                                        <option value="" selected="" disabled="">- Pilih Tunjangan</option>
                                        <?php foreach($tunjangan->result() as $row){ ?>
                                            <option value="<?= $row->id ?>"><?= $row->tunjangan." - ".$row->satuan." - ".$row->keterangan ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label>Nominal<small class="text-danger">*) Rupiah / Persentase</small></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Nominal" aria-label="Nominal" name="nominal" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Tipe<small class="text-danger">*) Rupiah / Persentase</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="inlineRadio1Type" value="N" required="">
                                            <label class="form-check-label" for="inlineRadio1Type">Nominal</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="type" id="inlineRadio2Type" value="P" required="">
                                            <label class="form-check-label" for="inlineRadio2Type">Persentase</label>
                                        </div>
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