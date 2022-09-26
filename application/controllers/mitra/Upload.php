<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class Upload extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company',
            'M_Cutoff'
        ]);
        $this->companyid = $this->session->userdata('company_id');
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Upload Data Cutoff Mitra',
            'company' => $this->M_Company->getById($this->companyid),
            'cabang' => $this->db->get_where('cabang', ['company_id' => $this->companyid, 'status' => 't']),
            'cutoff' => $this->M_Cutoff->getActive($this->companyid),
            'page' => 'mitra/mitra_upload_data'
        ];
        $this->load->view('templates', $var);
    }

    function detail($id){
        $summary = $this->db->select('la.*, c.bulan, c.tahun, c.start_date, c.end_date, cb.cabang')
                            ->from('log_upload_mitra la')
                            ->join('cutoff c', 'la.cutoff_id = c.id')
                            ->join('cabang cb', 'la.cabang_id = cb.id')
                            ->where([
                                'la.id' => $id,
                            ])->get()->row();
        $var = [
            'title' => 'Detail Summary Mitra',
            'company' => $this->M_Company->getById($this->companyid),
            'summary' => $summary,
            'page' => 'mitra/detail_summary',
            'ajax' => [
                'mitra_summary'
            ]
        ];
        $this->load->view('templates', $var);                
    }

    function update($id){
        $logid = $this->input->post('log_id', TRUE);
        $cutoffid = $this->input->post('cutoff_id', TRUE);
        $pegawaiid = $this->input->post('pegawai_id', TRUE);
        $arrayJumlah = $this->input->post('jumlah[]', TRUE);

        $tunjanganid = $this->input->post('tunjangan_id', TRUE);
        foreach($tunjanganid as $tr => $valTr){ 
            $jumlah = $arrayJumlah[$tr];
            $nominalTunjangan = str_replace([',', '.'], '', $valTr);
            $cekTunjangan = $this->db->get_where('summary_mitra_detail', [
                'pegawai_id' => $pegawaiid,
                'cutoff_id' => $cutoffid,
                'log_id' => $logid,
                'tunjangan_id' => $tr
            ]);
            if($cekTunjangan->num_rows() > 0){
                $this->db->where('id', $cekTunjangan->row()->id)->update('summary_mitra_detail', [
                    'log_id' => $logid,
                    'pegawai_id' => $pegawaiid,
                    'cutoff_id' => $cutoffid,
                    'tunjangan_id' => $tr,
                    'jumlah' => $jumlah,
                    'nominal' => $nominalTunjangan
                ]);
            }else{
                $this->db->insert('summary_mitra_detail', [
                    'log_id' => $logid,
                    'pegawai_id' => $pegawaiid,
                    'cutoff_id' => $cutoffid,
                    'tunjangan_id' => $tr,
                    'jumlah' => $jumlah,
                    'nominal' => $nominalTunjangan
                ]);
            }
        }

        $nominal_gapok = str_replace(['.', ','], '', $this->input->post('nominal_gapok', TRUE));
        $nominal_gaji_dilaporkan = str_replace(['.', ','], '', $this->input->post('nominal_gaji_dilaporkan', TRUE));
        $total_tunjangan = str_replace(['.', ','], '', $this->input->post('total_tunjangan', TRUE));
        $total_tunjangan_non_tunai = str_replace(['.', ','], '', $this->input->post('total_tunjangan_non_tunai', TRUE));
        $total_tunjangan_pengurangan = str_replace(['.', ','], '', $this->input->post('total_tunjangan_pengurangan', TRUE));
        $nominal_insentif = str_replace(['.', ','], '', $this->input->post('nominal_insentif', TRUE));

        $this->db->where('id', $id)->update('summary_mitra', [
            'nominal_gapok' => $nominal_gapok,
            'nominal_gaji_dilaporkan' => $nominal_gaji_dilaporkan,
            'nominal_tunjangan' => $total_tunjangan,
            'total_tunjangan_non_tunai' => $total_tunjangan_non_tunai,
            'total_tunjangan_pengurangan' => $total_tunjangan_pengurangan,
            'nominal_insentif' => $nominal_insentif,
            'thp' => ($nominal_insentif + $total_tunjangan) - $total_tunjangan_pengurangan
        ]);
        $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    function remove($id){
        $absensi = $this->db->get_where('log_upload_mitra', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-body pb-0">
                    <form action="<?= site_url('mitra/upload/delete/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h1 class="mb-3 text-danger"><i class="fas fa-exclamation"></i></h1>
                                <h5><strong class="mb-0">Hapus Absensi <br>
                                        Filename : <u><?= $absensi->filename ?></u>
                                </strong></h5>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-round bg-danger btn-lg w-100 mt-4 mb-0 text-white">Hapus</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        <?php
    }

    function modalDetailErrorLog(){
        $logid = $this->input->get('id', TRUE);
        $dataByLogId = $this->db->get_where('mitra_error_log', ['log_id' => $logid])->result();
        ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center w-5px">No</th>
                                    <th>Error Message</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    foreach($dataByLogId as $row){ 
                                        $data = json_decode($row->data);
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row->error_log ?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-2">
                                                    <small>
                                                        NIP<br>
                                                        Nama<br>
                                                        Tunjangan<br>
                                                        Angka<br>
                                                    </small>
                                                </div>
                                                <div class="col-10">
                                                    <small>
                                                        : <strong><?= $data->nik ?></strong> <br>
                                                        : <strong><?= $data->nama ?></strong> <br>
                                                        : <strong><?= $data->tunjangan ?></strong> <br>
                                                        : <strong><?= $data->angka ?></strong> <br>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $logAbsensi = $this->db->select('u.username, la.*, c.cabang, ct.bulan, ct.tahun')
                            ->from('log_upload_mitra la')
                            ->join('user u', 'la.user_id = u.id')
                            ->join('cabang c', 'la.cabang_id = c.id')
                            ->join('cutoff ct', 'la.cutoff_id = ct.id')
                            ->where([
                                'la.company_id' => $this->companyid
                            ])->order_by('id', "DESC")->get();
        $data = array();
        $no = 1;

        foreach($logAbsensi->result_array() as $row){
            $getLocked = $this->db->select('id')->get_where('summary_mitra', ['log_id' => $row['id'], 'lock' => 't'])->num_rows();
            $error = ($row['error_row'] > 0) ? $row['error_row'] : 0;
            $periode = 'Periode ' . $row['tahun']."".sprintf("%02d", $row['bulan']);
            $data[] =[
                $no++,
                '<p class="mb-0";><strong>'.$row['username'].'</strong></p>',
                '<p class="mb-0";><strong>'.$row['cabang'].'</strong></p>',
                '<p class="mb-0 text-center";><strong>'.$periode.'</strong></p>',
                '<a class="btn btn-sm btn-round btn-secondary text-white px-3 mb-0 mx-1" href="'.base_url('uploads/mitra/' . $row['filename']).'" style="width:100%" download><i class="fas fa-download me-2" aria-hidden="true"></i>'.$row['filename'].'</a>',
                '<button type="button" class="btn btn-sm btn-round btn-danger text-white px-3 mb-0 btn-detail-error" onclick="errorLogDetail('.$row['id'].')" style="width:100%"><i class="fas fa-arrow-up me-2" aria-hidden="true"></i>Error '.$error.' Row</button>',
                '<p class="text-center mb-0";><strong>'.$row['success_row'].'</strong></p>',
                '<p class="text-center mb-0";><strong>'.$row['total_row'].'</strong></p>',
                '<p class="text-center mb-0";><strong>'.$getLocked.'</strong></p>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row['timestamp']))).' - '.date('H:i:s', strtotime($row['timestamp'])).'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a href="'.site_url('mitra/' . $row['id']).'" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</a>
                    <button type="button" class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" onclick="remove('.$row['id'].')"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
                </div>
                
                <script>
                    function errorLogDetail(id){
                        var logid = id
                        $.ajax({
                            url: "'.site_url('mitra/upload/modalDetailErrorLog').'",
                            type: "get",
                            data: {id : id},
                            beforeSend: function(){
                                $("#modalEdit").modal("show")
                            },
                            success: function(res){
                                $(".data-edit").html(res)
                            }
                        })
                    }

                    function remove(id){
                        $.ajax({
                            url : "'.site_url('mitra/upload/remove/').'" + id,
                            type : "post",
                            data : {id : id},
                            success: function(res){
                                $(".data-delete").html(res)
                                $("#modalDelete").modal("show")
                            }
                        })
                    }
                </script>
                '
            ];
        }

        $output = [
            "draw"              => $draw,
            "recordsTotal"      => $logAbsensi->num_rows(),
            "recordsFiltered"   => $logAbsensi->num_rows(),
            "data"              => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    function detailTable(){
        $cutoffid = $this->input->get('cutoffid', TRUE);
        $logid = $this->input->get('logid', TRUE);

        $tunjangan = $this->db->select('t.*')
                                ->from('tunjangan t')
                                ->join('role_tunjangan rt', 't.role_id = rt.id')
                                ->where([
                                    't.company_id' => $this->companyid,
                                    't.status' => 't',
                                    'rt.jenis' => 'Mitra'
                                ])->order_by('t.urut', "ASC")->get();

        $getData = $this->db->select('p.nama, p.nik, s.*, tt.nama nama_template')
                            ->from('summary_mitra s')
                            ->join('pegawai p', 's.pegawai_id = p.id')
                            ->join('tunjangan_pegawai tp', 'tp.pegawai_id = p.id', "LEFT")
                            ->join('template_tunjangan tt', 'tp.template_id = tt.id', "LEFT")
                            ->where([
                                's.log_id' => $logid,
                                's.cutoff_id' => $cutoffid,
                                's.lock' => 'f'
                            ])->order_by('p.nama', "ASC")->get();
                            
        ?>
            <style>
                .sticky-col {
                    position: -webkit-sticky;
                    position: sticky;
                    background-color: white !important;
                }

                .first-col {
                    width: 100px;
                    min-width: 100px;
                    max-width: 100px;
                    left: 0px;
                }

                .second-col {
                    width: 150px;
                    min-width: 150px;
                    max-width: 150px;
                    left: 100px;
                }

                .third-col {
                    width: 250px;
                    min-width: 250px;
                    max-width: 250px;
                    left: 250px;
                }

                .four-col {
                    width: 100px;
                    min-width: 100px;
                    max-width: 100px;
                    left: 500px;
                }
            </style>
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <h5 class="mb-0"><strong>Summary</strong></h5>
                    </div>
                    <div class="col-lg-3 text-end pe-0">
                        <button type="button" class="btn btn-sm btn-warning" id="btn-lock"><i class="fas fa-lock me-2"></i> Lock</button>
                    </div>
                    <div class="col-lg-3 text-end">
                        <input type="text" name="" id="myInput" class="form-control form-control-sm" placeholder="Cari ...">
                    </div>
                </div>
            </div>

            <div class="table-responsive" style="max-height: 500px!important">
                <table id="example" class="table table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class="text-center w-5px sticky-col first-col">No</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class=" sticky-col second-col">NIP</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class=" sticky-col third-col">Nama</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class=" sticky-col four-col">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input mt-1 check-all" id="customCheckAll" style="margin-left: auto!important; margin-right: auto !important; left: -15px!important">
                                </div>
                                <small><strong>Check All</strong></small>
                            </th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="">Template <br> Tunjangan</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal Gaji <br> Pokok</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal Gaji <br> Dilaporkan</th>
                            <th colspan="<?= $tunjangan->num_rows() ?>" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Tunjangan</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal <br> Insentif</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal <br> Tunjangan</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">THP</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Action</th>
                        </tr>
                        <tr>
                            <?php 
                                foreach($tunjangan->result() as $th){ 
                                    $class = 'bg-white';
                                    if($th->type == 3){
                                        $class = 'bg-danger text-white';
                                    }
                            ?>
                                <th class="text-center <?= $class ?>" style="vertical-align:middle;text-align:center;position:sticky;top:50px;"><strong><?= $th->tunjangan ?></strong></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php
                            $no = 1; 
                            foreach($getData->result() as $row){ 
                        ?>
                        <tr>
                            <td style="z-index: 2;" class="text-center sticky-col first-col" width="5px"><?= $no++ ?></td>
                            <td style="z-index: 2;" class="sticky-col second-col"><strong><?= $row->nik ?></strong></td>
                            <td style="z-index: 2;" class="sticky-col third-col"><strong><?= $row->nama ?></strong></td>
                            <td style="z-index: 2;" class="sticky-col four-col">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input mt-1 check-data" name="pegawai_id" id="checkPegawai<?= $row->id ?>" value="<?= $row->id ?>" style="margin-left: auto!important; margin-right: auto !important;">
                                </div>
                            </td>
                            <td class="text-center"><strong><?= $row->nama_template ?></strong></td>
                            <td class="text-center"><strong><?= ($row->nominal_gapok) ? rupiah($row->nominal_gapok) : '-' ?></strong></td>
                            <td class="text-center"><strong><?= ($row->nominal_gaji_dilaporkan) ? rupiah($row->nominal_gaji_dilaporkan) : '-' ?></strong></td>
                            <?php 
                                foreach($tunjangan->result() as $tr){    
                                    $cekSummaryTunjangan = $this->db->get_where('summary_mitra_detail', ['pegawai_id' => $row->pegawai_id, 'log_id' => $logid, 'tunjangan_id' => $tr->id])->row();
                                    if(@$cekSummaryTunjangan->nominal){
                                        $nominalTunjangan = rupiah((int)str_replace('.', '', $cekSummaryTunjangan->nominal));
                                    }else{
                                        $nominalTunjangan = '-';
                                    }
                            ?>
                                <td class="text-center"><strong><?= $nominalTunjangan ?></strong></td>
                            <?php } ?>
                            <td class="text-center"><strong><?= ($row->nominal_insentif) ? rupiah($row->nominal_insentif) : '-' ?></strong></td>
                            <td class="text-center"><strong><?= ($row->nominal_tunjangan) ? rupiah($row->nominal_tunjangan) : '-' ?></strong></td>
                            <td class="text-center"><strong><?= ($row->thp) ? rupiah($row->thp) : '-' ?></strong></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit(<?= $row->id ?>)"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <form action="" method="post" id="form-lock">
                <input type="hidden" name="log_id" value="<?= $logid ?>">
                <div class="hasil">

                </div>
            </form>

            <script>
                $('#btn-lock').prop('disabled', true)
                function btnLock(){
                    if($('input[name="id[]"]').length === 0){
                        $('#btn-lock').prop('disabled', true)
                    }else{
                        $('#btn-lock').prop('disabled', false)
                    }
                }

                $('.check-all').click(function(){
                    if($(this).is(':checked')){
                        $('.check-data').prop('checked', true)
                        $(".check-data").each(function(){
                            var val = $(this).val()
                            if($("#hasil_" + val).length == 0) {
                                $('.hasil').append(
                                    $("<input>", {
                                        type: "hidden",
                                        val: val,
                                        name: "id[]",
                                        id: "hasil_" + val
                                    })
                                )
                            }
                        })
                    }else{
                        $('.check-data').prop('checked', false)
                        $(".check-data").each(function(){
                            var val = $(this).val()
                            $('#hasil_' + val).remove()
                        })
                    }

                    btnLock()
                })
                
                $('.check-data').click(function(){
                    var val = $(this).val()
                    
                    if($(this).is(':checked')){
                        $('.hasil').append(
                            $("<input>", {
                                type: "hidden",
                                val: val,
                                name: "id[]",
                                id: "hasil_" + val
                            })
                        )
                    }else{
                        $('#hasil_' + val).remove()
                    }

                    btnLock()
                })
                
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase()
                    $("#myTable tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    })
                })

                $('#btn-lock').click(function(){
                    var form = $('#form-lock').serialize()
                    $.ajax({
                        url: '<?= site_url('mitra/upload/summaryLock') ?>',
                        type: 'post',
                        data: form,
                        success: function(res){
                            location.reload()
                        }
                    })
                })

                function edit(id){
                    var logid = '<?= $logid ?>'
                    var cutoffid = '<?= $cutoffid ?>'
                    $.ajax({
                        url: '<?= site_url('mitra/upload/editSummary/') ?>' + id,
                        type: 'get',
                        data: {logid : logid, cutoffid : cutoffid},
                        success: function(res){
                            $('.data-edit-xl').html(res)
                            $('#modalEditXl').modal('show')
                        }
                    })
                }
            </script>
        <?php
        
    }

    function download($cabang_id){
        $cutoff = $this->M_Cutoff->getActive($this->companyid);
        $stringCutoff = "Periode " . $cutoff->tahun."".sprintf("%02d", $cutoff->bulan);
        $tunjangan = $this->db->select('t.*')
                                ->from('tunjangan t')
                                ->join('role_tunjangan rt', 't.role_id = rt.id')
                                ->where([
                                    'rt.jenis' => 'Mitra',
                                    't.status' => 't'
                                ])->order_by('t.urut', "ASC")->get();

        $pegawai = $this->db->select('p.nama, p.nik')
                            ->from('pegawai p')
                            ->join('jabatan j', 'p.jabatan_id = j.id')
                            ->where([
                                'p.company_id' => $this->companyid,
                                'p.cabang_id' => $cabang_id,
                                'j.jabatan' => 'Mitra'
                            ])->order_by('p.nama', "ASC")->get();

        $cabang = $this->db->get_where('cabang', ['id' => $cabang_id])->row();
                            
        $spreadsheet = new Spreadsheet();  
        $Excel_writer = new Xlsx($spreadsheet);

        $lastColumn = $this->toAlpha($tunjangan->num_rows() + 2);

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $styleBold = [
            'font' => [
                'bold' => true,
            ],
        ];

        $sheet->freezePane('D4'); 

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getRowDimension(3)->setRowHeight(45);
        $sheet->getColumnDimension($lastColumn)->setAutoSize(true);

        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3')->getAlignment()->setVertical('center');
        $sheet->getStyle('B3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B3')->getAlignment()->setVertical('center');
        $sheet->getStyle('C3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C3')->getAlignment()->setVertical('center');
        $sheet->getStyle('D3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D3')->getAlignment()->setVertical('center');
        $sheet->getStyle( $lastColumn . '3')->getAlignment()->setVertical('center');

        $sheet->getStyle('A1:' .$lastColumn. '1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:' .$lastColumn. '2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:' .$lastColumn. '1')->applyFromArray($styleBold);
        $sheet->getStyle('A2:' .$lastColumn. '2')->applyFromArray($styleBold);
        
        $sheet->getStyle('A3')->applyFromArray($styleBold);
        $sheet->getStyle('B3')->applyFromArray($styleBold);
        $sheet->getStyle('C3')->applyFromArray($styleBold);
        $sheet->getStyle('D3')->applyFromArray($styleBold);
        $sheet->getStyle($lastColumn . '3')->applyFromArray($styleBold);
        

        $sheetStyle = $spreadsheet->getActiveSheet();
        $sheetStyle->mergeCells('B1:' .$lastColumn. '1');
        $sheetStyle->mergeCells('B2:' .$lastColumn. '2');
        $sheetStyle->setCellValue('B1','Format Import Mitra - ' . $cabang->cabang);
        $sheetStyle->setCellValue('B2', $stringCutoff);
        $sheetStyle->setCellValue('A3','No');
        $sheetStyle->setCellValue('B3','NIP');
        $sheetStyle->setCellValue('C3','Nama');
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f5b642');

        $number = 4;
        foreach($tunjangan->result() as $row){
            if($row->type == 3){
                $sheet->getStyle($alpha . '3')->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('fc4503');
                $sheet->getStyle($alpha . '3')->getFont()
                        ->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
            }
            
            $alpha = $this->toAlpha($number);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($number, 3, $row->tunjangan);
            $sheet->getStyle($alpha . '3')->applyFromArray($styleBold);
            $sheet->getColumnDimension($alpha)->setAutoSize(true);
            $sheet->getStyle($alpha . '3')->getAlignment()->setHorizontal('center');
            $sheet->getStyle($alpha . '3')->getAlignment()->setVertical('center');
            $number++;
        }

        $no = 1;
        $excel_row = 4;
        foreach($pegawai->result() as $p){
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $no++);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $p->nik);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $p->nama);
            $excel_row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: attachment;filename=Format Import Mitra - '. $cabang->cabang .' - ' .$stringCutoff. '.Xlsx'); 
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $Excel_writer->save('php://output');
        exit;

        redirect($_SERVER['HTTP_REFERER']);
    }

    function import(){
        $cutoffid = $this->input->post('cutoff_id', TRUE);
        $cabangid = $this->input->post('cabang_id', TRUE);

        $cutoff = $this->db->get_where('cutoff', ['id' => $cutoffid])->row();
        $this->load->library('form_validation');
         $this->form_validation->set_rules('file', 'Upload File', 'callback_checkFileValidation');
         if($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect($_SERVER['HTTP_REFERER']);
         } else {
            if(!empty($_FILES['file']['name'])) { 
                $arr_file                   = explode('.', $_FILES['file']['name']);
                $extension                  = end($arr_file);
                $config['upload_path']      = './uploads/mitra/';
                $config['allowed_types']    = 'xlsx';
                $config['file_name']        = str_replace(' ', '_', $arr_file[0])."_".time().".".$extension;
                $config['overwrite']        = TRUE;

                $this->load->library('upload', $config);
                $this->upload->do_upload('file');
                $upload_data = $this->upload->data();
                $fileImport = $upload_data['file_name'];
                if(!$this->upload->do_upload('file')){
                    $this->session->set_flashdata('error', "Silahkan Pilih File Terlebih Dahulu");
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $newFileNames = explode('.',$fileImport);
                    $fileType = ucfirst($newFileNames[1]);
                    $path = './uploads/mitra/';
                    $inputFileType = $fileType;
                    $inputFileName = $path.$fileImport;
                    $reader = IOFactory::createReader($inputFileType);
                    $reader->setReadDataOnly(true);
                    $reader->setReadEmptyCells(false);
                    $spreadsheet = $reader->load($inputFileName);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
                    $sheet = $spreadsheet->getActiveSheet();
                    $stringCutoff = "Periode " . $cutoff->tahun."".sprintf("%02d", $cutoff->bulan);

                    foreach ($sheet->getRowIterator(3) as $index => $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(TRUE); 
                        $tunjangan_content = [];
                        $stringColumn = 3;
                        foreach ($cellIterator as $cell) {
                            $val = $cell->getValue();
                            if($val != 'No' && $val != 'NIP' && $val != 'Nama'){
                                $cekTunjangan = $this->db->select('t.*')
                                                        ->from('tunjangan t')
                                                        ->join('role_tunjangan rt', 't.role_id = rt.id')
                                                        ->where([
                                                            't.tunjangan' => $cell->getValue(),
                                                            'rt.jenis' => 'Mitra'
                                                        ])->get();

                                if($cekTunjangan->num_rows() > 0){
                                    $dataTunjangan = [
                                        'id' => $cekTunjangan->row()->id,
                                        'column' => $this->toAlpha($stringColumn),
                                        'tunjangan' => $cekTunjangan->row()->tunjangan
                                    ];
                                    array_push($tunjangan_content, $dataTunjangan);
                                }
                                $stringColumn++;
                            }
                        }
                        
                        $importTunjangan[] = $tunjangan_content;
                        break;
                    }
                    
                    if($sheetData[2]['B'] == $stringCutoff){
                        $dataLog = [
                            'company_id' => $this->companyid,
                            'user_id' => $this->session->userdata('userid'),
                            'cutoff_id' => $cutoffid,
                            'cabang_id' => $cabangid,
                            'filename' => $fileImport
                        ];
                        $this->db->insert('log_upload_mitra', $dataLog);
                        $logid = $this->db->insert_id();
                        $datas = [];
                        $success_row = 0;
                        $error_row = 0;
                        for($row = 4; $row <= count($sheetData); $row++){
                            $cekPegawai = $this->db->select('p.id, tp.template_id, p.nama, p.nominal_gaji_dilaporkan, p.nominal_gapok')
                                                    ->from('pegawai p')
                                                    ->join('tunjangan_pegawai tp', 'tp.pegawai_id = p.id')
                                                    ->join('template_tunjangan tt', 'tp.template_id = tt.id')
                                                    ->where([
                                                        'p.company_id' => $this->companyid,
                                                        'p.nik' => $sheetData[$row]['B']
                                                    ])->limit(1)->get();

                            if($cekPegawai->num_rows() > 0){
                                $pegawai = $cekPegawai->row();
                                $totalTunjangan = [];
                                $totalTunjanganNonTunai = [];
                                $totalTunjanganPengurangan = [];

                                foreach($importTunjangan[0] as $t){
                                    $cekTunjangan = $this->db->select('dt.type, dt.nominal, t.tunjangan, t.id, t.type type_tunjangan')
                                                            ->from('detail_template_tunjangan dt')
                                                            ->join('tunjangan t', 'dt.tunjangan_id = t.id')
                                                            ->where([
                                                                't.company_id' => $this->companyid,
                                                                'dt.template_id' => $pegawai->template_id,
                                                                't.id'=> $t['id'],
                                                                'dt.status' => 't'
                                                            ])->get();
                                    if($cekTunjangan->num_rows() > 0){
                                        if($sheetData[$row][$t['column']] != NULL) {
                                            $tunjangan = $cekTunjangan->row();
                                            $nominalTunjangan = (int)str_replace('.', '', $sheetData[$row][$t['column']]) * $tunjangan->nominal;
                                            $insert = [
                                                'log_id' => $logid,
                                                'pegawai_id' => $pegawai->id,
                                                'cutoff_id' => $cutoffid,
                                                'tunjangan_id' => $tunjangan->id,
                                                'jumlah' => rupiah($tunjangan->nominal)." * ".$sheetData[$row][$t['column']],
                                                'rit' => $sheetData[$row][$t['column']],
                                                'nominal' => $nominalTunjangan
                                            ];
                                            $this->db->insert('summary_mitra_detail', $insert);
                                            if($this->db->affected_rows() > 0){
                                                if($tunjangan->type_tunjangan == 1){
                                                    array_push($totalTunjangan, $nominalTunjangan);
                                                }elseif($tunjangan->type_tunjangan == 2){
                                                    array_push($totalTunjanganNonTunai, $nominalTunjangan);
                                                }elseif($tunjangan->type_tunjangan == 3){
                                                    array_push($totalTunjanganPengurangan, $nominalTunjangan);
                                                }
                                            }else{
                                                $log = [
                                                    'company_id' => $this->companyid,
                                                    'log_id' => $logid,
                                                    'error_log' => 'Gagal Di Tambahkan Ke Database',
                                                    'data' => json_encode([
                                                        'nik' => $sheetData[$row]['B'],
                                                        'nama' => $sheetData[$row]['C'],
                                                        'tunjangan' => $tunjangan->tunjangan,
                                                        'angka' => $sheetData[$row][$t['column']]
                                                    ])
                                                ];
                                                $this->db->insert('mitra_error_log', $log);
                                                $error_row = $error_row + 1;
                                            }
                                        }
                                    }
                                }

                                $dataSummary = [
                                    'cutoff_id' => $cutoffid,
                                    'cabang_id' => $cabangid,
                                    'log_id' => $logid,
                                    'pegawai_id' => $pegawai->id,
                                    'nominal_gapok' => $pegawai->nominal_gapok,
                                    'nominal_gaji_dilaporkan' => $pegawai->nominal_gaji_dilaporkan,
                                    'nominal_tunjangan' => array_sum($totalTunjangan),
                                    'total_tunjangan_non_tunai' => array_sum($totalTunjanganNonTunai),
                                    'total_tunjangan_pengurangan' => array_sum($totalTunjanganPengurangan),
                                    'nominal_insentif' => NULL,
                                    'thp' => array_sum($totalTunjangan) - array_sum($totalTunjanganPengurangan) + $pegawai->nominal_gapok,
                                    'lock' => 'f'
                                ];
                                $this->db->insert('summary_mitra', $dataSummary);
                                if($this->db->affected_rows() > 0){
                                    $success_row = $success_row + 1;
                                }
                            }else{
                                /* Error Log Here - Pegawai Tidak Tersedia */
                                $log = [
                                    'company_id' => $this->companyid,
                                    'log_id' => $logid,
                                    'error_log' => 'Pegawai Tidak Tersedia',
                                    'data' => json_encode([
                                        'nik' => $sheetData[$row]['B'],
                                        'nama' => $sheetData[$row]['C']
                                    ])
                                ];
                                $this->db->insert('mitra_error_log', $log);
                                $error_row = $error_row + 1;
                            }
                        }
                        $dataUpdate = [
                            'success_row' => $success_row,
                            'error_row' => $error_row,
                            'total_row' => $success_row + $error_row
                        ];
                        $this->db->where('id', $logid)->update('log_upload_mitra', $dataUpdate);
                        if($this->db->affected_rows() > 0){
                            $this->session->set_flashdata('success', "File Berhasil Di Upload");
                            redirect($_SERVER['HTTP_REFERER']);
                        }else{
                            unlink($inputFileName);
                            $this->db->where('id', $logid)->delete('log_upload_mitra');
                            $this->session->set_flashdata('error', "File Gagal Di Upload");
                            redirect($_SERVER['HTTP_REFERER']);
                        }
                    }else{
                        unlink($inputFileName);
                        $this->session->set_flashdata('error', "File Tidak Sesuai Dengan Format Yang Tersedia");
                        redirect($_SERVER['HTTP_REFERER']);
                    }

                    if($spreadsheet == FALSE){
                        unlink($inputFileName);
                        $this->session->set_flashdata('error', "File Tidak Support !");
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            }              
        }
    }

    function delete($id){
        $log = $this->db->get_where('log_upload_mitra', ['id' => $id])->row();
        if(unlink('./uploads/mitra/' . $log->filename)){
            $this->db->where('id', $id)->delete('log_upload_mitra');
            if($this->db->affected_rows() > 0){
                $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
            }else{
                $this->session->set_flashdata('error', "Data Gagal Di Hapus");
            }
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function editSummary($id){
        $logid = $this->input->get('logid', TRUE);
        $cutoffid = $this->input->get('cutoffid', TRUE);
        $summary = $this->db->select('s.*, p.nama, p.nik, p.nominal_gapok, p.nominal_gaji_dilaporkan')
                            ->from('summary_mitra s')
                            ->join('pegawai p', 's.pegawai_id = p.id')
                            ->where([
                                's.id' => $id,
                                's.log_id' => $logid,
                                's.cutoff_id' => $cutoffid
                            ])->get()->row();

        $tunjanganPegawai = $this->db->select('tm.*, tm.nama nama_template')
                            ->from('tunjangan_pegawai tp')
                            ->join('template_tunjangan tm', 'tp.template_id = tm.id')
                            ->where([
                                'tp.pegawai_id' => $summary->pegawai_id
                            ])->get()->row();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h6 class="font-weight-bolder">Edit Summary</h6>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('mitra/upload/update/' . $id) ?>" role="form text-left" method="post">
                        <input type="hidden" name="pegawai_id" value="<?= $summary->pegawai_id ?>">
                        <input type="hidden" name="summary_id" value="<?= $summary->id ?>">
                        <input type="hidden" name="log_id" value="<?= $summary->log_id ?>">
                        <input type="hidden" name="cutoff_id" value="<?= $cutoffid ?>">

                        <div class="row">
                            <div class="col-lg-3">
                                <label>NIP</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="NIP" aria-label="NIP" name="nip" value="<?= $summary->nik ?>" disabled>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <label>Nama</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Nama" aria-label="Nama" name="nama" value="<?= $summary->nama ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <label>Template Tunjangan</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" value="<?= @$tunjanganPegawai->nama_template ?>" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Nominal Gaji Pokok</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="nominal_gapok" value="<?= ($summary->nominal_gapok) ? rupiah($summary->nominal_gapok) : ' - ' ?>" disabled>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Nominal Gaji Dilaporkan</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="nominal_gaji_dilaporkan" value="<?= ($summary->nominal_gaji_dilaporkan) ? rupiah($summary->nominal_gaji_dilaporkan) : ' - ' ?>" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive p-0 mt-4">
                            <table class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5px">No</th>
                                        <th width="5px">Tunjangan</th>
                                        <th width="5px">Tipe</th>
                                        <th></th>
                                        <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $not = 1;
                                        $getTunjangan = $this->db->select('dt.*, t.tunjangan, t.keterangan, t.type tunjangan_type, rt.kode, rt.satuan, t.id tunjanganid')
                                                                ->from('detail_template_tunjangan dt')
                                                                ->join('tunjangan t', 'dt.tunjangan_id = t.id')
                                                                ->join('role_tunjangan rt', 't.role_id = rt.id')
                                                                ->where([
                                                                    'dt.template_id' => @$tunjanganPegawai->id,
                                                                    't.status' => 't'
                                                                ])
                                                                ->order_by('t.urut', "ASC")->get();

                                        $totalTunjangan = [];
                                        $totalTunjanganPengurangan = [];
                                        $totalTunjanganNonTunai = [];

                                        foreach($getTunjangan->result() as $tem){
                                            $badgeTunjangan = jenisTunjangan($tem->tunjangan_type);
                                            $nominal = ($tem->type == 'N') ? rupiah($tem->nominal) : $tem->nominal."%";
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $not++ ?></td>
                                        <td><?= $tem->tunjangan.' - '.$tem->keterangan ?></td>
                                        <td><?= $badgeTunjangan ?></td>
                                        <td>
                                            <?php
                                                $nominalHasil = 0; 
                                                $cekTunjangan = $this->db->get_where('summary_mitra_detail', ['pegawai_id' => $summary->pegawai_id, 'log_id' => $logid, 'tunjangan_id' => $tem->tunjanganid]);
                                                if($cekTunjangan->num_rows() > 0){
                                                    $nominalHasil = $cekTunjangan->row()->nominal;
                                                    if($tem->nominal != 1){
                                                        $nominalString = rupiah($tem->nominal)." * ".$cekTunjangan->row()->rit;
                                                    }else{
                                                        $nominalString = rupiah($tem->nominal)." * ".rupiah($cekTunjangan->row()->nominal);
                                                    }
                                                }

                                                if($tem->tunjangan_type == 1){
                                                    array_push($totalTunjangan, $nominalHasil);
                                                }elseif($tem->tunjangan_type == 2){
                                                    array_push($totalTunjanganNonTunai, $nominalHasil);
                                                }else{
                                                    array_push($totalTunjanganPengurangan, $nominalHasil);
                                                }
                                            ?>    
                                            <strong><?= $nominalString  ?></strong>
                                        </td>
                                        <td>
                                            <input type="hidden" name="jumlah[<?= $tem->tunjangan_id ?>]" value="<?= $nominalString ?>">
                                            <input type="text" class="form-control form-control-sm nominal-tunjangan" name="tunjangan_id[<?= $tem->tunjangan_id ?>]" data-type="<?= $tem->tunjangan_type ?>" data-nom="<?= $tem->type ?>" data-id="<?= $tem->id ?>" value="<?= rupiah($nominalHasil) ?>">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-3">
                                <label>Total Insentif</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control nominal-insentif" placeholder="Total Insentif" id="nominal_insentif" name="nominal_insentif" value="<?= ($summary->nominal_insentif) ? rupiah($summary->nominal_insentif) : 0 ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Tunjangan</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Total Tunjangan" id="total_tunjangan" name="total_tunjangan" value="<?= ($summary->nominal_tunjangan) ? rupiah($summary->nominal_tunjangan) : rupiah(array_sum($totalTunjangan)) ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Tunjangan Non Tunai</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Total Tunjangan" id="total_tunjangan_non_tunai" name="total_tunjangan_non_tunai" value="<?= ($summary->total_tunjangan_non_tunai) ? rupiah($summary->total_tunjangan_non_tunai) : rupiah(array_sum($totalTunjanganNonTunai)) ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Tunjangan Pengurangan</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Total Tunjangan" id="total_tunjangan_pengurangan" name="total_tunjangan_pengurangan" value="<?= ($summary->total_tunjangan_pengurangan) ? rupiah($summary->total_tunjangan_pengurangan) : rupiah(array_sum($totalTunjanganPengurangan)) ?>">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 me-4 ml-4 mb-0 text-white">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>

            <script>
                $('.nominal-tunjangan, .nominal-insentif').keyup(function(){
                    var sumPenambahan = 0;
                    var sumNonTunai = 0;
                    var sumPengurangan = 0;

                    var formatted = formatRupiah($(this).val())
                    $(this).val(formatted)

                    $(".nominal-tunjangan[data-type=1]" ).each(function(){
                        var amountPenambahan = parseInt($(this).val().replace(/[^0-9]+/g, ""))
                        sumPenambahan +=amountPenambahan
                    })

                    var sumInsentif = parseInt($('#nominal_insentif').val().replace(/[^0-9]+/g, ""))
                    sumPenambahan += sumInsentif

                    $('#total_tunjangan').val(formatRupiah(sumPenambahan))

                    $(".nominal-tunjangan[data-type=2]" ).each(function(){
                        var amountNonTunai = parseInt($(this).val().replace(/[^0-9]+/g, ""))
                        sumNonTunai +=amountNonTunai
                    })

                    $('#total_tunjangan_non_tunai').val(formatRupiah(sumNonTunai))

                    $(".nominal-tunjangan[data-type=3]" ).each(function(){
                        var amountPengurangan = parseInt($(this).val().replace(/[^0-9]+/g, ""))
                        sumPengurangan +=amountPengurangan
                    })

                    $('#total_tunjangan_pengurangan').val(formatRupiah(sumPengurangan))
                })

                (function($) {
                $.fn.inputFilter = function(callback, errMsg) {
                    return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
                    if (callback(this.value)) {
                        // Accepted value
                        if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
                            $(this).removeClass("input-error");
                            this.setCustomValidity("");
                        }
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        // Rejected value - restore the previous one
                        $(this).addClass("input-error");
                        this.setCustomValidity(errMsg);
                        this.reportValidity();
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    } else {
                        // Rejected value - nothing to restore
                        this.value = "";
                    }
                    });
                };
                }(jQuery));

                function formatRupiah(angka, prefix){
                    var number_string = angka.toString().replace(/[^0-9]+/g, ""),
                    split   		= number_string.split(','),
                    sisa     		= split[0].length % 3,
                    rupiah     		= split[0].substr(0, sisa),
                    ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
        
                    // tambahkan titik jika yang di input sudah menjadi angka ribuan
                    if(ribuan){
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }
        
                    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
                }
            </script>
        <?php
    }

    function summaryLock(){
        $logid = $this->input->post('log_id', TRUE);
        $ids = $this->input->post('id[]', TRUE);
        $success_row = 0;
        foreach($ids as $val){
            $this->db->where(['id'=> $val, 'log_id' => $logid])->update('summary_mitra', [
                'lock' => 't'
            ]);
            if($this->db->affected_rows() > 0){
                $success_row = $success_row + 1;
            }
        }
        if($success_row > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Lock");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Lock");
        }
    }

    /* Form Validation Callback */
    public function checkFileValidation($string) {
        $file_mimes = array('text/x-comma-separated-values', 
          'text/comma-separated-values', 
          'application/octet-stream', 
          'application/vnd.ms-excel', 
          'application/x-csv', 
          'text/x-csv', 
          'text/csv', 
          'application/csv', 
          'application/excel', 
          'application/vnd.msexcel', 
          'text/plain', 
          'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        if(isset($_FILES['file']['name'])) {
            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);
            if(($extension == 'xlsx' || $extension == 'Xlsx' || $extension == 'xls') && in_array($_FILES['file']['type'], $file_mimes)){
                return true;
            }else{
                $this->form_validation->set_message('checkFileValidation', 'File Yang Di Pilih Tidak Sesuai');
                return false;
            }
        }else{
            $this->form_validation->set_message('checkFileValidation', 'Silahkan Pilih File Terlebih Dahulu');
            return false;
        }
    }

    function toNum($data) {
        $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                           'f', 'g', 'h', 'i', 'j',
                           'k', 'l', 'm', 'n', 'o',
                           'p', 'q', 'r', 's', 't',
                           'u', 'v', 'w', 'x', 'y',
                           'z'
                           );
        $alpha_flip = array_flip($alphabet);
        $return_value = -1;
        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $return_value +=
                ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
        }
        return $return_value;
    }

    function toAlpha($data){
        $alphabet =   array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $alpha_flip = array_flip($alphabet);
        if($data <= 25){
          return $alphabet[$data];
        }
        elseif($data > 25){
          $dividend = ($data + 1);
          $alpha = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / 26));
          } 
          return $alpha;
        }
    }
    
}