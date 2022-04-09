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

        $logAbsensi = $this->db->select('p.nama, la.*')
                            ->from('log_upload_absensi la')
                            ->join('pegawai p', 'la.pegawai_id = p.id')
                            ->get();
        $data = array();
        $no = 1;

        foreach($logAbsensi->result_array() as $row){
            $data[] =[
                $no++,
                '<p class="mb-0";><strong>'.$row['nama'].'</strong></p>',
                '<p class="text-center mb-0";><strong>'.$row['total_row'].'</strong></p>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row['timestamp']))).' - '.date('H:i:s', strtotime($row['timestamp'])).'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-secondary text-white px-3 mb-0 mx-1" onclick="add('.$row['id'].')"><i class="fas fa-download me-2" aria-hidden="true"></i>Download</button>
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
            "recordsTotal"      => $logAbsensi->num_rows(),
            "recordsFiltered"   => $logAbsensi->num_rows(),
            "data"              => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }
}