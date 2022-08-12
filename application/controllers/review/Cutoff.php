<?php
class Cutoff extends CI_Controller{
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
            'title' => 'Review Cutoff',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $this->M_Cutoff->getActive($this->companyid),
            'cabang' => $this->db->get_where('cabang', ['status' => 't']),
            'divisi' => $this->db->get_where('divisi'),
            'page' => 'review/cutoff',
        ];
        $this->load->view('templates', $var);
    }

    function detail($id){
        $get = $this->db->select('rc.*, c.cabang, d.divisi')
                        ->from('review_cutoff rc')
                        ->join('cabang c', 'rc.cabang_id = c.id')
                        ->join('divisi d', 'rc.divisi_id = d.id')
                        ->where([
                            'rc.company_id' => $this->companyid,
                            'rc.id' => $id
                        ])->order_by('rc.id', "DESC")->get();

        $var = [
            'title' => 'Detail Cutoff',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $get->row(),
            'page' => 'review/cutoff_detail',
            'ajax' => [
                'cutoff'
            ]
        ];
        $this->load->view('templates', $var);
    }
    
    function create(){
        $cutoffid = $this->db->get_where('cutoff', ['is_active' => 't'])->row()->id;
        $datas = [
            'company_id' => $this->companyid,
            'cutoff_id' => $cutoffid,
            'cabang_id' => $this->input->post('cabang_id', TRUE),
            'divisi_id' => $this->input->post('divisi_id', TRUE)
        ];
        $this->db->insert('review_cutoff', $datas);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function summaryLock(){
        $ids = $this->input->post('id[]', TRUE);
        if($ids > 0){
            $this->output->set_content_type('application/json')->set_output(json_encode($this->input->post()));
            foreach($ids as $id){
                $this->db->where('id', $id)->update('summary', [
                    'review_cutoff_id' => $this->input->post('review_cutoff_id', TRUE),
                    'lock' => 't'
                ]);
            }

            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Tidak Ada Perubahan!");
        }
    }

    function update($id){
        $datas = [
            'hari_efektif' => $this->input->post('hari_efektif', TRUE),
            'total_hadir' => $this->input->post('total_hadir', TRUE),
            'izin' => $this->input->post('izin', TRUE),
            'sakit' => $this->input->post('sakit', TRUE),
            'alpa' => $this->input->post('alpa', TRUE),
            'hari_terlambat' => $this->input->post('hari_terlambat', TRUE),
            'menit_terlambat' => $this->input->post('menit_terlambat', TRUE),
            'hari_lembur' => $this->input->post('hari_lembur', TRUE),
            'menit_lembur' => $this->input->post('menit_lembur', TRUE),
            'shift' => $this->input->post('shift', TRUE)
        ];
        $this->db->where('id', $id)->update('summary', $datas);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function updateTunjagan(){
        $nip = $this->input->post('nip', TRUE);
        $cutoffid = $this->input->post('cutoff_id', TRUE);
        $review_cutoff_id = $this->input->post('review_cutoff_id', TRUE);
        $pegawai_id = $this->input->post('pegawai_id', TRUE);
        $tunjanganid = $this->input->post('tunjangan_id[]', TRUE);

        if($tunjanganid > 0){
            foreach($tunjanganid as $key => $val){
                $cekTunjangan = $this->db->get_where('summary_tunjangan', ['pegawai_id' => $pegawai_id, 'review_cutoff_id' => $review_cutoff_id, 'tunjangan_id' => $key]);
                if($cekTunjangan->num_rows() > 0){
                    $this->db->where('id', $cekTunjangan->row()->id)->update('summary_tunjangan', ['nominal' => $val]);
                }else{
                    $this->db->insert('summary_tunjangan', [
                        'pegawai_id' => $pegawai_id,
                        'nip' => $nip,
                        'review_cutoff_id' => $review_cutoff_id,
                        'tunjangan_id' => $key,
                        'nominal' => str_replace('.', "", $val)
                    ]);
                }

                if($this->db->affected_rows() > 0){
                    $this->db->where([
                        'cutoff_id' => $cutoffid,
                        'nip' => $nip,
                    ])->update('summary', [
                        'nominal_insentif' => str_replace('.', "", $this->input->post('nominal_insentif', TRUE)),
                        'nominal_tunjangan' => str_replace('.', "", $this->input->post('total_tunjangan', TRUE)),
                        'total_tunjangan_non_tunai' => str_replace('.', "", $this->input->post('total_tunjangan_non_tunai', TRUE)),
                        'total_tunjangan_pengurangan' => str_replace('.', "", $this->input->post('total_tunjangan_pengurangan', TRUE)),
                        'thp' => str_replace('.', "", $this->input->post('total_tunjangan', TRUE)) - str_replace('.', "", $this->input->post('total_tunjangan_pengurangan', TRUE)) + str_replace('.', "", $this->input->post('nominal_gapok', TRUE))
                    ]);
                }

            }
            
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    function edit($id){
        $reviewId = $this->input->get('reviewId', TRUE);
        $summary = $this->db->select('s.*, p.nama, p.id pegawai_id, p.nominal_gapok, p.nominal_gaji_dilaporkan')
                            ->from('summary s')
                            ->join('pegawai p', 's.nip = p.nik')
                            ->where('s.id', $id)->get()->row();

        $tunjanganPegawai = $this->db->select('tm.*, tm.nama nama_template')
                            ->from('tunjangan_pegawai tp')
                            ->join('template_tunjangan tm', 'tp.template_id = tm.id')
                            ->where([
                                'tp.pegawai_id' => $summary->pegawai_id
                            ])->get()->row();
        ?>
            <div class="card card-plain">
                <div class="card-header pb-0 text-left">
                    <h6 class="font-weight-bolder">Edit Cutoff</h6>
                </div>
                <div class="card-body pb-0">
                    <div class="nav-wrapper position-relative end-0 mb-3">
                        <ul class="nav nav-pills nav-fill p-1" id="tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#summary-tabs" role="tab" aria-controls="preview" aria-selected="true" tabindex="-1">
                                    <i class="fas fa-calendar text-sm me-2"></i> Summary
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#tunjangan-tabs" role="tab" aria-controls="code" aria-selected="false" tabindex="-1">
                                    <i class="fas fa-money-bill text-sm me-2"></i> Tunjangan
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="summary-tabs" role="tabpanel">
                            <form action="<?= site_url('review/cutoff/update/' . $id) ?>" role="form text-left" method="post">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>NIP</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="NIP" aria-label="NIP" name="nip" value="<?= $summary->nip ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <label>Nama</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Nama" aria-label="Nama" name="nama" value="<?= $summary->nama ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Hari Efektif</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Hari Efektif" aria-label="Hari Efektif" name="hari_efektif" value="<?= $summary->hari_efektif ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Hadir (Hari)</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Hadir" aria-label="Total Hadir" name="total_hadir" value="<?= $summary->total_hadir ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Izin</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Izin" aria-label="Total Izin" name="izin" value="<?= $summary->izin ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Sakit</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Sakit" aria-label="Total Sakit" name="sakit" value="<?= $summary->sakit ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Alpa</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Alpa" aria-label="Total Alpa" name="alpa" value="<?= $summary->alpa ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Terlambat (Hari)</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Terlambat" aria-label="Total Terlambat" name="hari_terlambat" value="<?= $summary->hari_terlambat ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Terlambat (Menit)</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Terlambat" aria-label="Total Terlambat" name="menit_terlambat" value="<?= $summary->menit_terlambat ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Lembur (Hari)</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Lembur" aria-label="Total Lembur" name="hari_lembur" value="<?= $summary->hari_lembur ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Lembur (Menit)</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="Total Lembur" aria-label="Total Lembur" name="menit_lembur" value="<?= $summary->menit_lembur ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="form-group">
                                            <label for="select-div">Shift</label>
                                            <select name="shift" class="form-control">
                                                <option value="" selected="" disabled="">- Pilih Shift</option>
                                                <?php 
                                                    $shift = $this->db->get_where('shift', ['company_id' => $this->companyid, 'status' => 't']);
                                                    foreach($shift->result() as $d){ ?>
                                                    <option value="<?= $d->keterangan ?>" <?= ($d->keterangan == $summary->shift) ? 'selected' : '' ?> ><?= $d->keterangan ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 me-4 ml-4 mb-0 text-white">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tunjangan-tabs" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label>Hari Efektif</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Hari Efektif" aria-label="Hari Efektif" name="hari_efektif" value="<?= $summary->hari_efektif ?> Hari" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label>Total Hadir (Hari)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Total Hadir" aria-label="Total Hadir" name="total_hadir" value="<?= $summary->total_hadir ?> Hari" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <label>Template Tunjangan</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="<?= $tunjanganPegawai->nama_template ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label>Nominal Gaji Pokok</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="<?= rupiah($summary->nominal_gapok) ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label>Nominal Gaji Dilaporkan</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" value="<?= rupiah($summary->nominal_gaji_dilaporkan) ?>" disabled>
                                    </div>
                                </div>
                            </div>
                            <form action="<?= site_url('review/cutoff/updateTunjagan/' . $id) ?>" role="form text-left" method="post">
                                <input type="hidden" name="review_cutoff_id" value="<?= $reviewId ?>">
                                <input type="hidden" name="cutoff_id" value="<?= $summary->cutoff_id ?>">
                                <input type="hidden" name="nip" value="<?= $summary->nip ?>">
                                <input type="hidden" name="pegawai_id" value="<?= $summary->pegawai_id ?>">
                                <input type="hidden" name="nominal_gapok" value="<?= $summary->nominal_gapok ?>">
                                <div class="table-responsive p-0 mt-4">
                                    <table class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center" width="5px">No</th>
                                                <th width="5px">Tunjangan</th>
                                                <th width="5px">Tipe</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $not = 1;
                                                $getTunjangan = $this->db->select('dt.*, t.tunjangan, t.keterangan, t.type tunjangan_type, rt.kode, rt.satuan')
                                                                        ->from('detail_template_tunjangan dt')
                                                                        ->join('tunjangan t', 'dt.tunjangan_id = t.id')
                                                                        ->join('role_tunjangan rt', 't.role_id = rt.id')
                                                                        ->where('dt.template_id', @$tunjanganPegawai->id)
                                                                        ->order_by('dt.id', "DESC")->get();

                                                $totalTunjangan = [];
                                                $totalTunjanganPengurangan = [];
                                                $totalTunjanganNonTunai = [];

                                                foreach($getTunjangan->result() as $tem){
                                                    $badgeTunjangan = jenisTunjangan($tem->tunjangan_type);
                                                    $nominal = ($tem->type == 'N') ? rupiah($tem->nominal) : $tem->nominal."%";
                                            ?>
                                            <tr>
                                                <td class="text-center"><?= $not++ ?></td>
                                                <td><?= $tem->tunjangan.' - '.$tem->keterangan ?></td>
                                                <td><?= $badgeTunjangan ?></td>
                                                <td>
                                                    <?php 
                                                        if($tem->kode == "HRI"):
                                                            $nominalString = rupiah($tem->nominal). " * " . $summary->total_hadir;
                                                            $nominalHasil = (int)str_replace('.', '', $tem->nominal) * $summary->total_hadir;
                                                        elseif($tem->kode == "JAM"):
                                                            $nominalString = rupiah($tem->nominal). " * " . $summary->total_hadir;
                                                            $nominalHasil = (int)str_replace('.', '', $tem->nominal) * $summary->total_hadir;
                                                        elseif($tem->kode == "BLN"): 
                                                            $nominalString = rupiah($tem->nominal). " * 1";
                                                            $nominalHasil = (int)str_replace('.', '', $tem->nominal) * 1;
                                                        elseif($tem->kode == "TT" || $tem->kode == "TGG"): 
                                                            if($tem->type == "N"):
                                                                $nominalString = rupiah($tem->nominal). " * 1";
                                                                $nominalHasil = (int)str_replace('.', '', $tem->nominal) * 1;
                                                            else:
                                                                $nominalString = "(".rupiah($summary->nominal_gapok)." * 100) / " . $tem->nominal ."%";
                                                                $nominalHasil = ($summary->nominal_gapok / 100) * (int)str_replace('.', '', $tem->nominal);
                                                            endif;
                                                        endif; 

                                                        if($tem->tunjangan_type == 1){
                                                            array_push($totalTunjangan, $nominalHasil);
                                                        }elseif($tem->tunjangan_type == 2){
                                                            array_push($totalTunjanganNonTunai, $nominalHasil);
                                                        }else{
                                                            array_push($totalTunjanganPengurangan, $nominalHasil);
                                                        }

                                                        $cekTunjangan = $this->db->get_where('summary_tunjangan', ['pegawai_id' => $summary->pegawai_id, 'review_cutoff_id' => $reviewId, 'tunjangan_id' => $tem->tunjangan_id]);
                                                        if($cekTunjangan->num_rows() > 0){
                                                            $nomTunjangan = (int)str_replace('.', '', $cekTunjangan->row()->nominal);
                                                        }else{
                                                            $nomTunjangan = $nominalHasil;
                                                        }
                                                    ?>
                                                    <strong><?= $nominalString ?></strong>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control form-control-sm nominal-tunjangan" name="tunjangan_id[<?= $tem->tunjangan_id ?>]" data-type="<?= $tem->tunjangan_type ?>" data-nom="<?= $tem->type ?>" data-id="<?= $tem->id ?>" value="<?= rupiah($nomTunjangan) ?>">
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>Total Insentif</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control nominal-insentif" placeholder="Total Insentif" id="nominal_insentif" name="nominal_insentif" value="<?= ($summary->nominal_insentif) ? rupiah($summary->nominal_insentif) : 0 ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Tunjangan</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Total Tunjangan" id="total_tunjangan" name="total_tunjangan" value="<?= ($summary->nominal_tunjangan) ? rupiah($summary->nominal_tunjangan) : rupiah(array_sum($totalTunjangan)) ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Tunjangan Non Tunai</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Total Tunjangan" id="total_tunjangan_non_tunai" name="total_tunjangan_non_tunai" value="<?= ($summary->total_tunjangan_non_tunai) ? rupiah($summary->total_tunjangan_non_tunai) : rupiah(array_sum($totalTunjanganNonTunai)) ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <label>Total Tunjangan Pengurangan</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Total Tunjangan" id="total_tunjangan_pengurangan" name="total_tunjangan_pengurangan" value="<?= ($summary->total_tunjangan_pengurangan) ? rupiah($summary->total_tunjangan_pengurangan) : rupiah(array_sum($totalTunjanganPengurangan)) ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 me-4 ml-4 mb-0 text-white">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>

            <script>
                $('.nominal-tunjangan, .nominal-insentif').keyup(function(){
                    var sumPenambahan = 0;
                    var sumNonTunai = 0;
                    var sumPengurangan = 0;

                    var formatted = formatRupiah($(this).val())
                    $(this).val(formatted)

                    $(".nominal-tunjangan[data-type=1]" ).each(function(){
                        var amountPenambahan = parseInt($(this).val().replace(/[^0-9]+/g, ""))
                        sumPenambahan +=amountPenambahan
                    })

                    var sumInsentif = parseInt($('#nominal_insentif').val().replace(/[^0-9]+/g, ""))
                    sumPenambahan += sumInsentif

                    $('#total_tunjangan').val(formatRupiah(sumPenambahan))

                    $(".nominal-tunjangan[data-type=2]" ).each(function(){
                        var amountNonTunai = parseInt($(this).val().replace(/[^0-9]+/g, ""))
                        sumNonTunai +=amountNonTunai
                    })

                    $('#total_tunjangan_non_tunai').val(formatRupiah(sumNonTunai))

                    $(".nominal-tunjangan[data-type=3]" ).each(function(){
                        var amountPengurangan = parseInt($(this).val().replace(/[^0-9]+/g, ""))
                        sumPengurangan +=amountPengurangan
                    })

                    $('#total_tunjangan_pengurangan').val(formatRupiah(sumPengurangan))
                })

                function formatRupiah(angka, prefix){
                    var number_string = angka.toString().replace(/[^0-9]+/g, ""),
                    split   		= number_string.split(','),
                    sisa     		= split[0].length % 3,
                    rupiah     		= split[0].substr(0, sisa),
                    ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
        
                    // tambahkan titik jika yang di input sudah menjadi angka ribuan
                    if(ribuan){
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }
        
                    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
                }

            </script>
        <?php
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $cutoffid = $this->db->get_where('cutoff', ['is_active' => 't'])->row()->id;

        $get = $this->db->select('rc.*, c.cabang, d.divisi')
                        ->from('review_cutoff rc')
                        ->join('cabang c', 'rc.cabang_id = c.id')
                        ->join('divisi d', 'rc.divisi_id = d.id')
                        ->where([
                            'rc.company_id' => $this->companyid,
                            'rc.cutoff_id' => $cutoffid
                        ])->order_by('rc.id', "DESC")->get();

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $getLocked = $this->db->select('id')->get_where('summary', ['review_cutoff_id' => $row->id, 'lock' => 't'])->num_rows();
            $periode = date('Y-m-d', strtotime($row->timestamp))."/".$row->cabang."/".$row->divisi;
            $data[] = [
                $no++,
                '<strong>'.$periode.'</strong>',
                '<strong>'.$row->cabang.'</strong>',
                '<strong>'.$row->divisi.'</strong>',
                '<strong>'.$getLocked.' Pegawai</strong>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row->timestamp)))." ".date('H:i:s', strtotime($row->timestamp)).'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a href="'.site_url('review/cutoff/' . $row->id).'" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</a>
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

    function detailTable(){
        $cutoffid = $this->input->get('cutoffid', TRUE);
        
        $reviewCutoff = $this->db->get_where('review_cutoff', ['id' => $cutoffid])->row();
        $getData = $this->db->select('s.*, p.nama, tt.nama nama_template_tunjangan, tp.template_id, p.id pegawai_id')
                            ->from('summary s')
                            ->join('pegawai p', 's.nip = p.nik')
                            ->join('tunjangan_pegawai tp', 'p.id = tp.id', "LEFT")
                            ->join('template_tunjangan tt', 'tp.template_id = tt.id', "LEFT")
                            ->where([
                                's.cutoff_id' => $reviewCutoff->cutoff_id,
                                's.lock' => 'f',
                                'p.cabang_id' => $reviewCutoff->cabang_id,
                                'p.divisi_id' => $reviewCutoff->divisi_id
                            ])->get();

        $tunjangan = $this->db->order_by('urut', "ASC")->get_where('tunjangan', ['company_id' => $this->companyid, 'status' => 't']);
        ?>
            <style>
                .sticky-col {
                    position: -webkit-sticky;
                    position: sticky;
                    background-color: white !important;
                }

                .first-col {
                    width: 100px;
                    min-width: 100px;
                    max-width: 100px;
                    left: 0px;
                }

                .second-col {
                    width: 150px;
                    min-width: 150px;
                    max-width: 150px;
                    left: 100px;
                }

                .third-col {
                    width: 250px;
                    min-width: 250px;
                    max-width: 250px;
                    left: 250px;
                }

                .four-col {
                    width: 100px;
                    min-width: 100px;
                    max-width: 100px;
                    left: 500px;
                }
            </style>
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <h5 class="mb-0"><strong>Summary</strong></h5>
                    </div>
                    <div class="col-lg-3 text-end pe-0">
                        <button type="button" class="btn btn-sm btn-warning" id="btn-lock"><i class="fas fa-lock me-2"></i> Lock</button>
                    </div>
                    <div class="col-lg-3 text-end">
                        <input type="text" name="" id="myInput" class="form-control form-control-sm" placeholder="Cari ...">
                    </div>
                </div>
            </div>

            <div class="table-responsive" style="max-height: 500px!important">
                <table id="example" class="table table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class="text-center w-5px sticky-col first-col">No</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class=" sticky-col second-col">NIP</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class=" sticky-col third-col">Nama</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white; z-index: 3;" class=" sticky-col four-col">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input mt-1 check-all" id="customCheckAll" style="margin-left: auto!important; margin-right: auto !important; left: -15px!important">
                                </div>
                                <small><strong>Check All</strong></small>
                            </th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-left">Hari <br> Efektif</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-left">Hadir <br> Hari</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">S</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">I</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">A</th>
                            <th colspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Terlambat</th>
                            <th colspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Lembur</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="">Shift</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="">Template <br> Tunjangan</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal Gaji <br> Pokok</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal Gaji <br> Dilaporkan</th>
                            <th colspan="<?= $tunjangan->num_rows() ?>" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Tunjangan</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal <br> Insentif</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Nominal <br> Tunjangan</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">THP</th>
                            <th rowspan="2" style="vertical-align : middle;text-align:center;position:sticky;top: 0;background-color:white" class="text-center">Action</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="vertical-align : middle;text-align:center;position:sticky;top:50px;background-color:white">Hari</th>
                            <th class="text-center" style="vertical-align : middle;text-align:center;position:sticky;top:50px;background-color:white">Menit</th>
                            <th class="text-center" style="vertical-align : middle;text-align:center;position:sticky;top:50px;background-color:white">Hari</th>
                            <th class="text-center" style="vertical-align : middle;text-align:center;position:sticky;top:50px;background-color:white">Menit</th>
                            <?php foreach($tunjangan->result() as $th){ ?>
                                <th class="text-center" style="vertical-align : middle;text-align:center;position:sticky;top:50px;background-color:white"><?= $th->tunjangan ?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php
                            $no = 1; 
                            foreach($getData->result() as $row){ 
                        ?>
                        <tr>
                            <td style="z-index: 2;" class="text-center sticky-col first-col" width="5px"><?= $no++ ?></td>
                            <td style="z-index: 2;" class="sticky-col second-col"><strong><?= $row->nip ?></strong></td>
                            <td style="z-index: 2;" class="sticky-col third-col"><strong><?= $row->nama ?></strong></td>
                            <td style="z-index: 2;" class="sticky-col four-col">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input mt-1 check-data" name="pegawai_id" id="checkPegawai<?= $row->id ?>" value="<?= $row->id ?>" style="margin-left: auto!important; margin-right: auto !important;">
                                </div>
                            </td>
                            <td class="text-center"><strong><?= $row->hari_efektif ?></strong></td>
                            <td class="text-center"><strong><?= $row->total_hadir ?></strong></td>
                            <td class="text-center"><strong><?= $row->sakit ?></strong></td>
                            <td class="text-center"><strong><?= $row->izin ?></strong></td>
                            <td class="text-center"><strong><?= $row->alpa ?></strong></td>
                            <td class="text-center"><strong><?= $row->hari_terlambat ?></strong></td>
                            <td class="text-center"><strong><?= $row->menit_terlambat?></strong></td>
                            <td class="text-center"><strong><?= $row->hari_lembur ?></strong></td>
                            <td class="text-center"><strong><?= $row->menit_lembur ?></strong></td>
                            <td><strong><?= $row->shift ?></strong></td>
                            <td class="text-center"><strong><?= $row->nama_template_tunjangan ?></strong></td>
                            <td class="text-center"><strong><?= ($row->nominal_gapok) ? rupiah($row->nominal_gapok) : '-' ?></strong></td>
                            <td class="text-center"><strong><?= ($row->nominal_gaji_dilaporkan) ? rupiah($row->nominal_gaji_dilaporkan) : '-' ?></strong></td>
                            <?php 
                                foreach($tunjangan->result() as $tr){    
                                    $cekSummaryTunjangan = $this->db->get_where('summary_tunjangan', ['pegawai_id' => $row->pegawai_id, 'review_cutoff_id' => $cutoffid, 'tunjangan_id' => $tr->id])->row();
                                    if(@$cekSummaryTunjangan->nominal){
                                        $nominalTunjangan = rupiah((int)str_replace('.', '', $cekSummaryTunjangan->nominal));
                                    }else{
                                        $nominalTunjangan = '-';
                                    }
                            ?>
                                <td class="text-center"><strong><?= $nominalTunjangan ?></strong></td>
                            <?php } ?>
                            <td class="text-center"><strong><?= ($row->nominal_insentif) ? rupiah($row->nominal_insentif) : '-' ?></strong></td>
                            <td class="text-center"><strong><?= ($row->nominal_tunjangan) ? rupiah($row->nominal_tunjangan) : '-' ?></strong></td>
                            <td class="text-center"><strong><?= ($row->thp) ? rupiah($row->thp) : '-' ?></strong></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit(<?= $row->id ?>)"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <form action="" method="post" id="form-lock">
                <input type="hidden" name="review_cutoff_id" value="<?= $cutoffid ?>">
                <div class="hasil">

                </div>
            </form>

            <script>
                $('#btn-lock').prop('disabled', true)
                function btnLock(){
                    if($('input[name="id[]"]').length === 0){
                        $('#btn-lock').prop('disabled', true)
                    }else{
                        $('#btn-lock').prop('disabled', false)
                    }
                }

                $('.check-all').click(function(){
                    if($(this).is(':checked')){
                        $('.check-data').prop('checked', true)
                        $(".check-data").each(function(){
                            var val = $(this).val()
                            if($("#hasil_" + val).length == 0) {
                                $('.hasil').append(
                                    $("<input>", {
                                        type: "hidden",
                                        val: val,
                                        name: "id[]",
                                        id: "hasil_" + val
                                    })
                                )
                            }
                        })
                    }else{
                        $('.check-data').prop('checked', false)
                        $(".check-data").each(function(){
                            var val = $(this).val()
                            $('#hasil_' + val).remove()
                        })
                    }

                    btnLock()
                })
                
                $('.check-data').click(function(){
                    var val = $(this).val()
                    
                    if($(this).is(':checked')){
                        $('.hasil').append(
                            $("<input>", {
                                type: "hidden",
                                val: val,
                                name: "id[]",
                                id: "hasil_" + val
                            })
                        )
                    }else{
                        $('#hasil_' + val).remove()
                    }

                    btnLock()
                })
                
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase()
                    $("#myTable tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    })
                })

                $('#btn-lock').click(function(){
                    var form = $('#form-lock').serialize()
                    $.ajax({
                        url: '<?= site_url('review/cutoff/summaryLock') ?>',
                        type: 'post',
                        data: form,
                        success: function(res){
                            location.reload()
                        }
                    })
                })

                function edit(id){
                    var reviewId = '<?= $cutoffid ?>'
                    $.ajax({
                        url: '<?= site_url('review/cutoff/edit/') ?>' + id,
                        type: 'get',
                        data: {id : id, reviewId : reviewId},
                        success: function(res){
                            $('.data-edit-xl').html(res)
                            $('#modalEditXl').modal('show')
                        }
                    })
                }
            </script>
        <?php
    }
}