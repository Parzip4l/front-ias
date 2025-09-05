const BASE_URL = document.querySelector('meta[name="sppd-api-url"]').content;
function formatDuration(duration) {
    return duration.replace("PT","").replace("H","j ").replace("M","m");
}

$(document).ready(function(){
    $(".select2").select2();

    $("#btnCariPesawat").on("click", function(){
        let origin = $("#origin").val();
        let destination = $("#destination").val();
        let departureDate = $("#tanggal_berangkat").val();
        let returnDate = $("#tanggal_pulang").val();
        let adults = $("#adults").val();

        // pergi
        $("#flightListPergi").html('<div class="text-center p-3">Loading...</div>');
        $.ajax({
            url: BASE_URL + "/flights/search",
            method: "GET",
            data: { origin, destination, date: departureDate, adults },
            success: function(res){
                $("#flightListPergi").empty();
                renderFlights(res, "#flightListPergi", "pergi");
            }
        });

        // pulang
        $("#flightListPulang").html('<div class="text-center p-3">Loading...</div>');
        $.ajax({
            url: BASE_URL + "/flights/search",
            method: "GET",
            data: { origin: destination, destination: origin, date: returnDate, adults },
            success: function(res){
                $("#flightListPulang").empty();
                renderFlights(res, "#flightListPulang", "pulang");
            }
        });
    });

    function renderFlights(res, container, type){
        if(res.data && res.data.length > 0){
            res.data.forEach(flight => {
                let harga = flight.price?.grandTotal ?? 0;
                let segs = flight.itineraries[0].segments;
                let duration = formatDuration(flight.itineraries[0].duration);
                let firstSeg = segs[0];
                let lastSeg  = segs[segs.length - 1];

                // jam berangkat & tiba
                let departTime = firstSeg.departure.at.substring(11,16);
                let arriveTime = lastSeg.arrival.at.substring(11,16);

                // transit
                let transitInfo = segs.length > 1
                    ? `<div class="text-muted small">Transit di ${segs.slice(0,-1).map(s=>s.arrival.iataCode).join(", ")}</div>`
                    : `<div class="text-muted small">Langsung</div>`;

                // maskapai
                let carrierName = res.dictionaries?.carriers?.[firstSeg.carrierCode] ?? firstSeg.carrierCode;

                // detail penumpang (ambil dari travelerPricings)
                let traveler = flight.travelerPricings?.[0];
                let fareDetail = traveler?.fareDetailsBySegment?.[0];
                let bagasi = fareDetail?.includedCheckedBags?.weight 
                    ? `${fareDetail.includedCheckedBags.weight} ${fareDetail.includedCheckedBags.weightUnit}` 
                    : "Tidak termasuk";
                let cabin = fareDetail?.cabin ?? "-";

                // kartu hasil
                let card = `
                <div class="card mb-2 flight-card" data-type="${type}" data-nama="${carrierName}" data-harga="${harga}">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-2">
                            <h6 class="fw-bold mb-1">${carrierName}</h6>
                            <div class="small text-muted">${firstSeg.departure.iataCode} → ${lastSeg.arrival.iataCode}</div>
                            ${transitInfo}
                            <div class="small">Bagasi: ${bagasi} | Cabin: ${cabin}</div>
                        </div>
                        <div class="text-center mb-2">
                            <div class="fw-bold">${departTime}</div>
                            <div class="text-muted small">${firstSeg.departure.iataCode}</div>
                        </div>
                        <div class="text-center mb-2">
                            <div class="small">${duration}</div>
                        </div>
                        <div class="text-center mb-2">
                            <div class="fw-bold">${arriveTime}</div>
                            <div class="text-muted small">${lastSeg.arrival.iataCode}</div>
                        </div>
                        <div class="text-end mb-2">
                            <div class="fw-bold text-danger">Rp ${parseInt(harga).toLocaleString('id-ID')}</div>
                            <button type="button" class="btn btn-sm btn-primary pilih-pesawat mt-1">Pilih</button>
                        </div>
                    </div>
                </div>`;
                $(container).append(card);
            });
        } else {
            $(container).html('<div class="alert alert-warning">Tidak ada penerbangan.</div>');
        }
    }


    // pilih pesawat
    $(document).on("click", ".pilih-pesawat", function(){
        let card = $(this).closest(".flight-card");
        let type = card.data("type");
        let nama = card.data("nama");
        let harga = card.data("harga");

        if(type === "pergi"){
            $("#transportasi_pergi").val(nama);
            $("#biaya_pergi").val(harga);
        } else {
            $("#transportasi_pulang").val(nama);
            $("#biaya_pulang").val(harga);
        }

        $("#pesawat-preview").removeClass("d-none").html(`
            <strong>Pergi:</strong> ${$("#transportasi_pergi").val() || '-'} (Rp ${parseInt($("#biaya_pergi").val() || 0).toLocaleString('id-ID')})<br>
            <strong>Pulang:</strong> ${$("#transportasi_pulang").val() || '-'} (Rp ${parseInt($("#biaya_pulang").val() || 0).toLocaleString('id-ID')})
        `);
    });
});
