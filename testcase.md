# Test Case Sistem Xini Boba

**nama Kelas :** 4f Sistem Informasi
**Nama kelompok :** Xini_Boba

---

## 1.Login Valid

<<<<<<< HEAD
=======
<<<<<<< HEAD
| ID Test   | Kategori | Langkah pengujian | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | --------------- | ------------ | ------ |
| Modul     | Login    |||
| TES N-01 | NEGATIF | MASUKAN HALAMAN USER DAN PASSWORD YANG KOSONG | USER : - PW : - | LOGIN GAGAL, PESAN ERROR MUNCUL | LOGIN GAGAL MENAMPILKAN PESAN HARUS DIISI TIDAK BOLEH KOSONG | FAIL |
| TES N-02 | NEGATIF | MASUKAN USER YANG VALID DAN MASUKAN PASSWORD YANG SALAH | USER : KUY PW : 986 | LOGIN GAGAL PESAN SALAH PW | LOGIN GAGAL DAN MENAMPILKAN PESAN USER TIDAK DI TEMUKAN |
| TES N-03 | NEGATIF | MASUKAN USER YANG SALAH DAN MASUKAN PASSWORD YANG BENAR | USER : KAY PW : 123 | LOGIN GAGAL PESAN SALAH USER | LOGIN GAGAL DAN MENAMPILKAN PESAN PW TIDAK VALID |
| TES N-04 | NEGATIF | MASUKAN PASSWORD KURANG DARI 100 KARAKTER | PW : 150 | MUNCUL PESAN PW MINIMAL 100 | MUNCUL PESAN PW MINIMAL 100 KARAKTER |
| TES N-05 | NEGATIF | DAFTAR AKUN DAN MASUKAN EMAIL YANG TIDAK SESUAI FORMAT | EMAIL : SUBHAN.COM | MUNCUL PESAN EMAIL TIDAK VALID | MUNCUL PESAN EMAIL YANG TIDAK SESUAI FORMAT |
| Tes N-06 | Negatif | Apload foto transaksi pembayaran  tanpa memilih file | Klik upload tanpa file | Muncul pesan pilih file terlebih dahulu | Muncul pesan pilih file dahulu |
 


=======
| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
>>>>>>> 3f385c8381e0596c37de9617b13c7f59535b62f1
| Modul     | Login    |
| Prioritas | Tinggi   |
>>>>>>> d921a83754841445cb763ff402f5caf6052f21c7

### Langkah:

1. Buka halaman login
2. Masukkan email valid
3. Masukkan password benar
4. Klik login

### Expected Result:

Berhasil masuk ke dashboard

### Status:

Passed# Test Case Sistem Xini Boba

## TC-001 Login Valid

| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
|TC-01|Edge|Login dengan username kosong|Username:Password:boba123|Sistem menolak login dan menampilkan pesan  "username wajib diisi"|Setelah tombol login ditekan,sistem tidak memproses login dan menampilkan pesan "username wajib diisi |PASS|
|TC-02|Ege|Registrasi dengan username yang  sudah terdaftar|Username:admin|Sistem menolak registrasi dan menampilkan pesan "Username sudah digunakan"|Sistem memeriksa database,menemukan username yang sama,lalu menampilkan  pesan "username sudah digunakan"|PASS|
|TC-03|Edge| Checkout dengan keranjang kosong|Tidak ada produk dalam keranjang|Sistem menolak proses checkout dan  menampilkan pesan "keranjang kosong"|Sistem tetap memproses checkout meskipun tidak ada produk di keranjang|FAIL|
### Langkah:

| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
|tes p-01|Positive|login:masuk menggunakan akun yang sudah terdaftar|user=kuy,pw=123|berhasil masuk ke dahsboard|berhasil masuk ke dashboard|pas|
|tes p-02|positive|buka halaman register, isi nama lengkap,username,email,dan password|user=kuy123,pw123,email=kuy@gmail.com|berhasil daftar dan masuk ke halaman login|berhasil daftar akun dan masuk ke halaman login|pas|
|tes p-03|positive|user meng klik menu boba ke dalam keranjang|klik boba brown sugar,QYT:1|berhasil memasukan menu boba kedalam keranjang dan jumlah bertambah|berhasil memasukan menu boba ke dalam keranjang|pas
|tes p-04|positive|user menghapus salah satu menu di keranjang|klik tombol hapus|menu terhapus,total item dan bayar otomatis berkurang|keranjang menjadi berkurang,total item menjadi 1,dan total biaya menjadi berkurang|pas|
|tes p-05|positive|user melakukan konfirmasi pemesanan di keranjang|klik tombol konfirmasi & masuk orderan|pesanan tersimpan dan user di arahkan ke halaman pembayaran|hasil menampilkan pebayaran pesanan (QR)|pas|
|tes p-06|positive|user melakukan scan QR pembayaran|menggunakan QR code valid/aplikasi (dana,gopay)|pembayaran berhasil,status pesanan berubah menjadi menunggu verifikasi|berhasil menyimpan my order|pas|


1. Buka halaman login
2. Masukkan email valid
3. Masukkan password benar
4. Klik login

### Expected Result:

Berhasil masuk ke dashboard

### Status:

Passed
