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
            'cutoff' => $this->M_Cutoff->getActive($this->companyid),
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
        $cutoff = $this->M_Cutoff->getActive($this->companyid);

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
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8">
                        <h5 class="mb-0"><strong>Detail Summary</strong></h5>
                    </div>
                    <div class="col-lg-4 text-end">
                        <button type="button" class="btn btn-sm btn-round bg-gradient-dark mb-0 btn-save"><i class="fas fa-save me-2"></i> Simpan Ke Cutoff</button>
                    </div>
                </div>
            </div>
            <form action="<?= site_url('trx/summary/save') ?>" method="POST" id="form-summary">
            <div class="table-responsive" style="max-height: 500px!important">
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
                            <th class="text-center">Jum. Hari Lembur</th>
                            <th class="text-center">Lembur (Menit)</th>
                            <th class="">Shift</th>
                        </tr>
                    </thead>
                    <input type="hidden" name="cabangid" value="<?= $cabangid ?>">
                    <input type="hidden" name="startDate" value="<?= $startDate ?>">
                    <input type="hidden" name="endDate" value="<?= $endDate ?>">
                    <input type="hidden" name="cutoffid" value="<?= $cutoff->id ?>">
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
                                    $hariLembur = [];

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

                                    $cekLembur =$this->db->select('*')
                                                        ->from('absensi')
                                                        ->where([
                                                            'nik' => $row->nik,
                                                            'DATE(`jam_in`) >=' => date('Y-m-d', strtotime($startDate)),
                                                            'DATE(`jam_in`) <=' => date('Y-m-d', strtotime($endDate)),
                                                            'lembur >' => 0
                                                        ])->group_by('DATE(`jam_in`)')->get();
                                    foreach($cekLembur->result() as $cl){
                                        array_push($lembur, $cl->lembur);
                                        array_push($hariLembur, 1);
                                    }
                                }
                        ?>
                        <input type="hidden" name="nip[]" value="<?= $nik ?>">
                        <input type="hidden" name="hari_efektif[]" value="<?= $hariEfektifPegawai ?>">
                        <input type="hidden" name="total_hadir[]" value="<?= array_sum(@$absensi) ?>">
                        <input type="hidden" name="sakit[]" value="<?= array_sum(@$sakit) ?>">
                        <input type="hidden" name="alpa[]" value="<?= array_sum(@$alpa) ?>">
                        <input type="hidden" name="izin[]" value="<?= array_sum(@$izin) ?>">
                        <input type="hidden" name="hari_terlambat[]" value="<?= array_sum(@$terlambat) ?>">
                        <input type="hidden" name="menit_terlambat[]" value="<?= array_sum(@$detailTerlambat) ?>">
                        <input type="hidden" name="hari_lembur[]" value="<?= array_sum(@$hariLembur) ?>">
                        <input type="hidden" name="menit_lembur[]" value="<?= array_sum(@$lembur) ?>">
                        <input type="hidden" name="shift[]" value="<?= $shiftName ?>">
                        <tr>
                            <td class="text-center" width="5px"><?= $no++ ?></td>
                            <td><strong><?= $nik ?></strong></td>
                            <td><strong><?= $row->nama ?></strong></td>
                            <td class="text-left"><strong><?= @$hariEfektifPegawai ?></strong></td>
                            <td class="text-left"><strong><?= array_sum(@$absensi)." Hari" ?></strong></td>
                            <td class="text-center"><strong><?= array_sum(@$sakit) ?></strong></td>
                            <td class="text-center"><strong><?= array_sum(@$izin) ?></strong></td>
                            <td class="text-center"><strong><?= array_sum(@$alpa) ?></strong></td>
                            <td class="text-center"><strong><?= array_sum(@$terlambat)." Hari" ?></strong></td>
                            <td class="text-center"><strong><?= array_sum(@$detailTerlambat) ?> Menit</strong></td>
                            <td class="text-center"><strong><?= array_sum(@$hariLembur) ?> Hari</strong></td>
                            <td class="text-center"><strong><?= array_sum(@$lembur) ?> Menit</strong></td>
                            <td><strong><?= $shiftName ?></strong></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="d-none" id="btn-submit">Sb</button>
            </form>
            <script>
                $('.btn-save').click(function(){
                    $("#btn-submit").click()
                })
            </script>
        <?php
    }

    function save(){
        $nip = $this->input->post('nip[]', TRUE);
        $hari_efektif = $this->input->post('hari_efektif[]', TRUE);
        $total_hadir = $this->input->post('total_hadir[]', TRUE);
        $sakit = $this->input->post('sakit[]', TRUE);
        $alpa = $this->input->post('alpa[]', TRUE);
        $izin = $this->input->post('izin[]', TRUE);
        $hari_terlambat = $this->input->post('hari_terlambat[]', TRUE);
        $menit_terlambat = $this->input->post('menit_terlambat[]', TRUE);
        $shift = $this->input->post('shift[]', TRUE);
        $hari_lembur = $this->input->post('hari_lembur[]', TRUE);
        $menit_lembur = $this->input->post('menit_lembur[]', TRUE);
        $cutoffid = $this->input->post('cutoffid', TRUE);
        
        $dataInsert = [];
        $dataUpdate = [];

        foreach($nip as $key => $val){
            $cabangid = $this->db->get_where('pegawai', ['nik' => $val])->row()->cabang_id;
            $datas = [
                'cutoff_id' => $cutoffid,
                'cabang_id' => $cabangid,
                'start_date' => $this->input->post('startDate', TRUE),
                'end_date' => $this->input->post('endDate', TRUE),
                'nip' => $val,
                'hari_efektif' => $hari_efektif[$key],
                'total_hadir' => $total_hadir[$key],
                'sakit' => $sakit[$key],
                'alpa' => $alpa[$key],
                'izin' => $izin[$key],
                'hari_terlambat' => $hari_terlambat[$key],
                'menit_terlambat' => $menit_terlambat[$key],
                'hari_lembur' => $hari_lembur[$key],
                'menit_lembur' => $menit_lembur[$key],
                'shift' => $shift[$key],
            ];
            $cek = $this->db->get_where('summary', ['nip' => $val, 'cutoff_id' => $cutoffid]);
            if($cek->num_rows() > 0){
                $this->db->where('id', $cek->row()->id)->update('summary', $datas);
                array_push($dataUpdate, 1);
            }else{
                $this->db->insert('summary', $datas);
                array_push($dataInsert, 1);
            }
        }
        
        if(array_sum($dataInsert) > 0 || array_sum($dataUpdate) > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }
}