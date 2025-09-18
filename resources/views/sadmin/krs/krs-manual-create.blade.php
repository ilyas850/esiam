@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>KRS Manual Mahasiswa ({{ $tahunActive->periode_tahun }} -
                        {{ $tipeActive->periode_tipe }})</b></h3>
                <br><br>
                <table width="100%">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $dataMhs->nama }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>
                            {{ $dataMhs->prodi }}
                        </td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td> {{ $dataMhs->nim }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>
                            {{ $dataMhs->kelas->kelas }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-5">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title"><b>Matakuliah diambil</b></h3>
                    </div>
                    <div class="box-body">
                        <table class="table" id="matakuliah-diambil">
                            <thead>
                                <th>Kode - Makul</th>
                                <th>SKS</th>
                                <th>Dosen</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>
                                @foreach ($dataKrsMhs as $key => $krs)
                                    <tr>
                                        <td>{{ $krs->kurperiode->makul->kode ?? '' }} -
                                            {{ $krs->kurperiode->makul->makul ?? '' }}
                                        </td>
                                        <td>{{ $krs->kurperiode->makul->akt_sks_teori + $krs->kurperiode->makul->akt_sks_praktek ?? '' }}
                                        </td>
                                        <td>{{ $krs->kurperiode->dosen->nama ?? '' }}</td>
                                        <td>{{ $krs->remark == 1 ? 'valid' : 'belum' }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs btn-cancel"
                                                data-id="{{ $krs->id_studentrecord }}" title="Batal">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title"><b>Matakuliah ({{ $tahunActive->periode_tahun }} -
                                {{ $tipeActive->periode_tipe }})</b></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="krs-table">
                                <thead>
                                    <tr>
                                        <th>Kode - Matakuliah</th>
                                        <th>Kelas</th>
                                        <th>SKS</th>
                                        <th>Semester</th>
                                        <th>Dosen</th>
                                        <th>Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dataKrs as $item)
                                        <tr>
                                            <td>{{ optional($item->makul)->kode }} - {{ optional($item->makul)->makul }}</td>
                                            <td>{{ optional($item->kelas)->kelas }}</td>
                                            <td>{{ (optional($item->makul)->akt_sks_teori ?? 0) + (optional($item->makul)->akt_sks_praktek ?? 0) }}
                                            </td>
                                            <td>{{ optional($item->semester)->semester }}</td>
                                            <td>{{ optional($item->dosen)->nama }}</td>
                                            <td>
                                                @if ($item->kurtrans)
                                                    <button type="button" class="btn btn-success btn-xs btn-save-krs"
                                                        data-id-student="{{ $dataMhs->idstudent }}"
                                                        data-id-kurperiode="{{ $item->id_kurperiode }}"
                                                        data-id-kurtrans="{{ optional($item->kurtrans)->idkurtrans }}">
                                                        <i class="fa fa-plus"></i> Tambah
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-default btn-xs" disabled
                                                        title="Mata kuliah ini tidak tersedia dalam kurikulum Anda">
                                                        Tidak Tersedia
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada mata kuliah yang ditawarkan untuk
                                                kelas ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        {{-- Ini akan menampilkan link paginasi (Halaman 1, 2, 3, dst.) --}}
                        <div class="text-center">
                            {{ $dataKrs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Event listener untuk tombol "Batal"
        $('.btn-cancel').on('click', function (e) {
            e.preventDefault(); // Mencegah reload halaman

            var studentRecordId = $(this).data('id'); // Ambil id_studentrecord dari tombol
            var row = $(this).closest('tr'); // Ambil baris tabel terkait

            // Konfirmasi sebelum mengirim permintaan
            if (!confirm('Apakah Anda yakin ingin membatalkan KRS ini?')) {
                return;
            }

            // Kirim permintaan AJAX
            $.ajax({
                url: '/krs-manual-cancel/' + studentRecordId,
                type: 'GET', // Gunakan metode GET sesuai dengan URL Anda
                success: function (response) {
                    // Jika berhasil, hapus baris dari tabel
                    row.remove();

                    // Tambahkan notifikasi atau pesan sukses jika perlu
                    alert('KRS berhasil dibatalkan.');
                },
                error: function (xhr) {
                    // Tangani error jika terjadi masalah
                    alert('Terjadi kesalahan. Gagal membatalkan KRS.');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.btn-save-krs').on('click', function() {
            var button = $(this);
            var idStudent = button.data('id-student');
            var idKurperiode = button.data('id-kurperiode');
            var idKurtrans = button.data('id-kurtrans');

            // Menonaktifkan tombol untuk mencegah klik ganda
            button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: '{{ url("save-krs-manual") }}', // Ganti dengan URL Anda
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_student: idStudent,
                    id_kurperiode: idKurperiode,
                    id_kurtrans: idKurtrans
                },
                success: function(response) {
                    // Contoh: Ganti tombol menjadi status "Ditambahkan"
                    button.removeClass('btn-success').addClass('btn-default');
                    button.html('<i class="fa fa-check"></i> Ditambahkan');
                    // Tambahkan notifikasi sukses jika perlu
                    // alert('KRS berhasil ditambahkan!');
                },
                error: function(xhr) {
                    // Jika gagal, aktifkan kembali tombol
                    button.prop('disabled', false).removeClass('btn-default').addClass('btn-success');
                    button.html('<i class="fa fa-plus"></i> Tambah');
                    // Tampilkan pesan error
                    alert('Terjadi kesalahan. Gagal menambahkan KRS.');
                }
            });
        });
    });
</script>