<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Detail Cutoff Periode <?= bulan($cutoff->bulan)." ".$cutoff->tahun ?></strong></h5><br>
                    <h5 class="mb-0 mt-n4"><strong><?= longdate_indo(date('Y-m-d', strtotime($cutoff->start_date)))." - ".longdate_indo(date('Y-m-d', strtotime($cutoff->end_date))) ?></strong></h5>
                    <input type="hidden" id="cutoffid" value="<?= $cutoff->id ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3 table-summary">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Summary</strong></h5>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="form-group">
                        <select id="cabang_id" class="form-control">
                            <option value="all" selected>- Semua Cabang</option>
                            <?php 
                                $cabang = $this->db->get_where('cabang', ['company_id' => $this->companyid, 'status' => 't']);
                                foreach($cabang->result() as $d){ ?>
                                <option value="<?= $d->id ?>"><?= $d->cabang ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>