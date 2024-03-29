<?php
class Users extends CI_Controller{
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
            'title' => 'Users',
            'company' => $this->M_Company->getById($this->companyid),
            'page' => 'users',
            'role' => $this->db->get('role')
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $cekUsername = $this->db->get_where('user', ['username' => $this->input->post('username', TRUE)]);
        if($cekUsername->num_rows() > 0){
            $this->session->set_flashdata('error', "Username Sudah Tersedia");
        }else{
            $dataInsert = [
                'company_id' => $this->companyid,
                'role_id' => $this->input->post('role_id', TRUE),
                'username' => $this->input->post('username', TRUE),
                'status' => ($this->input->post('status', TRUE) == 1) ? 't' : 'f',
                'password' => md5($this->input->post('password', TRUE))
            ];
            $this->db->insert('user', $dataInsert);
            if($this->db->affected_rows() > 0){
                $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
            }else{
                $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
            }
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        $cekUser = $this->db->get_where('user', ['id' => $id])->row();
        $dataUpdate = [
            'role_id' => $this->input->post('role_id', TRUE),
            'username' => $this->input->post('username', TRUE),
            'status' => ($this->input->post('status', TRUE) == 1) ? 't' : 'f',
            'password' => ($this->input->post('password', TRUE) == TRUE) ? md5($this->input->post('password', TRUE)) : $cekUser->password
        ];
        $this->db->where('id', $id)->update('user', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        if($this->session->userdata('userid') == $id){
            $this->session->set_flashdata('error', "Data Tidak Dapat Di Hapus");
        }else{
            $this->db->where('id', $id)->delete('user');
            if($this->db->affected_rows() > 0){
                $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
            }else{
                $this->session->set_flashdata('error', "Data Gagal Di Hapus");
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $role = $this->db->get('role');
        $user = $this->db->get_where('user', ['id' => $id, 'company_id' => $this->companyid])->row();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit User</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('users/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>Username <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" name="username" value="<?= $user->username ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>Password <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" placeholder="Password" aria-label="Password" name="password">
                                </div>
                            </div>
                            <?php if($this->session->userdata('roleid') == 1 || $this->session->userdata('roleid') == 3): ?>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Role <small class="text-danger">*</small></label>
                                    <select name="role_id" class="form-control" id="exampleFormControlSelect1" required="">
                                        <option value="" selected="" disabled="">- Pilih Role</option>
                                        <?php foreach($role->result() as $row){ ?>
                                            <option value="<?= $row->id ?>" <?= ($row->id == $user->role_id) ? 'selected' : '' ?>><?= $row->role ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <?php else: ?>
                                <input type="hidden" name="role_id" value="<?= $user->role_id ?>">
                            <?php endif; ?>
                            <div class="col-lg-12">
                                <label>Status<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="1" required="" <?= ($user->status == 't') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="inlineRadio1">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="2" required="" <?= ($user->status == 'f') ? 'checked' : '' ?>>
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

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        if($this->session->userdata('roleid') == 1){
            $get = $this->db->select('u.*, r.role')
                        ->from('user u')
                        ->join('role r', 'u.role_id = r.id')
                        ->where(['u.company_id' => $this->companyid])->get();
        }else{
            $get = $this->db->select('u.*, r.role')
                            ->from('user u')
                            ->join('role r', 'u.role_id = r.id')
                            ->where([
                                'u.company_id' => $this->companyid,
                                'u.id' => $this->session->userdata('userid')    
                            ])->get();
        }

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $badge = ($row->status == 't') ? '<span class="badge badge-sm bg-gradient-success">Active</span>' : '';
            $data[] = [
                $no++,
                '<strong>'.$row->username.'</strong>',
                '<p class="mb-0 text-center"><strong>'.$row->role.'</strong></p>',
                '<p class="mb-0 text-center">' . $badge . '</p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('users/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('users/edit/').'" + id,
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