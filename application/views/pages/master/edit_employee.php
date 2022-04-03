<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-8 m-auto">
            <form action="<?= site_url('employee/update/' . $pegawai->id) ?>" method="POST">
                <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                    <h6><strong>Edit Pegawai - <?= $pegawai->nama ?></strong></h6>
                    <div class="row mt-3">
                        <div class="col-lg-4">
                            <label>NIK <small class="text-danger">* <?= strip_tags(@form_error('nik')) ?></small></label>
                            <input class="form-control <?= (@form_error('nik')) ? 'is-invalid' : ((@set_value('nik')) ? 'is-valid' : '') ?>" type="text" placeholder="NIK" name="nik" value="<?= (@set_value('nik')) ? @set_value('nik') : $pegawai->nik ?>">
                        </div>
                        <div class="col-lg-8">
                            <label>Nama Lengkap</label>
                            <input class="form-control <?= (@form_error('nama')) ? 'is-invalid' : ((@set_value('nik')) ? 'is-valid' : '') ?>" type="text" placeholder="Nama Lengkap" name="nama" value="<?= (@set_value('nama')) ? @set_value('nama') : $pegawai->nama ?>">
                        </div>
                        <div class="col-lg-4">
                            <label>E-Ktp <small class="text-danger">* <?= strip_tags(@form_error('ektp')) ?></small></label>
                            <input class="form-control <?= (@form_error('ektp')) ? 'is-invalid' : ((@set_value('ektp')) ? 'is-valid' : '') ?>" type="text" placeholder="E-KTP" name="ektp" value="<?= (@set_value('ektp')) ? @set_value('ektp') : $pegawai->ektp ?>">
                        </div>
                        <div class="col-lg-4">
                            <label>Tanggal Lahir</label>
                            <input class="form-control <?= (@form_error('tgl_lahir')) ? 'is-invalid' : ((@set_value('tgl_lahir')) ? 'is-valid' : '') ?>" type="date" placeholder="Tanggal Lahir" name="tgl_lahir" value="<?= (@set_value('tgl_lahir')) ? @set_value('tgl_lahir') : $pegawai->tgl_lahir ?>">
                        </div>
                        <div class="col-lg-4">
                            <label>Status Pernikahan<small class="text-danger">*</small></label>
                            <div class="input-group mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="nikah" id="inlineRadio1" value="t" required="" <?= (@set_value('nikah') == 't' || $pegawai->nikah == 't') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio1">Sudah Menikah</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="nikah" id="inlineRadio2" value="f" required="" <?= (@set_value('nikah') == 'f' || $pegawai->nikah == 'f') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio2">Belum Menikah</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
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
                        <div class="col-lg-4">
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
                    </div>
                </div>
                
                <div class="card p-3 border-radius-xl bg-white js-active mt-4" data-animation="FadeIn">
                    <h6><strong>Detail Kepegawaian</strong></h6>
                    <div class="row mt-3">
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
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