# Test Case Sistem Xini Boba

**nama Kelas :** 4f Sistem Informasi
**Nama kelompok :** Xini_Boba

---

## 1.Login Valid

| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
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

| ID Test   | Kategori | Langkah pengujian | Data masukan | ExpecTed Result | Actual Reslt | Status |
| --------- | -------- | ----------------- | ------------ | --------------- | ------------ | ------ |
|TC-01|Edge|Login dengan username kosong|Username:Password:boba123|Sistem menolak login dan menampilkan pesan  "username wajib diisi"|Setelah tombol login ditekan,sistem tidak memproses login dan menampilkan pesan "username wajib diisi |PASS|
|TC-02|Ege|Registrasi dengan username yang  sudah terdaftar|Username:admin|Sistem menolak registrasi dan menampilkan pesan "Username sudah digunakan"|Sistem memeriksa database,menemukan username yang sama,lalu menampilkan  pesan "username sudah digunakan"|PASS|
|TC-03|Edge| Checkout dengan keranjang kosong|Tidak ada produk dalam keranjang|Sistem menolak proses checkout dan  menampilkan pesan "keranjang kosong"|Sistem tetap memproses checkout meskipun tidak ada produk di keranjang|FAIL|
### Langkah:

1. Buka halaman login
2. Masukkan email valid
3. Masukkan password benar
4. Klik login

### Expected Result:

Berhasil masuk ke dashboard

### Status:

Passed
