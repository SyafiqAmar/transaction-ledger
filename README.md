# Demo Transaksi pada Ledger
Prototype ini dibuat sebagai simulasi bagaimana suatu traksaksi itu masih dalam bentuk centralized, dimana data yang dimasukkan itu masih tersimpan pada satu database. berbeda dengan blockchain, dimana seluruh transaksi disimpan dan divalidasi secara decentralized oleh banyak node yang tersebar tanpa adanya satu server terpusat.

---
# Fitur yang dibuat

- **CRUD via Web** — tambah, lihat, edit, dan hapus transaksi lewat browser.
- **REST API (JSON)** — endpoint `/api/transactions` untuk CRUD via program.
- **Validasi input** — aturan di sisi server (field wajib, tipe data, nilai enum).
- **Landing page** — halaman sambutan yang menjelaskan konteks proyek.

Setiap transaksi punya field: `sender`, `receiver`, `amount`, `hash`, dan
`status` (`pending` / `confirmed` / `failed`).

---

| Komponen | Keterangan |
|----------|------------|
| **Laravel** | Framework PHP |
| **PHP 8.3** | Bahasa |
| **SQLite** | Database (1 file, tanpa server) |
| **Blade** | Template engine (tampilan) |
| **Tailwind CSS** | Styling (via CDN, tanpa build/npm) |

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
### Web (mengembalikan HTML)

| Method | URL | Keterangan |
|--------|-----|------------|
| GET | `/` | Landing page |
| GET | `/transactions` | Daftar transaksi |
| GET | `/transactions/create` | Form tambah |
| POST | `/transactions` | Simpan transaksi baru |
| GET | `/transactions/{id}` | Detail transaksi |
| GET | `/transactions/{id}/edit` | Form edit |
| PUT/PATCH | `/transactions/{id}` | Update transaksi |
| DELETE | `/transactions/{id}` | Hapus transaksi |

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