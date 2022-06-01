<?php
class Departement extends CI_Controller{
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
            'title' => 'Master Departement',
            'company' => $this->M_Company->getDefault(),
            'divisi' => $this->db->order_by('divisi', "ASC")->get('divisi'),
            'page' => 'kepegawaian/departement'
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $dataInsert = [
            'divisi_id' => $this->input->post('divisi_id', TRUE),
            'departement' => $this->input->post('departement', TRUE)
        ];
        $this->db->insert('departement', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        $dataUpdate = [
            'divisi_id' => $this->input->post('divisi_id', TRUE),
            'departement' => $this->input->post('departement', TRUE)
        ];
        $this->db->where('id', $id)->update('departement', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('departement');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $departement = $this->db->get_where('departement', ['id' => $id])->row();
        $divisi = $this->db->order_by('divisi', "ASC")->get('divisi');
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Departement</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('kepegawaian/departement/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Divisi <small class="text-danger">*</small></label>
                                    <select name="divisi_id" class="form-control" id="exampleFormControlSelect1" required="">
                                        <option value="" disabled="">- Pilih Divisi</option>
                                        <?php foreach($divisi->result() as $row){ ?>
                                            <option value="<?= $row->id ?>" <?= ($row->id == $departement->divisi_id) ? 'selected' : '' ?>><?= $row->divisi ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>Departement <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Departement" aria-label="Departement" name="departement" value="<?= $departement->departement ?>" required>
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

        $get = $this->db->select('d.*, dv.divisi')
                        ->from('departement d')
                        ->join('divisi dv', 'd.divisi_id = dv.id')
                        ->get();

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $data[] = [
                $no++,
                '<strong>'.$row->divisi.'</strong>',
                '<strong>'.$row->departement.'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('kepegawaian/departement/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('kepegawaian/departement/edit/').'" + id,
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