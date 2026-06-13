<?php
session_start();
header('Content-Type: application/json');
include 'koneksi.php';

// Proteksi API Sesuai Standar Dosen Anda
if (!isset($_SESSION['login'])) {
    echo json_encode(['status' => 'error', 'message' => 'Akses ilegal terdeteksi. Silakan login.']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

// ==========================================
// 1. DATA MAHASISWA (Bawaan Modul Dosen)
// ==========================================
if ($action == 'list_mahasiswa') {
    $query = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

// ==========================================
// 2. CRUD DATA DOSEN (TUGAS 1)
// ==========================================
if ($action == 'list_dosen') {
    $query = mysqli_query($conn, "SELECT * FROM dosen ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if ($action == 'get_dosen') {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM dosen WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
}

if ($action == 'save_dosen') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];

    if (empty($id)) {
        $query = mysqli_query($conn, "INSERT INTO dosen (nama, alamat) VALUES ('$nama', '$alamat')");
    } else {
        $query = mysqli_query($conn, "UPDATE dosen SET nama = '$nama', alamat = '$alamat' WHERE id = '$id'");
    }

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => 'Data dosen berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    }
}

if ($action == 'delete_dosen') {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "DELETE FROM dosen WHERE id = '$id'");
    if ($query) {
        echo json_encode(['status' => 'success', 'message' => 'Data dosen berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }
}

// ==========================================
// 3. CRUD DATA MATKUL (TUGAS 2)
// ==========================================
if ($action == 'list_matkul') {
    $query = mysqli_query($conn, "SELECT * FROM matkul ORDER BY id DESC");
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if ($action == 'get_matkul') {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM matkul WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
}

if ($action == 'save_matkul') {
    $id = $_POST['id'];
    $matkul = $_POST['matkul'];
    $sks = $_POST['sks'];

    if (empty($id)) {
        $query = mysqli_query($conn, "INSERT INTO matkul (matkul, sks) VALUES ('$matkul', '$sks')");
    } else {
        $query = mysqli_query($conn, "UPDATE matkul SET matkul = '$matkul', sks = '$sks' WHERE id = '$id'");
    }

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => 'Data matkul berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    }
}

if ($action == 'delete_matkul') {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "DELETE FROM matkul WHERE id = '$id'");
    if ($query) {
        echo json_encode(['status' => 'success', 'message' => 'Data matkul berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }
}

// ==========================================
// 4. CRUD DATA JADWAL (TUGAS 3)
// ==========================================
if ($action == 'list_jadwal') {
    $sql = "SELECT jadwal.*, dosen.nama AS nama_dosen, matkul.matkul AS nama_matkul 
            FROM jadwal 
            JOIN dosen ON jadwal.id_dosen = dosen.id 
            JOIN matkul ON jadwal.id_matkul = matkul.id 
            ORDER BY jadwal.id DESC";
    $query = mysqli_query($conn, $sql);
    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

if ($action == 'get_jadwal') {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM jadwal WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
}

if ($action == 'save_jadwal') {
    $id = $_POST['id'];
    $id_dosen = $_POST['id_dosen'];
    $id_matkul = $_POST['id_matkul'];
    $waktu = $_POST['waktu'];
    $ruang = $_POST['ruang'];

    if (empty($id)) {
        $query = mysqli_query($conn, "INSERT INTO jadwal (id_dosen, id_matkul, waktu, ruang) VALUES ('$id_dosen', '$id_matkul', '$waktu', '$ruang')");
    } else {
        $query = mysqli_query($conn, "UPDATE jadwal SET id_dosen = '$id_dosen', id_matkul = '$id_matkul', waktu = '$waktu', ruang = '$ruang' WHERE id = '$id'");
    }

    if ($query) {
        echo json_encode(['status' => 'success', 'message' => 'Data jadwal berhasil disimpan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
    }
}

if ($action == 'delete_jadwal') {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "DELETE FROM jadwal WHERE id = '$id'");
    if ($query) {
        echo json_encode(['status' => 'success', 'message' => 'Data jadwal berhasil dihapus']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }
}
?>