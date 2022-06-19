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
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#kepegawaian-tabs" role="tab" aria-controls="code" aria-selected="false" tabindex="-1">
                            <i class="fas fa-users text-sm me-2"></i> Kepegawaian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#keuangan-tabs" role="tab" aria-controls="code" aria-selected="false" tabindex="-1">
                            <i class="fas fa-money-bill text-sm me-2"></i> Keuangan
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="profile-tabs" role="tabpanel">
                    <!-- Detail Profile -->
                    <form action="<?= site_url('kepegawaian/employee/update/' . $pegawai->id) ?>" enctype="multipart/form-data" method="POST">
                        <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6><strong>Profil</strong></h6>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <?php if($pegawai->kode_cabang != NULL): ?>
                                <div class="col-lg-3 mb-3">
                                    <label>NIP</label>
                                    <input class="form-control" type="text" placeholder="NIP" name="nik" value="<?= $pegawai->kode_cabang."".sprintf("%05s", $pegawai->nik) ?>" disabled>
                                </div>
                                <?php endif; ?>

                                <div class="col-lg-3 mb-3">
                                    <label>No Kartu Keluarga</label>
                                    <input class="form-control <?= (@form_error('no_kk')) ? 'is-invalid' : ((@set_value('no_kk')) ? 'is-valid' : '') ?>" type="number" placeholder="No Kartu Keluarga" name="no_kk" value="<?= (@set_value('no_kk')) ? @set_value('no_kk') : $pegawai->no_kk ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>E-Ktp <small class="text-danger">* <?= strip_tags(@form_error('ektp')) ?></small></label>
                                    <input class="form-control <?= (@form_error('ektp')) ? 'is-invalid' : ((@set_value('ektp')) ? 'is-valid' : '') ?>" type="number" placeholder="E-KTP" name="ektp" value="<?= (@set_value('ektp')) ? @set_value('ektp') : $pegawai->ektp ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>NPWP</label>
                                    <input class="form-control <?= (@form_error('no_npwp')) ? 'is-invalid' : ((@set_value('no_npwp')) ? 'is-valid' : '') ?>" type="number" placeholder="No NPWP" name="no_npwp" value="<?= (@set_value('no_npwp')) ? @set_value('no_npwp') : $pegawai->no_npwp ?>">
                                </div>
                                
                                <div class="col-lg-6 mb-3">
                                    <label>Nama Lengkap <small class="text-danger">* <?= strip_tags(@form_error('nama')) ?></small></label>
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
                                        <label>Agama <small class="text-danger">* <?= strip_tags(@form_error('agama_id')) ?></small></label>
                                        <select name="agama_id" class="form-control <?= (@form_error('agama_id')) ? 'is-invalid' : ((@set_value('agama_id')) ? 'is-valid' : '') ?>" required="">
                                            <option value="" disabled="">- Pilih Agama</option>
                                            <?php foreach($agama->result() as $ag){ ?>
                                                <option value="<?= $ag->id ?>" <?= (@set_value('agama_id') == $ag->id || $pegawai->agama_id == $ag->id) ? 'selected' : '' ?> ><?= $ag->agama ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 mb-3">
                                    <label>Status Pernikahan<small class="text-danger">* <?= strip_tags(@form_error('nikah')) ?></small></label>
                                    <select name="nikah" class="form-control <?= (@form_error('nikah')) ? 'is-invalid' : ((@set_value('nikah')) ? 'is-valid' : '') ?>" required="">
                                        <option value="" selected="" disabled="">- Pilih Status Pernikahan</option>
                                        <?php foreach($pernikahan->result() as $n){ ?>
                                            <option value="<?= $n->id ?>" <?= (@set_value('nikah') == $n->id || $pegawai->nikah == $n->id) ? 'selected' : '' ?> ><?= $n->status ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <div class="form-group">
                                        <label>Jenjang Pendidikan <small class="text-danger">* <?= strip_tags(@form_error('pendidikan_id')) ?></small></label>
                                        <select name="pendidikan_id" class="form-control <?= (@form_error('pendidikan_id')) ? 'is-invalid' : ((@set_value('pendidikan_id')) ? 'is-valid' : '') ?>" required="">
                                            <option value="" selected="" disabled="">- Pilih Pendidikan</option>
                                            <?php foreach($pendidikan->result() as $p){ ?>
                                                <option value="<?= $p->id ?>" <?= (@set_value('pendidikan_id') == $p->id || $pegawai->pendidikan_id == $p->id) ? 'selected' : '' ?> ><?= $p->jenjang ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>Jumlah Tanggungan</label>
                                    <input class="form-control <?= (@form_error('jumlan_tanggungan')) ? 'is-invalid' : ((@set_value('jumlan_tanggungan')) ? 'is-valid' : '') ?>" type="number" placeholder="Jumlah Tanggungan" name="jumlan_tanggungan" value="<?= (@set_value('jumlan_tanggungan')) ? @set_value('jumlan_tanggungan') : $pegawai->jumlan_tanggungan ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>BPJS Kesehatan</label>
                                    <input class="form-control <?= (@form_error('no_bpjs_kesehatan')) ? 'is-invalid' : ((@set_value('no_bpjs_kesehatan')) ? 'is-valid' : '') ?>" type="number" placeholder="No BPJS Kesehatan" name="no_bpjs_kesehatan" value="<?= (@set_value('no_bpjs_kesehatan')) ? @set_value('no_bpjs_kesehatan') : $pegawai->no_bpjs_kesehatan ?>">
                                </div>

                                <div class="col-lg-3 mb-3">
                                    <label>BPJS Ketenagakerjaan</label>
                                    <input class="form-control <?= (@form_error('no_bpjs_ketenagakerjaan')) ? 'is-invalid' : ((@set_value('no_bpjs_ketenagakerjaan')) ? 'is-valid' : '') ?>" type="number" placeholder="No BPJS Ketenagakerjaan" name="no_bpjs_ketenagakerjaan" value="<?= (@set_value('no_bpjs_ketenagakerjaan')) ? @set_value('no_bpjs_ketenagakerjaan') : $pegawai->no_bpjs_ketenagakerjaan ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
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

                            <div class="button-row d-flex">
                                <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 mb-0 text-white">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade " id="kepegawaian-tabs" role="tabpanel">
                    <!-- Kepegawaian  -->
                    <form action="<?= site_url('kepegawaian/employee/updateKepegawian/' . $pegawai->id) ?>" enctype="multipart/form-data" method="POST">
                        <div class="card p-3 border-radius-xl bg-white js-active mt-4" data-animation="FadeIn" id="sec-detail-kepegawaian">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6><strong>Status Kepegawaian</strong></h6>
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
                                            <td><?= ($rows->tgl_finish) ? longdate_indo($rows->tgl_finish) : '-' ?></td>
                                            <td class="text-center btn-group">
                                                <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0 btn-edit-kepegawaian" id="<?= $rows->id ?>"><i class="fas fa-pencil-alt" aria-hidden="true"></i></button>
                                                <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="<?= site_url('kepegawaian/employee/deleteSK/' . $rows->id) ?>"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr class="my-2">

                            <div class="row mt-3">
                                <div class="col-lg-6">
                                    <h6><strong>Tunjangan <span class="badge badge-sm bg-gradient-success"><?= @$tunjanganPegawai->nama ?></span></strong></h6>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <?php if(@$tunjanganPegawai->id): ?>
                                        <button type="button" class="btn btn-sm btn-round btn-info mb-0" data-bs-toggle="modal" data-bs-target="#modalAddTunjangan"><i class="fas fa-pencil-alt me-2"></i> Tunjangan</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAddTunjangan"><i class="fas fa-plus me-2"></i> Tunjangan</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="table-responsive p-0 mt-4">
                                <table class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center w-5px">No</th>
                                            <th class="">Tunjangan</th>
                                            <th class="w-5px">Role</th>
                                            <th class="w-5px">Nominal</th>
                                            <th class="w-5px">Tipe</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $not = 1;
                                            $getTunjangan = $this->db->select('dt.*, t.tunjangan, t.keterangan, t.type tunjangan_type, rt.kode, rt.satuan')
                                                                    ->from('detail_template_tunjangan dt')
                                                                    ->join('tunjangan t', 'dt.tunjangan_id = t.id')
                                                                    ->join('role_tunjangan rt', 't.role_id = rt.id')
                                                                    ->where('dt.template_id', @$tunjanganPegawai->id)
                                                                    ->order_by('dt.id', "DESC")->get();

                                            foreach($getTunjangan->result() as $tem){
                                                $badgeType = ($tem->type == 'N') ? '<span class="badge badge-sm bg-gradient-primary">Nominal</span>' : '<span class="badge badge-sm bg-gradient-primary">Presentase</span>';
                                                $badgeTunjangan = jenisTunjangan($tem->tunjangan_type);
                                                $nominal = ($tem->type == 'N') ? rupiah($tem->nominal) : $tem->nominal."%";
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $not++ ?></td>
                                            <td><?= $tem->tunjangan.' - '.$tem->keterangan ?></td>
                                            <td><?= $tem->satuan ?></td>
                                            <td><?= $nominal ?></td>
                                            <td><?= $badgeType.' '.$badgeTunjangan ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr class="my-2">

                            <div class="row mt-2">
                                <div class="col-lg-6 mb-3">
                                    <label>Tanggal Habis Kontrak <small class="text-danger">* <?= strip_tags(@form_error('tgl_habis_kontrak')) ?></small></label>
                                    <input class="form-control <?= (@form_error('tgl_habis_kontrak')) ? 'is-invalid' : ((@set_value('tgl_habis_kontrak')) ? 'is-valid' : '') ?>" type="date" placeholder="Tanggal Habis Kontrak" name="tgl_habis_kontrak" value="<?= (@set_value('tgl_habis_kontrak')) ? @set_value('tgl_habis_kontrak') : $pegawai->tgl_habis_kontrak ?>">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label>Tanggal Resign <small class="text-danger">* <?= strip_tags(@form_error('resign_date')) ?></small></label>
                                    <input class="form-control <?= (@form_error('resign_date')) ? 'is-invalid' : ((@set_value('resign_date')) ? 'is-valid' : '') ?>" type="date" placeholder="Tanggal Resign" name="resign_date" value="<?= (@set_value('resign_date')) ? @set_value('tgl_habis_kontrak') : $pegawai->resign_date ?>">
                                </div>
                                
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Cabang <small class="text-danger">*</small></label>
                                        <select name="cabang_id" class="form-control <?= (@form_error('cabang_id')) ? 'is-invalid' : ((@set_value('cabang_id')) ? 'is-valid' : '') ?>" required="">
                                            <option value="" selected="" disabled="">- Pilih Cabang</option>
                                            <?php foreach($cabang->result() as $cb){ ?>
                                                <option value="<?= $cb->id ?>" <?= (@set_value('cabang_id') == $cb->id || $pegawai->cabang_id == $cb->id) ? 'selected' : '' ?> ><?= $cb->cabang ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Jabatan</label>
                                        <select name="jabatan_id" class="form-control <?= (@form_error('jabatan_id')) ? 'is-invalid' : ((@set_value('jabatan_id')) ? 'is-valid' : '') ?>">
                                            <option value="" selected="" disabled="">- Pilih Jabatan</option>
                                            <?php foreach($jabatan->result() as $j){ ?>
                                                <option value="<?= $j->id ?>" <?= (@set_value('jabatan_id') == $j->id || $pegawai->jabatan_id) ? 'selected' : '' ?> ><?= $j->jabatan ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="select-div">Divisi</label>
                                        <select name="divisi_id" class="form-control <?= (@form_error('divisi_id')) ? 'is-invalid' : ((@set_value('divisi_id')) ? 'is-valid' : '') ?>" id="select-div">
                                            <option value="" selected="" disabled="">- Pilih Divisi</option>
                                            <?php foreach($divisi->result() as $d){ ?>
                                                <option value="<?= $d->id ?>" <?= (@set_value('divisi_id') == $d->id || $pegawai->divisi_id == $d->id) ? 'selected' : '' ?> ><?= $d->divisi ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Departement</label>
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
                                        <label>Unit</label>
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
                <div class="tab-pane fade " id="keuangan-tabs" role="tabpanel">
                    <!-- Keuangan -->
                    <form action="<?= site_url('kepegawaian/employee/updateKeuangan/' . $pegawai->id) ?>" enctype="multipart/form-data" method="POST">
                        <div class="card p-3 border-radius-xl bg-white js-active" data-animation="FadeIn">
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <h6><strong>Keuangan</strong></h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label>Nominal Gaji Pokok</label>
                                    <input class="form-control <?= (@form_error('nominal_gapok')) ? 'is-invalid' : ((@set_value('nominal_gapok')) ? 'is-valid' : '') ?>" type="text" placeholder="Nominal Gaji Pokok" name="nominal_gapok" value="<?= (@set_value('nominal_gapok')) ? @set_value('nominal_gapok') : $pegawai->nominal_gapok ?>">
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label>Nominal Gaji Pokok Dilaporkan</label>
                                    <input class="form-control <?= (@form_error('nominal_gaji_dilaporkan')) ? 'is-invalid' : ((@set_value('nominal_gaji_dilaporkan')) ? 'is-valid' : '') ?>" type="text" placeholder="Nominal Gaji Pokok Dilaporkan" name="nominal_gaji_dilaporkan" value="<?= (@set_value('nominal_gaji_dilaporkan')) ? @set_value('nominal_gaji_dilaporkan') : $pegawai->nominal_gaji_dilaporkan ?>">
                                </div>

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

<!-- Modals -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h5 class="font-weight-bolder">Tambah Status Kepegawaian - <?= @$pegawai->nama ?></h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('kepegawaian/employee/addStatusKepegawaian') ?>" role="form text-left" method="post">
                            <input type="hidden" name="pegawai_id" value="<?= $pegawai->id ?>">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status Kepegawaian <small class="text-danger">*</small></label>
                                        <select name="status_id" class="form-control <?= (@form_error('status_id')) ? 'is-invalid' : ((@set_value('status_id')) ? 'is-valid' : '') ?>" required="">
                                            <option value="" selected="" disabled="">- Pilih Status Kepegawaian</option>
                                            <?php foreach($status_kepegawaian->result() as $sk){ ?>
                                                <option value="<?= $sk->id ?>" ><?= $sk->status ?></option>
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
                                                <label>Tanggal Finish</label>
                                                <input class="form-control" type="date" placeholder="Tanggal Finish" name="tgl_finish">
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

<div class="modal fade" id="modal-edit-kepegawaian" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-content-edit-kepegawiaan">
            
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddTunjangan" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h5 class="font-weight-bolder">Pilih Template Tunjangan - <?= @$pegawai->nama ?></h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('kepegawaian/employee/createTunjangan') ?>" role="form text-left" method="post">
                            <input type="hidden" name="pegawai_id" value="<?= $pegawai->id ?>">
                           
                            <?php 
                                $no = 1;
                                foreach($templateTunjangan->result() as $r){ 
                                    $tunjangan = $this->db->select('dt.*, t.tunjangan, t.keterangan, t.type tunjangan_type, rt.kode, rt.satuan')
                                                            ->from('detail_template_tunjangan dt')
                                                            ->join('tunjangan t', 'dt.tunjangan_id = t.id')
                                                            ->join('role_tunjangan rt', 't.role_id = rt.id')
                                                            ->where('dt.template_id', $r->id)
                                                            ->order_by('dt.id', "DESC")->get();
                            ?>
                                <div class="card mt-2">
                                    <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input mt-1" name="template_id" id="checkTemplate<?= $r->id ?>" value="<?= $r->id ?>" <?= (@$tunjanganPegawai->id == $r->id) ? 'checked' : ''  ?>>
                                                    <label class="custom-control-label" for="checkTemplate<?= $r->id ?>" ><h6><strong><?= $r->nama ?></strong></h6></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 text-lg-end">
                                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample<?= $r->id ?>" aria-expanded="false" aria-controls="collapseExample<?= $r->id ?>">
                                                    <i class="fas fa-chevron-down me-2"></i> Detail
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="collapse <?= (@$tunjanganPegawai->id == $r->id) ? 'show' : ''  ?>" id="collapseExample<?= $r->id ?>">
                                        <div class="card-body pt-2">
                                            <?php foreach($tunjangan->result() as $t){ 
                                                $badgeType = ($t->type == 'N') ? '<span class="badge badge-sm bg-gradient-primary me-2">&nbsp;&nbsp;Nominal&nbsp;&nbsp;</span>' : '<span class="badge badge-sm bg-gradient-primary me-2">Presentase</span>';
                                                $badgeTunjangan = jenisTunjangan($t->tunjangan_type);
                                                $nominal = ($t->type == 'N') ? rupiah($t->nominal) : $t->nominal."%";    
                                            ?>
                                                <hr class="my-1">
                                                <div class="d-flex justify-content-between">
                                                    <strong><?= $t->tunjangan." - ".$t->satuan." : ".$nominal ?></strong>
                                                    <strong><?= $badgeTunjangan ?></strong>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

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