<div class="container-fluid py-4">
    <div class="card ">
        <!-- Card header -->
        <div class="card-header pb-0">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Transaksi Summary</strong></h5> <br>
                    <h5 class="mb-0"><strong>Cutoff Periode <?= bulan($cutoff->bulan)." ".$cutoff->tahun; ?></strong></h5>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-3 mb-3">
                    <label>Start Date</label>
                    <p class="mb-0 text-dark"><strong><?= ($cutoff->start_date) ? longdate_indo(date('Y-m-d', strtotime($cutoff->start_date))) : '-'  ?></strong></p>
                    <input type="hidden" id="startDate" value="<?= @$cutoff->start_date ?>">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>End Date</label>
                    <p class="mb-0 text-dark"><strong><?= (@$cutoff->end_date) ? longdate_indo(date('Y-m-d', strtotime(@$cutoff->end_date))) : '-'  ?></strong></p>
                    <input type="hidden" id="endDate" value="<?= @$cutoff->end_date ?>">
                </div>
                <div class="col-lg-3 mb-3">
                    <div class="form-group">
                        <label>Cabang</label>
                        <select id="cabang_id" class="form-control">
                            <option value="" selected="" disabled="">- Pilih Cabang</option>
                            <option value="all" >- Semua</option>
                            <?php 
                                $cabang = $this->db->get_where('cabang', ['company_id' => $this->companyid, 'status' => 't']);
                                foreach($cabang->result() as $d){ ?>
                                <option value="<?= $d->id ?>"><?= $d->cabang ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 mb-3 mt-1 d-grid">
                    <button class="btn btn-primary mt-4 btn-search" type="button"><i class="fas fa-search me-2"></i> Cari</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3 table-summary">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Detail Summary</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0" data-bs-toggle="modal" data-bs-target="#modalAdd" disabled><i class="fas fa-save me-2"></i> Simpan Ke Cutoff</button>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h5 class="font-weight-bolder">Tambah Tunjangan</h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('master/tunjangan/create') ?>" role="form text-left" method="post">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Jenis Tunjangan <small class="text-danger">*</small></label>
                                        <select name="type" class="form-control" id="exampleFormControlSelect1" required>
                                            <option value="" selected="" disabled="">- Pilih Jenis Tunjangan</option>
                                            <option value="1" >Konsumtif</option>
                                            <option value="2" >Non - Konsumtif</option>
                                            <option value="3" >Pengurangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1">Role Tunjangan <small class="text-danger">*</small></label>
                                        <select name="role_id" class="form-control" id="exampleFormControlSelect1" required>
                                            <option value="" selected="" disabled="">- Pilih Role Tunjangan</option>
                                            <?php foreach($role as $r){ ?>
                                                <option value="<?= $r->id ?>" ><?= $r->kode." - ".$r->satuan ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Urut <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" placeholder="Nomor Urut Tunjangan" aria-label="Nomor Urut Tunjangan" name="urut" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label>Tunjangan <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Tunjangan" aria-label="Tunjangan" name="tunjangan" required>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <label>Keterangan <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Keterangan" aria-label="Keterangan" name="keterangan" required>
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