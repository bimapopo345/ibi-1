<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function kirimEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bimapopo345@gmail.com';
        $mail->Password = 'lwjp wlco hgyu ddme';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Pengaturan email
        $mail->setFrom('bimapopo345@gmail.com', 'Warung Makan');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        // Konten email
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Email Error: " . $mail->ErrorInfo);
        return false;
    }
}

// Template email untuk pesanan selesai
function getEmailTemplatePesananSelesai($nama_customer, $nomor_pesanan) {
    return "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
        <div style='background-color: #f8f8f8; padding: 20px; text-align: center;'>
            <h2 style='color: #e65100;'>Pesanan Anda Telah Selesai</h2>
        </div>
        
        <div style='padding: 20px;'>
            <p>Halo <strong>$nama_customer</strong>,</p>
            
            <p>Pesanan Anda dengan nomor <strong>#$nomor_pesanan</strong> telah selesai diproses dan siap untuk diambil.</p>
            
            <p>Silakan datang ke warung kami untuk mengambil pesanan Anda.</p>
            
            <div style='background-color: #f5f5f5; padding: 15px; margin: 20px 0;'>
                <p style='margin: 0;'><strong>Lokasi Pengambilan:</strong><br>
                Warung Makan<br>
                Jl. Contoh No. 123</p>
            </div>
            
            <p>Terima kasih telah memesan di Warung Makan kami!</p>
        </div>
        
        <div style='background-color: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #666;'>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>";
}

// Template email untuk konfirmasi pendaftaran
function getEmailTemplateRegistrasi($nama_customer, $username) {
    return "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
        <div style='background-color: #f8f8f8; padding: 20px; text-align: center;'>
            <h2 style='color: #e65100;'>Selamat Datang di Warung Makan</h2>
        </div>
        
        <div style='padding: 20px;'>
            <p>Halo <strong>$nama_customer</strong>,</p>
            
            <p>Terima kasih telah mendaftar di Warung Makan. Akun Anda telah berhasil dibuat dengan username: <strong>$username</strong></p>
            
            <p>Anda sekarang dapat:</p>
            <ul>
                <li>Memesan makanan</li>
                <li>Melihat riwayat pesanan</li>
                <li>Melacak status pesanan</li>
            </ul>
            
            <p>Silakan login untuk mulai memesan.</p>
        </div>
        
        <div style='background-color: #f8f8f8; padding: 20px; text-align: center; font-size: 12px; color: #666;'>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>";
}
?>
