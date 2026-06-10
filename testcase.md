# Test Case Sistem Xini Boba

**nama Kelas :** 4f Sistem Informasi
**Nama kelompok :** Xini_Boba

---

## 1.Login Valid

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

| ID        | TC-001 |
| --------- | ------ |
| Modul     | Login  |
| Prioritas | Tinggi |

### Langkah:

1. Buka halaman login
2. Masukkan email valid
3. Masukkan password benar
4. Klik login

### Expected Result:

Berhasil masuk ke dashboard

### Status:

Passed
