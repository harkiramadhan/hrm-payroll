<?php
class Cutoff extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company',
        ]);
        $this->companyid = $this->session->userdata('company_id');
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Master Cutoff',
            'company' => $this->M_Company->getById($this->companyid),
            'page' => 'master/cutoff'
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        if($this->input->post('is_active') == 1){
            $cek = $this->db->get_where('cutoff', ['is_active' => 't', 'company_id' => $this->companyid]);
            if($cek->num_rows() > 0){   
                var_dump($cek->row());
                $this->db->where('id', $cek->row()->id)->update('cutoff', ['is_active' => 'f']);
            }
        }
        $dataInsert = [
            'company_id' => $this->companyid,
            'bulan' => $this->input->post('bulan', TRUE),
            'tahun' => $this->input->post('tahun', TRUE),
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'is_active' => ($this->input->post('is_active', TRUE) == 1) ? 't' : 'f',
        ];
        $this->db->insert('cutoff', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        if($this->input->post('is_active') == 1){
            $cek = $this->db->get_where('cutoff', ['is_active' => 't', 'company_id' => $this->companyid]);
            if($cek->num_rows() > 0){   
                var_dump($cek->row());
                $this->db->where('id', $cek->row()->id)->update('cutoff', ['is_active' => 'f']);
            }
        }
        $dataInsert = [
            'bulan' => $this->input->post('bulan', TRUE),
            'tahun' => $this->input->post('tahun', TRUE),
            'start_date' => $this->input->post('start_date', TRUE),
            'end_date' => $this->input->post('end_date', TRUE),
            'is_active' => ($this->input->post('is_active', TRUE) == 1) ? 't' : 'f',
        ];
        $this->db->where('id', $id)->update('cutoff', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('cutoff');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $cutoff = $this->db->get_where('cutoff', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Cutoff</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/cutoff/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>Bulan <small class="text-danger">*</small></label>
                                <select name="bulan" class="form-control" id="exampleFormControlSelect1" required>
                                    <option value="" selected="" disabled="">- Pilih Bulan</option>
                                    <?php foreach(range(1,12) as $row){ ?>
                                        <option value="<?= $row ?>" <?= ($cutoff->bulan == $row) ? 'selected' : '' ?>><?= bulan($row) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>Tahun <small class="text-danger">*</small></label>
                                <select name="tahun" class="form-control" id="exampleFormControlSelect2" required>
                                    <option value="" selected="" disabled="">- Pilih Tahun</option>
                                    <?php foreach(range(date('Y') - 2,date('Y') + 2) as $y){ ?>
                                        <option value="<?= $y ?>" <?= ($cutoff->tahun == $y) ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Tanggal Mulai<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control" placeholder="Tanggal Mulai" aria-label="Tanggal Mulai" name="start_date" value="<?= $cutoff->start_date ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Tanggal Selesai<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control" placeholder="Tanggal Selesai" aria-label="Tanggal Selesai" name="end_date" value="<?= $cutoff->end_date ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Status<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" id="inlineRadio1" value="1" required="" <?= ($cutoff->is_active == 't') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="inlineRadio1">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" id="inlineRadio2" value="2" required="" <?= ($cutoff->is_active == 'f') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="inlineRadio2">Non Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 mb-0 text-white">Simpan</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        <?php
    }

    function remove($id){
        $cutoff = $this->db->get_where('cutoff', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/cutoff/delete/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h1 class="mb-3 text-danger"><i class="fas fa-exclamation"></i></h1>
                                <h5><strong class="mb-0">Hapus Cutoff <?= $cutoff->periode ?> <br> Tanggal <?= longdate_indo(date('Y-m-d', strtotime($cutoff->start_date)))." - ".longdate_indo(date('Y-m-d', strtotime($cutoff->end_date))) ?> </strong></h5>
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
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <button type="button" class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" onclick="remove('.$row->id.')"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('master/cutoff/edit/').'" + id,
                            type : "post",
                            data : {id : id},
                            success: function(res){
                                $(".data-edit").html(res)
                                $("#modalEdit").modal("show")
                            }
                        })
                    }
                    function remove(id){
                        $.ajax({
                            url : "'.site_url('master/cutoff/remove/').'" + id,
                            type : "post",
                            data : {id : id},
                            success: function(res){
                                $(".data-delete").html(res)
                                $("#modalDelete").modal("show")
                            }
                        })
                    }
                </script>'
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
}