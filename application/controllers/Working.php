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
            'page' => 'working_hour'
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->get('jam_kerja');

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $data[] = [
                $no++,
                '<strong>'.$row->kode.'</strong>',
                '<strong>'.$row->hari_kerja.'</strong>',
                '<strong>'.$row->jam_in.'</strong>',
                '<strong>'.$row->jam_out.'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0 btn-edit" data-id="3"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="http://localhost/monitoring-ltq/kelas/delete/3"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>'
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