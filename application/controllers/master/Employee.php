<?php
class Employee extends CI_Controller{
    function __construct(){
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model([
            'M_Company',
        ]);
        if($this->session->userdata('masuk') != TRUE)
            redirect('', 'refresh');
    }

    function index(){
        $var = [
            'title' => 'Master Pegawai',
            'company' => $this->M_Company->getDefault(),
            'page' => 'master/employee'
        ];
        $this->load->view('templates', $var);
    }

    function add(){
        $var = [
            'title' => 'Tambah Data Pegawai',
            'company' => $this->M_Company->getDefault(),
            'agama' => $this->db->get('agama'),
            'pendidikan' => $this->db->get('jenjang_pendidikan'),
            'jabatan' => $this->db->get('jabatan'),
            'companys' => $this->db->get('company'),
            'divisi' => $this->db->get('divisi'),
            'status_kepegawaian' => $this->db->get('status_kepegawaian'),
            'page' => 'master/add_employee',
            'ajax' => [
                'employee'
            ]
        ];
        $this->load->view('templates', $var);
    }

    function edit($id){
        $pegawai = $this->db->select('p.*, a.agama, pd.jenjang, c.company, j.jabatan, d.divisi, dp.departement, u.unit')
                        ->from('pegawai p')
                        ->join('agama a', 'p.agama_id = a.id', "LEFT")
                        ->join('jenjang_pendidikan pd', 'p.pendidikan_id = pd.id', "LEFT")
                        ->join('company c', 'p.company_id = c.id', "LEFT")
                        ->join('jabatan j', 'p.jabatan_id = j.id', "LEFT")
                        ->join('divisi d', 'p.divisi_id = d.id', "LEFT")
                        ->join('departement dp', 'p.dept_id = dp.id', "LEFT")
                        ->join('unit u', 'p.unit_id = u.id', "LEFT")
                        ->where([
                            'p.id' => $id
                        ])->get()->row();
        $var = [
            'title' => 'Edit Pegawai ' . $pegawai->nama,
            'pegawai' => $pegawai,
            'company' => $this->M_Company->getDefault(),
            'agama' => $this->db->get('agama'),
            'pendidikan' => $this->db->get('jenjang_pendidikan'),
            'jabatan' => $this->db->get('jabatan'),
            'companys' => $this->db->get('company'),
            'divisi' => $this->db->get('divisi'),
            'departement' => $this->db->get_where('departement', ['divisi_id' => $pegawai->divisi_id]),
            'unit' => $this->db->get_where('unit', ['dept_id' => $pegawai->dept_id]),
            'status_kepegawaian' => $this->db->get('status_kepegawaian'),
            'page' => 'master/edit_employee',
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
        
        $this->form_validation->set_rules('nik', 'NIK', 'is_unique[pegawai.nik]', [
            'is_unique' => '<strong>NIK Sudah Tersedia</strong>'
        ]);
        $this->form_validation->set_rules('ektp', 'EKTP', 'is_unique[pegawai.ektp]', [
            'is_unique' => '<strong>E-Ktp Sudah Tersedia</strong>'
        ]);
        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            $this->add();
        }else{
            $dataInsert = [
                'nik' => $this->input->post('nik', TRUE),
                'nama' => $this->input->post('nama', TRUE),
                'ektp' => $this->input->post('ektp', TRUE),
                'tgl_lahir' => $this->input->post('tgl_lahir', TRUE),
                'nikah' => $this->input->post('nikah', TRUE),
                'agama_id' => $this->input->post('agama_id', TRUE),
                'pendidikan_id' => $this->input->post('pendidikan_id', TRUE),
                'company_id' => $this->input->post('company_id', TRUE),
                'jabatan_id' => $this->input->post('jabatan_id', TRUE),
                'divisi_id' => $this->input->post('divisi_id', TRUE),
                'dept_id' => $dept_id,
                'unit_id' => $unit_id,
                'status_id' => $this->input->post('status_id', TRUE),
                'tgl_join_c1' => ($this->input->post('tgl_join_c1', TRUE) != "") ? $this->input->post('tgl_join_c1', TRUE) : NULL,
                'tgl_out_c1' => ($this->input->post('tgl_out_c1', TRUE) != "") ? $this->input->post('tgl_out_c1', TRUE) : NULL,
                'tgl_join_c2' => ($this->input->post('tgl_join_c2', TRUE) != "") ? $this->input->post('tgl_join_c2', TRUE) : NULL,
                'tgl_out_c2' => ($this->input->post('tgl_out_c2', TRUE) != "") ? $this->input->post('tgl_out_c2', TRUE) : NULL,
                'tgl_join_p' => ($this->input->post('tgl_join_p', TRUE) != "") ? $this->input->post('tgl_join_p', TRUE) : NULL,
                'tgl_p' => ($this->input->post('tgl_p', TRUE) != "") ? $this->input->post('tgl_p', TRUE) : NULL,
                'company_id' => $this->input->post('company_id', TRUE),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('pegawai', $dataInsert);
            if($this->db->affected_rows() > 0){
                $pegawai_id = $this->db->insert_id();
                $dataInsertMutasi = [
                    'pegawai_id' => $pegawai_id,
                    'pendidikan_id' => $this->input->post('pendidikan_id', TRUE),
                    'company_id' => $this->input->post('company_id', TRUE),
                    'jabatan_id' => $this->input->post('jabatan_id', TRUE),
                    'divisi_id' => $this->input->post('divisi_id', TRUE),
                    'dept_id' => $dept_id,
                    'unit_id' => $unit_id
                ];
                $this->db->insert('mutasi_pegawai', $dataInsertMutasi);
                if($this->db->affected_rows() > 0){
                    $this->session->set_flashdata('success', "Data Berhasil Di Tambahkan");
                }else{
                    $this->db->where('id', $pegawai_id)->delete('pegawai');
                    $this->session->set_flashdata('error', "Data Gagal Di Tambahkan");
                }

                redirect('master/employee', 'refresh');
            }
        }
    }

    function update($id){
        $deptArr = explode('_', $this->input->post('dept_id', TRUE));
        $unitArr = explode('_', $this->input->post('unit_id', TRUE));
        $dept_id = $deptArr[0];
        $unit_id = $unitArr[0];
        
        $this->form_validation->set_rules('nik', 'NIK', 'callback_edit_unique[pegawai.nik.'.$id.']', [
            'callback_edit_unique[pegawai.nik.'.$id.']' => '<strong>NIK Sudah Tersedia</strong>'
        ]);
        $this->form_validation->set_rules('ektp', 'EKTP', 'callback_edit_unique[pegawai.ektp.'.$id.']', [
            'callback_edit_unique[pegawai.ektp.'.$id.']' => '<strong>E-Ktp Sudah Tersedia</strong>'
        ]);
        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            $this->edit($id);
        }else{
            $dataUpdate = [
                'nik' => $this->input->post('nik', TRUE),
                'nama' => $this->input->post('nama', TRUE),
                'ektp' => $this->input->post('ektp', TRUE),
                'tgl_lahir' => $this->input->post('tgl_lahir', TRUE),
                'nikah' => $this->input->post('nikah', TRUE),
                'agama_id' => $this->input->post('agama_id', TRUE),
                'pendidikan_id' => $this->input->post('pendidikan_id', TRUE),
                'company_id' => $this->input->post('company_id', TRUE),
                'jabatan_id' => $this->input->post('jabatan_id', TRUE),
                'divisi_id' => $this->input->post('divisi_id', TRUE),
                'dept_id' => $dept_id,
                'unit_id' => $unit_id,
                'status_id' => $this->input->post('status_id', TRUE),
                'tgl_join_c1' => ($this->input->post('tgl_join_c1', TRUE) != "") ? $this->input->post('tgl_join_c1', TRUE) : NULL,
                'tgl_out_c1' => ($this->input->post('tgl_out_c1', TRUE) != "") ? $this->input->post('tgl_out_c1', TRUE) : NULL,
                'tgl_join_c2' => ($this->input->post('tgl_join_c2', TRUE) != "") ? $this->input->post('tgl_join_c2', TRUE) : NULL,
                'tgl_out_c2' => ($this->input->post('tgl_out_c2', TRUE) != "") ? $this->input->post('tgl_out_c2', TRUE) : NULL,
                'tgl_join_p' => ($this->input->post('tgl_join_p', TRUE) != "") ? $this->input->post('tgl_join_p', TRUE) : NULL,
                'tgl_p' => ($this->input->post('tgl_p', TRUE) != "") ? $this->input->post('tgl_p', TRUE) : NULL,
                'company_id' => $this->input->post('company_id', TRUE)
            ];
            $this->db->where('id', $id)->update('pegawai', $dataUpdate);
            if($this->db->affected_rows() > 0){
                $dataInsertMutasi = [
                    'pegawai_id' => $id,
                    'pendidikan_id' => $this->input->post('pendidikan_id', TRUE),
                    'company_id' => $this->input->post('company_id', TRUE),
                    'jabatan_id' => $this->input->post('jabatan_id', TRUE),
                    'divisi_id' => $this->input->post('divisi_id', TRUE),
                    'dept_id' => $dept_id,
                    'unit_id' => $unit_id
                ];
                $getWhere = $this->db->get_where('mutasi_pegawai', $dataInsertMutasi);
                if($getWhere->num_rows() > 0){}else{
                    $this->db->insert('mutasi_pegawai', $dataInsertMutasi);
                }
                $this->session->set_flashdata('success', "Data Berhasil Di Simpan");
                redirect('master/employee', 'refresh');
            }
        }
    }

    function delete($id){

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
                        ->get();

        $data = array();
        $no = 1;
        foreach($get->result() as $row){
            $nikah = ($row->nikah == 't') ? '<p class="mb-0 text-center"><span class="badge bg-primary">Menikah</span></p>' : '<p class="mb-0 text-center"><span class="badge bg-default">Belum Menikah</span></p>';
            $data[] = [
                $no++,
                '<p class="mb-0 text-center"><strong>'.$row->nik.'</strong></p>',
                '<strong>'.$row->nama.'</strong>',
                // '<p class="text-center"><strong>'.$row->ektp.'</strong></p>',
                // '<p class="text-center"><strong>'.$row->tgl_lahir.'</strong></p>',
                $nikah,
                // '<p class="text-center"><strong>'.$row->agama.'</strong></p>',
                // '<p class="text-center"><strong>'.$row->jenjang.'</strong></p>',
                '<p class="mb-0 text-center"><strong>'.$row->company.'</strong></p>',
                '<strong>'.$row->jabatan.'</strong>',
                '<p class="mb-0 text-center"><strong>'.$row->divisi.'</strong></p>',
                '<strong>'.$row->departement.'</strong>',
                '<strong>'.$row->unit.'</strong>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row->updated_at))).' - '.date('H:i:s', strtotime($row->updated_at)).'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a class="btn btn-sm btn-round btn-info text-white px-3 mb-0" href="'.site_url('master/employee/' . $row->id).'"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</a>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('master/employee/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
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
        $getDepartement = $this->db->get_where('departement', ['divisi_id' => $divisi_id])->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($getDepartement));
    }

    function get_unit(){
        $dept_id = $this->input->get('id', TRUE);
        $explode = explode('_', $dept_id);
        $getUnit = $this->db->get_where('unit', ['dept_id' => $explode[0]])->result();
        $this->output->set_content_type('application/json')->set_output(json_encode($getUnit));
    }

    function edit_unique($str, $field){
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $id);
        return isset($this->db)
            ? ($this->db->limit(1)->get_where($table, array($field => $str, 'id !=' => $id))->num_rows() === 0) : FALSE;
    }
}