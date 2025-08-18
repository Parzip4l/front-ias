document.addEventListener("DOMContentLoaded", function() {
    const rupiahInputs = document.querySelectorAll('.rupiah-input');

    function formatRupiah(value) {
        let number_string = value.replace(/[^,\d]/g, '').toString();
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return 'Rp ' + rupiah;
    }

    rupiahInputs.forEach(input => {
        const hiddenId = input.dataset.target;
        const hiddenInput = document.getElementById(hiddenId);

        // Format on load
        input.value = formatRupiah(hiddenInput.value);

        input.addEventListener('input', function(e) {
            let cursorPosition = input.selectionStart;
            let value = input.value;

            // Update display
            input.value = formatRupiah(value);

            // Update hidden input (angka murni)
            hiddenInput.value = value.replace(/[^0-9]/g, '');

            // Restore cursor
            input.setSelectionRange(cursorPosition, cursorPosition);
        });
    });
});
