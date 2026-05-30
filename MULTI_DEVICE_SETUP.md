# Panduan Multi-Device POS

## Skenario 1: Single Device (SQLite) - Default
Cukup copy folder project ke device baru. Database (`database/database.sqlite`) ikut tercopy.

```bash
php artisan serve
```

## Skenario 2: Multi Device dengan MySQL Server

### Step 1: Install MySQL di komputer server
- Download & install XAMPP / MySQL Community Server
- Buat database baru: `pos_kelontong`

### Step 2: Edit `.env` di folder project

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_kelontong
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Jalankan migrate & seed (sekali saja di server)

```bash
php artisan migrate:fresh --seed
```

### Step 4: Jalankan server agar bisa diakses device lain

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Step 5: Akses dari device lain
Buka browser di device lain, masukkan IP server:
```
http://192.168.1.x:8000
```

> Ganti `192.168.1.x` dengan IP address komputer server Anda.

---

## ⚠️ Catatan Penting

| Aspek | SQLite | MySQL Server |
|-------|--------|-------------|
| Setup | Mudah | Butuh install MySQL |
| Multi-device | ❌ Tidak support | ✅ Support |
| Backup | Copy 1 file | Export database |
| Performance | OK untuk kecil | Lebih powerful |

## Rekomendasi
- **1 kasir / 1 toko kecil** → SQLite (default)
- **Beberapa kasir / cabang** → MySQL Server

