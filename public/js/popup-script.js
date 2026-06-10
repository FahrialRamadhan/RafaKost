document.getElementById('btnSimpan').addEventListener('click', function () {
    const selectedStatus = document.getElementById('statusSelect').value;

    // Label & warna badge sesuai status yang dipilih
    const statusMap = {
        pending: { label: 'Pending', color: '#ca8a04', bg: '#fef9c3' },
        paid:    { label: 'Paid',    color: '#16a34a', bg: '#dcfce7' },
        failed:  { label: 'Failed',  color: '#dc2626', bg: '#fee2e2' },
        expired: { label: 'Expired', color: '#6b7280', bg: '#f3f4f6' },
    };

    const s = statusMap[selectedStatus] ?? statusMap.expired;

    Swal.fire({
        title: 'Ubah Status Booking?',
        html: `
            Status akan diubah menjadi&nbsp;
            <span style="
                display: inline-flex;
                align-items: center;
                gap: 5px;
                background: ${s.bg};
                color: ${s.color};
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                padding: 3px 10px;
                border-radius: 6px;
            ">
                <span style="width:7px;height:7px;border-radius:50%;background:${s.color};display:inline-block;"></span>
                ${s.label}
            </span>
        `,
        icon: 'question',
        showCancelButton: true,
        customClass: {
            popup:         'confirm-popup',
            title:         'confirm-popup-title',
            htmlContainer: 'confirm-popup-text',
            confirmButton: 'confirm-btn-blue',
            cancelButton:  'cancel-btn-soft',
        },
        buttonsStyling: false,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText:  'Batal',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('statusForm').submit();
        }
    });
});