<?php
class Employee extends CI_Controller{
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
            'title' => 'Pegawai',
            'company' => $this->M_Company->getDefault(),
            'page' => 'employee'
        ];
        $this->load->view('templates', $var);
    }

    function create(){

    }

    function update($id){

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
            $nikah = ($row->nikah == 't') ? '<span class="badge bg-primary">Menikah</span>' : '<span class="badge bg-default">Belum Menikah</span>';
            $data[] = [
                $no++,
                '<p class="text-center"><strong>'.$row->nik.'</strong></p>',
                '<strong>'.$row->nama.'</strong>',
                '<p class="text-center"><strong>'.$row->ektp.'</strong></p>',
                '<p class="text-center"><strong>'.$row->tgl_lahir.'</strong></p>',
                $nikah,
                '<p class="text-center"><strong>'.$row->agama.'</strong></p>',
                '<p class="text-center"><strong>'.$row->jenjang.'</strong></p>',
                '<p class="text-center"><strong>'.$row->company.'</strong></p>',
                '<p class="text-center"><strong>'.$row->jabatan.'</strong></p>',
                '<p class="text-center"><strong>'.$row->divisi.'</strong></p>',
                '<p class="text-center"><strong>'.$row->departement.'</strong></p>',
                '<p class="text-center"><strong>'.$row->unit.'</strong></p>',
                '<p class="text-center"><strong>'.date('Y-m-d H:i:s', strtotime($row->updated_at)).'</strong></p>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-round btn-info text-white px-3 mb-0" onclick="edit('.$row->id.')"><i class="fas fa-pencil-alt me-2" aria-hidden="true"></i>Edit</button>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('holidays/delete/' . $row->id).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>
                <script>
                    function edit(id){
                        $.ajax({
                            url : "'.site_url('holidays/edit/').'" + id,
                            type : "post",
                            data : {id : id},
                            success: function(res){
                                $(".data-edit").html(res)
                                $("#modalEdit").modal("show")
                            }
                        })
                    }
                </script>'
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