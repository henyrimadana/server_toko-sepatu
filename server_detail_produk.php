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
    $id_detail = $data->id_detail;
    $ukuran = $data->ukuran;
    $bahan = $data->bahan;
    $warna = $data->warna;
    $merk = $data->merk;

    $aksi = $data->aksi;
    if ($aksi == 'tambah') {
        $data2 = array(
            'id_detail' => $id_detail,
            'ukuran' => $ukuran,
            'bahan' => $bahan,
            'warna' => $warna,
            'merk' => $merk,

        );
        $abc->tambah_detail_produk($data2);
    } elseif ($aksi == 'ubah') {
        $data2 = array(
            'id_detail' => $id_detail,
            'ukuran' => $ukuran,
            'bahan' => $bahan,
            'warna' => $warna,
            'merk' => $merk,
        );
        $abc->ubah_detail_produk($data2);
    } elseif ($aksi == 'hapus') {
        $abc->hapus_detail_produk($id_detail);
    }

    unset($postdata, $data, $data2, $id_detail, $ukuran, $bahan, $warna, $merk, $aksi, $abc);
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (($_GET['aksi'] == 'tampil') and (isset($_GET['id_detail']))) {
        $id_detail = filter($_GET['id_detail']);
        $data = $abc->tampil_detail_produk($id_detail);
        echo json_encode($data);
    } else {
        $data = $abc->tampil_semua_detail();
        echo json_encode($data);
    }
    unset($postdata, $data, $id_detail, $abc);
}
?>