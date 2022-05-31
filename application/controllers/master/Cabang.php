<?php
class Cabang extends CI_Controller{
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
            'title' => 'Master Cabang',
            'company' => $this->M_Company->getDefault(),
            'shift' => $this->db->order_by('id', "ASC")->get('shift'),
            'page' => 'master/cabang'
        ];
        $this->load->view('templates', $var);
    }
}