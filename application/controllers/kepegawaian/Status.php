<?php
class Status extends CI_Controller{
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
            'title' => 'Master Status Kepegawaian',
            'company' => $this->M_Company->getById($this->companyid),
            'page' => 'kepegawaian/status'
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $now = time(); 
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->get_where('pegawai', ['company_id' => $this->companyid]);
        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $detail =  $this->db->select("m.tgl_join, m.tgl_finish, sk.status, sk.warning")
                                ->from('mutasi m')
                                ->join('status_kepegawaian sk', 'm.status_id = sk.id')
                                ->where('pegawai_id', $row->id)->order_by('m.id', "DESC")->get()->row();

            $join = (@$detail->tgl_join) ? longdate_indo(date('Y-m-d', strtotime($detail->tgl_join))) : ' - ';
            $finish = (@$detail->tgl_finish == '0000-00-00' || @$detail->tgl_finish == NULL) ? ' - ' : longdate_indo(date('Y-m-d', strtotime(@$detail->tgl_finish)));
            $status = (@$detail->status) ? $detail->status : ' - ';
            $warningDate = strtotime(@$detail->tgl_finish.'-'.@$detail->warning.' days');
            $datediff = $now - $warningDate;
            $date1 = new DateTime();
            $date2 = new DateTime(@$detail->tgl_finish);
            @$totalan = $date1->diff($date2);

            if(date('Y-m-d') >= date('Y-m-d',strtotime(@$detail->tgl_finish))){
                $total = @$totalan->days + 1;
                $warningStatus = '<span class="badge badge-sm bg-danger">Lewat '.@$total.' Hari</span>';
            }else{
                if(round($datediff / (60 * 60 * 24)) >= -@$detail->warning){
                    $warningStatus = '<span class="badge badge-sm bg-warning">Sisa '.@$totalan->days.' Hari</span>';
                }else{
                    $warningStatus = '<span class="badge badge-sm bg-success">Active</span>';
                }
            }

            $data[] = [
                $no++,
                '<strong>'.$row->nama.'</strong>',
                '<p class="mb-0"><strong>'.$status.'</strong></p>',
                (@$detail->tgl_finish == '0000-00-00' || @$detail->tgl_finish == NULL) ? '-' : @$warningStatus,
                '<p class="text-left mb-0"><strong>'.$join.'</strong></p>',
                '<p class="text-left mb-0"><strong>'.$finish.'</strong></p>',
                '<p class="text-left mb-0"><strong>'.masaKerja(@$detail->tgl_join, @$detail->tgl_finish).'</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-sm btn-round btn-info text-white px-3 mb-0" href="'.site_url('kepegawaian/employee/' . $row->id).'#sec-detail-kepegawaian"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</a>
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