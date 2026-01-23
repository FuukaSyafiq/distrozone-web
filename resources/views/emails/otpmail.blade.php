<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kode Verifikasi OTP</title>
	<style>
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background-color: #f4f7fa;
			margin: 0;
			padding: 0;
			line-height: 1.6;
		}

		.email-container {
			max-width: 600px;
			margin: 40px auto;
			background-color: #ffffff;
			border-radius: 12px;
			box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
			overflow: hidden;
		}

		.email-header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			padding: 30px 20px;
			text-align: center;
		}

		.email-header h1 {
			color: #ffffff;
			margin: 0;
			font-size: 24px;
			font-weight: 600;
		}

		.email-body {
			padding: 40px 30px;
			color: #333333;
		}

		.greeting {
			font-size: 18px;
			margin-bottom: 20px;
			color: #2d3748;
		}

		.message {
			font-size: 15px;
			color: #4a5568;
			margin-bottom: 25px;
		}

		.otp-container {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			border-radius: 10px;
			padding: 30px;
			text-align: center;
			margin: 30px 0;
			box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
		}

		.otp-label {
			color: #ffffff;
			font-size: 14px;
			margin-bottom: 10px;
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
		}

		.otp-code {
			color: #ffffff;
			font-size: 36px;
			font-weight: 700;
			letter-spacing: 8px;
			margin: 10px 0;
			text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
		}

		.info-box {
			background-color: #f7fafc;
			border-left: 4px solid #667eea;
			border-radius: 6px;
			padding: 20px;
			margin: 25px 0;
		}

		.info-title {
			font-weight: 600;
			color: #2d3748;
			margin-bottom: 12px;
			font-size: 15px;
		}

		.info-list {
			margin: 0;
			padding-left: 20px;
			color: #4a5568;
			font-size: 14px;
		}

		.info-list li {
			margin: 8px 0;
		}

		.warning-box {
			background-color: #fff5f5;
			border-left: 4px solid #f56565;
			border-radius: 6px;
			padding: 20px;
			margin: 25px 0;
		}

		.warning-text {
			color: #c53030;
			font-size: 14px;
			margin: 0;
		}

		.email-footer {
			background-color: #f7fafc;
			padding: 25px 30px;
			text-align: center;
			border-top: 1px solid #e2e8f0;
		}

		.footer-text {
			font-size: 14px;
			color: #718096;
			margin: 5px 0;
		}

		.app-name {
			font-weight: 600;
			color: #667eea;
		}

		.divider {
			border: 0;
			height: 1px;
			background: linear-gradient(to right, transparent, #cbd5e0, transparent);
			margin: 25px 0;
		}

		@media only screen and (max-width: 600px) {
			.email-container {
				margin: 20px;
			}

			.email-body {
				padding: 30px 20px;
			}

			.otp-code {
				font-size: 28px;
				letter-spacing: 6px;
			}
		}
	</style>
</head>

<body>
	<div class="email-container">
		<div class="email-header">
			<h1>🔐 Verifikasi Akun Anda</h1>
		</div>

		<div class="email-body">
			<p class="greeting">Halo <strong>{{ $user->nama ?? 'Pengguna' }}</strong>,</p>

			<p class="message">
				Kami menerima permintaan login ke akun Anda sebagai <strong>Admin/Kasir</strong>.
			</p>

			<p class="message">
				Berikut adalah kode verifikasi (OTP) Anda:
			</p>

			<div class="otp-container">
				<div class="otp-label">
					<span>🔐</span>
					<span>Kode OTP Anda</span>
				</div>
				<div class="otp-code">{{ $otp }}</div>
			</div>

			<div class="info-box">
				<div class="info-title">Kode ini:</div>
				<ul class="info-list">
					<li>Berlaku selama <strong>5 menit</strong></li>
					<li><strong>Hanya dapat digunakan satu kali</strong></li>
					<li>Jangan dibagikan kepada siapa pun, termasuk tim kami</li>
				</ul>
			</div>

			<div class="warning-box">
				<p class="warning-text">
					<strong>⚠️ Perhatian:</strong> Jika Anda <strong>tidak merasa melakukan login</strong>,
					mohon abaikan email ini dan segera hubungi administrator sistem.
				</p>
			</div>

			<hr class="divider">

			<p class="message">
				Terima kasih,<br>
				<strong>Tim Keamanan <span class="app-name">{{ config('app.name') }}</span></strong>
			</p>
		</div>

		<div class="email-footer">
			<p class="footer-text">
				Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
			</p>
			<p class="footer-text">
				© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
			</p>
		</div>
	</div>
</body>

</html>