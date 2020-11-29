<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_general extends CI_Model {
		
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	//Menghitung Jumlah Data seluruh isi tabel
	public function countdataall($table){
		$total = $this->db->get($table)->num_rows();
		return $total;
	}
	
	//Menghitung Jumlah Data dengan kondisi
	public function countdata($table, $where_array){
		$this->db->where($where_array);
		$total = $this->db->get($table)->num_rows();
		return $total;
	}
	
	//Membaca ID Terakhir dan Menambahkan 1 angka
	public function bacaidterakhir($table, $id){
		$query = $this->db->query("select $id+1 as $id from $table order by $id DESC LIMIT 1");
		if($query->num_rows() > 0){
			$query2 = $query->row();
			$idnya = $query2->$id;
		}else{
			$idnya = "1";
		}
		
		return $idnya;
	}
	
	//Melihat semua isi Tabel yang dipilih tanpa order
	public function view($table){
		return $this->db->get($table)->result();
	}
	
	//Melihat satu baris isi Tabel yang dipilih dengan kondisi dan tanpa order
	public function view_by($table, $where){
		$this->db->where($where);
		return $this->db->get($table)->row();
	}	
	
	//Melihat semua isi Tabel yang dipilih dengan kondisi dan order
	public function view_where($table, $where, $order =""){
		$this->db->where($where);
		$this->db->order_by($order);
		return $this->db->get($table)->result();
	}
	
	//Melihat semua isi Tabel yang dipilih dengan order tanpa kondisi
	public function view_order($table, $order =""){
		$this->db->order_by($order);
		return $this->db->get($table)->result();
	}
	
	//Melihat semua isi Tabel yang dipilih dengan order tanpa kondisi dan dengan limit
	public function view_order_limit($table, $order ="", $limit = ""){
		$this->db->order_by($order);
		$this->db->limit($limit);
		return $this->db->get($table)->result();
	}
	
	//Melihat semua isi Tabel yang dipilih dengan order dengan kondisi dan dengan limit
	public function view_where_order_limit($table, $where="", $order ="", $limit = ""){
		$this->db->where($where);
		$this->db->order_by($order);
		$this->db->limit($limit);
		return $this->db->get($table)->result();
	}
	
	//Melihat semua isi Tabel yang dipilih dengan order dengan grouping
	public function view_order_group_by($table, $order ="", $group_by = ""){
		$this->db->order_by($order);
		$this->db->group_by($group_by);
		return $this->db->get($table)->result();
	}
	
	//Melihat satu baris isi Tabel yang dijoinkan dengan Tabel Lain tanpa order
	public function view_join1_by($table1, $table2, $where, $field1){
		$this->db->join($table2,"$table1.$field1=$table2.$field1");
		$this->db->where($where);
		return $this->db->get($table1)->row();
	}
	
	//Melihat semua isi Tabel yang dijoinkan dengan Tabel Lain dengan order
	public function view_join1_order($table1, $table2, $order, $field1, $group_by){
		$this->db->join($table2,"$table1.$field1=$table2.$field1");
		$this->db->order_by($order);
		$this->db->group_by($group_by);
		return $this->db->get($table1)->result();
	}
	
	//Melihat semua isi Tabel yang dijoinkan dengan Tabel Lain dengan kondisi dan order
	public function view_join1_order_where($table1, $table2, $field1, $where, $order){
		$this->db->join($table2,"$table1.$field1=$table2.$field1");
		$this->db->where($where);
		$this->db->order_by($order);
		return $this->db->get($table1)->result();
	}
	
	//Menambahkan data ke dalam tabel yang dipilih
	public function add($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}
	
	//Mengedit data dalam tabel yang dipilih dengan kondisi
	function edit($table, $where, $data)
	{
		$this->db->where($where);
		$this->db->update($table, $data); 
		return $this->db->affected_rows();
	}
	
	//Menghapus data dalam tabel yang dipilih dengan kondisi
	function hapus($table, $where)
	{
		foreach ($where as $key => $value) {
			$this->db->where($key, $value);
		}
		$this->db->delete($table);
		return $this->db->affected_rows();
	}
	
	//Fungsi untuk Upload File ke dalam Folder yang dipilih Contoh ./assets/dist/img/
	public function file_upload($file_upload, $uploadPath)
    {
		$files = $file_upload;
		$_FILES['userfile']['name'] = $files['name'];
		$_FILES['userfile']['type'] = $files['type'];
		$_FILES['userfile']['error'] = $files['error'];
		$_FILES['userfile']['tmp_name'] = $files['tmp_name'];
		$_FILES['userfile']['size'] = $files['size'];
		$string = $files['name'];
		
		//Menghapus karakter yang tidak perlu
		$str = substr($string, 0, 39);
		$string_replace = array('\'','"', ';', '[', ']', 
								'{', '}',  '`', '~', '.', ',', ' ',
							   '!', '@', '#', '$', '%', '^', '&', '(', ')',
							   '-', '_', '+', '=');
		$pesan= strtolower(str_replace($string_replace, '',$str));
		$new_name = time().$pesan;
				
		// File upload configuration
		$config=array(); 
		$config['upload_path'] = $uploadPath;
		$config['allowed_types'] = 'avi|mp4|flv|pdf|jpg|jpeg|png|gif';
		$config['max_size'] = '5120'; //in kilobytes = 5MB 
		$config['max_width'] = '1920'; //in px
		$config['max_height'] = '1080'; //in px
		$config['file_name'] = $new_name;
				
		// Load and initialize upload library
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
        
		if ( ! $this->upload->do_upload()){
			$error = array('error' => $this->upload->display_errors());
			$nama_fileupload = "0,".$error;
		}else{
			// Uploaded userfile data
			$nama_fileupload = "1,".$this->upload->data('file_name');
		}
		
		//Menghasilkan nama file untuk digunakan, dengan pendanda komma yang bisa diexplode
		//explode(",",$nama_fileupload);
		//Gunakan explode[1] untuk nama file ata info error dan explode[0] untuk kondisi,
		//Jika 0 maka tampilkan pesan error dan kembali ke halaman upload gambar, jika kondisi 1 maka simpan data ke database
		return $nama_fileupload;
	}
	
	// Fungsi untuk melakukan proses upload file
	public function upload_csv($filename){
		$this->load->library('upload'); // Load librari upload
		
		$config['upload_path'] = './excel/';
		$config['allowed_types'] = 'xls|xlsx|csv';
		$config['max_size']	= '2048';
		$config['overwrite'] = true;
		$config['file_name'] = $filename;
	
		$this->upload->initialize($config); // Load konfigurasi uploadnya
		if($this->upload->do_upload('file')){ // Lakukan upload dan Cek jika proses upload berhasil
			// Jika berhasil :
			$return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
			return $return;
		}else{
			// Jika gagal :
			$return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
			return $return;
		}
	}
	
	//Fungsi untuk Upload File ke dalam Folder yang dipilih Contoh ./assets/dist/img/
	public function imageBase($file_upload)
    {
		$files = $file_upload;
		$check = getimagesize($files["tmp_name"]);
		if($check !== false) {
			$data = base64_encode(file_get_contents( $files["tmp_name"] ));
			return "data:".$check["mime"].";base64,".$data;
		} else {
			return "";
		}
	}
	
	//Fungsi untuk Konversi Tanggal dari YYYY-MM-DD ke dd mmmm yyyy dalam bahasa Indonesia
	public function getTanggalIndo($tanggal){
		if($tanggal!=NULL OR $tanggal!="0000-00-00"){
			$bulan = array ( 1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
			$pecahkan = explode('-', $tanggal); 			
			return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
		}else{
			return "";
		}
	}
	
	//Fungsi untuk Konversi Bulan dari MM menjadi angka romawi
	public function getBulanRomawi($bulan){
		$nama = array ( 1 =>   'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII');
		return $nama[ (int)$bulan ];
		
	}
	
	public function getNomor(){
		$total = $this->db->get("tbl_attenders")->num_rows();
		if($total>0){
			$baca = $this->db->query("select SUBSTRING_INDEX(attenders_number,'/',1) as nomor from tbl_attenders order by attenders_id DESC LIMIT 1")->row();
			return $baca->nomor;
		}else{
			return "000";
		}
	}
	
	public function cetak($attenders_id){
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
}