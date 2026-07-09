function confirmDelete() {
    return confirm('Yakin ingin menghapus data ini?');
}

function rupiah(angka) {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka);
}

document.addEventListener('DOMContentLoaded', function () {
    const shipping = document.getElementById('shippingType');
    const preview = document.getElementById('shippingPreview');
    if (shipping && preview) {
        const update = () => preview.textContent = rupiah(Number(shipping.selectedOptions[0].dataset.cost || 0));
        shipping.addEventListener('change', update);
        update();
    }

    document.querySelectorAll('.ajax-cart-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            const data = new FormData(form);
            data.append('ajax', '1');
            fetch(form.action, { method: 'POST', body: data })
                .then(response => response.json())
                .then(result => {
                    const alertBox = document.createElement('div');
                    alertBox.className = 'alert mt-2 ' + (result.success ? 'alert-success' : 'alert-danger');
                    alertBox.textContent = result.message;
                    form.parentElement.appendChild(alertBox);
                    setTimeout(() => alertBox.remove(), 2500);
                })
                .catch(() => form.submit());
        });
    });
});
