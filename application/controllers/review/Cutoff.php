<?php
class Cutoff extends CI_Controller{
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
            'title' => 'Review Cutoff',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $this->M_Cutoff->getActive($this->companyid),
            'page' => 'review/cutoff',
        ];
        $this->load->view('templates', $var);
    }

    function detail($id){
        $var = [
            'title' => 'Detail Cutoff',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $this->db->get_where('cutoff', ['id' => $id, 'company_id' => $this->companyid])->row(),
            'page' => 'review/cutoff_detail',
            'ajax' => [
                'cutoff'
            ]
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->order_by('id', "DESC")->get_where('cutoff', ['company_id' => $this->companyid]);

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $badge = ($row->is_active == 't') ? '<span class="badge badge-sm bg-gradient-success">Active</span>' : '<span class="badge badge-sm bg-gradient-danger">Non Active</span>';
            $periode = bulan($row->bulan)." ".$row->tahun;
            $data[] = [
                $no++,
                '<strong>'.$periode.'</strong>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row->start_date))).'</strong>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row->end_date))).'</strong>',
                $badge,
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a href="'.site_url('review/cutoff/' . $row->id).'" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</a>
                </div>'
            ];
        }
                        
        $output = [
            "draw"              => $draw,
            "recordsTotal"      => $get->num_rows(),
            "recordsFiltered"   => $get->num_rows(),
            "data"              => $data
        ];
        
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    function detailTable(){
        $cabangid = $this->input->get('cabangid', TRUE);
        $cutoffid = $this->input->get('cutoffid', TRUE);

        if(is_numeric($cabangid)){
            $this->db->where('s.cabang_id', $cabangid);
        }
        $getData = $this->db->select('s.*, p.nama')
                            ->from('summary s')
                            ->join('pegawai p', 's.nip = p.nik')
                            ->where('cutoff_id', $cutoffid)->get();

        ?>
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="mb-0"><strong>Summary</strong></h5>
                    </div>
                    <div class="col-lg-4 text-end">
                        <div class="form-group">
                            <select id="cabang_id" class="form-control">
                                <option value="all" <?= (!is_numeric($cabangid)) ? 'selected' : '' ?>>- Semua Cabang</option>
                                <?php 
                                    $cabang = $this->db->get_where('cabang', ['company_id' => $this->companyid, 'status' => 't']);
                                    foreach($cabang->result() as $d){ ?>
                                        <option value="<?= $d->id ?>" <?= ($cabangid == $d->id) ? 'selected' : '' ?>><?= $d->cabang ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive" style="max-height: 500px!important">
                <table id="example" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center w-5px">No</th>
                            <th class="">NIP</th>
                            <th>Nama</th>
                            <th class="text-left">Hari Efektif</th>
                            <th class="text-left">Hadir</th>
                            <th class="text-center">S</th>
                            <th class="text-center">I</th>
                            <th class="text-center">A</th>
                            <th class="text-center">Jum. Hari Terlambat</th>
                            <th class="text-center">Terlambat (Menit)</th>
                            <th class="text-center">Jum. Hari Lembur</th>
                            <th class="text-center">Lembur (Menit)</th>
                            <th class="">Shift</th>
                            <th class="" width="5px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $no = 1; 
                            foreach($getData->result() as $row){ 
                        ?>
                        <tr>
                            <td class="text-center" width="5px"><?= $no++ ?></td>
                            <td><strong><?= $row->nip ?></strong></td>
                            <td><strong><?= $row->nama ?></strong></td>
                            <td class="text-left"><strong><?= $row->hari_efektif." Hari" ?></strong></td>
                            <td class="text-left"><strong><?= $row->total_hadir." Hari" ?></strong></td>
                            <td class="text-center"><strong><?= $row->sakit ?></strong></td>
                            <td class="text-center"><strong><?= $row->izin ?></strong></td>
                            <td class="text-center"><strong><?= $row->alpa ?></strong></td>
                            <td class="text-center"><strong><?= $row->hari_terlambat." Hari" ?></strong></td>
                            <td class="text-center"><strong><?= $row->menit_terlambat." Menit"?></strong></td>
                            <td class="text-center"><strong><?= $row->hari_lembur." Hari" ?></strong></td>
                            <td class="text-center"><strong><?= $row->menit_lembur." Menit" ?></strong></td>
                            <td><strong><?= $row->shift ?></strong></td>
                            <td>
                                
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <script>
                $('#cabang_id').change(function(){
                    var cabangid = $(this).val()
                    var cutoffid = '<?= $cutoffid ?>'

                    $.ajax({
                        url: '<?= site_url('review/cutoff/detailTable') ?>',
                        type: 'get',
                        data: {cabangid : cabangid, cutoffid : cutoffid},
                        beforeSend: function(){
                            $('.table-summary').empty()
                        },
                        success: function(res){
                            localStorage.setItem('cabangid', cabangid)
                            $('.table-summary').html(res)
                        }
                    })
                })
            </script>
        <?php
    }
}