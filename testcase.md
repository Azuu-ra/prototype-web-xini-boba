# Test Case Sistem Xini Boba

**nama Kelas :** 4f Sistem Informasi
**Nama kelompok :** Xini_Boba

---

## 1.Login Valid

| Modul     | Login    |
| Prioritas | Tinggi   |

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

| ID        | TC-001 |
| --------- | ------ |
| Modul     | Login  |
| Prioritas | Tinggi |

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
