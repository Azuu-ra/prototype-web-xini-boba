# Test Case Sistem Xini Boba

**nama Kelas :** 4f Sistem Informasi

**Nama Projek :** Xini_Boba

---

## 1.Login 

| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
|log-01|Positive|login:masuk menggunakan akun yang sudah terdaftar|user=kuy,pw=123|berhasil masuk ke dahsboard|berhasil masuk ke dashboard|pas|
|log-02 | Negatif | Masukan Halaman User Dan Password Yang Kosong | User : - pw : - | Login Gagal, Pesan Error Muncul | Login Gagal Menampilkan Pesan Harus Diisi Tidak Boleh Kosong | Fail |
|log-03 | Negatif | Masukan User Yang Valid Dan Masukan Password Yang Salah | User : Kuy Pw : 986 | Login Gagal Pesan Salah Pw | Login Gagal Dan Menampilkan Pesan User Tidak Di Temukan |
| log-04 | Negatif | Masukan User Yang Salah Dan Masukan Password Yang Benar | user : kay pw : 123 | Login Gagal Pesan Salah User | Login Gagal Dan Menampilkan Pesan Pw Tidak Valid |
| log-05 | Negatif | Masukan Password Kurang Dari 100 Karakter | Pw : 150 | Muncul Pesan Pw Minimal 100 | Muncul Pesan Pw Minimal 100 Karakter |
|log-06|Edge|Login dengan username kosong|Username:Password:boba123|Sistem menolak login dan menampilkan pesan  "username wajib diisi"|Setelah tombol login ditekan,sistem tidak memproses login dan menampilkan pesan "username wajib diisi |PASS|

---

## 2.Register

| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
|Res-01|positive|buka halaman register, isi nama lengkap,username,email,dan password|user=kuy123,pw123,email=kuy@gmail.com|berhasil daftar dan masuk ke halaman login|berhasil daftar akun dan masuk ke halaman login|pas|
| Res-02 | Negatif | Daftar Akun Dan Masukan Email Yang Tidak Sesuai Format | Email : Subhan.Com | Muncul Pesan Email Tidak Valid | Muncul Pesan Email Yang Tidak Sesuai Format |
| Res-03 | Negatif | Apload Foto Transaksi Pembayaran  Tanpa Memilih File | Klik Upload Tanpa File | Muncul Pesan Pilih File Terlebih Dahulu | Muncul Pesan Pilih File Dahulu |
|Res-04|Ege|Registrasi dengan username yang  sudah terdaftar|Username:admin|Sistem menolak registrasi dan menampilkan pesan "Username sudah digunakan"|Sistem memeriksa database,menemukan username yang sama,lalu menampilkan  pesan "username sudah digunakan"|PASS|
|Res-05|Edge| Checkout dengan keranjang kosong|Tidak ada produk dalam keranjang|Sistem menolak proses checkout dan  menampilkan pesan "keranjang kosong"|Sistem tetap memproses checkout meskipun tidak ada produk di keranjang|FAIL|

---

## 3. Keranjang

| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
|krn-01|positive|user meng klik menu boba ke dalam keranjang|klik boba brown sugar,QYT:1|berhasil memasukan menu boba kedalam keranjang dan jumlah bertambah|berhasil memasukan menu boba ke dalam keranjang|pas
|krn-02|positive|user menghapus salah satu menu di keranjang|klik tombol hapus|menu terhapus,total item dan bayar otomatis berkurang|keranjang menjadi berkurang,total item menjadi 1,dan total biaya menjadi berkurang|pas|

---

## 4. Chekout
| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
|cek-01|positive|user melakukan konfirmasi pemesanan di keranjang|klik tombol konfirmasi & masuk orderan|pesanan tersimpan dan user di arahkan ke halaman pembayaran|hasil menampilkan pebayaran pesanan (QR)|pas|
|cek-02|positive|user melakukan scan QR pembayaran|menggunakan QR code valid/aplikasi (dana,gopay)|pembayaran berhasil,status pesanan berubah menjadi menunggu verifikasi|berhasil menyimpan my order|pas|

---

