
function validateFile(input) {
    const file = input.files[0];
    if (file) {
        // max 1 MB = 1 * 1024 * 1024 byte
        if (file.size > 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'File terlalu besar',
                text: 'Ukuran file maksimal 1 MB!',
                confirmButtonText: 'OK'
            });
            input.value = ""; // reset input
        } else if (file.type !== "application/pdf") {
            Swal.fire({
                icon: 'warning',
                title: 'Format tidak valid',
                text: 'Hanya file PDF yang diperbolehkan!',
                confirmButtonText: 'OK'
            });
            input.value = ""; // reset input
        }
    }
}