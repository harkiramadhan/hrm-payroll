<div class="container-fluid py-4">
    <div class="card ">
        <!-- Card header -->
        <div class="card-header pb-0">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Transaksi Summary</strong></h5>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-3 mb-3">
                    <label>Start Date</label>
                    <input class="form-control" type="date" placeholder="Start Date" id="start_date">
                </div>
                <div class="col-lg-3 mb-3">
                    <label>End Date</label>
                    <input class="form-control" type="date" placeholder="End Date" id="end_date">
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

    <div class="card mt-3">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Detail Summary</strong></h5>
                </div>
            </div>
        </div>
        <div class="table-responsive table-summary" style="max-height: 500px!important">

        </div>
    </div>
</div>