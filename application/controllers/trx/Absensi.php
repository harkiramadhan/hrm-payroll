<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\IWriter;


class Absensi extends CI_Controller{
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
            'title' => 'Transaksi Absensi',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $this->M_Cutoff->getActive(),
            'page' => 'trx/absensi'
        ];
        $this->load->view('templates', $var);
    }

    function detail($id){
        $var = [
            'title' => 'Detail Transaksi Absensi',
            'company' => $this->M_Company->getDefault(),
            'cutoff' => $this->M_Cutoff->getActive(),
            'page' => 'trx/absensi_detail'
        ];
        $this->load->view('templates', $var);
    }

    function table(){
        $cutoffid = $this->M_Cutoff->getActive()->id;
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $logAbsensi = $this->db->select('p.nama, la.*')
                            ->from('log_upload_absensi la')
                            ->join('pegawai p', 'la.pegawai_id = p.id')
                            ->where([
                                'la.cutoff_id' => $cutoffid,
                                'la.company_id' => $this->companyid
                            ])->get();
        $data = array();
        $no = 1;

        foreach($logAbsensi->result_array() as $row){
            $data[] =[
                $no++,
                '<p class="mb-0";><strong>'.$row['nama'].'</strong></p>',
                '<p class="text-center mb-0";><strong>'.$row['total_row'].'</strong></p>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row['timestamp']))).' - '.date('H:i:s', strtotime($row['timestamp'])).'</strong>',
                '<div class="btn-group" role="group" aria-label="Basic example">
                    <a href="'.site_url('trx/absensi/' . $row['id']).'" class="btn btn-sm btn-round btn-primary text-white px-3 mb-0"><i class="fas fa-eye me-2" aria-hidden="true"></i>Detail</a>
                    <a class="btn btn-sm btn-round btn-secondary text-white px-3 mb-0 mx-1" href="'.base_url('uploads/absensi/' . $row['filename']).'" download><i class="fas fa-download" aria-hidden="true"></i></a>
                    <a class="btn btn-sm btn-round btn-link text-danger px-3 mb-0" href="'.site_url('trx/absensi/delete/' . $row['id']).'"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                </div>'
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

    function tableDetail($id){
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $absensi = $this->db->select('a.*, p.nama, s.keterangan shift')
                            ->from('absensi a')
                            ->join('pegawai p', 'a.nik = p.nik')
                            ->join('shift s', 'a.shift_id = s.id')
                            ->where([
                                'a.log_id' => $id,
                                'a.company_id' => $this->companyid,
                            ])->get();
        $data = array();
        $no = 1;

        foreach($absensi->result_array() as $row){
            $data[] =[
                $no++,
                '<p class="mb-0";><strong>'.$row['nama'].'</strong></p>',
                '<p class="mb-0";><strong>'.$row['shift'].'</strong></p>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row['jam_in']))).' - '.date('H:i', strtotime($row['jam_in'])).'</strong>',
                '<strong>'.longdate_indo(date('Y-m-d', strtotime($row['jam_out']))).' - '.date('H:i', strtotime($row['jam_out'])).'</strong>',
                '<p class="mb-0 text-center";><strong>'.$row['keterangan'].'</strong></p>',
            ];
        }

        $output = [
            "draw"              => $draw,
            "recordsTotal"      => $absensi->num_rows(),
            "recordsFiltered"   => $absensi->num_rows(),
            "data"              => $data
        ];
        $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    /* Trx */
    function delete($id){
        $log = $this->db->get_where('log_upload_absensi', ['id' => $id])->row();
        if(unlink('./uploads/absensi/' . $log->filename)){
            $this->db->where('id', $id)->delete('log_upload_absensi');
            if($this->db->affected_rows() > 0){
                $this->session->set_flashdata('success', "Data Berhasil Di Hapus");
            }else{
                $this->session->set_flashdata('error', "Data Gagal Di Hapus");
            }
        }else{
            $this->session->set_flashdata('error', "Data Gagal Di Hapus");
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    /* PhpSpreadsheet Code Here! */
    function download(){
        $shift = $this->db->get_where('shift', ['status' => 't', 'company_id' => $this->companyid])->result();

        $spreadsheet = new Spreadsheet();  
        $Excel_writer = new Xlsx($spreadsheet);

        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet();
        $styleBold = [
            'font' => [
                'bold' => true,
            ],
        ];

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getStyle('A:I')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:I')->getAlignment()->setVertical('center');
        $sheet->getStyle('B1:I3')->applyFromArray($styleBold);
        $sheet->getStyle('D3')->getFont()->getColor()->setRGB ('FFFF0000');
        $sheet->getStyle('E3')->getFont()->getColor()->setRGB ('FFFF0000');
        $sheet->getStyle('G3')->getFont()->getColor()->setRGB ('FFFF0000');
        $sheet->getStyle('I2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('ffe100');

        $sheetStyle = $spreadsheet->getActiveSheet();

        $sheetStyle->mergeCells('B1:I1');
        $sheetStyle->mergeCells('B2:B3');
        $sheetStyle->mergeCells('C2:C3');
        $sheetStyle->mergeCells('F2:F3');
        $sheetStyle->mergeCells('I2:I3');
        $sheetStyle->setCellValue('B1','Format Import Absensi');
        $sheetStyle->setCellValue('B2','NIP');
        $sheetStyle->setCellValue('C2','Nama');
        $sheetStyle->setCellValue('D2','Tanggal Masuk (DD-MM-YY H:I)');
        $sheetStyle->setCellValue('D3','31-01-2022 08:59');
        $sheetStyle->setCellValue('E2','Tanggal Keluar (DD-MM-YY H:I)');
        $sheetStyle->setCellValue('E3','31-01-2022 18:59');
        $sheetStyle->setCellValue('F2','Kode Shift');
        $sheetStyle->setCellValue('G2','Keterangan');
        $sheetStyle->setCellValue('G3','S= Sakit, C= Cuti, I= Izin, A= Alpa');
        $sheetStyle->setCellValue('I2','Kode Shift Tersedia');

        $excel_row = 4;
        foreach($shift as $row){
            $spreadsheet->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->kode);
            $sheet->getStyle('I' . $excel_row)->applyFromArray($styleBold);
            $sheet->getStyle('I' . $excel_row)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('fffe00');
            $excel_row++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Content-Disposition: attachment;filename=Format Import Absensi.Xlsx'); 
        header('Cache-Control: max-age=0');
        ob_end_clean();
        $Excel_writer->save('php://output');
        exit;

        redirect($_SERVER['HTTP_REFERER']);
    }

    function import(){
        $cutoffid = $this->M_Cutoff->getActive()->id;
        $this->load->library('form_validation');
         $this->form_validation->set_rules('file', 'Upload File', 'callback_checkFileValidation');
         if($this->form_validation->run() == false) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect($_SERVER['HTTP_REFERER']);
         } else {
            if(!empty($_FILES['file']['name'])) { 
                $arr_file                   = explode('.', $_FILES['file']['name']);
                $extension                  = end($arr_file);
                $config['upload_path']      = './uploads/absensi/';
                $config['allowed_types']    = 'xlsx';
                $config['file_name']        = "abs_".time().".".$extension;
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
                    $path = './uploads/absensi/';
                    $inputFileType = $fileType;
                    $inputFileName = $path.$fileImport;
                    $reader = IOFactory::createReader($inputFileType);
                    $reader->setReadDataOnly(true);
                    $reader->setReadEmptyCells(false);
                    $spreadsheet = $reader->load($inputFileName);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray(null,true,true,true);

                    if($sheetData[1]['B'] == 'Format Import Absensi' && $sheetData[2]['D'] == 'Tanggal Masuk (DD-MM-YY H:I)' && $sheetData[2]['E'] == 'Tanggal Keluar (DD-MM-YY H:I)'){
                        $count = 0;
                        $dataLog = [
                            'company_id' => $this->companyid,
                            'pegawai_id' => $this->session->userdata('pegawai_id'),
                            'cutoff_id' => $cutoffid,
                            'filename' => $fileImport
                        ];
                        $this->db->insert('log_upload_absensi', $dataLog);
                        $logid = $this->db->insert_id();
                        for($row = 4; $row <= count($sheetData); $row++){
                            $separate = $this->stringSeperator($sheetData[$row]['B']);
                            $nip = (int)$separate['nip'];
                            $cabang = $separate['cabang'];

                            $cek = $this->db->limit(1)->get_where('pegawai', ['nik' => $nip, 'kode_cabang' => $cabang]);
                            if($cek->num_rows() > 0){
                                $shift = $this->db->limit(1)->get_where('shift', ['kode' => $sheetData[$row]['F']]);
                                if($shift->num_rows() > 0){
                                    $datas = [
                                        'company_id' => $this->companyid,
                                        'log_id' => $logid,
                                        'nik' => $nip,
                                        'jam_in' => date('Y-m-d H:i:s', strtotime($sheetData[$row]['D'].":00")),
                                        'jam_out' => date('Y-m-d H:i:s', strtotime($sheetData[$row]['E'].":00")),
                                        'shift_id' => $shift->row()->id,
                                        'keterangan' => ucfirst($sheetData[$row]['G'])
                                    ];
                                    $this->db->insert('absensi', $datas);
                                    if($this->db->affected_rows() > 0){
                                        $count++;
                                    }
                                }
                            }
                        }

                        $dataUpdate = [
                            'total_row' => $count
                        ];
                        $this->db->where('id', $logid)->update('log_upload_absensi', $dataUpdate);
                        if($this->db->affected_rows() > 0){
                            $this->session->set_flashdata('success', "File Berhasil Di Upload");
                            redirect($_SERVER['HTTP_REFERER']);
                        }else{
                            unlink($inputFileName);
                            $this->db->where('id', $logid)->delete('log_upload_absensi');
                            $this->session->set_flashdata('error', "File Gagal Di Upload");
                            redirect($_SERVER['HTTP_REFERER']);
                        }
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

    function stringSeperator($string){
        $numbers =array();
        $alpha = array();
        $array = str_split($string);
        for($x = 0; $x< count($array); $x++){
            if(is_numeric($array[$x]))
                array_push($numbers,$array[$x]);
            else
                array_push($alpha,$array[$x]);
        }// end for         
    
        $alpha = implode($alpha);
        $numbers = implode($numbers);
    
        $arr = [
            'cabang' => $alpha,
            'nip' => $numbers
        ];
        return $arr;
    
    }
}