// Memanggil fungsi loadData pertama kali saat halaman selesai dimuat seluruhnya
document.addEventListener('DOMContentLoaded', () => {
    loadMahasiswa();
    loadDosen();
    loadMatkul();
    loadJadwal();
});

// Inisialisasi Bootstrap Modal Object dari index.php supaya bisa ditutup/buka via JS
// Sesuai dengan screenshot Langkah 6 dosen Anda
const mhsModal = new bootstrap.Modal(document.getElementById('mahasiswaModal'));
const dosenModal = new bootstrap.Modal(document.getElementById('modalDosen'));
const matkulModal = new bootstrap.Modal(document.getElementById('modalMatkul'));
const jadwalModal = new bootstrap.Modal(document.getElementById('modalJadwal'));

// ==========================================
// 1. FUNGSI CRUD MAHASISWA (Asli Bawaan Dosen)
// ==========================================
function loadMahasiswa() {
    fetch('api.php?action=list_mahasiswa')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="5" class="text-center text-muted">Data Mahasiswa kosong</td></tr>`;
            } else {
                data.forEach((mhs, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${mhs.nim}</td>
                            <td>${mhs.nama}</td>
                            <td>${mhs.prodi}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editMahasiswa(${mhs.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteMahasiswa(${mhs.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tabelMahasiswa').innerHTML = html;
        });
}

// ==========================================
// 2. FUNGSI CRUD DOSEN (TUGAS 1)
// ==========================================
function loadDosen() {
    fetch('api.php?action=list_dosen')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="4" class="text-center text-muted">Belum ada data dosen.</td></tr>`;
            } else {
                data.forEach(dosen => {
                    html += `
                        <tr>
                            <td>${dosen.id}</td>
                            <td>${dosen.nama}</td>
                            <td>${dosen.alamat}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editDosen(${dosen.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteDosen(${dosen.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tabelDosen').innerHTML = html;
        });
}

function tambahDosen() {
    document.getElementById('formDosen').reset();
    document.getElementById('dosen_id').value = '';
    document.getElementById('modalDosenTitle').innerText = 'Tambah Dosen';
    dosenModal.show();
}

document.getElementById('formDosen').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('id', document.getElementById('dosen_id').value);
    formData.append('nama', document.getElementById('dosen_nama').value);
    formData.append('alamat', document.getElementById('dosen_alamat').value);

    fetch('api.php?action=save_dosen', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        alert(res.message);
        if (res.status === 'success') {
            dosenModal.hide();
            loadDosen();
            loadJadwal(); // Update dropdown jadwal jika ada perubahan master data
        }
    });
});

function editDosen(id) {
    fetch(`api.php?action=get_dosen&id=${id}`)
        .then(response => response.json())
        .then(dosen => {
            document.getElementById('dosen_id').value = dosen.id;
            document.getElementById('dosen_nama').value = dosen.nama;
            document.getElementById('dosen_alamat').value = dosen.alamat;
            document.getElementById('modalDosenTitle').innerText = 'Edit Data Dosen';
            dosenModal.show();
        });
}

function deleteDosen(id) {
    if (confirm('Hapus data dosen ini?')) {
        fetch(`api.php?action=delete_dosen&id=${id}`)
            .then(response => response.json())
            .then(res => {
                alert(res.message);
                loadDosen();
                loadJadwal();
            });
    }
}

// ==========================================
// 3. FUNGSI CRUD MATKUL (TUGAS 2)
// ==========================================
function loadMatkul() {
    fetch('api.php?action=list_matkul')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="4" class="text-center text-muted">Belum ada data mata kuliah.</td></tr>`;
            } else {
                data.forEach(matkul => {
                    html += `
                        <tr>
                            <td>${matkul.id}</td>
                            <td>${matkul.matkul}</td>
                            <td>${matkul.sks} SKS</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editMatkul(${matkul.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteMatkul(${matkul.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tabelMatkul').innerHTML = html;
        });
}

function tambahMatkul() {
    document.getElementById('formMatkul').reset();
    document.getElementById('matkul_id').value = '';
    document.getElementById('modalMatkulTitle').innerText = 'Tambah Mata Kuliah';
    matkulModal.show();
}

document.getElementById('formMatkul').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('id', document.getElementById('matkul_id').value);
    formData.append('matkul', document.getElementById('matkul_nama').value);
    formData.append('sks', document.getElementById('matkul_sks').value);

    fetch('api.php?action=save_matkul', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        alert(res.message);
        if (res.status === 'success') {
            matkulModal.hide();
            loadMatkul();
            loadJadwal();
        }
    });
});

function editMatkul(id) {
    fetch(`api.php?action=get_matkul&id=${id}`)
        .then(response => response.json())
        .then(matkul => {
            document.getElementById('matkul_id').value = matkul.id;
            document.getElementById('matkul_nama').value = matkul.matkul;
            document.getElementById('matkul_sks').value = matkul.sks;
            document.getElementById('modalMatkulTitle').innerText = 'Edit Mata Kuliah';
            matkulModal.show();
        });
}

function deleteMatkul(id) {
    if (confirm('Hapus mata kuliah ini?')) {
        fetch(`api.php?action=delete_matkul&id=${id}`)
            .then(response => response.json())
            .then(res => {
                alert(res.message);
                loadMatkul();
                loadJadwal();
            });
    }
}

// ==========================================
// 4. FUNGSI CRUD JADWAL (TUGAS 3)
// ==========================================
function loadJadwal() {
    fetch('api.php?action=list_jadwal')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="6" class="text-center text-muted">Belum ada jadwal kuliah yang diatur.</td></tr>`;
            } else {
                data.forEach(jadwal => {
                    html += `
                        <tr>
                            <td>${jadwal.id}</td>
                            <td>${jadwal.nama_dosen}</td>
                            <td>${jadwal.nama_matkul}</td>
                            <td>${jadwal.waktu}</td>
                            <td>${jadwal.ruang}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editJadwal(${jadwal.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteJadwal(${jadwal.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tabelJadwal').innerHTML = html;
        });
        
    // Sekaligus me-refresh isi opsi pilihan Dosen & Matkul di dalam form input jadwal
    refreshDropdownJadwal();
}

function refreshDropdownJadwal() {
    // Ambil Data Dosen untuk Pilihan di Form Jadwal
    fetch('api.php?action=list_dosen')
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Pilih Dosen Pengampu --</option>';
            data.forEach(d => { options += `<option value="${d.id}">${d.nama}</option>`; });
            document.getElementById('jadwal_dosen').innerHTML = options;
        });

    // Ambil Data Matkul untuk Pilihan di Form Jadwal
    fetch('api.php?action=list_matkul')
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Pilih Mata Kuliah --</option>';
            data.forEach(m => { options += `<option value="${m.id}">${m.matkul}</option>`; });
            document.getElementById('jadwal_matkul').innerHTML = options;
        });
}

function tambahJadwal() {
    document.getElementById('formJadwal').reset();
    document.getElementById('jadwal_id').value = '';
    document.getElementById('modalJadwalTitle').innerText = 'Buat Jadwal Baru';
    jadwalModal.show();
}

document.getElementById('formJadwal').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('id', document.getElementById('jadwal_id').value);
    formData.append('id_dosen', document.getElementById('jadwal_dosen').value);
    formData.append('id_matkul', document.getElementById('jadwal_matkul').value);
    formData.append('waktu', document.getElementById('jadwal_waktu').value);
    formData.append('ruang', document.getElementById('jadwal_ruang').value);

    fetch('api.php?action=save_jadwal', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        alert(res.message);
        if (res.status === 'success') {
            jadwalModal.hide();
            loadJadwal();
        }
    });
});

function editJadwal(id) {
    fetch(`api.php?action=get_jadwal&id=${id}`)
        .then(response => response.json())
        .then(jadwal => {
            document.getElementById('jadwal_id').value = jadwal.id;
            document.getElementById('jadwal_dosen').value = jadwal.id_dosen;
            document.getElementById('jadwal_matkul').value = jadwal.id_matkul;
            document.getElementById('jadwal_waktu').value = jadwal.waktu;
            document.getElementById('jadwal_ruang').value = jadwal.ruang;
            document.getElementById('modalJadwalTitle').innerText = 'Ubah Jadwal Kuliah';
            jadwalModal.show();
        });
}

function deleteJadwal(id) {
    if (confirm('Hapus data jadwal kuliah ini?')) {
        fetch(`api.php?action=delete_jadwal&id=${id}`)
            .then(response => response.json())
            .then(res => {
                alert(res.message);
                loadJadwal();
            });
    }
}