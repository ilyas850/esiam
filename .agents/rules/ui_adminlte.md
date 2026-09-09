# Aturan Otomatis Desain UI/UX AdminLTE 2.4

Setiap kali agent diminta untuk membuat, memodifikasi, merapikan, atau merevisi halaman tampilan (`.blade.php`), komponen UI, modal, tabel, atau dashboard di repository ini:

1. **Wajib Otomatis Menggunakan Standar AdminLTE 2.4 (Bootstrap 3)** tanpa perlu instruksi tambahan dari pengguna.
2. **Komponen Wajib**:
   - Card/Box: `.box`, `.box-header.with-border`, `.box-title`, `.box-body`, `.box-footer`, dengan warna `.box-primary`, `.box-success`, `.box-info`, `.box-warning`, `.box-danger`.
   - Statistik/Metrik: `.info-box` atau `.small-box` standar AdminLTE.
   - Tabel: `.table.table-bordered.table-striped.table-hover` dalam `.table-responsive`.
   - Tombol: `.btn`, `.btn-sm`/`.btn-xs`, `.btn-flat`, warna standar AdminLTE.
   - Tag/Status: `.label` (`.label-success`, `.label-warning`, `.label-danger`, `.label-info`).
   - Callout/Alert: `.callout` (`.callout-warning`, `.callout-info`, dll.).
   - Ikon: Font Awesome (`fa fa-*`).
   - Form: `.form-group`, `.form-control`, `.input-group`.
3. **Format Tanggal**: Bahasa Indonesia (Senin-Minggu, Januari-Desember).
4. **Dilarang**: Menggunakan custom CSS modern (seperti Tailwind CSS atau ad-hoc flexbox yang merusak tema) yang tidak kompatibel dengan AdminLTE 2.4.
