<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Menu</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-plus me-2"></i> Menu</button>
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
                        <th class="text-center w-5px">Id</th>
                        <th class="text-left w-5px">Menu</th>
                        <th class="text-center w-5px">Urut</th>
                        <th class="text-center w-5px">icon</th>
                        <th class="text-center w-5px">Status</th>
                        <th class="text-left w-5px">Url</th>
                        <th class="text-center w-5px">Dropdown</th>
                        <th class="text-center w-5px">Root</th>
                        <th class="text-center w-5px">Root Menu</th>
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
                        <h5 class="font-weight-bolder">Tambah Menu</h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('master/menus/create') ?>" role="form text-left" method="post">
                            <div class="row">
                                <div class="col-lg-5">
                                    <label>Menu <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Menu" aria-label="Menu" name="menu" required>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <label>Url <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Url" aria-label="Url" name="url" required>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label>Urut <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" placeholder="Urut" aria-label="Urut" name="urut" required>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label>Status<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio1Status" value="t" required="">
                                            <label class="form-check-label" for="inlineRadio1Status">Aktif</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadio2Status" value="f" required="">
                                            <label class="form-check-label" for="inlineRadio2Status">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label>Is Root ?<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="root" id="inlineRadioRoot1" value="t" required="">
                                            <label class="form-check-label" for="inlineRadioRoot1">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="root" id="inlineRadioRoot2" value="f" required="">
                                            <label class="form-check-label" for="inlineRadioRoot2">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label>Is Dropdown ?<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="dropdown" id="inlineRadio1" value="t" required="">
                                            <label class="form-check-label" for="inlineRadio1">Ya</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="dropdown" id="inlineRadio2" value="f" required="">
                                            <label class="form-check-label" for="inlineRadio2">Tidak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Child From ?</label>
                                        <select name="root_id" class="form-control" id="exampleFormControlSelect1">
                                            <option value="" selected="" disabled="">- Pilih Root Menu</option>
                                            <?php foreach($root->result() as $row){ ?>
                                                <option value="<?= $row->id ?>"><?= $row->menu ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <label>Pilih Icon <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3 justify-content-center" style="max-height: 350px!important; overflow-y: scroll;">
                                        <?php foreach($fontawesome->result() as $fa){ ?>
                                            <div class="form-check form-check-inline text-center">
                                                <input class="form-check-input" type="radio" name="icon" id="inlineRadioIcon<?= $fa->id ?>" value="<?= $fa->class ?>" required="">
                                                <label class="form-check-label" for="inlineRadioIcon<?= $fa->id ?>"></label>
                                                <h4><i class="<?= $fa->class ?> mb-0"></i></h3>
                                            </div>
                                        <?php } ?>
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

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 data-edit">
              
            </div>
        </div>
    </div>
</div>