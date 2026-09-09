# Antigravity Workspace Guidelines for ESIAM

## Aturan Utama Desain & Tampilan (UI/UX) - Wajib AdminLTE 2.4

Setiap kali membuat, mengubah, merapikan, atau merevisi tampilan (view `.blade.php`), komponen UI, tabel, form, modal, kartu metrik, maupun dashboard di proyek ini:
**WAJIB SECARA OTOMATIS menggunakan desain dan komponen standar AdminLTE versi 2.4 (Bootstrap 3).**
Pengguna tidak perlu lagi mengulang instruksi "buat sesuai AdminLTE" atau "konsisten dengan AdminLTE". Agent harus langsung menerapkannya secara otomatis dan proaktif.

---

### 1. Struktur Halaman & Layout
- Selalu ikuti struktur layout standar ESIAM:
  ```blade
  @extends('layouts.master')

  @section('side')
      @include('layouts.side')
  @endsection

  @section('content')
      <section class="content">
          ...
      </section>
  @endsection
  ```
- Gunakan grid system Bootstrap 3: `.container-fluid`, `.row`, `.col-md-*`, `.col-sm-*`, `.col-xs-*`.

### 2. Boxes & Containers (Komponen Utama)
- Gunakan komponen box AdminLTE standar:
  - Container: `.box` (atau `.box.box-solid`)
  - Header: `.box-header.with-border` dengan `.box-title` dan `.box-tools.pull-right`
  - Konten: `.box-body` (bisa dengan `.table-responsive` jika berisi tabel)
  - Footer: `.box-footer`
  - Varian warna border/header: `.box-primary`, `.box-success`, `.box-info`, `.box-warning`, `.box-danger`, `.box-default`.

### 3. Widget & Kartu Metrik (Dashboard & Statistics)
- **Info Box**:
  ```blade
  <div class="info-box">
      <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
      <div class="info-box-content">
          <span class="info-box-text">Judul Metrik</span>
          <span class="info-box-number">123</span>
      </div>
  </div>
  ```
- **Small Box**:
  ```blade
  <div class="small-box bg-green">
      <div class="inner">
          <h3>123</h3>
          <p>Deskripsi</p>
      </div>
      <div class="icon"><i class="fa fa-check"></i></div>
      <a href="..." class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
  </div>
  ```
- **User Widget**: `.box.box-widget.widget-user` untuk informasi profil/ringkasan akun.

### 4. Tabel & Data
- Gunakan class tabel Bootstrap 3 / AdminLTE:
  ```blade
  <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
          ...
      </table>
  </div>
  ```
- Jika memerlukan DataTables, inisialisasi dengan style AdminLTE standar.
- Jika membuat custom pagination, gunakan struktur `.pagination` Bootstrap 3 (`<ul class="pagination pagination-sm no-margin pull-right"><li><a href="...">1</a></li></ul>`) dengan sliding window agar tidak meluap ke bawah.

### 5. Tombol (Buttons) & Aksi
- Gunakan class tombol Bootstrap 3:
  - Ukuran: `.btn-xs`, `.btn-sm`, `.btn-flat`.
  - Varian warna AdminLTE: `.btn-primary`, `.btn-success`, `.btn-info`, `.btn-warning`, `.btn-danger`, `.btn-default`.
  - Tombol aplikasi/shortcut: `.btn.btn-app`.

### 6. Badges, Labels & Callouts
- Status / Tag: Gunakan `.label` dengan `.label-success`, `.label-warning`, `.label-danger`, `.label-info`, `.label-primary`, `.label-default` (atau `.badge`).
- Alert / Banner Perhatian: Gunakan AdminLTE Callout (`.callout.callout-warning`, `.callout.callout-info`, `.callout.callout-danger`, `.callout.callout-success`).

### 7. Form, Input & Modal
- Form: `.form-group`, `<label>`, `.form-control`, `select.form-control`.
- Input group: `.input-group`, `.input-group-addon`.
- Modal: Modal standar Bootstrap 3 AdminLTE (`.modal`, `.modal-dialog`, `.modal-content`, `.modal-header`, `.modal-body`, `.modal-footer`).

### 8. Ikon & Formatting
- Ikon: Gunakan Font Awesome (`fa fa-*`) atau Glyphicons (`glyphicon glyphicon-*`).
- Bahasa & Tanggal: Gunakan format bahasa Indonesia (nama hari: Senin, Selasa... nama bulan: Januari, Februari...).
- Hindari inline styling custom yang bertentangan dengan tema AdminLTE 2.4.
