<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Unit</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-plus me-2"></i> Unit</button>
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
                        <th class="w-5px">Divisi</th>
                        <th class="w-5px">Departement</th>
                        <th>Unit</th>
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
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h5 class="font-weight-bolder">Tambah Unit</h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('kepegawaian/unit/create') ?>" role="form text-left" method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="select-div">Divisi <small class="text-danger">*</small></label>
                                        <select name="divisi_id" class="form-control" id="select-div" required="">
                                            <option value="" selected="" disabled="">- Pilih Divisi</option>
                                            <?php foreach($divisi->result() as $row){ ?>
                                                <option value="<?= $row->id ?>"><?= $row->divisi ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="select-dept">Departement <small class="text-danger">*</small></label>
                                        <select name="dept_id" class="form-control" id="select-dept" required="" disabled="disabled">
                                            <option value="" selected="" disabled="">- Pilih Departement</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label>Unit <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Unit" aria-label="Unit" name="unit" required>
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
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-body p-0 data-edit">
              
            </div>
        </div>
    </div>
</div>