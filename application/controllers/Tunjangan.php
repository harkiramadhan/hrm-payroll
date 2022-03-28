<?php
class Tunjangan extends CI_Controller{
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
            'title' => 'Tunjangan',
            'company' => $this->M_Company->getDefault(),
            'page' => 'tunjangan'
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $dataInsert = [
            'type' => $this->input->post('type', TRUE),
            'tunjangan' => $this->input->post('tunjangan', TRUE),
            'status' => 't'
        ];
        $this->db->insert('tunjangan', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        $dataUpdate = [
            'type' => $this->input->post('type', TRUE),
            'tunjangan' => $this->input->post('tunjangan', TRUE),
            'status' => 't'
        ];
        $this->db->where('id', $id)->update('tunjangan', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('tunjangan');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $tunjangan = $this->db->get_where('tunjangan', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Tunjangan</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('tunjangan/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Jenis Tunjangan <small class="text-danger">*</small></label>
                                    <select name="type" class="form-control" id="exampleFormControlSelect1" required>
                                        <option value="" selected="" disabled="">- Pilih Jenis Tunjangan</option>
                                        <option value="1" <?= ($tunjangan->type == 1 ) ? 'selected' : '' ?> >Konsumtif</option>
                                        <option value="2" <?= ($tunjangan->type == 2 ) ? 'selected' : '' ?> >Non - Konsumtif</option>
                                        <option value="3" <?= ($tunjangan->type == 3 ) ? 'selected' : '' ?> >Pengurangan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>Tunjangan <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Tunjangan" aria-label="Tunjangan" name="tunjangan" value="<?= $tunjangan->tunjangan ?>" required>
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

        $get = $this->db->order_by('id', "ASC")->get('tunjangan');

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $badge = jenisTunjangan($row->type);
            $data[] = [
                $no++,
                $badge,
                '<strong>'.$row->tunjangan.'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('tunjangan/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('tunjangan/edit/').'" + id,
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