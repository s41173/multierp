<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Koneksi_lib
{
 // Fungsi Untuk Membuka Koneksi Ke Database
 protected function ConnectMysql()
 {
  $server = "localhost"; //server database mysql
  $username = "root"; // username mysql
  $password = ""; // password mysql
  $connection = mysql_connect($server,$username,$password) or die ("Damn, Lost Connection !");
  return $connection;
 }
 // Fungsi Untuk Memilih Database Yang Akan Di gunakan
 private function DataBase()
 {
  $db = "raksanaapp"; // nama database mysql
  $connectdb = mysql_select_db($db) or die (" Where is the fuckin database ? ");
  return $connectdb;
 }
 // Fungsi Untuk Menutup Koneksi Dari Database
 function CloseLink()
 {
  $tutup = mysql_close($this->ConnectMysql()) or die (" Gak Bisa Di tutup koneksi nya ");
  return $tutup;
 }
 // Fungsi Membuka Koneksi Dan Memilih Database
 function OpenLink()
 {
  $this->ConnectMysql();
  $this->DataBase();
 }
}
 
//   $connect = new ConnectToDatabase('localhost','root','xgian','db_smk4');    

/* End of file Property.php */