<?php
class Employee extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model([
            'M_Company',
        ]);
        $this->companyid = $this->session->userdata('company_id');
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Master Pegawai',
            'company' => $this->M_Company->getById($this->companyid),
            'page' => 'kepegawaian/employee'
        ];
        $this->load->view('templates', $var);
    }

    function add(){
        $var = [
            'title' => 'Tambah Data Pegawai',
            'company' => $this->M_Company->getDefault(),
            'agama' => $this->db->get('agama'),
            'pendidikan' => $this->db->get('jenjang_pendidikan'),
            'pernikahan' => $this->db->get('status_pernikahan'),
            'cabang' => $this->db->get_where('cabang', ['company_id' => $this->companyid]),
            'page' => 'kepegawaian/add_employee',
            'ajax' => [
                'employee'
            ]
        ];
        $this->load->view('templates', $var);
    }

    function edit($id){
        $pegawai = $this->db->select('p.*, a.agama, pd.jenjang, c.company, cb.cabang, j.jabatan, d.divisi, dp.departement, u.unit')
                        ->from('pegawai p')
                        ->join('agama a', 'p.agama_id = a.id', "LEFT")
                        ->join('jenjang_pendidikan pd', 'p.pendidikan_id = pd.id', "LEFT")
                        ->join('company c', 'p.company_id = c.id', "LEFT")
                        ->join('cabang cb', 'p.cabang_id = cb.id', "LEFT")
                        ->join('jabatan j', 'p.jabatan_id = j.id', "LEFT")
                        ->join('divisi d', 'p.divisi_id = d.id', "LEFT")
                        ->join('departement dp', 'p.dept_id = dp.id', "LEFT")
                        ->join('unit u', 'p.unit_id = u.id', "LEFT")
                        ->where([
                            'p.id' => $id
                        ])->get()->row();

        $kepegawaian = $this->db->select('m.*, sk.status')
                                ->from('mutasi m')
                                ->join('status_kepegawaian sk', 'm.status_id = sk.id', 'LEFT')
                                ->where([
                                    'm.pegawai_id' => $id
                                ])->order_by('tgl_join', "ASC")->get();

        $tunjanganPegawai = $this->db->select('tm.*')
                                    ->from('tunjangan_pegawai tp')
                                    ->join('template_tunjangan tm', 'tp.template_id = tm.id')
                                    ->where([
                                        'tp.pegawai_id' => $id
                                    ])->get()->row();

        $var = [
            'title' => 'Edit Pegawai ' . $pegawai->nama,
            'pegawai' => $pegawai,
            'company' => $this->M_Company->getDefault(),
            'agama' => $this->db->get('agama'),
            'pendidikan' => $this->db->get('jenjang_pendidikan'),
            'jabatan' => $this->db->get_where('jabatan', ['company_id' => $this->companyid]),
            'companys' => $this->db->get('company'),
            'divisi' => $this->db->get_where('divisi', ['company_id' => $this->companyid]),
            'departement' => $this->db->get_where('departement', ['divisi_id' => $pegawai->divisi_id]),
            'unit' => $this->db->get_where('unit', ['dept_id' => $pegawai->dept_id]),
            'status_kepegawaian' => $this->db->get_where('status_kepegawaian', ['company_id' => $this->companyid, 'is_active' => 't']),
            'cabang' => $this->db->get_where('cabang', ['company_id' => $this->companyid]),
            'kepegawaian' => $kepegawaian,
            'templateTunjangan' => $this->db->get_where('template_tunjangan', ['status' => 't', 'company_id' => $this->companyid]),
            'pernikahan' => $this->db->get('status_pernikahan'),
            'tunjanganPegawai' => $tunjanganPegawai,
            'family' => $this->db->get_where('family', ['pegawai_id' => $id]),
            'page' => 'kepegawaian/edit_employee',
            'ajax' => [
                'employee'
            ]
        ];
        $this->load->view('templates', $var);
    }

    function create(){
        $deptArr = explode('_', $this->input->post('dept_id', TRUE));
        $unitArr = explode('_', $this->input->post('unit_id', TRUE));
        $dept_id = $deptArr[0];
        $unit_id = $unitArr[0];

        if($this->input->post('cabang_id', TRUE) != NULL){
            $cekCabang = $this->db->get_where('cabang', ['id' => $this->input->post('cabang_id', TRUE)])->row();
            $kode_cabang = $cekCabang->kode;
        }else{
            $kode_cabang = NULL;
        }
        
        $this->form_validation->set_rules('ektp', 'EKTP', 'required|is_unique[pegawai.ektp]', [
            'is_unique' => '<strong>E-Ktp Sudah Tersedia</strong>',
            'required' => '<strong>E-Ktp Wajib Di Isi</strong>',
        ]);
        $this->form_validation->set_rules('nama', 'Nama', 'required', [
            'required' => '<strong>Nama Wajib Di Isi</strong>'
        ]);
        
        $this->form_validation->set_rules('agama_id', 'Agama', 'required', [
            'required' => '<strong>Agama Wajib Di Isi</strong>'
        ]);
        
        $this->form_validation->set_rules('nikah', 'Nikah', 'required', [
            'required' => '<strong>Pernikahan Wajib Di Isi</strong>'
        ]);
        
        $this->form_validation->set_rules('pendidikan_id', 'Pendidikan', 'required', [
            'required' => '<strong>Pendidikan Wajib Di Isi</strong>'
        ]);

        $this->form_validation->set_rules('cabang_id', 'Cabang', 'required', [
            'required' => '<strong>Cabang Wajib Di Isi</strong>'
        ]);

        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required', [
            'required' => '<strong>Tanggal Lahir Wajib Di Isi</strong>'
        ]);

        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            $this->add();
        }else{
            /* Foto Upload */ 
            $config['upload_path']      = './uploads/image';  
            $config['allowed_types']    = 'jpg|jpeg|png'; 
            $config['encrypt_name']    = TRUE;
            
            $this->load->library('upload', $config);
            if($this->upload->do_upload('foto')){
                @unlink('./uploads/image/' . @$cek->foto);

                $fotoData = $this->upload->data();
                $foto = $fotoData['file_name'];
            }else{
                $foto = NULL;
            }

            /* KTP Upload */
            $this->load->library('upload', $config);
            if($this->upload->do_upload('foto_ktp')){
                @unlink('./uploads/image/' . @$cek->foto_ktp);

                $fotoKTPData = $this->upload->data();
                $fotoKtp = $fotoKTPData['file_name'];
            }else{
                $fotoKtp = NULL;
            }

            /* KK Upload */
            $this->load->library('upload', $config);
            if($this->upload->do_upload('foto_kk')){
                @unlink('./uploads/image/' . @$cek->foto_kk);

                $fotoKkData = $this->upload->data();
                $fotoKk = $fotoKkData['file_name'];
            }else{
                $fotoKk = NULL;
            }
            
            $nik = '';
            @$lastNik = $this->db->select('nik')->order_by('nik', "DESC")->get_where('pegawai', ['company_id' => $this->companyid])->row()->nik;

            if(@$lastNik){
                $nik = @$lastNik + 1;
            }else{
                $nik = 1;
            }

            $dataInsert = [
                'company_id' => $this->companyid,
                'kode_cabang' => $kode_cabang,
                'nik' => $nik,
                'nama' => $this->input->post('nama', TRUE),
                'ektp' => $this->input->post('ektp', TRUE),
                'tgl_lahir' => $this->input->post('tgl_lahir', TRUE),
                'nikah' => $this->input->post('nikah', TRUE),
                'jumlan_tanggungan' => $this->input->post('jumlan_tanggungan', TRUE),
                'agama_id' => $this->input->post('agama_id', TRUE),
                'pendidikan_id' => $this->input->post('pendidikan_id', TRUE),
                'no_kk' => $this->input->post('no_kk', TRUE),
                'no_npwp' => $this->input->post('no_npwp', TRUE),
                'no_bpjs_kesehatan' => $this->input->post('no_bpjs_kesehatan', TRUE),
                'no_bpjs_ketenagakerjaan' => $this->input->post('no_bpjs_ketenagakerjaan', TRUE),
                'alamat_domisili' => $this->input->post('alamat_domisili', TRUE),
                'alamat_ktp' => $this->input->post('alamat_ktp', TRUE),
                'nama_ibu' => $this->input->post('nama_ibu', TRUE),
                'email' => $this->input->post('email', TRUE),
                'foto' => $foto,
                'foto_ktp' => $fotoKtp,
                'foto_kk' => $fotoKk,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('pegawai', $dataInsert);
            if($this->db->affected_rows() > 0){
                $pegawai_id = $this->db->insert_id();
                $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
            }else{
                $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
            }

            redirect('kepegawaian/employee/' . $pegawai_id, 'refresh');
        }
    }

    function createTunjangan(){
        $cek = $this->db->get_where('tunjangan_pegawai', ['pegawai_id' => $this->input->post('pegawai_id', TRUE)]);
        if($cek->num_rows() > 0){
            $dataUpdate = [
                'template_id' => $this->input->post('template_id', TRUE),
            ];
            $this->db->where('id', $cek->row()->id)->update('tunjangan_pegawai', $dataUpdate);
        }else{
            $dataInsert = [
                'template_id' => $this->input->post('template_id', TRUE),
                'pegawai_id' => $this->input->post('pegawai_id', TRUE)
            ];
            $this->db->insert('tunjangan_pegawai', $dataInsert);
        }

        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }

        redirect($_SERVER['HTTP_REFERER'],'refresh');
    }

    function createFamily(){
        $dataInsert = [
            'pegawai_id' => $this->input->post('pegawai_id', TRUE),
            'nama' => $this->input->post('nama', TRUE),
            'nik' => $this->input->post('nik', TRUE),
            'tgll' => $this->input->post('tgll', TRUE),
            'jenkel' => $this->input->post('jenkel', TRUE),
            'tipe' => $this->input->post('tipe', TRUE),
            'status' => $this->input->post('status', TRUE),
        ];
        $this->db->insert('family', $dataInsert);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER'],'refresh');
    }

    function update($id){
        /* Valudation */
        $this->form_validation->set_rules('ektp', 'EKTP', 'callback_edit_unique[pegawai.ektp.'.$id.']', [
            'callback_edit_unique[pegawai.ektp.'.$id.']' => '<strong>E-Ktp Sudah Tersedia</strong>'
        ]);

        $cek = $this->db->get_where('pegawai', ['id' => $id])->row();

        /* Foto Upload */ 
        $config['upload_path']      = './uploads/image';  
        $config['allowed_types']    = 'jpg|jpeg|png'; 
        $config['encrypt_name']    = TRUE;
        
        $this->load->library('upload', $config);
        if($this->upload->do_upload('foto')){
            @unlink('./uploads/image/' . @$cek->foto);

            $fotoData = $this->upload->data();
            $foto = $fotoData['file_name'];
        }else{
            $foto = @$cek->foto;
        }

        /* KTP Upload */
        $this->load->library('upload', $config);
        if($this->upload->do_upload('foto_ktp')){
            @unlink('./uploads/image/' . @$cek->foto_ktp);

            $fotoKTPData = $this->upload->data();
            $fotoKtp = $fotoKTPData['file_name'];
        }else{
            $fotoKtp = @$cek->foto_ktp;
        }

        /* KK Upload */
        $this->load->library('upload', $config);
        if($this->upload->do_upload('foto_kk')){
            @unlink('./uploads/image/' . @$cek->foto_kk);

            $fotoKkData = $this->upload->data();
            $fotoKk = $fotoKkData['file_name'];
        }else{
            $fotoKk = @$cek->foto_kk;
        }

        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            $this->edit($id);
        }else{
            $cekCabang = $this->db->get_where('cabang', ['kode' => $cek->kode_cabang])->row();
            if($this->input->post('cabang_id', TRUE) == TRUE && $cekCabang->id != $this->input->post('cabang_id', TRUE)){
                $mutasi = $this->db->order_by('id', "DESC")->get_where('mutasi_pegawai', ['pegawai_id' => $cek->id])->row();
                $dataUpdateMutasi = [
                    'cabang_id' => ($this->input->post('cabang_id', TRUE) == TRUE) ? $this->input->post('cabang_id', TRUE) : $cek->cabang_id,
                ];
                $this->db->where('id', $mutasi->id)->update('mutasi_pegawai', $dataUpdateMutasi);
            }

            $dataUpdate = [
                'nama' => ($this->input->post('nama', TRUE) == TRUE) ? $this->input->post('nama', TRUE) : $cek->nama,
                'ektp' => ($this->input->post('ektp', TRUE) == TRUE) ? $this->input->post('ektp', TRUE) : $cek->ektp,
                'tgl_lahir' => ($this->input->post('tgl_lahir', TRUE) == TRUE) ? $this->input->post('tgl_lahir', TRUE) : $cek->tgl_lahir,
                'nikah' => ($this->input->post('nikah', TRUE) == TRUE) ? $this->input->post('nikah', TRUE) : $cek->nikah,
                'jumlan_tanggungan' => ($this->input->post('jumlan_tanggungan', TRUE) == TRUE) ? $this->input->post('jumlan_tanggungan', TRUE) : $cek->jumlan_tanggungan,
                'agama_id' => ($this->input->post('agama_id', TRUE) == TRUE) ? $this->input->post('agama_id', TRUE) : $cek->agama_id,
                'pendidikan_id' => ($this->input->post('pendidikan_id', TRUE) == TRUE) ? $this->input->post('pendidikan_id', TRUE) : $cek->pendidikan_id,
                'no_kk' => ($this->input->post('no_kk', TRUE) == TRUE) ? $this->input->post('no_kk', TRUE) : $cek->no_kk,
                'no_npwp' => ($this->input->post('no_npwp', TRUE) == TRUE) ? $this->input->post('no_npwp', TRUE) : $cek->no_npwp,
                'no_bpjs_kesehatan' => ($this->input->post('no_bpjs_kesehatan', TRUE) == TRUE) ? $this->input->post('no_bpjs_kesehatan', TRUE) : $cek->no_bpjs_kesehatan,
                'no_bpjs_ketenagakerjaan' => ($this->input->post('no_bpjs_ketenagakerjaan', TRUE) == TRUE) ? $this->input->post('no_bpjs_ketenagakerjaan', TRUE) : $cek->no_bpjs_ketenagakerjaan,
                'alamat_domisili' => ($this->input->post('alamat_domisili', TRUE) == TRUE) ? $this->input->post('alamat_domisili', TRUE) : $cek->alamat_domisili,
                'alamat_ktp' => ($this->input->post('alamat_ktp', TRUE) == TRUE) ? $this->input->post('alamat_ktp', TRUE) : $cek->alamat_ktp,
                'nama_ibu' => ($this->input->post('nama_ibu', TRUE) == TRUE) ? $this->input->post('nama_ibu', TRUE) : $cek->nama_ibu,
                'email' => ($this->input->post('email', TRUE) == TRUE) ? $this->input->post('email', TRUE) : $cek->email,
                'foto' => $foto,
                'foto_ktp' => $fotoKtp,
                'foto_kk' => $fotoKk,
                'cabang_id' => ($this->input->post('cabang_id', TRUE) == TRUE) ? $this->input->post('cabang_id', TRUE) : $cek->cabang_id,
            ];
            $this->db->where('id', $id)->update('pegawai', $dataUpdate);
            if($this->db->affected_rows() > 0){
                $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
            }else{
                $this->session->set_flashdata('error', "Data Gagal Di Simpan");
            }

            redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
    }

    function updateKepegawian($id){
        $deptArr = explode('_', $this->input->post('dept_id', TRUE));
        $unitArr = explode('_', $this->input->post('unit_id', TRUE));
        $dept_id = $deptArr[0];
        $unit_id = $unitArr[0];

        $cek = $this->db->get_where('pegawai', ['id' => $id])->row();
        if($this->input->post('cabang_id', TRUE) != NULL && $cek->kode_cabang == NULL){
            $cekCabang = $this->db->get_where('cabang', ['id' => $this->input->post('cabang_id', TRUE)])->row();
            $kode_cabang = $cekCabang->kode;
        }else{
            $kode_cabang = $cek->kode_cabang;
        }

        $dataUpdate = [
            'tgl_habis_kontrak' => ($this->input->post('tgl_habis_kontrak', TRUE) == TRUE) ? $this->input->post('tgl_habis_kontrak', TRUE) : $cek->tgl_habis_kontrak,
            'resign_date' => ($this->input->post('resign_date', TRUE) == TRUE) ? $this->input->post('resign_date', TRUE) : $cek->resign_date,
            'company_id' => ($this->input->post('company_id', TRUE) == TRUE) ? $this->input->post('company_id', TRUE) : $cek->company_id,
            'cabang_id' => ($this->input->post('cabang_id', TRUE) == TRUE) ? $this->input->post('cabang_id', TRUE) : $cek->cabang_id,
            'jabatan_id' => ($this->input->post('jabatan_id', TRUE) == TRUE) ? $this->input->post('jabatan_id', TRUE) : $cek->jabatan_id,
            'divisi_id' => ($this->input->post('divisi_id', TRUE) == TRUE) ? $this->input->post('divisi_id', TRUE) : $cek->divisi_id,
            'dept_id' => $dept_id,
            'unit_id' => $unit_id,
            'company_id' => ($this->input->post('company_id', TRUE) == TRUE) ? $this->input->post('company_id', TRUE) : $cek->company_id,
        ];
        $this->db->where('id', $id)->update('pegawai', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $dataInsertMutasi = [
                'pegawai_id' => $id,
                'pendidikan_id' => $cek->pendidikan_id,
                'company_id' => ($this->input->post('company_id', TRUE) == TRUE) ? $this->input->post('company_id', TRUE) : $cek->company_id,
                'cabang_id' => ($this->input->post('cabang_id', TRUE) == TRUE) ? $this->input->post('cabang_id', TRUE) : $cek->cabang_id,
                'jabatan_id' => ($this->input->post('jabatan_id', TRUE) == TRUE) ? $this->input->post('jabatan_id', TRUE) : $cek->jabatan_id,
                'divisi_id' => ($this->input->post('divisi_id', TRUE) == TRUE) ? $this->input->post('divisi_id', TRUE) : $cek->divisi_id,
                'dept_id' => $dept_id,
                'unit_id' => $unit_id
            ];
            $getWhere = $this->db->get_where('mutasi_pegawai', $dataInsertMutasi);
            if($getWhere->num_rows() > 0){}else{
                $this->db->insert('mutasi_pegawai', $dataInsertMutasi);
            }
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    function updateKeuangan($id){
        $dataUpdate = [
            'nama_bank' => ($this->input->post('nama_bank', TRUE) == TRUE) ? $this->input->post('nama_bank', TRUE) : $cek->nama_bank,
            'nama_rekening' => ($this->input->post('nama_rekening', TRUE) == TRUE) ? $this->input->post('nama_rekening', TRUE) : $cek->nama_rekening,
            'no_rekening' => ($this->input->post('no_rekening', TRUE) == TRUE) ? $this->input->post('no_rekening', TRUE) : $cek->no_rekening,
            'nominal_gapok' => ($this->input->post('nominal_gapok', TRUE) == TRUE) ? $this->input->post('nominal_gapok', TRUE) : $cek->nominal_gapok,
            'nominal_gaji_dilaporkan' => ($this->input->post('nominal_gaji_dilaporkan', TRUE) == TRUE) ? $this->input->post('nominal_gaji_dilaporkan', TRUE) : $cek->nominal_gaji_dilaporkan,
        ];
        $this->db->where('id', $id)->update('pegawai', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER'], 'refresh');
    }

    function updateFamily($id){
        $dataUpdate = [
            'nama' => $this->input->post('nama', TRUE),
            'nik' => $this->input->post('nik', TRUE),
            'tgll' => $this->input->post('tgll', TRUE),
            'jenkel' => $this->input->post('jenkel', TRUE),
            'tipe' => $this->input->post('tipe', TRUE),
            'status' => $this->input->post('status', TRUE),
        ];
        $this->db->where('id', $id)->update('family', $dataUpdate);
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }
        redirect($_SERVER['HTTP_REFERER'],'refresh');
    }

    function addStatusKepegawaian(){
        $datas = [
            'pegawai_id' => $this->input->post('pegawai_id', TRUE),
            'status_id' => $this->input->post('status_id', TRUE),
            'tgl_join' => $this->input->post('tgl_join', TRUE),
            'tgl_finish' => $this->input->post('tgl_finish', TRUE)
        ];

        $cek = $this->db->get_where('mutasi', [
            'pegawai_id' => $this->input->post('pegawai_id', TRUE),
            'status_id' => $this->input->post('status_id', TRUE)
        ]);

        if($cek->num_rows() > 0){
            $this->db->where('id', $cek->row()->id)->update('mutasi', [
                'tgl_join' => $this->input->post('tgl_join', TRUE),
                'tgl_finish' => $this->input->post('tgl_finish', TRUE)
            ]);
        }else{
            $this->db->insert('mutasi', $datas);
        }

        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Simpan");
        }

        redirect($_SERVER['HTTP_REFERER']);
        
    }

    function delete($id){

    }

    function deleteSK($id){
        $this->db->where('id', $id)->delete('mutasi');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function deleteFamily($id){
        $this->db->where('id', $id)->delete('family');
        if($this->db->affected_rows() > 0){
            $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    function modalEditKepegawaian(){
        $id = $this->input->get('id', TRUE);
        $data = $this->db->get_where('mutasi', ['id' => $id])->row();
        $status_kepegawaian = $this->db->get_where('status_kepegawaian', ['company_id' => $this->companyid, 'is_active' => 't']);
        ?>
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h5 class="font-weight-bolder">Tambah Status Kepegawaian - <?= @$pegawai->nama ?></h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('kepegawaian/employee/addStatusKepegawaian') ?>" role="form text-left" method="post">
                            <input type="hidden" name="pegawai_id" value="<?= $data->pegawai_id ?>">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Status Kepegawaian <small class="text-danger">*</small></label>
                                        <select name="status_id" class="form-control <?= (@form_error('status_id')) ? 'is-invalid' : ((@set_value('status_id')) ? 'is-valid' : '') ?>" required="">
                                            <option value="" selected="" disabled="">- Pilih Status Kepegawaian</option>
                                            <?php foreach($status_kepegawaian->result() as $sk){ ?>
                                                <option value="<?= $sk->id ?>" <?=  ($sk->id == $data->status_id) ? 'selected' : '' ?> ><?= $sk->status ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Tanggal Join<small class="text-danger">*</small></label>
                                                <input class="form-control" type="date" placeholder="Tanggal Join" name="tgl_join" value="<?= $data->tgl_join ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Tanggal Finish</label>
                                                <input class="form-control" type="date" placeholder="Tanggal Finish" name="tgl_finish" value="<?= $data->tgl_finish ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 mb-0 text-white">Tambahkan</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                        <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        <?php
    }

    function modalEditFamily(){
        $id = $this->input->get('id', TRUE);
        $data = $this->db->get_where('family', ['id' => $id])->row();
        ?>
            <div class="modal-body p-0">
                <div class="card card-plain">
                    <div class="card-header pb-0 text-left">
                        <h5 class="font-weight-bolder">Edit Keluarga</h5>
                    </div>
                    <div class="card-body pb-0">
                        <form action="<?= site_url('kepegawaian/employee/updateFamily/' . $id) ?>" role="form text-left" method="post">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Nama <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Nama" aria-label="Nama" name="nama" value="<?= $data->nama ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <label>NIK <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control" placeholder="NIK" aria-label="NIK" name="nik" value="<?= $data->nik ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Tanggal Lahir <small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <input type="date" class="form-control" placeholder="Tanggal Lahir" aria-label="Tanggal Lahir" name="tgll" value="<?= $data->tgll ?>" required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Jenis Kelamin<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenkel" id="inlineRadioJenkel1" value="L" <?= ($data->jenkel == 'L') ? 'checked' : '' ?> required="">
                                            <label class="form-check-label" for="inlineRadioJenkel1">Laki Laki</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="jenkel" id="inlineRadioJenkel2" value="P" <?= ($data->jenkel == 'P') ? 'checked' : '' ?> required="">
                                            <label class="form-check-label" for="inlineRadioJenkel2">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Tipe<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipe" id="inlineRadioTipe1" value="P" <?= ($data->tipe == 'P') ? 'checked' : '' ?> required="">
                                            <label class="form-check-label" for="inlineRadioTipe1">Pasangan</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="tipe" id="inlineRadioTipe2" value="A" <?= ($data->tipe == 'A') ? 'checked' : '' ?> required="">
                                            <label class="form-check-label" for="inlineRadioTipe2">Anak</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label>Status<small class="text-danger">*</small></label>
                                    <div class="input-group mb-3">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadioStatus1" value="H" <?= ($data->status == 'H') ? 'checked' : '' ?> required="">
                                            <label class="form-check-label" for="inlineRadioStatus1">Hidup</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="inlineRadioStatus2" value="M" <?= ($data->status == 'M') ? 'checked' : '' ?> required="">
                                            <label class="form-check-label" for="inlineRadioStatus2">Meninggal</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-sm btn-round bg-success btn-lg w-100 mt-4 mb-0 text-white">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                        <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </div>
        <?php
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $get = $this->db->select('p.*, a.agama, pd.jenjang, c.company, j.jabatan, d.divisi, dp.departement, u.unit')
                        ->from('pegawai p')
                        ->join('agama a', 'p.agama_id = a.id', "LEFT")
                        ->join('jenjang_pendidikan pd', 'p.pendidikan_id = pd.id', "LEFT")
                        ->join('company c', 'p.company_id = c.id', "LEFT")
                        ->join('jabatan j', 'p.jabatan_id = j.id', "LEFT")
                        ->join('divisi d', 'p.divisi_id = d.id', "LEFT")
                        ->join('departement dp', 'p.dept_id = dp.id', "LEFT")
                        ->join('unit u', 'p.unit_id = u.id', "LEFT")
                        ->where('p.company_id', $this->companyid)->order_by('id', "DESC")->get();

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $nik = ($row->kode_cabang != NULL) ? $row->kode_cabang."".sprintf("%05s", $row->nik) : ' - ';
            $data[] = [
                $no++,
                '<p class="mb-0"><strong>'.$nik.'</strong></p>',
                '<strong>'.$row->nama.'</strong>',
                '<p class="mb-0"><strong>'.$row->divisi.' / '.$row->jabatan.'</strong></p>',
                '<strong>'.$row->departement.' / '.$row->unit.'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-sm btn-round btn-info text-white px-3 mb-0" href="'.site_url('kepegawaian/employee/' . $row->id).'"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</a>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('kepegawaian/employee/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
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

    function get_dept(){
        $divisi_id = $this->input->get('id', TRUE);
        $getDepartement = $this->db->get_where('departement', ['divisi_id' => $divisi_id, 'company_id' => $this->companyid])->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($getDepartement));
    }

    function get_unit(){
        $dept_id = $this->input->get('id', TRUE);
        $explode = explode('_', $dept_id);
        $getUnit = $this->db->get_where('unit', ['dept_id' => $explode[0], 'company_id' => $this->companyid])->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($getUnit));
    }

    function edit_unique($str, $field){
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);
        return isset($this->db)
            ? ($this->db->limit(1)->get_where($table, [$field => $str, 'id !=' => $id, 'company_id !=' => $this->companyid])->num_rows() === 0) : FALSE;
    }
}