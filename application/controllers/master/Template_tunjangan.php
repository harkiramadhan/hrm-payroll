<?php
class Template_tunjangan extends CI_Controller{
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
            'title' => 'Master Template Tunjangan',
            'company' => $this->M_Company->getById($this->companyid),
            'page' => 'master/template_tunjangan'
        ];
        $this->load->view('templates', $var);
    }

    function detail($id){
        $tunjangan = $this->db->select('t.*, rt.kode, rt.satuan')
                            ->from('tunjangan t')
                            ->join('role_tunjangan rt', 't.role_id = rt.id')
                            ->where([
                                't.company_id' => $this->companyid,
                                't.status' => 't'
                            ])->order_by('t.id', "DESC")->get();
        $var = [
            'template_tunjangan' => $this->db->get_where('template_tunjangan', ['id' => $id])->row(),
            'tunjangan' => $tunjangan,
            'title' => 'Detail Template Tunjangan',
            'company' => $this->M_Company->getDefault(),
            'page' => 'master/detail_template_tunjangan'
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $dataInsert = [
            'company_id' => $this->companyid,
            'nama' => $this->input->post('nama', TRUE),
            'status' => $this->input->post('status', TRUE)
        ];
        $this->db->insert('template_tunjangan', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function createDetail(){
        $dataInsert = [
            'company_id' => $this->companyid,
            'template_id' => $this->input->post('template_id', TRUE),
            'tunjangan_id' => $this->input->post('tunjangan_id', TRUE),
            'nominal' => $this->input->post('nominal', TRUE),
            'type' => $this->input->post('type', TRUE),
            'status' => $this->input->post('status', TRUE)
        ];
        $this->db->insert('detail_template_tunjangan', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        $dataUpdate = [
            'nama' => $this->input->post('nama', TRUE),
            'status' => $this->input->post('status', TRUE)
        ];
        $this->db->where('id', $id)->update('template_tunjangan', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function updateDetail($id){
        $dataUpdate = [
            'tunjangan_id' => $this->input->post('tunjangan_id', TRUE),
            'nominal' => $this->input->post('nominal', TRUE),
            'type' => $this->input->post('type', TRUE),
            'status' => $this->input->post('status', TRUE)
        ];
        $this->db->where('id', $id)->update('detail_template_tunjangan', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('template_tunjangan');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function deleteDetail($id){
        $this->db->where('id', $id)->delete('detail_template_tunjangan');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $template = $this->db->get_where('template_tunjangan', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Template Tunjangan</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/template_tunjangan/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-8">
                                <label>Nama Template Tunjangan <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Nama Template Tunjangan" aria-label="Nama Template Tunjangan" name="nama" value="<?= $template->nama ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Status<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="t" <?= ($template->status == 't') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio1">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="f" <?= ($template->status == 'f') ? 'checked' : '' ?> required="">
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

    function editDetail($id){
        $detail = $this->db->get_where('detail_template_tunjangan', ['id' => $id])->row();
        $tunjangan = $this->db->select('t.*, rt.kode, rt.satuan')
                            ->from('tunjangan t')
                            ->join('role_tunjangan rt', 't.role_id = rt.id')
                            ->where([
                                't.company_id' => $this->companyid,
                                't.status' => 't'
                            ])->order_by('t.id', "DESC")->get();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Tunjangan</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/template_tunjangan/updateDetail/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>Tunjangan <small class="text-danger">*</small></label>
                                <select name="tunjangan_id" class="form-control" id="exampleFormControlSelect1">
                                    <option value="" selected="" disabled="">- Pilih Tunjangan</option>
                                    <?php foreach($tunjangan->result() as $row){ ?>
                                        <option value="<?= $row->id ?>" <?= ($detail->tunjangan_id == $row->id) ? 'selected' : '' ?>><?= $row->tunjangan." - ".$row->satuan." - ".$row->keterangan ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>Nominal<small class="text-danger">*) Rupiah / Persentase</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Nominal" aria-label="Nominal" name="nominal" value="<?= $detail->nominal ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Tipe<small class="text-danger">*) Rupiah / Persentase</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="inlineRadio1Type" value="N" <?= ($detail->type == 'N') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio1Type">Nominal</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="type" id="inlineRadio2Type" value="P" <?= ($detail->type == 'P') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio2Type">Persentase</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>Status<small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="t" <?= ($detail->status == 't') ? 'checked' : '' ?> required="">
                                        <label class="form-check-label" for="inlineRadio1">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="f" <?= ($detail->status == 'f') ? 'checked' : '' ?> required="">
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
        $template = $this->db->get_where('template_tunjangan', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/template_tunjangan/delete/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h1 class="mb-3 text-danger"><i class="fas fa-exclamation"></i></h1>
                                <h5><strong class="mb-0">Hapus Template Tunjangan <?= $template->nama ?> </strong></h5>
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

    function removeDetail($id){
        $tunjangan = $this->db->select('dt.*, t.tunjangan')
                                ->from('detail_template_tunjangan dt')
                                ->join('tunjangan t', 'dt.tunjangan_id = t.id')
                                ->where('dt.id', $id)->get()->row();
        ?>
            <div class="card card-plain">
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/template_tunjangan/deleteDetail/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h1 class="mb-3 text-danger"><i class="fas fa-exclamation"></i></h1>
                                <h5><strong class="mb-0">Hapus Tunjangan <?= $tunjangan->tunjangan ?> </strong></h5>
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

        $get = $this->db->order_by('id', "DESC")->get_where('template_tunjangan', ['company_id' => $this->companyid]);

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $badge = ($row->status == 't') ? '<span class="badge badge-sm bg-gradient-success">Active</span>' : '<span class="badge badge-sm bg-gradient-danger">Non Active</span>';
            $data[] = [
                $no++,
                '<strong>'.$row->nama.'</strong>',
                '<p class="text-center mb-0">'.$badge.'</p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a href="'.site_url('master/template_tunjangan/' . $row->id).'" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0 mx-1" onclick="edit('.$row->id.')"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</a>
                    <button type="button" class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" onclick="remove('.$row->id.')"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('master/template_tunjangan/edit/').'" + id,
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
                            url : "'.site_url('master/template_tunjangan/remove/').'" + id,
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

    function tableDetail($id){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->select('dt.*, t.tunjangan, t.keterangan, t.type tunjangan_type, rt.kode, rt.satuan')
                        ->from('detail_template_tunjangan dt')
                        ->join('tunjangan t', 'dt.tunjangan_id = t.id')
                        ->join('role_tunjangan rt', 't.role_id = rt.id')
                        ->where('dt.template_id', $id)
                        ->order_by('dt.id', "DESC")->get();

                              
        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $badge = ($row->status == 't') ? '<span class="badge badge-sm bg-gradient-success">Active</span>' : '<span class="badge badge-sm bg-gradient-danger">Non Active</span>';
            $badgeType = ($row->type == 'N') ? '<span class="badge badge-sm bg-gradient-primary">Nominal</span>' : '<span class="badge badge-sm bg-gradient-primary">Presentase</span>';
            $badgeTunjangan = jenisTunjangan($row->tunjangan_type);
            $nominal = ($row->type == 'N') ? rupiah($row->nominal) : $row->nominal."%";
            $data[] = [
                $no++,
                '<strong>'.$row->tunjangan.' - '.$row->keterangan.'</strong>',
                '<strong>'.$row->satuan.'</strong>',
                '<p class="text-end mb-0">'.$nominal.'</p>',
                '<p class="text-end mb-0">'.$badgeType.' '.$badgeTunjangan.'</p>',
                '<p class="text-end mb-0">'.$badge.'</p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <button type="button" class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" onclick="remove('.$row->id.')"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('master/template_tunjangan/editDetail/').'" + id,
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
                            url : "'.site_url('master/template_tunjangan/removeDetail/').'" + id,
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