<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cetak extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('m_general');
	}
	
	
	public function pdf($attenders_id="")
    {
		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8', 
			'format' => 'A4-L',
			'margin_left' => 0,
			'margin_right' => 0,
			'margin_top' => 0,
			'margin_bottom' => 0,
			'margin_header' => 0,
			'margin_footer' => 0,
		]); //L For Landscape , P for Portrait
		$mpdf->SetTitle("Certificate");
		
		if($attenders_id==""){
			$data['tbl_attenders'] = $this->db->query("select * from tbl_attenders order by attenders_name ASC");
			$halaman = $this->load->view('v_cetak',$data,true);
		}else{
			$data['attenders'] = $this->db->query("select * from tbl_attenders where attenders_id='$attenders_id'")->row();
			$halaman = $this->load->view('v_cetak_id',$data,true);
		}
		$mpdf->SetDefaultBodyCSS('background', "url('./assets/sertifikat.jpg')");
		$mpdf->SetDefaultBodyCSS('background-image-resize', 6);
		$mpdf->WriteHTML($halaman);
		$mpdf->Output();
    }
	
	public function one($attenders_id="")
    {
			$mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8', 
				'format' => 'A4-L',
				'margin_left' => 0,
				'margin_right' => 0,
				'margin_top' => 0,
				'margin_bottom' => 0,
				'margin_header' => 0,
				'margin_footer' => 0,
			]); //L For Landscape , P for Portrait
			
			$data['attenders'] = $this->db->query("select * from tbl_attenders where attenders_id='$attenders_id'")->row();
			$attenders_name = $data['attenders']->attenders_name.".pdf";
			$halaman = $this->load->view('v_cetak_id',$data,true);
			$mpdf->SetDefaultBodyCSS('background', "url('./assets/sertifikat.jpg')");
			$mpdf->SetDefaultBodyCSS('background-image-resize', 6);
			$mpdf->SetTitle("$attenders_name");
			$mpdf->WriteHTML($halaman);
			$mpdf->Output("$attenders_name", 'D');
    }
	
	public function all()
    {
		$tbl_attenders = $this->db->query("select * from tbl_attenders order by attenders_name ASC")->result();
		foreach($tbl_attenders as $attenders){
			$mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8', 
				'format' => 'A4-L',
				'margin_left' => 0,
				'margin_right' => 0,
				'margin_top' => 0,
				'margin_bottom' => 0,
				'margin_header' => 0,
				'margin_footer' => 0,
			]); //L For Landscape , P for Portrait
			
			$data['attenders'] = $this->db->query("select * from tbl_attenders where attenders_id='$attenders->attenders_id'")->row();
			$attenders_name = $data['attenders']->attenders_name;
			$halaman = $this->load->view('v_cetak_id',$data,true);
			$mpdf->SetDefaultBodyCSS('background', "url('./assets/sertifikat.jpg')");
			$mpdf->SetDefaultBodyCSS('background-image-resize', 6);
			$mpdf->SetTitle("$attenders_name");
			$mpdf->WriteHTML($halaman);
			$mpdf->Output("D:/pdf/".$attenders_name.".pdf", 'F');
		}
    }
	
	public function allpanitia()
    {
		$tbl_attenders = $this->db->query("select * from tbl_attenders_panitia order by attenders_name ASC")->result();
		foreach($tbl_attenders as $attenders){
			$mpdf = new \Mpdf\Mpdf([
				'mode' => 'utf-8', 
				'format' => 'A4-L',
				'margin_left' => 0,
				'margin_right' => 0,
				'margin_top' => 0,
				'margin_bottom' => 0,
				'margin_header' => 0,
				'margin_footer' => 0,
			]); //L For Landscape , P for Portrait
			
			$data['attenders'] = $this->db->query("select * from tbl_attenders_panitia where attenders_id='$attenders->attenders_id'")->row();
			$attenders_name = $data['attenders']->attenders_name;
			$halaman = $this->load->view('v_cetak_id',$data,true);
			$mpdf->SetDefaultBodyCSS('background', "url('./assets/sertifikat-panitia.jpg')");
			$mpdf->SetDefaultBodyCSS('background-image-resize', 6);
			$mpdf->SetTitle("$attenders_name");
			$mpdf->WriteHTML($halaman);
			$mpdf->Output("D:/pdf/".$attenders_name.".pdf", 'F');
		}
    }
}