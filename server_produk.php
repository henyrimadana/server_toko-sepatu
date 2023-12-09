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

    $id_produk = $data->id_produk;
    $image = $data->image;
    $nama_produk = $data->nama_produk;
    $harga = $data->harga;
    $stok = $data->stok;
    $id_detail = $data->id_detail;
    $id_kategori = $data->id_kategori;

    $aksi = $data->aksi;
    if ($aksi == 'tambah') {
        $data2 = array(
            'id_produk' => $id_produk,
            'image' => $image,
            'nama_produk' => $nama_produk,
            'harga' => $harga,
            'stok' => $stok,
            'id_detail' => $id_detail,
            'id_kategori' => $id_kategori
        );
        $abc->tambah_produk($data2);
    } elseif ($aksi == 'ubah') {
        $data2 = array(
            'id_produk' => $id_produk,
            'image' => $image,
            'nama_produk' => $nama_produk,
            'harga' => $harga,
            'stok' => $stok,
            'id_detail' => $id_detail,
            'id_kategori' => $id_kategori
        );
        $abc->ubah_produk($data2);
    } elseif ($aksi == 'hapus') {
        $abc->hapus_produk($id_produk);
    }

    unset($postdata, $data, $data2, $id_produk, $image, $nama_produk, $harga, $stok, $id_detail, $id_kategori, $aksi, $abc);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (($_GET['aksi'] == 'tampil') and (isset($_GET['id_produk']))) {
        $id_produk = filter($_GET['id_produk']);
        $data = $abc->tampil_produk($id_produk);
        echo json_encode($data);
    } else {
        $data = $abc->tampil_semua_produk();
        echo json_encode($data);
    }
    unset($postdata, $data, $id_produk, $abc);
}
?>