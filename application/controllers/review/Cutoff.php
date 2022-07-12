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

    function update($id){
        $datas = [
            'hari_efektif' => $this->input->post('hari_efektif', TRUE),
            'total_hadir' => $this->input->post('total_hadir', TRUE),
            'izin' => $this->input->post('izin', TRUE),
            'sakit' => $this->input->post('sakit', TRUE),
            'alpa' => $this->input->post('alpa', TRUE),
            'hari_terlambat' => $this->input->post('hari_terlambat', TRUE),
            'menit_terlambat' => $this->input->post('menit_terlambat', TRUE),
            'hari_lembur' => $this->input->post('hari_lembur', TRUE),
            'menit_lembur' => $this->input->post('menit_lembur', TRUE),
            'shift' => $this->input->post('shift', TRUE)
        ];
        $this->db->where('id', $id)->update('summary', $datas);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $summary = $this->db->select('s.*, p.nama')
                            ->from('summary s')
                            ->join('pegawai p', 's.nip = p.nik')
                            ->where('s.id', $id)->get()->row();
        
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Summary</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('review/cutoff/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-3">
                                <label>NIP</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="NIP" aria-label="NIP" name="nip" value="<?= $summary->nip ?>" disabled>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <label>Nama</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Nama" aria-label="Nama" name="nama" value="<?= $summary->nama ?>" disabled>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Hari Efektif</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Hari Efektif" aria-label="Hari Efektif" name="hari_efektif" value="<?= $summary->hari_efektif ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Hadir (Hari)</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Hadir" aria-label="Total Hadir" name="total_hadir" value="<?= $summary->total_hadir ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Izin</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Izin" aria-label="Total Izin" name="izin" value="<?= $summary->izin ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Sakit</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Sakit" aria-label="Total Sakit" name="sakit" value="<?= $summary->sakit ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Alpa</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Alpa" aria-label="Total Alpa" name="alpa" value="<?= $summary->alpa ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Terlambat (Hari)</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Terlambat" aria-label="Total Terlambat" name="hari_terlambat" value="<?= $summary->hari_terlambat ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Terlambat (Menit)</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Terlambat" aria-label="Total Terlambat" name="menit_terlambat" value="<?= $summary->menit_terlambat ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Lembur (Hari)</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Lembur" aria-label="Total Lembur" name="hari_lembur" value="<?= $summary->hari_lembur ?>">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Total Lembur (Menit)</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Total Lembur" aria-label="Total Lembur" name="menit_lembur" value="<?= $summary->menit_lembur ?>">
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <div class="form-group">
                                    <label for="select-div">Shift</label>
                                    <select name="shift" class="form-control">
                                        <option value="" selected="" disabled="">- Pilih Shift</option>
                                        <?php 
                                            $shift = $this->db->get_where('shift', ['company_id' => $this->companyid, 'status' => 't']);
                                            foreach($shift->result() as $d){ ?>
                                            <option value="<?= $d->keterangan ?>" <?= ($d->keterangan == $summary->shift) ? 'selected' : '' ?> ><?= $d->keterangan ?></option>
                                        <?php } ?>
                                    </select>
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
        <?php
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
                                <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit(<?= $row->id ?>)"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <script>
                function edit(id){
                    $.ajax({
                        url: '<?= site_url('review/cutoff/edit/') ?>' + id,
                        type: 'get',
                        data: {id : id},
                        success: function(res){
                            $('.data-edit').html(res)
                            $('#modalEdit').modal('show')
                        }
                    })
                }

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