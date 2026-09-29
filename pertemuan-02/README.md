# Pertemuan 02

## 1. Tujuan Praktikum
Tujuan praktikum P2 ini adalah untuk membuat dan memahami dasar-dasar arsitektur Model-View-Controller (MVC) menggunakan PHP asli (native) tanpa framework[cite: 4, 5]. Melalui praktikum ini, saya belajar bagaimana alur sebuah *request* ditangani oleh *front controller*, dipetakan oleh *router*, dan diproses oleh *controller* sebelum akhirnya ditampilkan ke layar melalui *view*[cite: 4, 5].

## 2. Struktur Direktori
- `pertemuan-02/`: Folder utama proyek MVC.
- `application/`: Folder untuk menyimpan kode spesifik aplikasi yang sedang dibangun, berisi konfigurasi (`config/`), logika pengontrol (`controllers/`), dan file antarmuka (`views/`).
- `assets/`: Direktori untuk menyimpan aset publik statis seperti file CSS (`app.css`), JavaScript, dan gambar.
- `dokumentasi/`: Folder khusus untuk menyimpan bukti tangkapan layar (screenshot) hasil pengujian tugas.
- `system/`: Folder yang berisi inti (core) dari kerangka kerja (framework) MVC buatan sendiri, seperti file `Router.php` dan file inti lainnya.
- `index.php`: File utama sebagai titik masuk aplikasi (*front controller*).
- `README.md`: File dokumentasi dan laporan hasil implementasi praktikum.

## 3. Front controller
File `index.php` berfungsi sebagai gerbang utama atau satu-satunya pintu masuk (single entry point) bagi seluruh *request* dari *browser*. Daripada memanggil file PHP yang berbeda-beda secara langsung, semua permintaan dari pengguna akan diarahkan ke `index.php` ini terlebih dahulu. File ini yang kemudian akan memanggil komponen-komponen sistem (seperti router) untuk mendistribusikan *request* tersebut ke *controller* yang tepat.

## 4. Routing dan Pemetaan URL
| URL/Route | Controller | Method | Parameter | View |
|---|---|---|---|---|
| / | Home | index | - | home/index.php |
| home/index | Home | index | - | home/index.php |
| home/info/mvc | Home | info | mvc | home/info.php |
| info/routing | Home | info | routing | home/info.php |
| siswa/(:num) | Home | siswa | (angka nisn) | home/siswa.php |

**Penjelasan rute modifikasi ATM:**
Pemetaan rute `siswa/(:num)` mengarahkan URL ke Controller `Home` dan memanggil method `siswa`. Parameter angka dari URL (contohnya `2522500001`) akan ditangkap oleh *wildcard* `(:num)` dan dioper ke variabel parameter `$nisn` di dalam method tersebut. Selanjutnya, method ini mengembalikan *View* `home/siswa` yang merender rincian data mahasiswa berdasarkan konteks parameter yang dikirim.

## 5. Base URL dan Helper
- `base_url()`: Berfungsi untuk mengembalikan *string* alamat dasar URL aplikasi (root direktori proyek). Ini sangat berguna untuk memanggil file-file aset eksternal (statis). Contoh pada implementasi P2: `<link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">`.
- `site_url()`: Mirip dengan `base_url()`, tetapi fungsi ini digunakan khusus untuk membuat tautan internal navigasi aplikasi yang menyertakan file *front controller* (`index.php`), sehingga sistem *routing* bekerja dengan baik. Contoh implementasi: `<a href="<?= site_url('siswa/2522500001') ?>">Lihat Siswa</a>`.

## 6. Alur Request-response
**1. Alur eksekusi aktual P2:**
Saat pengguna mengakses URL di *browser*, *request* dikirim ke `index.php` sebagai Front Controller. File ini meneruskannya ke objek `Router` yang akan mencocokkan URL dengan rute di `routes.php`. Setelah cocok, router mengeksekusi `Controller` beserta method-nya. Controller memproses data array dan memuat file `View`. Hasil akhir dari View ini dikembalikan dalam format HTML sebagai *Response* ke *browser*.

**2. Posisi Model dalam arsitektur MVC lengkap:**
Browser → `index.php` → `Router` → `Controller` → meminta data ke `Model` → berinteraksi dengan `basis data/data` → mengirim data kembali ke `Model` → diterima `Controller` → dikirim ke `View` (untuk dirender) → Response akhir dikembalikan ke Browser.

Pada implementasi P2, Model belum digunakan karena akses dan pengelolaan basis data baru akan mulai diimplementasikan pada pertemuan P3.

## 7. Hasil Pengujian dan Debugging
**Skenario Pengujian Valid dan Tidak Valid:**
- **Valid:** Mengakses rute yang terdaftar (seperti `.../index.php/siswa/2522500001`). Hasil: halaman dirender dengan sempurna tanpa error, menampilkan data variabel siswa ke dalam *View*.
- **Tidak Valid:** Mengakses rute acak yang tidak ada atau *controller* yang tidak dibuat. Hasil: aplikasi memunculkan status kode 404 dari `Router.php` ("Route tidak valid" atau "Controller tidak ditemukan").

**Dokumentasi Debugging:**
- **Gejala:** Mendapat error "404 Not Found" bawaan asli dari *server* (Apache) saat mencoba mengakses rute `siswa/2522500001` melalui browser.
- **Penyebab:** Kesalahan bukan terletak pada struktur logika pemograman MVC, melainkan pada ketidaksesuaian *path* direktori di URL *browser* dengan nama folder fisik yang ada di direktori *web server* lokal (Laragon/htdocs), sehingga *request* sama sekali tidak pernah mencapai file `index.php`.
- **Perbaikan:** Memastikan penulisan URL di *browser* diketik sesuai dengan nama direktori yang tepat di repositori lokal, serta memastikan aturan routing custom `$route['siswa/(:num)'] = 'home/siswa/$1';` sudah tersimpan di `routes.php`.
- **Hasil Uji Ulang:** URL berhasil ditangkap oleh server, diarahkan ke rute yang benar oleh Router, berhasil mengakses method di Controller Home, dan menampilkan View ke browser tanpa memicu pesan error 404.

## 8. Bukti Tangkapan Layar
### Gambar 1.
![Gambar 1 -](dokumentasi/gambar1.png)

### Gambar 2.
![Gambar 2 -](dokumentasi/gambar2.png)

### Gambar 3.
![Gambar 2 -](dokumentasi/gambar3.png)

## 9. Kesimpulan P2
Dari praktikum ini, kerangka kerja MVC dasar yang dibangun sudah dapat menerima *request*, melakukan proses *routing* untuk menerjemahkan URL menjadi perintah spesifik, memanggil *Controller* beserta method dan parameternya, serta memuat antarmuka pengguna secara dinamis melalui *View*. Fitur baru yang akan ditambahkan pada implementasi P3 adalah layer *Model*, yang nantinya akan melengkapi keseluruhan arsitektur agar aplikasi ini dapat terhubung ke dalam pengelolaan basis data.