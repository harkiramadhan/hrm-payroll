<?php
class Absensi extends CI_Controller{
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
            'title' => 'Transaksi Absensi',
            'company' => $this->M_Company->getDefault(),
            'page' => 'trx/absensi'
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $pegawai = $this->db->select('p.*, j.jabatan')
                            ->from('pegawai p')
                            ->join('jabatan j', 'p.jabatan_id = j.id', "LEFT")
                            ->get();
        $data = array();
        $no = 1;

        foreach($pegawai->result_array() as $key => $row){
            $data[] =[
                $no++,
                '<p class="mb-0";><strong>'.$row['nama'].'</strong></p>',
                '<p class="mb-0";><strong>'.$row['jabatan'].'</strong></p>',
                '<p class="text-center mb-0";><strong>00</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-success text-white px-3 mb-0 mx-1" onclick="add('.$row['id'].')"><i class="fas fa-plus me-2" aria-hidden="true"></i>Tambah</button>
                    <button type="button" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0" onclick="detail('.$row['id'].')"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</button>
                </div>
                <script>
                    function detail(id){
                        $.ajax({
                            url : "'.site_url('trx/tunjangan/modalDetail/').'" + id,
                            type : "post",
                            data : {id : id},
                            success: function(res){
                                $(".data-edit").html(res)
                                $("#modalEdit").modal("show")
                            }
                        })
                    }

                    function add(id){
                        $.ajax({
                            url : "'.site_url('trx/tunjangan/modalAdd/').'" + id,
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
            "recordsTotal"      => $pegawai->num_rows(),
            "recordsFiltered"   => $pegawai->num_rows(),
            "data"              => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
}