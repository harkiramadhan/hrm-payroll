<div class="container-fluid py-4">
    <div class="card">
        <!-- Card header -->
        <div class="card-header">
            <div class="row">
                <div class="col-lg-8">
                    <h5 class="mb-0"><strong>Detail Review Cutoff</strong></h5><br>
                    <h5 class="mb-0 mt-n4"><strong>Cabang: <?= $cutoff->cabang."/ Divisi: " . $cutoff->divisi ?></strong></h5>
                    <input type="hidden" value="<?= $cutoff->id ?>" id="cutoffid">
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

            </div>
        </div>
        
    </div>
</div>