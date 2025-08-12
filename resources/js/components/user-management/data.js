import gridjs from 'gridjs/dist/gridjs.umd.js';

// Pastikan API URL tersedia
const apiUrl = window.APP_API_URL + "/user/user-list";
const token = window.APP_BEARER_TOKEN;

function actionButtons(id, name) {
  return gridjs.html(`
    <button class="btn btn-sm btn-primary me-1" onclick="editUser(${id})">Edit</button>
    <button class="btn btn-sm btn-danger" onclick="deleteUser(${id}, '${name.replace(/'/g, "\\'")}')">Hapus</button>
  `);
}

fetch(apiUrl, {
  headers: {
    "Authorization": `Bearer ${token}`,
    "Accept": "application/json",
  },
})
.then((response) => {
  if (!response.ok) throw new Error("Gagal mengambil data");
  return response.json();
})
.then((data) => {
  const users = data.data || data;

  new gridjs.Grid({
  columns: [
    {
      name: "No",
      formatter: (cell, row) => row.index + 1,
      width: "50px"
    },
    "Nama",
    "Email",
    "Role",
    {
      name: "email_verified_at",
      hidden: true,
    },
    {
      name: "Verifikasi",
      formatter: (cell, row) => {
        const emailVerifiedAt = row.cells[4].data;
        if (emailVerifiedAt) {
          return gridjs.html('<span class="badge bg-success">Terverifikasi</span>');
        } else {
          return gridjs.html('<span class="badge bg-danger">Belum Terverifikasi</span>');
        }
      },
      width: "130px"
    },
    {
      name: "Aksi",
      formatter: (cell, row) => {
        const id = row.cells[6].data;  // ambil id asli dari kolom tersembunyi ke-7
        const name = row.cells[1].data;
        return actionButtons(id, name);
      },
      width: "150px"
    },
    {
      name: "ID",
      hidden: true
    }
  ],
  data: users.map((user, index) => [
    index + 1,
    user.name,
    user.email,
    user.role ?? "-",
    user.email_verified_at || "",
    "",  // verifikasi badge via formatter
    user.id,  // simpan id asli untuk tombol aksi
  ]),
  search: true,
  sort: true,
  pagination: { limit: 10 },
}).render(document.getElementById("table-gridjs"));
})
.catch((error) => {
  console.error("Error memuat data user:", error);
});

// Fungsi edit user dengan SweetAlert
window.editUser = function(id) {
  Swal.fire({
    icon: 'info',
    title: 'Edit User',
    text: 'Edit user dengan ID: ' + id,
    confirmButtonText: 'OK'
  });
  // Tambahkan logic redirect/modal edit disini
}

// Fungsi hapus user dengan SweetAlert konfirmasi
window.deleteUser = function(id, name) {
  Swal.fire({
    title: 'Yakin?',
    text: 'Anda yakin akan menghapus data ? data yang sudah di hapus tidak dapat dikembalikan !',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(window.APP_API_URL + `/user/${id}`, {
        method: 'DELETE',
        headers: {
          "Authorization": `Bearer ${window.APP_BEARER_TOKEN}`,
          "Accept": "application/json",
          "Content-Type": "application/json",
        }
      })
      .then(res => {
        if (!res.ok) throw new Error('Gagal menghapus user');
        return res.json();
      })
      .then(() => {
        Swal.fire('Terhapus!', 'User berhasil dihapus.', 'success');
        location.reload();
      })
      .catch(err => {
        Swal.fire('Error', err.message, 'error');
      });
    }
  });
}
