<?php
error_reporting(1); //error ditampilkan

class Database
{
   private $host = "localhost";
   private $dbname = "tokosepatu";
   private $user = "root";
   private $password = "";
   private $port = "3306";
   private $conn;

   //function yang pertama kali di-load saat class dipanggil
   public function __construct()
   {
      // koneksi database
      try {
         $this->conn = new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname;charset=utf8", $this->user, $this->password);
         $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
         echo "Koneksi Gagal";
         exit(); // keluar dari skrip jika koneksi gagal
      }
   }

   public function tampil_semua_produk()
   {
      $query = $this->conn->prepare("SELECT p.*, d.*, k.* FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori JOIN detail_produk d ON p.id_detail = d.id_detail ORDER BY p.id_produk");
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
   }


   public function tampil_semua_kategori()
   {
      $query = $this->conn->prepare("SELECT id_kategori, nama_kategori FROM kategori order by id_kategori");
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
   }

   public function tampil_semua_pelanggan()
   {
      $query = $this->conn->prepare("SELECT id_pelanggan, nama, alamat, no_hp, email, username, password, role FROM pelanggan order by id_pelanggan");
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
      // $query->closeCursor();
      // unset($data);
   }

   public function tampil_semua_detail()
   {
      $query = $this->conn->prepare("SELECT id_detail, ukuran, bahan, warna, merk FROM detail_produk order by id_detail");
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
   }

   public function tampil_semua_transaksi()
   {
      $query = $this->conn->prepare("SELECT t.id_transaksi, p.nama_produk, l.nama, t.tanggal, t.jumlah FROM transaksi t JOIN produk p ON t.id_produk=p.id_produk JOIN pelanggan l ON t.id_pelanggan=l.id_pelanggan ORDER BY id_transaksi");
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
      // $query->closeCursor();
      // unset($data);
   }

   //  FUNGSI UNTUK TAMPIL
   public function tampil_produk($id_produk)
   {
      $query = $this->conn->prepare("SELECT p.*, d.*, k.* FROM produk p JOIN kategori k ON p.id_kategori = k.id_kategori JOIN detail_produk d ON p.id_detail = d.id_detail WHERE p.id_produk=?");
      $query->execute(array($id_produk));
      $data = $query->fetch(PDO::FETCH_ASSOC);
      return $data;
   }


   public function tampil_kategori($id_kategori)
   {
      $query = $this->conn->prepare("SELECT id_kategori, nama_kategori FROM kategori WHERE id_kategori=?");
      $query->execute(array($id_kategori));
      $data = $query->fetch(PDO::FETCH_ASSOC);
      return $data;
      // $query->closeCursor();
      // unset($id_produk, $data);
   }

   public function tampil_pelanggan($id_pelanggan)
   {
      $query = $this->conn->prepare("SELECT id_pelanggan, nama, alamat, no_hp, email, username, password, role FROM pelanggan WHERE id_pelanggan=?");
      $query->execute(array($id_pelanggan));
      $data = $query->fetch(PDO::FETCH_ASSOC);
      return $data;
      // $query->closeCursor();
      // unset($id_pelanggan, $data);
   }

   public function tampil_username($username)
   {
      $query = $this->conn->prepare("SELECT id_pelanggan, nama, alamat, no_hp, email, username, password, role FROM pelanggan WHERE username=?");
      $query->execute(array($username));
      $data = $query->fetch(PDO::FETCH_ASSOC);
      return $data;
      // $query->closeCursor();
      // unset($id_pelanggan, $data);
   }

   public function tampil_detail_produk($id_detail)
   {
      // tambahin ini lo hen
      $query = $this->conn->prepare("SELECT id_detail, ukuran, bahan, warna, merk FROM detail_produk WHERE id_detail=?");
      $query->execute(array($id_detail));
      $data = $query->fetch(PDO::FETCH_ASSOC);
      return $data;
      //hapus variable dari memory
      // $query->closeCursor();
      // unset($id_detail, $data);
   }

   public function tampil_transaksi($id_transaksi)
   {
      $query = $this->conn->prepare("SELECT id_transaksi, id_produk, id_pelanggan, tanggal, jumlah FROM transaksi WHERE id_transaksi=?");
      $query->execute(array($id_transaksi));
      $data = $query->fetch(PDO::FETCH_ASSOC);
      return $data;
      // $query->closeCursor();
      // unset($id_transaksi, $data);
   }




   // FUNGSI UNTUK TAMBAH DATA PADA TABEL 
   public function tambah_produk($data)
   {

      $query = $this->conn->prepare("INSERT IGNORE INTO produk (id_produk, image, nama_produk, harga, stok, id_detail, id_kategori) VALUES (?,?,?,?,?,?,?)");
      $query->execute(array($data['id_produk'], $data['image'], $data['nama_produk'], $data['harga'], $data['stok'], $data['id_detail'], $data['id_kategori']));
      $query->closeCursor();
      unset($data);
   }

   public function tambah_kategori($data)
   {
      $query = $this->conn->prepare("INSERT IGNORE INTO kategori (id_kategori, nama_kategori) VALUES (?,?)");
      $query->execute(array($data['id_kategori'], $data['nama_kategori']));
      // $query->closeCursor();
      // unset($data);
   }

   public function tambah_pelanggan($data)
   {
      $query = $this->conn->prepare("INSERT IGNORE INTO pelanggan (id_pelanggan, nama, alamat, no_hp, email, username, password) VALUES (?,?,?,?,?,?,?)");
      $query->execute(array($data['id_pelanggan'], $data['nama'], $data['alamat'], $data['no_hp'], $data['email'], $data['username'], $data['password']));
      // $query->closeCursor();
      // unset($data);
   }

   public function tambah_detail_produk($data)
   {
      $query = $this->conn->prepare("INSERT IGNORE INTO detail_produk (id_detail, ukuran, bahan, warna, merk) VALUES (?,?,?,?,?)");
      $query->execute(array($data['id_detail'], $data['ukuran'], $data['bahan'], $data['warna'], $data['merk']));
      $query->closeCursor();
      unset($data);
   }

   public function tambah_transaksi($data)
   {
      $query = $this->conn->prepare("INSERT IGNORE INTO transaksi (id_transaksi, id_produk, id_pelanggan, tanggal, jumlah) VALUES (?,?,?,?,?)");
      $query->execute(array($data['id_transaksi'], $data['id_produk'], $data['id_pelanggan'], $data['tanggal'], $data['jumlah']));
      $query->closeCursor();
      unset($data);
   }


   // FUNGSI UNTUK UBAH DATA PADA TABEL



   public function ubah_produk($data)
   {
      $query = $this->conn->prepare("UPDATE produk SET image=?, nama_produk=?, harga=?, stok=?, id_detail=?, id_kategori=? WHERE id_produk=?");
      $query->execute(array($data['image'], $data['nama_produk'], $data['harga'], $data['stok'], $data['id_detail'], $data['id_kategori'], $data['id_produk']));
      $query->closeCursor();
      unset($data);
   }

   public function ubah_kategori($data)
   {
      $query = $this->conn->prepare("UPDATE kategori set nama_kategori=? WHERE id_kategori=?");
      $query->execute(array($data['nama_kategori'], $data['id_kategori']));
      $query->closeCursor();
      unset($data);
   }

   public function ubah_pelanggan($data)
   {
      $query = $this->conn->prepare("UPDATE pelanggan set nama=?, alamat=?, no_hp=?, email=?, username=?, password=? WHERE id_pelanggan=?");
      $query->execute(array($data['nama'], $data['alamat'], $data['no_hp'], $data['email'], $data['username'], $data['password'], $data['id_pelanggan']));
      $query->closeCursor();
      unset($data);
   }

   public function ubah_detail_produk($data)
   {
      $query = $this->conn->prepare("UPDATE detail_produk set ukuran=?, bahan=?, warna=?, merk=? WHERE id_detail=?");
      $query->execute(array($data['ukuran'], $data['bahan'], $data['warna'], $data['merk'], $data['id_detail']));
      $query->closeCursor();
      unset($data);
   }

   public function ubah_transaksi($data)
   {
      $query = $this->conn->prepare("UPDATE transaksi set id_produk=?, id_pelanggan=?, tanggal=?, jumlah=? WHERE id_transaksi=?");
      $query->execute(array($data['id_produk'], $data['id_pelanggan'], $data['tanggal'], $data['jumlah'], $data['id_transaksi']));

      $query->closeCursor();
      unset($data);
   }

   //   FUNGSI UNTUK HAPUS DATA PADA TABEL
   public function hapus_produk($id_produk)
   {
      $query = $this->conn->prepare("DELETE FROM produk WHERE id_produk=?;
      
      SET @max_id_produk = (SELECT MAX(id_produk) FROM produk);
      
      SET @sql = CONCAT('ALTER TABLE produk AUTO_INCREMENT = ', @max_id_produk + 1);
      
      PREPARE stmt FROM @sql;
      EXECUTE stmt;
      DEALLOCATE PREPARE stmt;
      ");
      $query->execute(array($id_produk));
      $query->closeCursor();
      unset($id_produk);
   }

   public function hapus_kategori($id_kategori)
   {
      $query = $this->conn->prepare("DELETE FROM kategori WHERE id_kategori=?;
      
      SET @max_id = (SELECT MAX(id_kategori) FROM kategori);
      
      SET @sql = CONCAT('ALTER TABLE kategori AUTO_INCREMENT = ', @max_id + 1);
      
      PREPARE stmt FROM @sql;
      EXECUTE stmt;
      DEALLOCATE PREPARE stmt;
      ");
      $query->execute(array($id_kategori));
      $query->closeCursor();
      unset($id_kategori);
   }

   public function hapus_pelanggan($id_pelanggan)
   {
      $query = $this->conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan=?;
      
      SET @max_id = (SELECT MAX(id_pelanggan) FROM pelanggan);
      
      SET @sql = CONCAT('ALTER TABLE pelanggan AUTO_INCREMENT = ', @max_id + 1);
      
      PREPARE stmt FROM @sql;
      EXECUTE stmt;
      DEALLOCATE PREPARE stmt;
      ");
      $query->execute(array($id_pelanggan));
      $query->closeCursor();
      unset($id_pelanggan);
   }

   public function hapus_detail_produk($id_detail)
   {
      $query = $this->conn->prepare("DELETE FROM detail_produk WHERE id_detail=?;
      
      SET @max_id = (SELECT MAX(id_detail) FROM detail_produk);
      
      SET @sql = CONCAT('ALTER TABLE detail_produk AUTO_INCREMENT = ', @max_id + 1);
      
      PREPARE stmt FROM @sql;
      EXECUTE stmt;
      DEALLOCATE PREPARE stmt;
      ");
      $query->execute(array($id_detail));
      $query->closeCursor();
      unset($id_detail);
   }

   public function hapus_transaksi($id_transaksi)
   {
      $query = $this->conn->prepare("DELETE FROM transaksi WHERE id_transaksi=?;
      
      SET @max_id = (SELECT MAX(id_transaksi) FROM transaksi);
      
      SET @sql = CONCAT('ALTER TABLE transaksi AUTO_INCREMENT = ', @max_id + 1);
      
      PREPARE stmt FROM @sql;
      EXECUTE stmt;
      DEALLOCATE PREPARE stmt;
      ");
      $query->execute(array($id_transaksi));
      $query->closeCursor();
      unset($id_transaksi);
   }

   public function ambil_semua_kategori()
   {
      $query = $this->conn->prepare("SELECT id_kategori, nama_kategori FROM kategori ORDER BY id_kategori");
      $query->execute();
      $data = $query->fetchAll(PDO::FETCH_ASSOC);
      return $data;
   }
}

