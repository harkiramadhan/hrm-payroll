<?php 
class Summary extends CI_Controller{
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
            'title' => 'Transaksi Summary',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $this->M_Cutoff->getActive(),
            'page' => 'trx/summary',
            'ajax' => [
                'summary'
            ]
        ];
        $this->load->view('templates', $var);
    }

    function searchTable(){
        $startDate = $this->input->get('startDate', TRUE);
        $endDate = $this->input->get('endDate', TRUE);
        $cabangid =$this->input->get('cabangid', TRUE);

        if(is_numeric($cabangid)){$this->db->where('cabang_id', $cabangid);}
        $pegawai = $this->db->select('*')
                            ->from('pegawai')
                            ->where([
                                'company_id' => $this->companyid,
                            ])->order_by('nama', "ASC")->get();
        

        $shift = $this->db->get_where('shift', ['company_id' => $this->companyid, 'status' => 't'])->result();
        $datas = [];
        foreach($shift as $s){
            $begin = new DateTime($startDate);
            $end   = new DateTime($endDate);
            $hari = [];
            $hariEfektif = [];
            
            $jam_kerja = $this->db->get_where('jam_kerja', ['company_id' => $this->companyid, 'status' => 't', 'shift_id' => $s->id])->result();
            foreach($jam_kerja as $j){
                array_push($hari, $j->hari_kerja);
            }

            for($i = $begin; $i <= $end; $i->modify('+1 day')){
                if(in_array($i->format('l'), $hari)){
                    $detailHariEfektif= date('Y-m-d', strtotime($i->format('Y-m-d')));
                    array_push($hariEfektif, $detailHariEfektif);
                }
            }

            $datas[$s->id] = [
                'shift' => $s->keterangan,
                'kode' => $s->kode,
                'hari' => $hari,
                'total_hari_efektif' => count($hariEfektif),
                'detail_hari_efektif' => $hariEfektif
            ];
        }

        ?>
            <table id="example" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center w-5px">No</th>
                        <th class="">NIP</th>
                        <th>Nama</th>
                        <th class="text-left">Hari Efektif</th>
                        <th class="text-left">Hadir</th>
                        <th class="text-center">S</th>
                        <th class="text-center">I</th>
                        <th class="text-center">A</th>
                        <th class="text-center">Jum. Hari Terlambat</th>
                        <th class="text-center">Terlambat (Menit)</th>
                        <th class="text-center">Lembur (Menit)</th>
                        <th class="">Shift</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1; 
                        foreach($pegawai->result() as $row){ 
                            $getLatestShiftEmployee = $this->db->select('shift_id')->order_by('jam_in', "DESC")->limit(1)->get_where('absensi', ['nik' => $row->nik]);
                            $nik = ($row->kode_cabang != NULL) ? $row->nik : '-';
                            $shift = $this->db->get_where('shift', ['id' => @$getLatestShiftEmployee->row()->shift_id])->row();

                            $hariEfektifPegawai = 0;
                            if($getLatestShiftEmployee->num_rows() > 0){
                                $shiftid = $getLatestShiftEmployee->row()->shift_id;
                                $shiftName = $shift->keterangan;
                                $hariEfektifPegawai = $datas[$shiftid]['total_hari_efektif']." Hari";

                                $absensi = [];
                                $detailAbsensi = [];
                                $terlambat = [];
                                $detailTerlambat = [];
                                $sakit = [];
                                $detailSakit = [];
                                $izin = [];
                                $detailIzin = [];
                                $alpa = [];
                                $detailAlpa = [];
                                $lembur = [];

                                foreach($datas[$shiftid]['detail_hari_efektif'] as $he){
                                    $cekAbsensi = $this->db->get_where('absensi', [
                                                                'nik' => $row->nik,
                                                                'DATE(`jam_in`)' => date('Y-m-d', strtotime($he)),
                                                                'keterangan' => ''
                                                            ]);
                                    if($cekAbsensi->num_rows() > 0){
                                        array_push($absensi, 1);
                                        array_push($detailAbsensi, $cekAbsensi->result());
                                    }

                                    $cekTerlambat = $this->db->get_where('absensi', [
                                                                'nik' => $row->nik,
                                                                'DATE(`jam_in`)' => date('Y-m-d', strtotime($he)),
                                                                'keterangan' => '',
                                                                'late >' => 0
                                                            ]);
                                    if($cekTerlambat->num_rows() > 0){
                                        array_push($terlambat, 1);
                                        array_push($detailTerlambat, $cekTerlambat->row()->late);
                                    }

                                    $cekSakit = $this->db->get_where('absensi', [
                                                                'nik' => $row->nik,
                                                                'DATE(`jam_in`)' => date('Y-m-d', strtotime($he)),
                                                                'keterangan' => 'S'
                                                            ]);

                                    if($cekSakit->num_rows() > 0){
                                        array_push($sakit, 1);
                                        array_push($detailSakit, $cekSakit->row()->keterangan);
                                    }

                                    $cekIzin = $this->db->get_where('absensi', [
                                                                'nik' => $row->nik,
                                                                'DATE(`jam_in`)' => date('Y-m-d', strtotime($he)),
                                                                'keterangan' => 'I'
                                                            ]);

                                    if($cekIzin->num_rows() > 0){
                                        array_push($izin, 1);
                                        array_push($detailIzin, $cekIzin->row()->keterangan);
                                    }

                                    $cekAlpa = $this->db->get_where('absensi', [
                                                                'nik' => $row->nik,
                                                                'DATE(`jam_in`)' => date('Y-m-d', strtotime($he)),
                                                                'keterangan' => 'A'
                                                            ]);

                                    if($cekAlpa->num_rows() > 0){
                                        array_push($alpa, 1);
                                        array_push($detailAlpa, $cekAlpa->row()->keterangan);
                                    }
                                }

                                $cekLembur =$this->db->get_where('absensi', [
                                                                    'nik' => $row->nik,
                                                                    'DATE(`jam_in`) >=' => date('Y-m-d', strtotime($startDate)),
                                                                    'DATE(`jam_in`) <=' => date('Y-m-d', strtotime($endDate)),
                                                                    'lembur >' => 0
                                                                ]);
                                foreach($cekLembur->result() as $cl){
                                    array_push($lembur, $cl->lembur);
                                }
                            }
                    ?>
                    <tr>
                        <td class="text-center" width="5px"><?= $no++ ?></td>
                        <td><strong><?= $nik ?></strong></td>
                        <td><strong><?= $row->nama ?></strong></td>
                        <td class="text-left"><strong><?= $hariEfektifPegawai ?></strong></td>
                        <td class="text-left"><strong><?= array_sum($absensi)." Hari" ?></strong></td>
                        <td class="text-center"><strong><?= array_sum($sakit) ?></strong></td>
                        <td class="text-center"><strong><?= array_sum($izin) ?></strong></td>
                        <td class="text-center"><strong><?= array_sum($alpa) ?></strong></td>
                        <td class="text-center"><strong><?= array_sum($terlambat)." Hari" ?></strong></td>
                        <td class="text-center"><strong><?= array_sum($detailTerlambat) ?> Menit</strong></td>
                        <td class="text-center"><strong><?= array_sum($lembur) ?> Menit</strong></td>
                        <td><strong><?= $shiftName ?></strong></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php
    }
}