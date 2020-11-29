<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Import extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('m_general');
		$this->filename = date("Y-m-d-H-i-s");
	}
	public function index()
    {
		$this->load->view("v_import");	
    }
	
	public function upload()
    {
        // Load plugin PHPExcel nya
		$upload = $this->m_general->upload_csv($this->filename);
		if($upload['result'] == "success"){
			
			include APPPATH.'third_party/PHPExcel/PHPExcel.php';
			$data_upload = $this->upload->data();

            $excelreader     = new PHPExcel_Reader_Excel2007();
            $loadexcel         = $excelreader->load('./excel/'.$data_upload['file_name']); // Load file yang telah diupload ke folder excel
            $sheet             = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
			foreach($sheet as $row){
							$id_terakhir = $this->m_general->bacaidterakhir("tbl_attenders", "attenders_id");
								$data = array(
									'attenders_id' => $id_terakhir,
									'attenders_name'  => $row['A'],
									'attenders_email'  => $row['B'],
								);
								$this->m_general->add("tbl_attenders", $data);
                    }
           
            $this->session->set_flashdata('notif', '<div class="alert alert-success"><b>PROSES IMPORT BERHASIL!</b> Data berhasil diimport!</div>');
            redirect('import/');

        } else {
			$this->session->set_flashdata('notif', '<div class="alert alert-danger"><b>PROSES IMPORT GAGAL!</b> '.$this->upload->display_errors().'</div>');
            redirect('import/');

        }
    }
}