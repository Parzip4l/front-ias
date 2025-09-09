let map, marker;

// Init map sekali aja
function initMap(lat = -2.5489, lon = 118.0149, zoom = 5) {
    if (!map) {
        map = L.map('map').setView([lat, lon], zoom);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);
    } else {
        map.setView([lat, lon], zoom);
    }

    if (marker) marker.remove();
    marker = L.marker([lat, lon]).addTo(map);
}

// fetch helper
async function fetchData(url) {
    try {
        const res = await fetch(url);
        if (!res.ok) throw new Error("Network error");
        return await res.json();
    } catch (err) {
        console.error(err);
        return [];
    }
}

// geocode smarter
async function geocodeSmart(prov, reg, dist, vill) {
    const candidates = [
        `${vill}, ${dist}, ${reg}, ${prov}, Indonesia`,
        `${dist}, ${reg}, ${prov}, Indonesia`,
        `${reg}, ${prov}, Indonesia`
    ];

    for (const addr of candidates) {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(addr)}&countrycodes=id&limit=1&viewbox=95,-11,141,6&bounded=1`;
        try {
            const res = await fetch(url, { headers: { 'Accept-Language': 'id' } });
            const data = await res.json();
            if (data.length > 0) {
                return { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) };
            }
        } catch (e) {
            console.error("Geocode error:", e);
        }
    }
    return null;
}

// cascade select
async function loadProvinces() {
    const data = await fetchData(`${BASE_URL}/wilayah/provinces`);
    $('#province').html('<option value="">Pilih Provinsi</option>');
    data.forEach(p => $('#province').append(`<option value="${p.id}">${p.name}</option>`));
}

$('#province').on('change', async function () {
    const id = $(this).val();
    $('#regency').html('<option>Loading...</option>');
    $('#district').html('<option></option>');
    $('#village').html('<option></option>');
    if (!id) return;

    const data = await fetchData(`${BASE_URL}/wilayah/regencies/${id}`);
    $('#regency').html('<option value="">Pilih Kabupaten</option>');
    data.forEach(r => $('#regency').append(`<option value="${r.id}">${r.name}</option>`));
});

$('#regency').on('change', async function () {
    const id = $(this).val();
    $('#district').html('<option>Loading...</option>');
    $('#village').html('<option></option>');
    if (!id) return;

    const data = await fetchData(`${BASE_URL}/wilayah/districts/${id}`);
    $('#district').html('<option value="">Pilih Kecamatan</option>');
    data.forEach(d => $('#district').append(`<option value="${d.id}">${d.name}</option>`));
});

$('#district').on('change', async function () {
    const id = $(this).val();
    $('#village').html('<option>Loading...</option>');
    if (!id) return;

    const data = await fetchData(`${BASE_URL}/wilayah/villages/${id}`);
    $('#village').html('<option value="">Pilih Desa</option>');
    data.forEach(v => $('#village').append(`<option value="${v.id}">${v.name}</option>`));
});

$('#village').on('change', async function () {
    await showMapIfComplete();
});

async function showMapIfComplete() {
    const prov = $('#province option:selected').text();
    const reg = $('#regency option:selected').text();
    const dist = $('#district option:selected').text();
    const vill = $('#village option:selected').text();

    if (prov && reg && dist && vill &&
        !prov.includes('Pilih') &&
        !reg.includes('Pilih') &&
        !dist.includes('Pilih') &&
        !vill.includes('Pilih')) {

        const loc = await geocodeSmart(prov, reg, dist, vill);
        if (loc) {
            $('#map').show();
            initMap(loc.lat, loc.lon, 14);
        } else {
            alert('Lokasi tidak ditemukan di peta.');
        }
    }
}

// init awal
$(document).ready(function () {
    loadProvinces();
});