<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Detail Summary Mitra</strong></h5><br>
                    <h5 class="mb-0 mt-n4"><strong>Cabang : <?= $summary->cabang ?></strong></h5><br>
                    <h5 class="mb-0 mt-n4"><strong>Periode : <?= $summary->tahun."".sprintf("%02d", $summary->bulan) ?></strong></h5>
                    <input type="hidden" value="<?= $summary->cutoff_id ?>" id="cutoffid">
                    <input type="hidden" value="<?= $this->uri->segment(2) ?>" id="logid">
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3 table-summary rounded">
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Summary</strong></h5>
                </div>

            </div>
        </div>
        
    </div>
</div>