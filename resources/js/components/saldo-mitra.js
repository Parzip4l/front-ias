import { Grid, html } from "gridjs";

// === Dummy Data Mitra ===
const dummyMitraSaldo = [
    {
        id: 1,
        nama: "Garuda Indonesia",
        saldo: 15000000,
        updated_at: "2025-08-11 14:22:00"
    },
    {
        id: 2,
        nama: "Aston Hotel",
        saldo: 5000000,
        updated_at: "2025-08-11 13:10:00"
    },
    {
        id: 3,
        nama: "Allianz Asuransi",
        saldo: 2000000,
        updated_at: "2025-08-10 09:15:00"
    }
];

// === Load Data ke Grid ===
function loadSaldoMitra() {
    new Grid({
        columns: [
            { name: "ID", hidden: true },
            "Nama Mitra",
            {
                name: "Saldo",
                formatter: (cell) => `Rp ${Number(cell).toLocaleString("id-ID")}`
            },
            {
                name: "Last Update",
                formatter: (cell) => new Date(cell).toLocaleString("id-ID")
            },
            {
                name: "Aksi",
                formatter: (_, row) => {
                    return html(`
                        <button 
                            class="btn btn-sm btn-outline-primary btn-topup-single" 
                            data-id="${row.cells[0].data}" 
                            data-nama="${row.cells[1].data}">
                            Top-Up
                        </button>
                    `);
                }
            }
        ],
        search: true,
        pagination: { limit: 5 },
        sort: true,
        data: dummyMitraSaldo.map((m) => [
            m.id,
            m.nama,
            m.saldo,
            m.updated_at,
            null
        ])
    }).render(document.getElementById("table-saldo-mitra"));
}

// === Buka Modal Top-Up ===
$(document).on("click", "#btn-topup, .btn-topup-single", function () {
    let mitraId = $(this).data("id") || "";
    let mitraNama = $(this).data("nama") || "";

    // Reset form
    $("#form-topup")[0].reset();

    // Isi dropdown mitra
    let options = "";
    dummyMitraSaldo.forEach((m) => {
        options += `<option value="${m.id}" ${m.id == mitraId ? "selected" : ""}>${m.nama}</option>`;
    });
    $('select[name="mitra_id"]').html(options);

    if (mitraNama) {
        $("#modalTopUpLabel").text(`Top-Up Saldo: ${mitraNama}`);
    } else {
        $("#modalTopUpLabel").text("Top-Up Saldo Mitra");
    }

    // Bootstrap 5 show modal tanpa jQuery
    const modalEl = document.getElementById("modalTopUp");
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
});

// === Submit Top-Up Dummy ===
$("#form-topup").on("submit", function (e) {
    e.preventDefault();

    let mitraId = $('select[name="mitra_id"]').val();
    let amount = parseInt($('input[name="amount"]').val());

    // Update saldo di dummy data
    let mitra = dummyMitraSaldo.find((m) => m.id == mitraId);
    if (mitra) {
        mitra.saldo += amount;
        mitra.updated_at = new Date().toISOString();
    }

    toastr.success("Top-Up berhasil (dummy data)!");
    $("#modalTopUp").modal("hide");

    // Reload tabel
    document.getElementById("table-saldo-mitra").innerHTML = "";
    loadSaldoMitra();
});

// === Init Load ===
document.addEventListener("DOMContentLoaded", () => {
    loadSaldoMitra();
});
