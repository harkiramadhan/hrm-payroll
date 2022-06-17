<?php
class Menus extends CI_Controller{
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
            'title' => 'Master Menu',
            'company' => $this->M_Company->getById($this->companyid),
            'page' => 'master/menu',
            'fontawesome' => $this->db->get('fontawesome'),
            'root' => $this->db->get_where('menu1', ['root' => 't', 'status' => 't'])
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $dataInsert = [
            'company_id' => $this->companyid,
            'menu' => $this->input->post('menu', TRUE),
            'url' => $this->input->post('url', TRUE),
            'urut' => $this->input->post('urut', TRUE),
            'root' => $this->input->post('root', TRUE),
            'status' => $this->input->post('status', TRUE),
            'dropdown' => $this->input->post('dropdown', TRUE),
            'root_id' => $this->input->post('root_id', TRUE),
            'icon' => $this->input->post('icon', TRUE)
        ];
        $this->db->insert('menu1', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        $dataUpdate = [
            'menu' => $this->input->post('menu', TRUE),
            'url' => $this->input->post('url', TRUE),
            'urut' => $this->input->post('urut', TRUE),
            'root' => $this->input->post('root', TRUE),
            'status' => $this->input->post('status', TRUE),
            'dropdown' => $this->input->post('dropdown', TRUE),
            'root_id' => $this->input->post('root_id', TRUE),
            'icon' => $this->input->post('icon', TRUE)
        ];
        $this->db->where('id', $id)->update('menu1', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('menu1');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $menu = $this->db->get_where('menu1', ['id' => $id])->row();
        $fontawesome = $this->db->get('fontawesome');
        $root = $this->db->get_where('menu1', ['root' => 't', 'status' => 't']);
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Menu</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/menus/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-5">
                                <label>Menu <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Menu" aria-label="Menu" name="menu" value="<?= $menu->menu ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <label>Url <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Url" aria-label="Url" name="url" value="<?= $menu->url ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label>Urut <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Urut" aria-label="Urut" name="urut" value="<?= $menu->urut ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Status<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio1Status" value="t" <?= ($menu->status == 't') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio1Status">Aktif</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio2Status" value="f" <?= ($menu->status == 'f') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio2Status">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Is Root ?<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="root" id="inlineRadioRoot1" value="t" <?= ($menu->root == 't') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadioRoot1">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="root" id="inlineRadioRoot2" value="f" <?= ($menu->root == 'f') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadioRoot2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Is Dropdown ?<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="dropdown" id="inlineRadio1" value="t" <?= ($menu->dropdown == 't') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="dropdown" id="inlineRadio2" value="f" <?= ($menu->dropdown == 'f') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Child From ?</label>
                                    <select name="root_id" class="form-control" id="exampleFormControlSelect1">
                                        <option value="" selected="" disabled="">- Pilih Root Menu</option>
                                        <?php foreach($root->result() as $row){ ?>
                                            <option value="<?= $row->id ?>" <?= ($menu->root_id == $row->id) ? 'selected' : '' ?>><?= $row->menu ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <label>Pilih Icon <small class="text-danger">*</small></label>
                                <div class="input-group mb-3 justify-content-center" style="max-height: 350px!important; overflow-y: scroll;">
                                    <?php foreach($fontawesome->result() as $fa){ ?>
                                        <div class="form-check form-check-inline text-center">
                                            <input class="form-check-input" type="radio" name="icon" id="inlineRadioIcon<?= $fa->id ?>" value="<?= $fa->class ?>" <?= ($menu->icon == $fa->class) ? 'checked' : '' ?> required="">
                                            <label class="form-check-label" for="inlineRadioIcon<?= $fa->id ?>"></label>
                                            <h4><i class="<?= $fa->class ?> mb-0"></i></h3>
                                        </div>
                                    <?php } ?>
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

        $get = $this->db->get_where('menu1', ['company_id' => $this->companyid]);

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            @$root = $this->db->get_where('menu1', ['id' => $row->root_id])->row()->menu;
            $badge = ($row->status == 't') ? '<span class="badge badge-sm bg-gradient-success">Active</span>' : '<span class="badge badge-sm bg-gradient-danger">Non Active</span>';
            $badgeRoot = (@$root) ? '<span class="badge badge-sm bg-primary">'.@$root.'</span>' : ' - ';
            $data[] = [
                '<strong>'.$row->id.'</strong>',
                '<strong>'.$row->menu.'</strong>',
                '<p class="text-center mb-0"><strong>'.$row->urut.'</strong></p>',
                '<p class="text-center mb-0"><strong><i class="text-primary '.$row->icon.'"></i></strong></p>',
                '<p class="text-center mb-0"><strong>'.$badge.'</strong></p>',
                '<strong>'.$row->url.'</strong>',
                '<p class="text-center mb-0"><strong>'.$row->dropdown.'</strong></p>',
                '<p class="text-center mb-0"><strong>'.$row->root.'</strong></p>',
                '<p class="text-center mb-0"><strong>'.$badgeRoot.'</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('master/menus/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('master/menus/edit/').'" + id,
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