<!DOCTYPE html>
<html>

<head>
    <title>Kode OTP Reset Password</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div
        style="max-w: 500px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; border-top: 5px solid #800000; text-align: center;">
        <h2 style="color: #333333; margin-bottom: 20px;">Permintaan Reset Password</h2>
        <p style="color: #666666; font-size: 14px; line-height: 1.6;">Kami menerima permintaan untuk mengatur ulang
            password akun Anda. Silakan masukkan kode OTP di bawah ini ke dalam aplikasi:</p>

        <div
            style="background-color: #f4f4f4; border: 1px dashed #cccccc; margin: 30px 0; padding: 20px; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #800000; border-radius: 10px;">
            {{ $otp }}
        </div>

        <p style="color: #999999; font-size: 12px;">Kode ini hanya berlaku selama 10 menit. Jika Anda tidak pernah
            meminta kode ini, mohon abaikan email ini.</p>

        <hr style="border: none; border-top: 1px solid #eeeeee; margin: 30px 0;">
        <p style="color: #aaaaaa; font-size: 11px;">&copy; {{ date('Y') }} Satgas PPKPT USN Kolaka.</p>
    </div>
</body>

</html>
