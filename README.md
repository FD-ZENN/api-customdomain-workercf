# Cloudflare Worker Domain Manager

Aplikasi web untuk mengelola domain Cloudflare Workers dengan mudah. Aplikasi ini memungkinkan Anda menambahkan domain baru ke Cloudflare Workers dan melihat daftar domain yang sudah aktif, lengkap dengan notifikasi Telegram.

## 🚀 Fitur

- ✅ Tambah domain baru ke Cloudflare Workers
- 📋 Lihat daftar domain aktif
- 📱 Notifikasi Telegram otomatis
- 🎨 Interface yang responsif dan modern
- 🔄 Preview hostname real-time
- 📊 Sorting domain berdasarkan subdomain

## 📋 Persyaratan

- PHP 7.4 atau lebih baru
- cURL extension untuk PHP
- Akun Cloudflare dengan API access
- Bot Telegram (opsional, untuk notifikasi)

## ⚙️ Konfigurasi

1. **Clone atau download repository ini**

2. **Edit konfigurasi di bagian atas file PHP:**

```php
// Cloudflare API Configuration
$account_id = 'your_cloudflare_account_id';
$auth_email = 'your_cloudflare_email';
$auth_key = 'your_cloudflare_api_token';
$zone_id = 'your_zone_id';
$service = 'your_worker_service_name';
$domain = 'your_domain.com';

// Telegram Bot Configuration (opsional)
$bot_token = 'your_telegram_bot_token';
$chat_id = 'your_telegram_chat_id';
```

### 🔑 Cara Mendapatkan Kredensial

#### Cloudflare:
1. Login ke [Cloudflare Dashboard](https://dash.cloudflare.com)
2. **Account ID**: Sidebar kanan di halaman overview
3. **Auth Email**: Email akun Cloudflare Anda
4. **API Token**: My Profile → API Tokens → Create Token
5. **Zone ID**: Overview domain → API section (sidebar kanan)
6. **Service**: Nama Cloudflare Worker yang sudah dibuat

#### Telegram (Opsional):
1. Buat bot baru dengan [@BotFather](https://t.me/botfather)
2. Dapatkan bot token dari BotFather
3. Chat ID bisa didapat dari [@userinfobot](https://t.me/userinfobot)

## 🚀 Instalasi

1. Upload file ke web server Anda
2. Pastikan PHP dan cURL extension terinstall
3. Edit konfigurasi sesuai dengan data Anda
4. Akses melalui browser

## 💻 Cara Penggunaan

### Menambah Domain Baru

1. **Isi form "Tambah Domain Baru":**
   - **Wildcard**: Masukkan nama wildcard (contoh: `zoom.us`, `*.example`)
   - **Subdomain**: Pilih dari dropdown yang tersedia:
     - `vip-id1`
     - `vip-id2`
     - `vip-id3`
     - `vip-sg1`
   - **Domain**: Otomatis terisi dari konfigurasi

2. **Preview hostname** akan muncul secara real-time: `wildcard.subdomain.domain.com`

3. **Klik "🚀 Tambah Domain"**

4. **Status** akan ditampilkan:
   - ✅ Sukses: Domain berhasil ditambahkan
   - ❌ Error: Pesan error akan ditampilkan

### Melihat Domain Aktif

- Daftar domain aktif ditampilkan di bagian bawah
- Domain diurutkan berdasarkan subdomain
- Menampilkan jumlah total domain

## 📱 Notifikasi Telegram

Jika dikonfigurasi, aplikasi akan mengirim notifikasi Telegram untuk:

### Berhasil Menambah Domain:
```
🎉 Domain Berhasil Ditambahkan

📍 Hostname: example.vip-id1.domain.com
⚙️ Service: my-worker
🌐 Zone: domain.com
🔧 Environment: production
⏰ Waktu: 2024-01-15 10:30:00

✅ Domain siap digunakan!
```

### Error Menambah Domain:
```
❌ Error Menambahkan Domain

📍 Hostname: example.vip-id1.domain.com
⚠️ Error: Domain already exists
📊 HTTP Code: 400
⏰ Waktu: 2024-01-15 10:30:00

🔧 Silakan periksa konfigurasi dan coba lagi.
```

## 🎨 Fitur Interface

- **Responsive Design**: Bekerja dengan baik di desktop dan mobile
- **Real-time Preview**: Hostname preview yang update otomatis
- **Modern UI**: Design yang clean dan user-friendly
- **Error Handling**: Pesan error yang jelas dan informatif
- **Loading States**: Feedback visual saat proses berlangsung

## 🔧 Customization

### Menambah Subdomain Baru

Edit bagian dropdown di HTML:

```html
<select id="subdomain" name="subdomain" required>
    <option value="">Pilih Subdomain</option>
    <option value="vip-id1">vip-id1</option>
    <option value="vip-id2">vip-id2</option>
    <option value="vip-id3">vip-id3</option>
    <option value="vip-sg1">vip-sg1</option>
    <!-- Tambahkan subdomain baru di sini -->
    <option value="vip-us1">vip-us1</option>
</select>
```

### Mengubah Style

Edit bagian `<style>` dalam file PHP untuk mengcustomize tampilan sesuai kebutuhan.

## 🐛 Troubleshooting

### Error "cURL error" atau "Connection failed"
- Pastikan cURL extension terinstall di PHP
- Periksa koneksi internet server
- Pastikan firewall tidak memblokir koneksi ke Cloudflare API

### Error "Authentication failed"
- Periksa kembali API token dan email
- Pastikan API token memiliki permission yang cukup
- Pastikan account ID benar

### Error "Zone not found"
- Periksa Zone ID apakah sudah benar
- Pastikan domain sudah terdaftar di Cloudflare

### Notifikasi Telegram tidak terkirim
- Periksa bot token dan chat ID
- Pastikan bot sudah di-start dengan mengirim `/start`
- Periksa koneksi ke Telegram API

## 📝 Changelog

### Version 1.0.0
- Initial release
- Basic domain management
- Telegram notifications
- Responsive UI
- Real-time hostname preview

## 📜 License

Project ini menggunakan MIT License. Lihat file `LICENSE` untuk detail.

## 🆘 Support

Jika mengalami masalah atau memiliki pertanyaan:

1. Buka issue di GitHub repository
2. Sertakan detail error dan konfigurasi (tanpa kredensial sensitif)
3. Include screenshot jika memungkinkan

## ⚠️ Security Note

- **JANGAN** commit file dengan kredensial asli ke repository public
- Gunakan environment variables untuk production
- Pastikan file PHP tidak dapat diakses langsung jika berisi kredensial
- Gunakan HTTPS untuk production deployment

## 🙏 Acknowledgments

- Cloudflare untuk API yang powerful
- Telegram untuk Bot API
- PHP community untuk dokumentasi yang excellent
