<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .main-card { margin-top: 30px; margin-bottom: 50px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">SIKAD Kampus</a>
        <div class="d-flex">
            <span class="navbar-text me-3 text-white">Halo, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container main-card">
    <h2 class="mb-4 text-center">Manajemen Data Akademik</h2>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="mhs-tab" data-bs-toggle="tab" data-bs-target="#mhs" type="button">Data Mahasiswa</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="dosen-tab" data-bs-toggle="tab" data-bs-target="#dosen" type="button">Data Dosen (Tugas 1)</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="matkul-tab" data-bs-toggle="tab" data-bs-target="#matkul" type="button">Data Matkul (Tugas 2)</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="jadwal-tab" data-bs-toggle="tab" data-bs-target="#jadwal" type="button">Data Jadwal (Tugas 3)</button>
        </li>
    </ul>

    <div class="tab-content card p-4 border-top-0 shadow-sm bg-white" id="myTabContent">
        
        <div class="tab-pane fade show active" id="mhs">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Daftar Mahasiswa (Bawaan)</h5>
                <button class="btn btn-primary btn-sm" disabled>Tambah Mahasiswa</button>
            </div>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th><th>NIM</th><th>Nama</th><th>Prodi</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelMahasiswa"></tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="dosen">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Master Data Dosen</h5>
                <button class="btn btn-success btn-sm" onclick="tambahDosen()">Tambah Data Dosen</button>
            </div>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-success">
                    <tr>
                        <th>ID</th><th>Nama Dosen</th><th>Alamat</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelDosen"></tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="matkul">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Master Data Mata Kuliah</h5>
                <button class="btn btn-info btn-sm text-white" onclick="tambahMatkul()">Tambah Matkul</button>
            </div>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-info">
                    <tr>
                        <th>ID</th><th>Mata Kuliah</th><th>SKS</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelMatkul"></tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="jadwal">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Jadwal Kuliah Aktif</h5>
                <button class="btn btn-warning btn-sm text-white" onclick="tambahJadwal()">Tambah Jadwal</button>
            </div>
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-warning">
                    <tr>
                        <th>ID</th><th>Dosen Pengampu</th><th>Mata Kuliah</th><th>Waktu</th><th>Ruangan</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelJadwal"></tbody>
            </table>
        </div>

    </div>
</div>

<div class="modal fade" id="mahasiswaModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog"></div></div>

<div class="modal fade" id="modalDosen" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalDosenTitle">Tambah Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formDosen">
                <div class="modal-body">
                    <input type="hidden" id="dosen_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Dosen</label>
                        <input type="text" class="form-control" id="dosen_nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" id="dosen_alamat" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMatkul" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalMatkulTitle">Tambah Mata Kuliah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formMatkul">
                <div class="modal-body">
                    <input type="hidden" id="matkul_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="matkul_nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah SKS</label>
                        <input type="number" class="form-control" id="matkul_sks" min="1" max="6" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalJadwal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="modalJadwalTitle">Buat Jadwal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formJadwal">
                <div class="modal-body">
                    <input type="hidden" id="jadwal_id">
                    <div class="mb-3">
                        <label class="form-label">Dosen Pengampu</label>
                        <select class="form-select" id="jadwal_dosen" required></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mata Kuliah</label>
                        <select class="form-select" id="jadwal_matkul" required></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Waktu</label>
                        <input type="text" class="form-control" id="jadwal_waktu" placeholder="Contoh: Senin, 08:00 - 10:30" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruangan Kelas</label>
                        <input type="text" class="form-control" id="jadwal_ruang" placeholder="Contoh: V.402" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning text-white">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html>