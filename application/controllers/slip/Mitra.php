<?php
class Mitra extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->model([
            'M_Company',
            'M_Cutoff'
        ]);
        $this->companyid = $this->session->userdata('company_id');
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Slip Gaji Mitra',
            'company' => $this->M_Company->getById($this->companyid),
            'cutoff' => $this->M_Cutoff->getActive($this->companyid),
            'page' => 'slip/mitra'
        ];
        $this->load->view('templates', $var);
    }

    function detail($id, $cutoffid){
        $cabang = $this->db->get_where('cabang', ['id' => $id])->row();
        $var = [
            'cabang' => $cabang,
            'title' => 'Slip Gaji Mitra - ' . $cabang->cabang,
            'company' => $this->M_Company->getById($this->companyid),
            'cutoff' => $this->db->get_where('cutoff', ['id' => $cutoffid])->row(),
            'page' => 'slip/mitra_detail'
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $cabang = $this->db->get_where('cabang', ['company_id' => $this->companyid, 'status' => 't']);
        $cutoffid = $this->M_Cutoff->getActive($this->companyid)->id;

        $data = array();
        $no = 1;

        foreach($cabang->result_array() as $row){
            $data[] = [
                $no++,
                '<p class="mb-0";><strong>'.$row['cabang'].'</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a href="'.site_url('slip/mitra/' . $row['id']).'/'.$cutoffid.'" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</a>
                </div>'
            ];
        }

        $output = [
            "draw"              => $draw,
            "recordsTotal"      => $cabang->num_rows(),
            "recordsFiltered"   => $cabang->num_rows(),
            "data"              => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    function detailTable($cabangid, $cutoffid){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $getData = $this->db->select('s.*, p.nama, p.nik')
                            ->from('summary_mitra s')
                            ->join('pegawai p', 's.pegawai_id = p.id')
                            ->where([
                                's.lock' => 't',
                                's.cutoff_id' => $cutoffid,
                                's.cabang_id' => $cabangid
                            ])->order_by('p.nama', 'ASC')->get();

        $data = array();
        $no = 1;

        foreach($getData->result_array() as $row){
            $data[] = [
                $no++,
                '<p class="mb-0";><strong>'.$row['nik'].'</strong></p>',
                '<p class="mb-0";><strong>'.$row['nama'].'</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a href="'.site_url('slip/mitra/' . $row['id']).'/'.$cutoffid.'/pdf" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0" target="__BLANK"><i class="fas fa-print me-3" aria-hidden="true"></i>Download Slip</a>
                </div>'
            ];
        }

        $output = [
            "draw"              => $draw,
            "recordsTotal"      => $getData->num_rows(),
            "recordsFiltered"   => $getData->num_rows(),
            "data"              => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    function pdf($summaryid, $cutoffid){
        ob_clean();
        $cutofff = $this->db->get_where('cutoff', ['id' => $cutoffid])->row();
        $summary = $this->db->select('s.*, p.nama, p.nik, p.no_rekening, j.jabatan, d.departement, p.id pegawai_id')
                            ->from('summary_mitra s')
                            ->join('pegawai p', 's.pegawai_id = p.id')
                            ->join('jabatan j', 'p.jabatan_id = j.id')
                            ->join('departement d', 'p.dept_id = d.id')
                            ->where([
                                's.id' => $summaryid,
                                's.cutoff_id' => $cutoffid
                            ])->get()->row();

        $tunjangan = $this->db->select('st.nominal, st.jumlah, t.*')
                            ->from('summary_mitra_detail st')
                            ->join('tunjangan t', 'st.tunjangan_id = t.id')
                            ->where([
                                'st.pegawai_id' => $summary->pegawai_id,
                                'st.log_id' => $summary->log_id,
                                'st.nominal !=' => 0,                   
                                't.type !=' => 3
                            ])->order_by('t.urut', "ASC")->get();

        $tunjanganPotongan = $this->db->select('st.nominal, st.jumlah, t.*')
                            ->from('summary_mitra_detail st')
                            ->join('tunjangan t', 'st.tunjangan_id = t.id')
                            ->where([
                                'st.pegawai_id' => $summary->pegawai_id,
                                'st.log_id' => $summary->log_id,
                                'st.nominal !=' => 0,                   
                                't.type' => 3
                            ])->order_by('t.urut', "ASC")->get();

        $kehadiran = $this->db->get_where('summary_mitra_detail', [
            'tunjangan_id' => 47,
            'pegawai_id' => $summary->pegawai_id,
            'log_id' => $summary->log_id,
        ]);

        $periode = bulan($cutofff->bulan)." ".$cutofff->tahun;
        $filename = "Slip Mitra - " . $summary->nama . ' - Periode ' . $periode;
        $var = [
            'cutoff' => $cutofff,
            'summary' => $summary,
            'tunjangan' => $tunjangan,
            'tunjanganPotongan' => $tunjanganPotongan,
            'kehadiran' => $kehadiran,
            'title' => $filename
        ];
        // $this->load->view('pages/slip/mitra_pdf', $var);
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
        $html = $this->load->view('pages/slip/mitra_pdf', $var, true);
        $mpdf->WriteHTML($html);
        $mpdf->Output($filename.".pdf", "I");
        ob_end_flush();
    }
}