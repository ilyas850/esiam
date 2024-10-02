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
                            {{ $dataMhs->prodi->prodi }}
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
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title"><b>Matakuliah diambil</b></h3>
                    </div>
                    <div class="box-body">
                        <table class="table">
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
                                            {{ $krs->kurperiode->makul->makul ?? '' }}</td>
                                        <td>{{ $krs->kurperiode->makul->akt_sks_teori + $krs->kurperiode->makul->akt_sks_praktek ?? '' }}
                                        </td>
                                        <td>{{ $krs->kurperiode->dosen->nama ?? '' }}</td>
                                        <td>{{ $krs->remark == 1 ? 'valid' : 'belum' }}</td>
                                        <td>
                                            <button class="btn btn-danger btn-xs btn-cancel" data-id="{{ $krs->id_studentrecord }}" title="Batal">
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
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title"><b>Matakuliah ({{ $tahunActive->periode_tahun }} -
                                {{ $tipeActive->periode_tipe }})</b></h3>
                    </div>
                    <div class="box-body">
                        <table class="table" id="example1">
                            <thead>
                                <tr>
                                    <th>Kode - Makul</th>
                                    <th>SKS</th>
                                    <th>Semester</th>
                                    <th>Dosen</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataKrs as $key => $item)
                                    <tr>
                                        <td>{{ $item->makul->kode }} - {{ $item->makul->makul }}</td>
                                        <td>{{ $item->makul->akt_sks_teori + $item->makul->akt_sks_praktek }}</td>
                                        <td>{{ $item->semester->semester }}</td>
                                        <td>{{ $item->dosen->nama ?? '' }}</td>
                                        <td>
                                            <form action="{{ url('save-krs-manual') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id_student" value="{{ $dataMhs->idstudent }}">
                                                <input type="hidden" name="id_kurperiode"
                                                    value="{{ $item->id_kurperiode }}">
                                                <input type="hidden" name="id_kurtrans"
                                                    value="{{ $item->kurtrans->idkurtrans }}">
                                                <button type="submit" class="btn btn-success btn-xs">Tambah</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Event listener untuk tombol "Batal"
        $('.btn-cancel').on('click', function(e) {
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
                success: function(response) {
                    // Jika berhasil, hapus baris dari tabel
                    row.remove();
                    
                    // Tambahkan notifikasi atau pesan sukses jika perlu
                    alert('KRS berhasil dibatalkan.');
                },
                error: function(xhr) {
                    // Tangani error jika terjadi masalah
                    alert('Terjadi kesalahan. Gagal membatalkan KRS.');
                }
            });
        });
    });
</script>

