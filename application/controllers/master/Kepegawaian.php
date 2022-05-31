<?php
class Kepegawaian extends CI_Controller{
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
            'title' => 'Master Status Kepegawaian',
            'company' => $this->M_Company->getDefault(),
            'page' => 'master/kepegawaian'
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->get('pegawai');
        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $detail =  $this->db->select("m.tgl_join, m.tgl_finish, sk.status")
                                ->from('mutasi m')
                                ->join('status_kepegawaian sk', 'm.status_id = sk.id')
                                ->where('pegawai_id', $row->id)->order_by('timestamp', "DESC")->get()->row();

            $join = (@$detail->tgl_join) ? longdate_indo(date('Y-m-d', strtotime($detail->tgl_join))) : ' - ';
            $finish = (@$detail->tgl_finish) ? longdate_indo(date('Y-m-d', strtotime($detail->tgl_finish))) : ' - ';
            $status = (@$detail->status) ? $detail->status : ' - ';
            $data[] = [
                $no++,
                '<strong>'.$row->nama.'</strong>',
                '<p class="text-center mb-0"><strong>'.$status.'</strong></p>',
                '<p class="text-center mb-0"><strong>'.$join.'</strong></p>',
                '<p class="text-center mb-0"><strong>'.$finish.'</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-sm btn-round btn-info text-white px-3 mb-0" href="'.site_url('master/employee/' . $row->id).'#sec-detail-kepegawaian"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</a>
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