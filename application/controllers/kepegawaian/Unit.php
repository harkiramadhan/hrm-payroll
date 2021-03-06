<?php
class Unit extends CI_Controller{
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
            'title' => 'Master Unit',
            'company' => $this->M_Company->getById($this->companyid),
            'divisi' => $this->db->order_by('divisi', "ASC")->get_where('divisi', ['company_id' => $this->companyid]),
            'page' => 'kepegawaian/unit',
            'ajax' => [
                'unit'
            ]
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $dataInsert = [
            'company_id' => $this->companyid,
            'divisi_id' => $this->input->post('divisi_id', TRUE),
            'dept_id' => $this->input->post('dept_id', TRUE),
            'unit' => $this->input->post('unit', TRUE)
        ];
        $this->db->insert('unit', $dataInsert);
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
            'dept_id' => $this->input->post('dept_id', TRUE),
            'unit' => $this->input->post('unit', TRUE)
        ];
        $this->db->where('id', $id)->update('unit', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('unit');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $unit = $this->db->select('d.departement, dv.divisi, u.*')
                        ->from('unit u')
                        ->join('departement d', 'u.dept_id = d.id')
                        ->join('divisi dv', 'd.divisi_id = dv.id')
                        ->where(['u.id' => $id])
                        ->get()->row();
        
        $divisi = $this->db->order_by('divisi', "ASC")->get_where('divisi', ['company_id' => $this->companyid]);
        $departement = $this->db->get_where('departement', ['divisi_id' => $unit->divisi_id, 'company_id' => $this->companyid]);
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Unit</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('kepegawaian/unit/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="select-div2">Divisi <small class="text-danger">*</small></label>
                                    <select name="divisi_id" class="form-control" id="select-div2" required="">
                                        <option value="" selected="" disabled="">- Pilih Divisi</option>
                                        <?php foreach($divisi->result() as $row){ ?>
                                            <option value="<?= $row->id ?>" <?= ($row->id == $unit->divisi_id)  ? 'selected' : '' ?>><?= $row->divisi ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="select-dept2">Departement <small class="text-danger">*</small></label>
                                    <select name="dept_id" class="form-control" id="select-dept2" required="">
                                        <?php foreach($departement->result() as $d){ ?>
                                            <option value="<?= $d->id ?>" <?= ($unit->dept_id == $d->id) ? 'selected' : '' ?> ><?= $d->departement ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>Unit <small class="text-danger">*</small></label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Unit" aria-label="Unit" name="unit" value="<?= $unit->unit ?>" required>
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

            <script>
                $('#select-div2').change(function(){
                    var id = $(this).val()
                    $.ajax({
                        url: siteUrl + 'kepegawaian/unit/get_dept',
                        type: 'get',
                        data: {id : id},
                        success: function(res){
                            $('#select-dept2')
                                .prop("disabled", false)
                                .find("option")
                                .remove()
                                .end()
                                .append("<option value='' selected disabled>- Pilih Departement </option>")
                            for (var i = 0; i < res.length; i++){
                                var dept = res[i].departement
                                var dept_id = res[i].id
                                $('#select-dept2').append("<option value='" + dept_id + "'>" + dept + "</option>")
                            }
                        }
                    })
                })
            </script>
        <?php
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->select('d.departement, dv.divisi, u.*')
                        ->from('unit u')
                        ->join('departement d', 'u.dept_id = d.id')
                        ->join('divisi dv', 'd.divisi_id = dv.id')
                        ->where('u.company_id', $this->companyid)->get();

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $data[] = [
                $no++,
                '<strong>'.$row->divisi.'</strong>',
                '<strong>'.$row->departement.'</strong>',
                '<strong>'.$row->unit.'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('kepegawaian/unit/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('kepegawaian/unit/edit/').'" + id,
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

    function get_dept(){
        $divisi_id = $this->input->get('id', TRUE);
        $getDepartement = $this->db->get_where('departement', ['divisi_id' => $divisi_id, 'company_id' => $this->companyid])->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($getDepartement));
    }
}