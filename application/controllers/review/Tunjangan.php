<?php
class Tunjangan extends CI_Controller{
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
            'title' => 'Review Tunjangan',
            'company' => $this->M_Company->getDefault(),
            'tunjangan' => $this->db->order_by('urut', "ASC")->get('tunjangan'),
            'page' => 'review/tunjangan'
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

        $tunjangan = $this->db->order_by('urut', "ASC")->get('tunjangan');
        $data = array();
        $no = 1;

        foreach($pegawai->result_array() as $key => $row){
            $data[] =[
                $no++,
                $row['nama'],
                $row['jabatan'],
                '00'
            ];

            /* Loop Tunjangan && Match IT*/
            foreach($tunjangan->result_array() as $t){
                array_push($data[$key], $t['tunjangan']);
            }

            /* Total Take Home Pay */
            $takeHomePay = "000000" . $row['id'];
            array_push($data[$key], $takeHomePay);
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