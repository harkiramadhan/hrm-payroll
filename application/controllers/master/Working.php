<?php
class Working extends CI_Controller{
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
            'title' => 'Jam Kerja',
            'company' => $this->M_Company->getDefault(),
            'shift' => $this->db->order_by('id', "ASC")->get('shift'),
            'page' => 'master/working_hour'
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $dataInsert = [
            'shift_id' => $this->input->post('shift_id', TRUE),
            'hari_kerja' => $this->input->post('hari_kerja', TRUE),
            'jam_in' => $this->input->post('jam_in', TRUE),
            'jam_out' => $this->input->post('jam_out', TRUE),
        ];
        $this->db->insert('jam_kerja', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function update($id){
        $dataUpdate = [
            'shift_id' => $this->input->post('shift_id', TRUE),
            'hari_kerja' => $this->input->post('hari_kerja', TRUE),
            'jam_in' => $this->input->post('jam_in', TRUE),
            'jam_out' => $this->input->post('jam_out', TRUE),
        ];
        $this->db->where('id', $id)->update('jam_kerja', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function delete($id){
        $this->db->where('id', $id)->delete('jam_kerja');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $working = $this->db->get_where('jam_kerja', ['id' => $id])->row();
        $shift =  $this->db->order_by('id', "ASC")->get('shift');
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h5 class="font-weight-bolder">Edit Jam Kerja</h5>
                </div>
                <div class="card-body pb-0">
                    <form action="<?= site_url('master/working/update/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Kode / Shift <small class="text-danger">*</small></label>
                                    <select name="shift_id" class="form-control" id="exampleFormControlSelect1" required="">
                                        <option value="" selected="" disabled="">- Pilih Kode / Shift</option>
                                        <?php foreach($shift->result() as $sr){ ?>
                                            <option value="<?= $sr->id ?>" <?= ($working->shift_id == $sr->id) ? 'selected' : '' ?>><?= $sr->kode." - ".$sr->keterangan ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Hari Kerja <small class="text-danger">*</small></label>
                                    <select name="hari_kerja" class="form-control" id="exampleFormControlSelect1" required="">
                                        <option value="" selected="" disabled="">- Pilih Hari Kerja</option>
                                        <option <?= ($working->hari_kerja == "Senin") ? 'selected' : '' ?> value="Senin">Senin</option>
                                        <option <?= ($working->hari_kerja == "Selasa") ? 'selected' : '' ?> value="Selasa">Selasa</option>
                                        <option <?= ($working->hari_kerja == "Rabu") ? 'selected' : '' ?> value="Rabu">Rabu</option>
                                        <option <?= ($working->hari_kerja == "Kamis") ? 'selected' : '' ?> value="Kamis">Kamis</option>
                                        <option <?= ($working->hari_kerja == "Jum'at") ? 'selected' : '' ?> value="Jum'at">Jum</option>
                                        <option <?= ($working->hari_kerja == "Sabtu") ? 'selected' : '' ?> value="Sabtu">Sabtu</option>
                                        <option <?= ($working->hari_kerja == "Minggu") ? 'selected' : '' ?> value="Minggu">Minggu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>Jam (In) <small class="text-danger">*) Format HH:MM</small></label>
                                <div class="input-group mb-3">
                                    <input type="time" class="form-control" placeholder="Jam (In)" aria-label="Jam (In)" name="jam_in" value="<?= $working->jam_in ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>Jam (Out) <small class="text-danger">*) Format HH:MM</small></label>
                                <div class="input-group mb-3">
                                    <input type="time" class="form-control" placeholder="Jam (Out)" aria-label="Jam (Out)" name="jam_out" value="<?= $working->jam_out ?>" required>
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

        $get = $this->db->select('s.kode, s.keterangan, j.*')
                        ->from('jam_kerja j')
                        ->join('shift s', 'j.shift_id = s.id')
                        ->get();

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $data[] = [
                $no++,
                '<p class="text-center mb-0"><strong>'.$row->kode.'</strong></p>',
                '<p class="text-center mb-0"><strong>'.$row->keterangan.'</strong></p>',
                '<strong>'.$row->hari_kerja.'</strong>',
                '<p class="text-center mb-0"><strong>'.$row->jam_in.'</strong></p>',
                '<p class="text-center mb-0"><strong>'.$row->jam_out.'</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('master/working/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('master/working/edit/').'" + id,
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