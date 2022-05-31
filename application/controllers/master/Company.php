<?php
class Company extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company',
        ]);
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Master Company',
            'company' => $this->M_Company->getDefault(),
            'shift' => $this->db->order_by('id', "ASC")->get('shift'),
            'page' => 'master/company'
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        if($this->input->post('is_default', TRUE)  == 't'){
            $cek = $this->db->get_where('company', ['is_default' => 't']);
            if($cek->num_rows() > 0){
                $this->db->where('id', $cek->row()->id)->update('company', [
                    'is_default' => 'f'
                ]);
            }
        }

        $datas = [
            'kode' => $this->input->post('kode', TRUE),
            'company' => $this->input->post('company', TRUE),
            'is_default' => $this->input->post('is_default', TRUE),
        ];
        $this->db->insert('company', $datas);

        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        if($this->input->post('is_default', TRUE)  == 't'){
            $cek = $this->db->get_where('company', ['is_default' => 't']);
            if($cek->num_rows() > 0){
                $this->db->where('id', $cek->row()->id)->update('company', [
                    'is_default' => 'f'
                ]);
            }
        }

        $datas = [
            'kode' => $this->input->post('kode', TRUE),
            'company' => $this->input->post('company', TRUE),
            'is_default' => $this->input->post('is_default', TRUE),
        ];
        $this->db->where('id', $id);
        $this->db->update('company', $datas);

        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('company');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $company = $this->db->get_where('company', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Company</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/company/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>Company<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Nama Company" aria-label="Nama Company" name="company" value="<?= $company->company ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <label>Kode <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Kode" aria-label="Kode" name="kode" value="<?= $company->kode ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Default Company ?<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_default" id="inlineRadio1" value="t" <?= ($company->is_default == 't') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_default" id="inlineRadio2" value="f" <?= ($company->is_default == 'f') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
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

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->get('company');

        $data = array();
        $no = 1;

        foreach($get->result() as $row){
            $data[] = [
                '<strong>'.$no++.'</strong>',
                '<strong>'.$row->company.'</strong>',
                '<p class="text-center mb-0"><strong>'.$row->kode.'</strong></p>',
                '<p class="text-center mb-0">'.badgeCompany($row->is_default).'</p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('master/company/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('master/company/edit/').'" + id,
                            type : "post",
                            data : {id : id},
                            success: function(res){
                                $(".data-edit").html(res)
                                $("#modalEdit").modal("show")
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