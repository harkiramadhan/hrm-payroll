<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;

class Upload extends CI_Controller{
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
            'title' => 'Upload Data Cutoff Mitra',
            'company' => $this->M_Company->getById($this->companyid),
            'cabang' => $this->db->get_where('cabang', ['company_id' => $this->companyid, 'status' => 't']),
            'page' => 'mitra/mitra_upload_data'
        ];
        $this->load->view('templates', $var);
    }

    
    function remove($id){
        $absensi = $this->db->get_where('log_upload_mitra', ['id' => $id])->row();
        ?>
            <div class="card card-plain">
                <div class="card-body pb-0">
                    <form action="<?= site_url('mitra/upload/delete/' . $id) ?>" role="form text-left" method="post">
                        <div class="row">
                            <div class="col-lg-12 text-center">
                                <h1 class="mb-3 text-danger"><i class="fas fa-exclamation"></i></h1>
                                <h5><strong class="mb-0">Hapus Absensi <br>
                                        Filename : <u><?= $absensi->filename ?></u>
                                </strong></h5>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-sm btn-round bg-danger btn-lg w-100 mt-4 mb-0 text-white">Hapus</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                    <button type="button" class="btn btn-sm btn-link btn-block  ml-auto" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        <?php
    }

    function modalDetailErrorLog(){
        $logid = $this->input->get('id', TRUE);
        $dataByLogId = $this->db->get_where('mitra_error_log', ['log_id' => $logid])->result();
        ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th class="text-center w-5px">No</th>
                                    <th>Error Message</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $no = 1;
                                    foreach($dataByLogId as $row){ 
                                        $data = json_decode($row->data);
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= $row->error_log ?></td>
                                        <td>
                                            <div class="row">
                                                <div class="col-2">
                                                    <small>
                                                        NIP<br>
                                                        Nama<br>
                                                        In<br>
                                                        Out<br>
                                                        Shift<br>
                                                        Ket<br>
                                                    </small>
                                                </div>
                                                <div class="col-10">
                                                    <small>
                                                        : <strong><?= $data->nik ?></strong> <br>
                                                        : <strong><?= $data->nama ?></strong> <br>
                                                        : <strong><?= $data->jam_in ?></strong> <br>
                                                        : <strong><?= $data->jam_out ?></strong> <br>
                                                        : <strong><?= $data->shift_id ?></strong> <br>
                                                        : <strong><?= $data->keterangan ?></strong> <br>
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php
    }

    function table(){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $logAbsensi = $this->db->select('u.username, la.*')
                            ->from('log_upload_mitra la')
                            ->join('user u', 'la.user_id = u.id')
                            ->where([
                                'la.company_id' => $this->companyid
                            ])->order_by('id', "DESC")->get();
        $data = array();
        $no = 1;

        foreach($logAbsensi->result_array() as $row){
            $error = ($row['error_row'] > 0) ? $row['error_row'] : 0;
            $data[] =[
                $no++,
                '<p class="mb-0";><strong>'.$row['username'].'</strong></p>',
                '<a class="btn btn-sm btn-round btn-secondary text-white px-3 mb-0 mx-1" href="'.base_url('uploads/mitra/' . $row['filename']).'" style="width:100%" download><i class="fas fa-download me-2" aria-hidden="true"></i>'.$row['filename'].'</a>',
                '<button type="button" class="btn btn-sm btn-round btn-danger text-white px-3 mb-0 btn-detail-error" onclick="errorLogDetail('.$row['id'].')" style="width:100%"><i class="fas fa-arrow-up me-2" aria-hidden="true"></i>Error '.$error.' Row</button>',
                '<p class="text-center mb-0";><strong>'.$row['success_row'].'</strong></p>',
                '<p class="text-center mb-0";><strong>'.$row['total_row'].'</strong></p>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row['timestamp']))).' - '.date('H:i:s', strtotime($row['timestamp'])).'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a href="'.site_url('mitra/upload/' . $row['id']).'" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</a>
                    <button type="button" class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" onclick="remove('.$row['id'].')"><i class="far fa-trash-alt" aria-hidden="true"></i></button>
                </div>
                
                <script>
                    function errorLogDetail(id){
                        var logid = id
                        $.ajax({
                            url: "'.site_url('mitra/upload/modalDetailErrorLog').'",
                            type: "get",
                            data: {id : id},
                            beforeSend: function(){
                                $("#modalEdit").modal("show")
                            },
                            success: function(res){
                                $(".data-edit").html(res)
                            }
                        })
                    }

                    function remove(id){
                        $.ajax({
                            url : "'.site_url('mitra/upload/remove/').'" + id,
                            type : "post",
                            data : {id : id},
                            success: function(res){
                                $(".data-delete").html(res)
                                $("#modalDelete").modal("show")
                            }
                        })
                    }
                </script>
                '
            ];
        }

        $output = [
            "draw"              => $draw,
            "recordsTotal"      => $logAbsensi->num_rows(),
            "recordsFiltered"   => $logAbsensi->num_rows(),
            "data"              => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    function download($cabang_id){
        $cutoff = $this->M_Cutoff->getActive($this->companyid);
        $stringCutoff = "Periode " . $cutoff->tahun."".sprintf("%02d", $cutoff->bulan);
        $tunjangan = $this->db->select('t.*')
                                ->from('tunjangan t')
                                ->join('role_tunjangan rt', 't.role_id = rt.id')
                                ->where([
                                    'rt.jenis' => 'Mitra',
                                    't.status' => 't'
                                ])->order_by('t.urut', "ASC")->get();

        $pegawai = $this->db->select('p.nama, p.nik')
                            ->from('pegawai p')
                            ->join('jabatan j', 'p.jabatan_id = j.id')
                            ->where([
                                'p.company_id' => $this->companyid,
                                'p.cabang_id' => $cabang_id,
                                'j.jabatan' => 'Mitra'
                            ])->order_by('nik', "ASC")->get();

        $cabang = $this->db->get_where('cabang', ['id' => $cabang_id])->row();
                            
        $spreadsheet = new Spreadsheet();  
        $Excel_writer = new Xlsx($spreadsheet);

        $lastColumn = $this->toAlpha($tunjangan->num_rows() + 2);

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $styleBold = [
            'font' => [
                'bold' => true,
            ],
        ];

        $sheet->freezePane('D4'); 

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getRowDimension(3)->setRowHeight(45);
        $sheet->getColumnDimension($lastColumn)->setAutoSize(true);

        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A3')->getAlignment()->setVertical('center');
        $sheet->getStyle('B3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B3')->getAlignment()->setVertical('center');
        $sheet->getStyle('C3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('C3')->getAlignment()->setVertical('center');
        $sheet->getStyle('D3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D3')->getAlignment()->setVertical('center');
        $sheet->getStyle( $lastColumn . '3')->getAlignment()->setVertical('center');

        $sheet->getStyle('A1:' .$lastColumn. '1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:' .$lastColumn. '2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:' .$lastColumn. '1')->applyFromArray($styleBold);
        $sheet->getStyle('A2:' .$lastColumn. '2')->applyFromArray($styleBold);
        
        $sheet->getStyle('A3')->applyFromArray($styleBold);
        $sheet->getStyle('B3')->applyFromArray($styleBold);
        $sheet->getStyle('C3')->applyFromArray($styleBold);
        $sheet->getStyle('D3')->applyFromArray($styleBold);
        $sheet->getStyle($lastColumn . '3')->applyFromArray($styleBold);
        

        $sheetStyle = $spreadsheet->getActiveSheet();
        $sheetStyle->mergeCells('B1:' .$lastColumn. '1');
        $sheetStyle->mergeCells('B2:' .$lastColumn. '2');
        $sheetStyle->setCellValue('B1','Format Import Mitra - ' . $cabang->cabang);
        $sheetStyle->setCellValue('B2', $stringCutoff);
        $sheetStyle->setCellValue('A3','No');
        $sheetStyle->setCellValue('B3','NIP');
        $sheetStyle->setCellValue('C3','Nama');
        $sheet->getStyle('A3:' . $lastColumn . '3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f5b642');

        $number = 4;
        foreach($tunjangan->result() as $row){
            $alpha = $this->toAlpha($number);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow($number, 3, $row->tunjangan);
            $sheet->getStyle($alpha . '3')->applyFromArray($styleBold);
            $sheet->getColumnDimension($alpha)->setAutoSize(true);
            $sheet->getStyle($alpha . '3')->getAlignment()->setHorizontal('center');
            $sheet->getStyle($alpha . '3')->getAlignment()->setVertical('center');
            
            $number++;
        }

        $no = 1;
        $excel_row = 4;
        foreach($pegawai->result() as $p){
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $no++);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $p->nik);
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $p->nama);
            $excel_row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: attachment;filename=Format Import Mitra - '. $cabang->cabang .'.Xlsx'); 
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $Excel_writer->save('php://output');
        exit;

        redirect($_SERVER['HTTP_REFERER']);
    }

    function import(){
        $cutoffid = $this->input->post('cutoff_id', TRUE);
        $cutoff = $this->db->get_where('cutoff', ['id' => $cutoffid])->row();
        $this->load->library('form_validation');
         $this->form_validation->set_rules('file', 'Upload File', 'callback_checkFileValidation');
         if($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect($_SERVER['HTTP_REFERER']);
         } else {
            if(!empty($_FILES['file']['name'])) { 
                $arr_file                   = explode('.', $_FILES['file']['name']);
                $extension                  = end($arr_file);
                $config['upload_path']      = './uploads/mitra/';
                $config['allowed_types']    = 'xlsx';
                $config['file_name']        = str_replace(' ', '_', $arr_file[0])."_".time().".".$extension;
                $config['overwrite']        = TRUE;

                $this->load->library('upload', $config);
                $this->upload->do_upload('file');
                $upload_data = $this->upload->data();
                $fileImport = $upload_data['file_name'];
                if(!$this->upload->do_upload('file')){
                    $this->session->set_flashdata('error', "Silahkan Pilih File Terlebih Dahulu");
                    redirect($_SERVER['HTTP_REFERER']);
                }else{
                    $newFileNames = explode('.',$fileImport);
                    $fileType = ucfirst($newFileNames[1]);
                    $path = './uploads/mitra/';
                    $inputFileType = $fileType;
                    $inputFileName = $path.$fileImport;
                    $reader = IOFactory::createReader($inputFileType);
                    $reader->setReadDataOnly(true);
                    $reader->setReadEmptyCells(false);
                    $spreadsheet = $reader->load($inputFileName);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);
                    $sheet = $spreadsheet->getActiveSheet();
                    $stringCutoff = "Periode " . $cutoff->tahun."".sprintf("%02d", $cutoff->bulan);

                    foreach ($sheet->getRowIterator(3) as $index => $row) {
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(TRUE); // This loops through all cells, even if a cell value is not set.
                        $tunjangan_content = [];
                        $stringColumn = 4;
                        foreach ($cellIterator as $cell) {
                            $val = $cell->getValue();
                            if($val != 'No' && $val != 'NIP' && $val != 'Nama'){
                                $cekTunjangan = $this->db->get_where('tunjangan', ['tunjangan' => $cell->getValue()]);
                                if($cekTunjangan->num_rows() > 0){
                                    $dataTunjangan = [
                                        'id' => $cekTunjangan->row()->id,
                                        'column' => $this->toAlpha($stringColumn),
                                        'tunjangan' => $cekTunjangan->row()->tunjangan
                                    ];
                                    array_push($tunjangan_content, $dataTunjangan);
                                }
                            }

                            $stringColumn++;
                        }
                        
                        $tunjangan[] = $tunjangan_content;
                        break;
                    }
                    
                    // $this->output->set_content_type('application/json')->set_output(json_encode($tunjangan[0]));
                    
                    
                    if($sheetData[2]['B'] == $stringCutoff){
                        $success_row = 0;
                        $error_row = 0;
                        $dataLog = [
                            'company_id' => $this->companyid,
                            'user_id' => $this->session->userdata('userid'),
                            'cutoff_id' => $cutoffid,
                            'filename' => $fileImport
                        ];
                    //     // $this->db->insert('log_upload_mitra', $dataLog);
                    //     // $logid = $this->db->insert_id();
                        for($row = 4; $row <= count($sheetData); $row++){
                            $cekPegawai = $this->db->limit(1)->get_where('pegawai', ['nik' => $sheetData[$row]['B']]);
                            if($cekPegawai->num_rows() > 0){

                            }else{

                            }
                        }

                    //     // $dataUpdate = [
                    //     //     'success_row' => $success_row,
                    //     //     'error_row' => $error_row,
                    //     //     'total_row' => $success_row + $error_row
                    //     // ];
                    //     // $this->db->where('id', $logid)->update('log_upload_mitra', $dataUpdate);
                    //     // if($this->db->affected_rows() > 0){
                    //     //     $this->session->set_flashdata('success', "File Berhasil Di Upload");
                    //     //     redirect($_SERVER['HTTP_REFERER']);
                    //     // }else{
                    //     //     unlink($inputFileName);
                    //     //     $this->db->where('id', $logid)->delete('log_upload_mitra');
                    //     //     $this->session->set_flashdata('error', "File Gagal Di Upload");
                    //     //     redirect($_SERVER['HTTP_REFERER']);
                    //     // }
                    }else{
                        unlink($inputFileName);
                        $this->session->set_flashdata('error', "File Tidak Sesuai Dengan Format Yang Tersedia");
                        redirect($_SERVER['HTTP_REFERER']);
                    }

                    if($spreadsheet == FALSE){
                        unlink($inputFileName);
                        $this->session->set_flashdata('error', "File Tidak Support !");
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            }              
        }
    }

    /* Form Validation Callback */
    public function checkFileValidation($string) {
        $file_mimes = array('text/x-comma-separated-values', 
          'text/comma-separated-values', 
          'application/octet-stream', 
          'application/vnd.ms-excel', 
          'application/x-csv', 
          'text/x-csv', 
          'text/csv', 
          'application/csv', 
          'application/excel', 
          'application/vnd.msexcel', 
          'text/plain', 
          'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
        if(isset($_FILES['file']['name'])) {
            $arr_file = explode('.', $_FILES['file']['name']);
            $extension = end($arr_file);
            if(($extension == 'xlsx' || $extension == 'Xlsx' || $extension == 'xls') && in_array($_FILES['file']['type'], $file_mimes)){
                return true;
            }else{
                $this->form_validation->set_message('checkFileValidation', 'File Yang Di Pilih Tidak Sesuai');
                return false;
            }
        }else{
            $this->form_validation->set_message('checkFileValidation', 'Silahkan Pilih File Terlebih Dahulu');
            return false;
        }
    }

    function toNum($data) {
        $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                           'f', 'g', 'h', 'i', 'j',
                           'k', 'l', 'm', 'n', 'o',
                           'p', 'q', 'r', 's', 't',
                           'u', 'v', 'w', 'x', 'y',
                           'z'
                           );
        $alpha_flip = array_flip($alphabet);
        $return_value = -1;
        $length = strlen($data);
        for ($i = 0; $i < $length; $i++) {
            $return_value +=
                ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
        }
        return $return_value;
    }

    function toAlpha($data){
        $alphabet =   array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $alpha_flip = array_flip($alphabet);
        if($data <= 25){
          return $alphabet[$data];
        }
        elseif($data > 25){
          $dividend = ($data + 1);
          $alpha = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % 26;
            $alpha = $alphabet[$modulo] . $alpha;
            $dividend = floor((($dividend - $modulo) / 26));
          } 
          return $alpha;
        }
    }
    
}