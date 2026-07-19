# Demo Transaksi pada Ledger
Prototype ini dibuat sebagai simulasi bagaimana suatu traksaksi itu masih dalam bentuk centralized, dimana data yang dimasukkan itu masih tersimpan pada satu database. berbeda dengan blockchain, dimana seluruh transaksi disimpan dan divalidasi secara decentralized oleh banyak node yang tersebar tanpa adanya satu server terpusat.

---
# Fitur yang dibuat

- **Login/Register (Laravel Breeze)** — tiap user punya akun sendiri, transaksi & koneksi exchange di-scope per user.
- **CRUD via Web** — tambah, lihat, edit, dan hapus transaksi lewat browser.
- **REST API (JSON)** — endpoint `/api/transactions` untuk CRUD via program.
- **Konversi Crypto → IDR otomatis** — saat tambah transaksi, user pilih ticker (BTC/USDT, SOL/USDT, TRX/USDT) + input jumlah crypto, lalu otomatis dikonversi: `crypto → USD` (harga real-time via exchange) → `USD → IDR` (kurs harian Frankfurter) → dikirim sebagai nominal invoice Xendit.
- **Koneksi akun exchange (Binance/Bybit/Indodax)** — user simpan API key exchange miliknya sendiri (dienkripsi di database) untuk cek saldo (balance) & ambil harga ticker.
- **Halaman Wallet** — menampilkan saldo crypto user dari tiap exchange yang terhubung (testnet).
- **Validasi input** — aturan di sisi server (field wajib, tipe data, nilai enum).
- **Landing page** — halaman sambutan yang menjelaskan konteks proyek.

Setiap transaksi punya field: `sender`, `receiver`, `ticker`, `crypto_amount`,
`amount` (hasil konversi ke IDR), `hash`, dan `status` (`pending` / `confirmed`
/ `failed`, di-update otomatis lewat webhook Xendit — tidak bisa diubah manual).

---

| Komponen | Keterangan |
|----------|------------|
| **Laravel** | Framework PHP |
| **PHP 8.3** | Bahasa |
| **SQLite** | Database (1 file, tanpa server) |
| **Blade** | Template engine (tampilan) |
| **Laravel Breeze** | Auth scaffolding (login/register) |
| **Tailwind CSS** | Styling |
| **Xendit** | Payment gateway (invoice + webhook) |
| **CCXT (Node.js)** | Ambil harga ticker & saldo dari Binance/Bybit/Indodax, lewat microservice kecil di `crypto-service/` |
| **Frankfurter API** | Kurs USD → IDR (gratis, tanpa API key) |

---

## Arsitektur Konversi Crypto

Karena **CCXT adalah library Node.js** (bukan PHP), Laravel tidak bisa
memanggilnya langsung. Solusinya: microservice kecil Express (`crypto-service/`)
yang membungkus CCXT dan expose 2 endpoint HTTP, dipanggil dari Laravel lewat
`Http` client (`app/Services/CryptoService.php`):

```
Laravel (app/Services/CryptoService.php)
   │  HTTP request
   ▼
crypto-service/index.js (Express + CCXT)   ← Node.js, port 3000
   │  fetchTicker() / fetchBalance()
   ▼
Binance / Bybit / Indodax (testnet)
```

Untuk pengembangan, exchange di-set ke **sandbox/testnet mode**
(`exchange.setSandboxMode(true)`) supaya tidak menyentuh dana asli.

### Menjalankan `crypto-service`

```bash
cd crypto-service
npm install
node index.js
```
Servis ini harus tetap berjalan (di terminal terpisah) selama menggunakan
fitur convert harga/balance. Tambahkan di `.env` Laravel:
```env
CRYPTO_SERVICE_URL=http://localhost:3000
```

---

- PHP 8.3+ dan ekstensi: `pdo_sqlite`, `mbstring`, `xml`, `curl`
- [Composer](https://getcomposer.org/)


```bash
git clone https://github.com/SyafiqAmar/transaction-ledger.git
cd transaction-ledger

composer install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate

php artisan serve
```

Buka **http://127.0.0.1:8000** di browser.

---

## Konfigurasi Xendit

Fitur ini membutuhkan kredensial Xendit agar fitur pembayaran bisa digunakan. 
Setelah `cp .env.example .env`, isi 2 variabel ini di file `.env`:

​```env
XENDIT_SECRET_KEY=xnd_development_xxxxx
XENDIT_CALLBACK_TOKEN=xxxxx
​```
ganti xxx dengan token yang di dapat dari xendit.

**Cara dapat kredensialnya:**

1. Daftar / login di [dashboard Xendit](https://dashboard.xendit.co).
2. **Secret Key** — menu *Settings → API Keys*. Pakai key **test** (diawali
   `xnd_development_`) untuk percobaan.
3. **Callback Token** — menu *Settings → Webhooks → Webhook verification token*.

**Webhook (biar status update otomatis):**

- Di *Settings → Webhooks*, set URL webhook untuk event **Invoice** ke:
  `https://<domain-kamu>/api/xendit/webhook`

### Web (mengembalikan HTML, wajib login)

| Method | URL | Keterangan |
|--------|-----|------------|
| GET | `/` | Landing page |
| GET | `/login`, `/register` | Auth (Breeze) |
| GET | `/dashboard` | Shortcut ke "Tambah Transaksi" & "Wallet" |
| GET | `/transactions` | Daftar transaksi milik user yang login |
| GET | `/transactions/create` | Form tambah (pilih ticker + jumlah crypto) |
| POST | `/transactions` | Simpan transaksi baru → auto-convert crypto→IDR → redirect ke invoice Xendit |
| GET | `/transactions/{id}` | Detail transaksi |
| GET | `/transactions/{id}/edit` | Form edit |
| PUT/PATCH | `/transactions/{id}` | Update transaksi |
| DELETE | `/transactions/{id}` | Hapus transaksi |
| POST | `/transactions/{id}/pay` | Membuat ulang invoice & mengarahkan ke pembayaran via Xendit |
| GET | `/exchange-credentials` | Form hubungkan API key exchange (Binance/Bybit/Indodax) |
| POST | `/exchange-credentials` | Simpan/update API key (terenkripsi) |
| GET | `/wallet` | Saldo crypto per exchange yang terhubung |
| POST | `/api/xendit/webhook` | Menerima notifikasi pembayaran dari Xendit (auto-update status) |

### API (mengembalikan JSON)

| Method | URL | Keterangan |
|--------|-----|------------|
| GET | `/api/transactions` | List semua transaksi |
| POST | `/api/transactions` | Buat transaksi baru |
| GET | `/api/transactions/{id}` | Detail transaksi |
| PUT/PATCH | `/api/transactions/{id}` | Update transaksi |
| DELETE | `/api/transactions/{id}` | Hapus transaksi |

**Contoh membuat transaksi via API:**

```bash
curl -X POST http://127.0.0.1:8000/api/transactions \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"sender":"Aman","receiver":"Amin","amount":100,"hash":"abc123","status":"pending"}'
```

Response (`201 Created`):

```json
{
  "success": true,
  "message": "Transaksi berhasil dibuat",
  "data": { "id": 1, "sender": "Aman", "receiver": "Amin", "amount": "100.00", "hash": "abc123", "status": "pending" }
}
```

---