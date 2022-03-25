<?php
class Cutoff extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company'
        ]);
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Cutoff',
            'company' => $this->M_Company->getDefault(),
            'page' => 'cutoff'
        ];
        $this->load->view('templates', $var);
    }
}