<?php
error_reporting(1);
include "Database.php";
$abc = new Database();

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true'); // Perbaiki penulisan 'Access'
    header('Access-Control-Max-Age: 86400');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}
$postdata = file_get_contents("php://input");

function filter($data)
{
    $data = preg_replace('/[^a-zA-Z0-9]/', '', $data);
    return $data;
    // unset($data);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode($postdata);
    $id_transaksi = $data->id_transaksi;
    $id_produk = $data->id_produk;
    $id_pelanggan = $data->id_pelanggan;
    $tanggal = $data->tanggal;
    $jumlah = $data->jumlah;
    $aksi = $data->aksi;
    if ($aksi == 'tambah') {
        $data2 = array(
            'id_transaksi' => $id_transaksi,
            'id_produk' => $id_produk,
            'id_pelanggan' => $id_pelanggan,
            'tanggal' => $tanggal,
            'jumlah' => $jumlah,
        );
        $abc->tambah_transaksi($data2);
    } elseif ($aksi == 'ubah') {
        $data2 = array(
            'id_transaksi' => $id_transaksi,
            'id_produk' => $id_produk,
            'id_pelanggan' => $id_pelanggan,
            'tanggal' => $tanggal,
            'jumlah' => $jumlah,
        );
        $abc->ubah_transaksi($data2);
    } elseif ($aksi == 'hapus') {
        $abc->hapus_transaksi($id_transaksi);
    }

    unset($postdata, $data, $data2, $id_transaksi, $id_produk, $id_pelanggan, $tanggal, $jumlah, $aksi, $abc);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (($_GET['aksi'] == 'tampil') and (isset($_GET['id_transaksi']))) {
        // Kode yang sudah ada untuk menampilkan semua data pelanggan atau berdasarkan ID pelanggan
        $id_transaksi = filter($_GET['id_transaksi']);
        $data = $abc->tampil_transaksi($id_transaksi);
        echo json_encode($data);
    } elseif ($_GET['aksi'] == 'tampil_by_pelanggan' and (isset($_GET['id_pelanggan']))) {
        // Menampilkan informasi username berdasarkan nama pengguna
        $id_pelanggan = filter($_GET['id_pelanggan']);
        $data = $abc->tampil_transaksi_by_pelanggan($id_pelanggan);
        echo json_encode($data);
    } else {
        $data = $abc->tampil_semua_transaksi();
        echo json_encode($data);
    }
    unset($postdata, $data, $id_transaksi, $id_pelanggan, $abc);
}
