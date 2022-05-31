<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-10 m-auto">
            <form action="<?= site_url('master/employee/update/' . $pegawai->id) ?>" enctype="multipart/form-data" method="POST">
                <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                    <h6><strong>Edit Pegawai - <?= $pegawai->nama ?></strong></h6>
                    <div class="row mt-3">
                        <div class="col-lg-3 mb-3">
                            <label>NIK <small class="text-danger">* <?= strip_tags(@form_error('nik')) ?></small></label>
                            <input class="form-control <?= (@form_error('nik')) ? 'is-invalid' : ((@set_value('nik')) ? 'is-valid' : '') ?>" type="number" placeholder="NIK" name="nik" value="<?= (@set_value('nik')) ? @set_value('nik') : $pegawai->nik ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>No Kartu Keluarga <small class="text-danger">* <?= strip_tags(@form_error('no_kk')) ?></small></label>
                            <input class="form-control <?= (@form_error('no_kk')) ? 'is-invalid' : ((@set_value('no_kk')) ? 'is-valid' : '') ?>" type="number" placeholder="No Kartu Keluarga" name="no_kk" value="<?= (@set_value('no_kk')) ? @set_value('no_kk') : $pegawai->no_kk ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>E-Ktp <small class="text-danger">* <?= strip_tags(@form_error('ektp')) ?></small></label>
                            <input class="form-control <?= (@form_error('ektp')) ? 'is-invalid' : ((@set_value('ektp')) ? 'is-valid' : '') ?>" type="number" placeholder="E-KTP" name="ektp" value="<?= (@set_value('ektp')) ? @set_value('ektp') : $pegawai->ektp ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>NPWP <small class="text-danger">* <?= strip_tags(@form_error('no_npwp')) ?></small></label>
                            <input class="form-control <?= (@form_error('no_npwp')) ? 'is-invalid' : ((@set_value('no_npwp')) ? 'is-valid' : '') ?>" type="number" placeholder="No NPWP" name="no_npwp" value="<?= (@set_value('no_npwp')) ? @set_value('no_npwp') : $pegawai->no_npwp ?>">
                        </div>
                        
                        <div class="col-lg-6 mb-3">
                            <label>Nama Lengkap</label>
                            <input class="form-control <?= (@form_error('nama')) ? 'is-invalid' : ((@set_value('nama')) ? 'is-valid' : '') ?>" type="text" placeholder="Nama Lengkap" name="nama" value="<?= (@set_value('nama')) ? @set_value('nama') : $pegawai->nama ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Alamat Email</label>
                            <input class="form-control <?= (@form_error('email')) ? 'is-invalid' : ((@set_value('email')) ? 'is-valid' : '') ?>" type="email" placeholder="Alamat Email" name="email" value="<?= (@set_value('email')) ? @set_value('email') : $pegawai->email ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Nama Ibu Kandung</label>
                            <input class="form-control <?= (@form_error('nama_ibu')) ? 'is-invalid' : ((@set_value('nama_ibu')) ? 'is-valid' : '') ?>" type="text" placeholder="Nama Ibu Kandung" name="nama_ibu" value="<?= (@set_value('nama_ibu')) ? @set_value('nama_ibu') : $pegawai->nama_ibu ?>">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label>Alamat Sesuai Domisili</label>
                            <input class="form-control <?= (@form_error('alamat_domisili')) ? 'is-invalid' : ((@set_value('alamat_domisili')) ? 'is-valid' : '') ?>" type="text" placeholder="Alamat Sesuai Domisili" name="alamat_domisili" value="<?= (@set_value('alamat_domisili')) ? @set_value('alamat_domisili') : $pegawai->alamat_domisili ?>">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label>Alamat Sesuai KTP</label>
                            <input class="form-control <?= (@form_error('alamat_ktp')) ? 'is-invalid' : ((@set_value('alamat_ktp')) ? 'is-valid' : '') ?>" type="text" placeholder="Alamat Sesuai KTP" name="alamat_ktp" value="<?= (@set_value('alamat_ktp')) ? @set_value('alamat_ktp') : $pegawai->alamat_ktp ?>">
                        </div>
                        
                        <div class="col-lg-3 mb-3">
                            <label>Tanggal Lahir</label>
                            <input class="form-control <?= (@form_error('tgl_lahir')) ? 'is-invalid' : ((@set_value('tgl_lahir')) ? 'is-valid' : '') ?>" type="date" placeholder="Tanggal Lahir" name="tgl_lahir" value="<?= (@set_value('tgl_lahir')) ? @set_value('tgl_lahir') : $pegawai->tgl_lahir ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div class="form-group">
                                <label>Agama <small class="text-danger">*</small></label>
                                <select name="agama_id" class="form-control <?= (@form_error('agama_id')) ? 'is-invalid' : ((@set_value('agama_id')) ? 'is-valid' : '') ?>" required="">
                                    <option value="" disabled="">- Pilih Agama</option>
                                    <?php foreach($agama->result() as $ag){ ?>
                                        <option value="<?= $ag->id ?>" <?= (@set_value('agama_id') == $ag->id || $pegawai->agama_id == $ag->id) ? 'selected' : '' ?> ><?= $ag->agama ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 mb-3">
                            <label>Status Pernikahan<small class="text-danger">*</small></label>
                            <div class="input-group input-group-sm mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="nikah" id="inlineRadio1" value="t" required="" <?= (@set_value('nikah') == 't' || $pegawai->nikah == 't') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio1">Menikah</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="nikah" id="inlineRadio2" value="f" required="" <?= (@set_value('nikah') == 'f' || $pegawai->nikah == 'f') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio2">Belum Menikah</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <div class="form-group">
                                <label>Jenjang Pendidikan <small class="text-danger">*</small></label>
                                <select name="pendidikan_id" class="form-control <?= (@form_error('pendidikan_id')) ? 'is-invalid' : ((@set_value('pendidikan_id')) ? 'is-valid' : '') ?>" required="">
                                    <option value="" selected="" disabled="">- Pilih Pendidikan</option>
                                    <?php foreach($pendidikan->result() as $p){ ?>
                                        <option value="<?= $p->id ?>" <?= (@set_value('pendidikan_id') == $p->id || $pegawai->pendidikan_id == $p->id) ? 'selected' : '' ?> ><?= $p->jenjang ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>BPJS Kesehatan <small class="text-danger">* <?= strip_tags(@form_error('no_bpjs_kesehatan')) ?></small></label>
                            <input class="form-control <?= (@form_error('no_bpjs_kesehatan')) ? 'is-invalid' : ((@set_value('no_bpjs_kesehatan')) ? 'is-valid' : '') ?>" type="number" placeholder="No BPJS Kesehatan" name="no_bpjs_kesehatan" value="<?= (@set_value('no_bpjs_kesehatan')) ? @set_value('no_bpjs_kesehatan') : $pegawai->no_bpjs_kesehatan ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>BPJS Ketenagakerjaan <small class="text-danger">* <?= strip_tags(@form_error('no_bpjs_ketenagakerjaan')) ?></small></label>
                            <input class="form-control <?= (@form_error('no_bpjs_ketenagakerjaan')) ? 'is-invalid' : ((@set_value('no_bpjs_ketenagakerjaan')) ? 'is-valid' : '') ?>" type="number" placeholder="No BPJS Ketenagakerjaan" name="no_bpjs_ketenagakerjaan" value="<?= (@set_value('no_bpjs_ketenagakerjaan')) ? @set_value('no_bpjs_ketenagakerjaan') : $pegawai->no_bpjs_ketenagakerjaan ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Tanggal Habis Kontrak <small class="text-danger">* <?= strip_tags(@form_error('tgl_habis_kontrak')) ?></small></label>
                            <input class="form-control <?= (@form_error('tgl_habis_kontrak')) ? 'is-invalid' : ((@set_value('tgl_habis_kontrak')) ? 'is-valid' : '') ?>" type="date" placeholder="Tanggal Habis Kontrak" name="tgl_habis_kontrak" value="<?= (@set_value('tgl_habis_kontrak')) ? @set_value('tgl_habis_kontrak') : $pegawai->tgl_habis_kontrak ?>">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label>Tanggal Resign <small class="text-danger">* <?= strip_tags(@form_error('resign_date')) ?></small></label>
                            <input class="form-control <?= (@form_error('resign_date')) ? 'is-invalid' : ((@set_value('resign_date')) ? 'is-valid' : '') ?>" type="date" placeholder="Tanggal Resign" name="resign_date" value="<?= (@set_value('resign_date')) ? @set_value('tgl_habis_kontrak') : $pegawai->resign_date ?>">
                        </div>

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label>Nama Bank</label>
                            <input class="form-control <?= (@form_error('nama_bank')) ? 'is-invalid' : ((@set_value('nama_bank')) ? 'is-valid' : '') ?>" type="text" placeholder="Nama Bank" name="nama_bank" value="<?= (@set_value('nama_bank')) ? @set_value('nama_bank') : $pegawai->nama_bank ?>">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label>No Rekening</label>
                            <input class="form-control <?= (@form_error('no_rekening')) ? 'is-invalid' : ((@set_value('no_rekening')) ? 'is-valid' : '') ?>" type="number" placeholder="No Rekening" name="no_rekening" value="<?= (@set_value('no_rekening')) ? @set_value('no_rekening') : $pegawai->no_rekening ?>">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label>Nama Di Rekening</label>
                            <input class="form-control <?= (@form_error('nama_rekening')) ? 'is-invalid' : ((@set_value('nama_rekening')) ? 'is-valid' : '') ?>" type="text" placeholder="Nama Di Rekening" name="nama_rekening" value="<?= (@set_value('nama_rekening')) ? @set_value('nama_rekening') : $pegawai->nama_rekening ?>">
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <label>Foto Pegawai</label>
                            <?php if($pegawai->foto): ?>
                                <img src="<?= base_url('uploads/image/' . $pegawai->foto) ?>" alt="Image placeholder" class="card-img-top" id="image-preview-pegawai">
                            <?php else: ?>
                                <img src="<?= base_url('assets/img/no-image-available.jpg') ?>" alt="" id="image-preview-pegawai" class="card-img-top">
                            <?php endif; ?>

                            <input type="file" name="foto" class="form-control mt-2" placeholder="" id="image-source-pegawai" onchange="previewImagePegawai();" >
                        </div>
                        <div class="col-lg-4">
                            <label>Foto KTP</label>
                            <?php if($pegawai->foto_ktp): ?>
                                <img src="<?= base_url('uploads/image/' . $pegawai->foto_ktp) ?>" alt="Image placeholder" class="card-img-top" id="image-preview-ktp">
                            <?php else: ?>
                                <img src="<?= base_url('assets/img/no-image-available.jpg') ?>" alt="" id="image-preview-ktp" class="card-img-top">
                            <?php endif; ?>

                            <input type="file" name="foto_ktp" class="form-control mt-2" placeholder="" id="image-source-ktp" onchange="previewImageKtp();">
                        </div>
                        <div class="col-lg-4">
                            <label>Foto Kartu Keluarga</label>
                            <?php if($pegawai->foto_kk): ?>
                                <img src="<?= base_url('uploads/image/' . $pegawai->foto_kk) ?>" alt="Image placeholder" class="card-img-top" id="image-preview-kk">
                            <?php else: ?>
                                <img src="<?= base_url('assets/img/no-image-available.jpg') ?>" alt="" id="image-preview-kk" class="card-img-top">
                            <?php endif; ?>

                            <input type="file" name="foto_kk" class="form-control mt-2" placeholder="" id="image-source-kk" onchange="previewImageKk();">
                        </div>
                    </div>
                </div>
                
                <div class="card p-3 border-radius-xl bg-white js-active mt-4" data-animation="FadeIn" id="sec-detail-kepegawaian">
                    <div class="row">
                        <div class="col-lg-6">
                            <h6><strong>Detail Kepegawaian</strong></h6>
                        </div>
                        <div class="col-lg-6 text-end">
                            <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd"><i class="fas fa-plus me-2"></i> Status Kepegawaian</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive p-0 mt-4">
                        <table class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center w-5px">No</th>
                                    <th class="">Status Kepegawaian</th>
                                    <th class="w-5px">Tgl Join</th>
                                    <th class="w-5px">Tgl Finish</th>
                                    <th class="text-center w-5px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $nos = 1;
                                    foreach($kepegawaian->result() as $rows){ ?>
                                <tr>
                                    <td class="text-center"><?= $nos++ ?></td>
                                    <td><?= $rows->status ?></td>
                                    <td><?= longdate_indo($rows->tgl_join) ?></td>
                                    <td><?= longdate_indo($rows->tgl_finish) ?></td>
                                    <td class="text-center">
                                        <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="<?= site_url('master/employee/deleteSK/' . $rows->id) ?>"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <hr class="my-2">

                    <div class="row mt-2">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Company <small class="text-danger">*</small></label>
                                <select name="company_id" class="form-control <?= (@form_error('company_id')) ? 'is-invalid' : ((@set_value('company_id')) ? 'is-valid' : '') ?>" required="">
                                    <option value="" selected="" disabled="">- Pilih Company</option>
                                    <?php foreach($companys->result() as $c){ ?>
                                        <option value="<?= $c->id ?>" <?= (@set_value('company_id') == $c->id || $pegawai->company_id == $c->id) ? 'selected' : '' ?> ><?= $c->company ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Company <small class="text-danger">*</small></label>
                                <select name="cabang_id" class="form-control <?= (@form_error('cabang_id')) ? 'is-invalid' : ((@set_value('cabang_id')) ? 'is-valid' : '') ?>" required="">
                                    <option value="" selected="" disabled="">- Pilih Cabang</option>
                                    <?php foreach($companys->result() as $c){ ?>
                                        <option value="<?= $c->id ?>" <?= (@set_value('company_id') == $c->id || $pegawai->company_id == $c->id) ? 'selected' : '' ?> ><?= $c->company ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Jabatan <small class="text-danger">*</small></label>
                                <select name="jabatan_id" class="form-control <?= (@form_error('jabatan_id')) ? 'is-invalid' : ((@set_value('jabatan_id')) ? 'is-valid' : '') ?>"required="">
                                    <option value="" selected="" disabled="">- Pilih Jabatan</option>
                                    <?php foreach($jabatan->result() as $j){ ?>
                                        <option value="<?= $j->id ?>" <?= (@set_value('jabatan_id') == $j->id || $pegawai->jabatan_id) ? 'selected' : '' ?> ><?= $j->jabatan ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="select-div">Divisi <small class="text-danger">*</small></label>
                                <select name="divisi_id" class="form-control <?= (@form_error('divisi_id')) ? 'is-invalid' : ((@set_value('divisi_id')) ? 'is-valid' : '') ?>" required="" id="select-div">
                                    <option value="" selected="" disabled="">- Pilih Divisi</option>
                                    <?php foreach($divisi->result() as $d){ ?>
                                        <option value="<?= $d->id ?>" <?= (@set_value('divisi_id') == $d->id || $pegawai->divisi_id == $d->id) ? 'selected' : '' ?> ><?= $d->divisi ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Departement <small class="text-danger">*</small></label>
                                <select name="dept_id" class="form-control <?= (@form_error('dept_id')) ? 'is-invalid' : ((@set_value('dept_id')) ? 'is-valid' : '') ?>" id="select-dept" <?= (@form_error('dept_id')) ? 'disabled' : '' ?>>
                                    <option value="" selected="" disabled="">- Pilih Departement</option>
                                    <?php 
                                        if(@set_value('dept_id')): 
                                            $explodeDept = explode('_', @set_value('dept_id'));
                                            $dept_id = $explodeDept[0];
                                            $dept_name = $explodeDept[1];
                                    ?>
                                        <option value="<?= @$dept_id ?>" selected><?= @$dept_name ?></option>
                                    <?php else: ?>
                                        <?php foreach($departement->result() as $d){ ?>
                                            <option value="<?= $d->id ?>" <?= ($pegawai->dept_id == $d->id) ? 'selected' : '' ?>><?= $d->departement ?></option>
                                        <?php } ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>Unit <small class="text-danger">*</small></label>
                                <select name="unit_id" class="form-control <?= (@form_error('unit_id')) ? 'is-invalid' : ((@set_value('unit_id')) ? 'is-valid' : '') ?>" id="select-unit" <?= (@form_error('unit_id')) ? 'disabled' : '' ?>>
                                    <option value="" selected="" disabled="">- Pilih Unit</option>
                                    <?php 
                                        if(@set_value('unit_id')): 
                                            $explodeUnit = explode('_', @set_value('unit_id'));
                                            $unit_id = $explodeUnit[0];
                                            $unit_name = $explodeUnit[1];
                                    ?>
                                        <option value="<?= @$unit_id ?>" selected><?= @$unit_name ?></option>
                                    <?php else: ?>
                                        <?php foreach($unit->result() as $u){ ?>
                                            <option value="<?= $u->id ?>" <?= ($pegawai->unit_id == $u->id) ? 'selected' : '' ?>><?= $u->unit ?></option>
                                        <?php } ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="button-row d-flex">
                        <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 mb-0 text-white">Simpan</button>
                    </div>
                </div>
            </form>
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
                        <h5 class="font-weight-bolder">Tambah Status Kepegawaian <br> <?= @$pegawai->nama ?></h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('master/employee/addStatusKepegawaian') ?>" role="form text-left" method="post">
                            <input type="hidden" name="pegawai_id" value="<?= $pegawai->id ?>">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status Kepegawaian <small class="text-danger">*</small></label>
                                        <select name="status_id" class="form-control <?= (@form_error('status_id')) ? 'is-invalid' : ((@set_value('status_id')) ? 'is-valid' : '') ?>" required="">
                                            <option value="" selected="" disabled="">- Pilih Status Kepegawaian</option>
                                            <?php foreach($status_kepegawaian->result() as $sk){ ?>
                                                <option value="<?= $sk->id ?>" <?= (@set_value('status_id') == $sk->id || $pegawai->status_id == $sk->id) ? 'selected' : '' ?> ><?= $sk->status ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Tanggal Join<small class="text-danger">*</small></label>
                                                <input class="form-control" type="date" placeholder="Tanggal Join" name="tgl_join" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Tanggal Finish<small class="text-danger">*</small></label>
                                                <input class="form-control" type="date" placeholder="Tanggal Finish" name="tgl_finish" required>
                                            </div>
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