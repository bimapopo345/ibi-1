document.addEventListener('DOMContentLoaded', function() {
    // Contoh fungsi untuk menampilkan notifikasi
    function showNotification(message) {
        alert(message);
    }

    // Contoh penggunaan
    const notifyButtons = document.querySelectorAll('button[name="notify"]');
    notifyButtons.forEach(button => {
        button.addEventListener('click', function() {
            showNotification('Notifikasi telah dikirim!');
        });
    });
});