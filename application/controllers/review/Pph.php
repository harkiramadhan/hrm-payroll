<?php
class Pph extends CI_Controller{
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
            'title' => 'Review PPH 21',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $this->M_Cutoff->getActive($this->companyid),
            'cabang' => $this->db->get_where('cabang', ['status' => 't']),
            'divisi' => $this->db->get_where('divisi'),
            'pkp' => $this->db->get('persentase_pkp'),
            'page' => 'review/pph21',
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $cutoff = $this->db->get_where('cutoff', [
            'is_active' => 't',
            'company_id' => $this->companyid,
        ])->row();

        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->select('p.nama, p.nik, j.jabatan, d.divisi, dp.departement, u.unit, cb.cabang, p.id, s.thp, pt.nominal tarif_ptkp, pt.text text_ptkp')
                        ->from('pegawai p')
                        ->join('cabang cb', 'p.cabang_id = cb.id', "LEFT")
                        ->join('jabatan j', 'p.jabatan_id = j.id', "LEFT")
                        ->join('divisi d', 'p.divisi_id = d.id', "LEFT")
                        ->join('departement dp', 'p.dept_id = dp.id', "LEFT")
                        ->join('unit u', 'p.unit_id = u.id', "LEFT")
                        ->join('summary s', 'p.nik = s.nip')
                        ->join('ptkp pt', 'p.nikah = pt.pernikahan_id AND p.jumlan_tanggungan = pt.tanggungan')
                        ->where('s.cutoff_id', $cutoff->id)
                        ->where('s.lock', 't')
                        ->where('p.company_id', $this->companyid)->order_by('nik', "ASC")->get();

        $data = array();
        $no = 1;
        $persetasePkp = $this->db->get('persentase_pkp');
        foreach($get->result() as $row){
            $thp = $row->thp;
            $jbt = ($row->thp * 5) / 100;
            $potJabatan = ($jbt > 500000) ? 500000 : $jbt;
            $netoSebulan = $thp - $potJabatan; 
            $netoSetahun = $netoSebulan * 12;
            $ptkp = $row->tarif_ptkp;
            $pkpSetahun = $netoSetahun - $ptkp;
            
            $fivePercent = 0;
            $fifteenPercent = 0;
            $fivePercent = 0;
            $twentyFivePercent = 0;
            $fiftyPercent = 0;

            if($pkpSetahun < 60000000){
                $fivePercent = ($pkpSetahun * 5) / 100;
            }elseif($pkpSetahun < 250000000){
                $fivePercent = (60000000 * 5) / 100;
                $pengurangan = $pkpSetahun - 60000000;
                $fifteenPercent = ($pengurangan * 15) / 100;
            }elseif($pkpSetahun < 500000000){
                $fivePercent = ($pkpSetahun - 60000000 * 5) / 100;
                $fifteenPercent = ($pkpSetahun - 250000000 * 15) / 100;
                $pengurangan = $pkpSetahun - 250000000;
                $twentyFivePercent = ($pengurangan * 25) / 100;
            }elseif($pkpSetahun > 500000000){
                $fivePercent = (60000000 * 5) / 100;
                $fifteenPercent = (250000000 * 15) / 100;
                $pengurangan = $pkpSetahun - 500000000;
                $fiftyPercent = ($pengurangan * 30) / 100;
            }

            $totalPkpSetahun = $fivePercent + $fifteenPercent + $twentyFivePercent + $fifteenPercent;
            $totalPkpSebulan = ($totalPkpSetahun * 12) / 100;
            $thpAkhir = $thp + $totalPkpSebulan;

            $data[] = [
                $no++,
                '<p class="mb-0"><strong>'.$row->nik.'</strong></p>',
                '<strong>'.$row->nama.'</strong>',
                '<strong>'.@$row->cabang.'</strong>',
                '<p class="mb-0"><strong>'.$row->divisi.' / '.$row->jabatan.'</strong></p>',
                '<strong>'.$row->departement.' / '.$row->unit.'</strong>',
                '<strong>'.$row->text_ptkp.'</strong>',
                '<strong>'.rupiah($thp).'</strong>',
                '<strong>'.rupiah($potJabatan).'</strong>',
                '<strong>'.rupiah($netoSebulan).'</strong>',
                '<strong>'.rupiah($netoSetahun).'</strong>',
                '<strong>'.rupiah($ptkp).'</strong>',
                '<strong>'.rupiah($pkpSetahun).'</strong>',
                '<strong>'.rupiah($fivePercent).'</strong>',
                '<strong>'.rupiah($fifteenPercent).'</strong>',
                '<strong>'.rupiah($twentyFivePercent).'</strong>',
                '<strong>'.rupiah($fiftyPercent).'</strong>',
                '<strong>'.rupiah($totalPkpSetahun).'</strong>',
                '<strong>'.rupiah($totalPkpSebulan).'</strong>',
                '<strong>'.rupiah($thpAkhir).'</strong>',
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