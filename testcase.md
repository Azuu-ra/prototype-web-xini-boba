# Test Case Sistem Xini Boba

**nama Kelas :** 4f Sistem Informasi

**Nama Projek :** Xini_Boba

---

| User      | Code | Admin                 | Code     |
| --------- | ---- | --------------------- | -------- |
| Login     | log  | Login Admin           | adm-log  |
| Register  | res  | Kelola Produk         | adm-prd  |
| Keranjang | krn  | Kelola Pesanan        | adm-ord  |
| Checkout  | cek  | Verifikasi Pembayaran | adm-ver  |
| Voucher   | voc  | Kelola Voucher        | adm-voc  |
| My Order  | ord  | Laporan               | adm-lap  |
| Check In  | chk  | Kelola User           | adm-usr  |
| Profil    | pro  | Dashboard             | adm-dash |
| Logout    | out  | Logout                | adm-out  |

---

## 1.Login

### User :

| ID Test | Kategori | Langkah pengujian                                       | Data masukan              | ExpecTed Result                                                   | Actual Reslt                                                                                          | Status | Bukti |
| ------- | -------- | ------------------------------------------------------- | ------------------------- | ----------------------------------------------------------------- | ----------------------------------------------------------------------------------------------------- | ------ | ----- |
| log-01  | Positive | login:masuk menggunakan akun yang sudah terdaftar       | user=kuy,pw=123           | berhasil masuk ke dahsboard                                       | berhasil masuk ke dashboard                                                                           | pas    |
| log-02  | Negatif  | Masukan Halaman User Dan Password Yang Kosong           | User : - pw : -           | Login Gagal, Pesan Error Muncul                                   | Login Gagal Menampilkan Pesan Harus Diisi Tidak Boleh Kosong                                          | Pas    |
| log-03  | Negatif  | Masukan User Yang Valid Dan Masukan Password Yang Salah | User : Kuy Pw : 986       | Login Gagal Pesan Salah Pw                                        | Login Gagal Dan Menampilkan Pesan User Tidak Di Temukan                                               |
| log-04  | Negatif  | Masukan User Yang Salah Dan Masukan Password Yang Benar | user : kay pw : 123       | Login Gagal Pesan Salah User                                      | Login Gagal Dan Menampilkan Pesan Pw Tidak Valid                                                      |
| log-05  | Negatif  | Masukan Password Kurang Dari 100 Karakter               | Pw : 150                  | Muncul Pesan Pw Minimal 100                                       | Muncul Pesan Pw Minimal 100 Karakter                                                                  |
| log-06  | Edge     | Login dengan username kosong                            | Username:Password:boba123 | Sistem menolak login dan menampilkan pesan "username wajib diisi" | Setelah tombol login ditekan,sistem tidak memproses login dan menampilkan pesan "username wajib diisi | PASS   |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 2.Register

### User :

| ID Test | Kategori | Langkah pengujian                                                   | Data masukan                          | ExpecTed Result                                                            | Actual Reslt                                                                                             | Status | Bukti |
| ------- | -------- | ------------------------------------------------------------------- | ------------------------------------- | -------------------------------------------------------------------------- | -------------------------------------------------------------------------------------------------------- | ------ | ----- |
| Res-01  | positive | buka halaman register, isi nama lengkap,username,email,dan password | user=kuy123,pw123,email=kuy@gmail.com | berhasil daftar dan masuk ke halaman login                                 | berhasil daftar akun dan masuk ke halaman login                                                          | pas    |
| Res-02  | Negatif  | Daftar Akun Dan Masukan Email Yang Tidak Sesuai Format              | Email : Subhan.Com                    | Muncul Pesan Email Tidak Valid                                             | Muncul Pesan Email Yang Tidak Sesuai Format                                                              |
| Res-03  | Negatif  | Apload Foto Transaksi Pembayaran Tanpa Memilih File                 | Klik Upload Tanpa File                | Muncul Pesan Pilih File Terlebih Dahulu                                    | Muncul Pesan Pilih File Dahulu                                                                           |
| Res-04  | Edge     | Registrasi dengan username yang sudah terdaftar                     | Username:admin                        | Sistem menolak registrasi dan menampilkan pesan "Username sudah digunakan" | Sistem memeriksa database,menemukan username yang sama,lalu menampilkan pesan "username sudah digunakan" | PASS   |

### Admin:

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 3. Keranjang

### User :

| ID Test | Kategori | Langkah pengujian                             | Data masukan                               | ExpecTed Result                                                     | Actual Reslt                                                                       | Status | Bukti |
| ------- | -------- | --------------------------------------------- | ------------------------------------------ | ------------------------------------------------------------------- | ---------------------------------------------------------------------------------- | ------ | ----- |
| krn-01  | positive | user meng klik menu boba ke dalam keranjang   | klik boba brown sugar,QYT:1                | berhasil memasukan menu boba kedalam keranjang dan jumlah bertambah | berhasil memasukan menu boba ke dalam keranjang                                    | pas    |
| krn-02  | positive | user menghapus salah satu menu di keranjang   | klik tombol hapus                          | menu terhapus,total item dan bayar otomatis berkurang               | keranjang menjadi berkurang,total item menjadi 1,dan total biaya menjadi berkurang | pas    |
| krn-03  | Negatif  | User mencoba menambah produk dengan jumlah 0  | QTY : 0                                    | Sistem menolak dan menampilkan pesan jumlah minimal 1               | Sistem menampilkan pesan jumlah minimal 1                                          |        |
| krn-04  | Negatif  | User mencoba menambah produk melebihi stok    | QTY : 999                                  | Sistem menolak dan menampilkan pesan stok tidak mencukupi           | Sistem menampilkan pesan stok tidak mencukupi                                      |        |
| krn-05  | Edge     | User menambahkan produk yang sama dua kali    | Boba Brown Sugar, QTY : 1 lalu tambah lagi | Jumlah produk bertambah menjadi 2, bukan membuat item baru          | Jumlah produk menjadi 2 pada item yang sama                                        |        |
| krn-06  | Edge     | User menghapus seluruh produk dalam keranjang | Klik hapus semua item                      | Keranjang menjadi kosong dan total pembayaran Rp0                   | Keranjang kosong dan total pembayaran Rp0                                          |        |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 4. Chekout

### User :

| ID Test | Kategori | Langkah pengujian                                                  | Data masukan                                    | ExpecTed Result                                                         | Actual Reslt                                                           | Status | Bukti |
| ------- | -------- | ------------------------------------------------------------------ | ----------------------------------------------- | ----------------------------------------------------------------------- | ---------------------------------------------------------------------- | ------ | ----- |
| cek-01  | positive | user melakukan konfirmasi pemesanan di keranjang                   | klik tombol konfirmasi & masuk orderan          | pesanan tersimpan dan user di arahkan ke halaman pembayaran             | hasil menampilkan pebayaran pesanan (QR)                               | pas    |
| cek-02  | positive | user melakukan scan QR pembayaran                                  | menggunakan QR code valid/aplikasi (dana,gopay) | pembayaran berhasil,status pesanan berubah menjadi menunggu verifikasi  | berhasil menyimpan my order                                            | pas    |
| cek-03  | Negatif  | User melakukan checkout tanpa login                                | Klik checkout                                   | Sistem meminta user login terlebih dahulu                               | Sistem mengarahkan user ke halaman login                               | P      |
| cek-04  | Negatif  | User melakukan checkout dengan keranjang kosong                    | Tidak ada produk dalam keranjang                | Sistem menolak checkout dan menampilkan pesan keranjang kosong          | Sistem menampilkan pesan keranjang kosong                              |        |
| cek-05  | Negatif  | User mengunggah bukti pembayaran dengan format file tidak didukung | File .exe                                       | Sistem menolak upload dan menampilkan pesan format tidak valid          | Sistem menampilkan pesan format file tidak valid                       |        |
| cek-06  | Edge     | User mengunggah file bukti pembayaran berukuran sangat besar       | File > 10 MB                                    | Sistem menolak upload dan menampilkan pesan ukuran file terlalu besar   | Sistem menampilkan pesan ukuran file terlalu besar                     |        |
| cek-07  | Positive | User berhasil mengunggah bukti pembayaran yang valid               | File JPG/PNG                                    | Bukti pembayaran tersimpan dan status menjadi menunggu verifikasi       | Bukti pembayaran berhasil tersimpan                                    |        |
| cek-08  | Edge     | User menekan tombol checkout dua kali secara cepat                 | Double click checkout                           | Sistem hanya membuat satu transaksi                                     | Sistem hanya menyimpan satu transaksi                                  |        |
| cek-09  | Edge     | Checkout dengan keranjang kosong                                   | Tidak ada produk dalam keranjang                | Sistem menolak proses checkout dan menampilkan pesan "keranjang kosong" | Sistem tetap memproses checkout meskipun tidak ada produk di keranjang | FAIL   |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 5. Vocher

### User :

| ID Test | Kategori | Langkah Pengujian                                   | Data Masukan                  | Expected Result                                                 | Actual Result | Status | Bukti |
| ------- | -------- | --------------------------------------------------- | ----------------------------- | --------------------------------------------------------------- | ------------- | ------ | ----- |
| voc-01  | Positive | User menukar poin dengan voucher yang tersedia      | Voucher Diskon 10%            | Voucher berhasil ditukar dan poin berkurang                     |               |        |
| voc-02  | Negatif  | User menukar voucher dengan poin tidak mencukupi    | Poin : 50, Voucher : 100 poin | Sistem menolak penukaran dan menampilkan pesan poin tidak cukup |               |        |
| voc-03  | Edge     | User mencoba menukar voucher yang sama berkali-kali | Voucher Diskon 10%            | Sistem membatasi penukaran sesuai aturan                        |               |        |
| voc-04  | Positive | User melihat daftar voucher yang tersedia           | Buka menu voucher             | Sistem menampilkan seluruh voucher yang aktif                   |               |        |
| voc-05  | Edge     | User membuka voucher yang sudah kadaluarsa          | Voucher Expired               | Sistem menampilkan status voucher tidak dapat digunakan         |               |        |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 6. My Order

### User :

| ID Test | Kategori | Langkah Pengujian                               | Data Masukan               | Expected Result                                           | Actual Result | Status | Bukti |
| ------- | -------- | ----------------------------------------------- | -------------------------- | --------------------------------------------------------- | ------------- | ------ | ----- |
| ord-01  | Positive | User membuka halaman My Order                   | Klik menu My Order         | Sistem menampilkan daftar pesanan user                    |               |        |
| ord-02  | Positive | User melihat detail pesanan                     | Klik salah satu pesanan    | Sistem menampilkan detail produk, jumlah, dan total harga |               |        |
| ord-03  | Edge     | User belum memiliki pesanan                     | Data pesanan kosong        | Sistem menampilkan pesan belum ada pesanan                |               |        |
| ord-04  | Positive | User melihat status pesanan menunggu verifikasi | Pesanan baru dibayar       | Sistem menampilkan status menunggu verifikasi             |               |        |
| ord-05  | Positive | User melihat status pesanan diproses            | Pesanan telah diverifikasi | Sistem menampilkan status diproses                        |               |        |
| ord-06  | Positive | User melihat status pesanan selesai             | Pesanan selesai            | Sistem menampilkan status selesai                         |               |        |
| ord-07  | Positive | User melihat riwayat pesanan lama               | Riwayat pesanan            | Sistem menampilkan seluruh riwayat transaksi              |               |        |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 7. Check In

### User :

| ID Test | Kategori | Langkah Pengujian                                           | Data Masukan                   | Expected Result                          | Actual Result | Status | bukti |
| ------- | -------- | ----------------------------------------------------------- | ------------------------------ | ---------------------------------------- | ------------- | ------ | ----- |
| chk-01  | Positive | User melakukan check in harian                              | Klik tombol Check In           | Sistem memberikan poin check in          |               |        |
| chk-02  | Negatif  | User melakukan check in lebih dari satu kali dalam sehari   | Klik Check In dua kali         | Sistem menolak check in kedua            |               |        |
| chk-03  | Positive | User melihat total poin setelah check in                    | Check In berhasil              | Total poin bertambah sesuai aturan       |               |        |
| chk-04  | Edge     | User membuka halaman check in sebelum melakukan check in    | Buka halaman check in          | Sistem menampilkan status belum check in |               |        |
| chk-05  | Edge     | User melakukan check in berturut-turut selama beberapa hari | Check In 7 hari berturut-turut | Sistem memberikan bonus sesuai ketentuan |               |        |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 8. Profil

### User :

| ID Test | Kategori | Langkah Pengujian                                        | Data Masukan        | Expected Result                      | Actual Result | Status | bukti |
| ------- | -------- | -------------------------------------------------------- | ------------------- | ------------------------------------ | ------------- | ------ | ----- |
| pro-01  | Positive | User membuka halaman profil                              | Klik menu profil    | Sistem menampilkan data profil user  |               |        |
| pro-02  | Positive | User mengubah nama profil                                | Nama baru           | Data berhasil diperbarui             |               |        |
| pro-03  | Positive | User mengubah foto profil                                | File JPG/PNG        | Foto profil berhasil diperbarui      |               |        |
| pro-04  | Negatif  | User mengunggah foto profil dengan format tidak didukung | File .exe           | Sistem menolak upload file           |               |        |
| pro-05  | Positive | User mengganti password dengan data valid                | Password baru       | Password berhasil diperbarui         |               |        |
| pro-06  | Negatif  | User memasukkan password lama yang salah                 | Password lama salah | Sistem menolak perubahan password    |               |        |
| pro-07  | Edge     | User mengosongkan data nama saat update profil           | Nama kosong         | Sistem menampilkan pesan wajib diisi |               |        |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---

## 9. Logout

### User :

| ID Test | Kategori | Langkah Pengujian                                       | Data Masukan  | Expected Result                                      | Actual Result | Status | Bukti |
| ------- | -------- | ------------------------------------------------------- | ------------- | ---------------------------------------------------- | ------------- | ------ | ----- |
| out-01  | Positive | User menekan tombol logout                              | Klik logout   | Sistem keluar dari akun dan kembali ke halaman login |               |        |
| out-02  | Edge     | User mencoba mengakses halaman dashboard setelah logout | URL dashboard | Sistem mengarahkan ke halaman login                  |               |        |

### Admin :

| ID Test | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status | Bukti |
| ------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ | ----- |

---
