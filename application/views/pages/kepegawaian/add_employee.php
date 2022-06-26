<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-10 m-auto">
            <div class="nav-wrapper position-relative end-0 mb-3">
                <ul class="nav nav-pills nav-fill p-1" id="tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#profile-tabs" role="tab" aria-controls="preview" aria-selected="true" tabindex="-1">
                            <i class="fas fa-user text-sm me-2"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#kepegawaian-tabs" role="tab" aria-controls="code" aria-selected="false" tabindex="-1" disabled>
                            <i class="fas fa-users text-sm me-2"></i> Kepegawaian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#keuangan-tabs" role="tab" aria-controls="code" aria-selected="false" tabindex="-1" disabled>
                            <i class="fas fa-money-bill text-sm me-2"></i> Keuangan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="profile-tabs" role="tabpanel">
                    <!-- Detail Profile -->
                    <form action="<?= site_url('kepegawaian/employee/create') ?>" enctype="multipart/form-data" method="POST">
                        <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6><strong>Profil</strong></h6>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-lg-4 mb-3">
                                    <label>No Kartu Keluarga</label>
                                    <input class="form-control <?= (@form_error('no_kk')) ? 'is-invalid' : ((@set_value('no_kk')) ? 'is-valid' : '') ?>" type="number" placeholder="No Kartu Keluarga" name="no_kk" value="<?= (@set_value('no_kk')) ? @set_value('no_kk') : '' ?>">
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label>E-Ktp <small class="text-danger">* <?= strip_tags(@form_error('ektp')) ?></small></label>
                                    <input class="form-control <?= (@form_error('ektp')) ? 'is-invalid' : ((@set_value('ektp')) ? 'is-valid' : '') ?>" type="number" placeholder="E-KTP" name="ektp" value="<?= (@set_value('ektp')) ? @set_value('ektp') : '' ?>">
                                </div>

                                <div class="col-lg-4 mb-3">
                                    <label>NPWP</label>
                                    <input class="form-control <?= (@form_error('no_npwp')) ? 'is-invalid' : ((@set_value('no_npwp')) ? 'is-valid' : '') ?>" type="number" placeholder="No NPWP" name="no_npwp" value="<?= (@set_value('no_npwp')) ? @set_value('no_npwp') : '' ?>">
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label>Nama Lengkap <small class="text-danger">* <?= strip_tags(@form_error('nama')) ?></small></label>
                                    <input class="form-control <?= (@form_error('nama')) ? 'is-invalid' : ((@set_value('nama')) ? 'is-valid' : '') ?>" type="text" placeholder="Nama Lengkap" name="nama" value="<?= (@set_value('nama')) ? @set_value('nama') : '' ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>Alamat Email</label>
                                    <input class="form-control <?= (@form_error('email')) ? 'is-invalid' : ((@set_value('email')) ? 'is-valid' : '') ?>" type="email" placeholder="Alamat Email" name="email" value="<?= (@set_value('email')) ? @set_value('email') : '' ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>Nama Ibu Kandung</label>
                                    <input class="form-control <?= (@form_error('nama_ibu')) ? 'is-invalid' : ((@set_value('nama_ibu')) ? 'is-valid' : '') ?>" type="text" placeholder="Nama Ibu Kandung" name="nama_ibu" value="<?= (@set_value('nama_ibu')) ? @set_value('nama_ibu') : '' ?>">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label>Alamat Sesuai Domisili</label>
                                    <input class="form-control <?= (@form_error('alamat_domisili')) ? 'is-invalid' : ((@set_value('alamat_domisili')) ? 'is-valid' : '') ?>" type="text" placeholder="Alamat Sesuai Domisili" name="alamat_domisili" value="<?= (@set_value('alamat_domisili')) ? @set_value('alamat_domisili') : '' ?>">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label>Alamat Sesuai KTP</label>
                                    <input class="form-control <?= (@form_error('alamat_ktp')) ? 'is-invalid' : ((@set_value('alamat_ktp')) ? 'is-valid' : '') ?>" type="text" placeholder="Alamat Sesuai KTP" name="alamat_ktp" value="<?= (@set_value('alamat_ktp')) ? @set_value('alamat_ktp') : '' ?>">
                                </div>
                                
                                <div class="col-lg-3 mb-3">
                                    <label>Tanggal Lahir</label>
                                    <input class="form-control <?= (@form_error('tgl_lahir')) ? 'is-invalid' : ((@set_value('tgl_lahir')) ? 'is-valid' : '') ?>" type="date" placeholder="Tanggal Lahir" name="tgl_lahir" value="<?= (@set_value('tgl_lahir')) ? @set_value('tgl_lahir') : '' ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <label>Agama <small class="text-danger">* <?= strip_tags(@form_error('agama_id')) ?></small></label>
                                        <select name="agama_id" class="form-control <?= (@form_error('agama_id')) ? 'is-invalid' : ((@set_value('agama_id')) ? 'is-valid' : '') ?>">
                                            <option value="" selected="" disabled="">- Pilih Agama</option>
                                            <?php foreach($agama->result() as $ag){ ?>
                                                <option value="<?= $ag->id ?>" <?= (@set_value('agama_id') == $ag->id) ? 'selected' : '' ?> ><?= $ag->agama ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 mb-3">
                                    <label>Status Pernikahan<small class="text-danger">* <?= strip_tags(@form_error('nikah')) ?></small></label>
                                    <select name="nikah" class="form-control <?= (@form_error('nikah')) ? 'is-invalid' : ((@set_value('nikah')) ? 'is-valid' : '') ?>">
                                        <option value="" selected="" disabled="">- Pilih Status Pernikahan</option>
                                        <?php foreach($pernikahan->result() as $n){ ?>
                                            <option value="<?= $n->id ?>" <?= (@set_value('nikah') == $n->id) ? 'selected' : '' ?> ><?= $n->status ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <label>Jenjang Pendidikan <small class="text-danger">* <?= strip_tags(@form_error('pendidikan_id')) ?></small></label>
                                        <select name="pendidikan_id" class="form-control <?= (@form_error('pendidikan_id')) ? 'is-invalid' : ((@set_value('pendidikan_id')) ? 'is-valid' : '') ?>">
                                            <option value="" selected="" disabled="">- Pilih Pendidikan</option>
                                            <?php foreach($pendidikan->result() as $p){ ?>
                                                <option value="<?= $p->id ?>" <?= (@set_value('pendidikan_id') == $p->id) ? 'selected' : '' ?> ><?= $p->jenjang ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>Jumlah Tanggungan</label>
                                    <input class="form-control <?= (@form_error('jumlan_tanggungan')) ? 'is-invalid' : ((@set_value('jumlan_tanggungan')) ? 'is-valid' : '') ?>" type="number" placeholder="Jumlah Tanggungan" name="jumlan_tanggungan" value="<?= (@set_value('jumlan_tanggungan')) ? @set_value('jumlan_tanggungan') : '' ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>BPJS Kesehatan</label>
                                    <input class="form-control <?= (@form_error('no_bpjs_kesehatan')) ? 'is-invalid' : ((@set_value('no_bpjs_kesehatan')) ? 'is-valid' : '') ?>" type="number" placeholder="No BPJS Kesehatan" name="no_bpjs_kesehatan" value="<?= (@set_value('no_bpjs_kesehatan')) ? @set_value('no_bpjs_kesehatan') : '' ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>BPJS Ketenagakerjaan</label>
                                    <input class="form-control <?= (@form_error('no_bpjs_ketenagakerjaan')) ? 'is-invalid' : ((@set_value('no_bpjs_ketenagakerjaan')) ? 'is-valid' : '') ?>" type="number" placeholder="No BPJS Ketenagakerjaan" name="no_bpjs_ketenagakerjaan" value="<?= (@set_value('no_bpjs_ketenagakerjaan')) ? @set_value('no_bpjs_ketenagakerjaan') : '' ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <label>Cabang <small class="text-danger">*</small></label>
                                        <select name="cabang_id" class="form-control <?= (@form_error('cabang_id')) ? 'is-invalid' : ((@set_value('cabang_id')) ? 'is-valid' : '') ?>" required="">
                                            <option value="" selected="" disabled="">- Pilih Cabang</option>
                                            <?php foreach($cabang->result() as $cb){ ?>
                                                <option value="<?= $cb->id ?>" ><?= $cb->cabang ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Foto Pegawai</label>
                                    <img src="<?= base_url('assets/img/no-image-available.jpg') ?>" alt="" id="image-preview-pegawai" class="card-img-top">
                                    <input type="file" name="foto" class="form-control mt-2" placeholder="" id="image-source-pegawai" onchange="previewImagePegawai();" >
                                </div>
                                <div class="col-lg-4">
                                    <label>Foto KTP</label>
                                    <img src="<?= base_url('assets/img/no-image-available.jpg') ?>" alt="" id="image-preview-ktp" class="card-img-top">
                                    <input type="file" name="foto_ktp" class="form-control mt-2" placeholder="" id="image-source-ktp" onchange="previewImageKtp();">
                                </div>
                                <div class="col-lg-4">
                                    <label>Foto Kartu Keluarga</label>
                                    <img src="<?= base_url('assets/img/no-image-available.jpg') ?>" alt="" id="image-preview-kk" class="card-img-top">
                                    <input type="file" name="foto_kk" class="form-control mt-2" placeholder="" id="image-source-kk" onchange="previewImageKk();">
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
    </div>
</div>