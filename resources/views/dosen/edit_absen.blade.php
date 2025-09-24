@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

<style>
    /* 1. Sembunyikan titik radio button asli (tetap sama) */
    #tabel-absensi .btn-group label input[type="radio"] {
    position: absolute;
    opacity: 0;
    }

    /* 2. Style dasar untuk SEMUA tombol (mode "outline") */
    #tabel-absensi .btn-group label.btn {
    background-color: #fff; /* Latar belakang putih */
    border: 1px solid; /* Garis pinggir solid */
    font-weight: bold;
    transition: all 0.2s ease-in-out;
    }

    /* 3. Atur warna TULISAN dan GARIS PINGGIR untuk setiap status */
    #tabel-absensi .btn-group label.btn-success { color: #00a65a; }
    #tabel-absensi .btn-group label.btn-warning { color: #f39c12; }
    #tabel-absensi .btn-group label.btn-info { color: #00c0ef; }
    #tabel-absensi .btn-group label.btn-danger { color: #dd4b39; }

    /* 4. Style untuk tombol yang AKTIF atau di-hover (mode "fill") */
    #tabel-absensi .btn-group label.btn.active,
    #tabel-absensi .btn-group label.btn:hover {
    color: #fff !important; /* Tulisan menjadi putih */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transform: translateY(-1px);
    }

    /* 5. Atur warna LATAR BELAKANG untuk tombol yang AKTIF atau di-hover */
    #tabel-absensi .btn-group label.btn-success.active,
    #tabel-absensi .btn-group label.btn-success:hover {
    background-color: #00a65a;
    }
    #tabel-absensi .btn-group label.btn-warning.active,
    #tabel-absensi .btn-group label.btn-warning:hover {
    background-color: #f39c12;
    }
    #tabel-absensi .btn-group label.btn-info.active,
    #tabel-absensi .btn-group label.btn-info:hover {
    background-color: #00c0ef;
    }
    #tabel-absensi .btn-group label.btn-danger.active,
    #tabel-absensi .btn-group label.btn-danger:hover {
    background-color: #dd4b39;
    }
    </style>


    @section('content')
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i> Edit Absensi Mahasiswa</h3>
                </div>
                <form action="{{ url('save_edit_absensi') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_bap" value="{{ $id }}">
                    <input type="hidden" name="id_kurperiode" value="{{ $idk }}">

                    <div class="box-body">
                        <div class="callout callout-info">
                            <h4><i class="fa fa-info-circle"></i> Petunjuk</h4>
                            <p>Ubah status kehadiran mahasiswa sesuai dengan data yang benar.</p>
                        </div>

                        <table class="table table-bordered table-hover" id="tabel-absensi">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Mahasiswa (Nama & NIM)</th>
                                    <th class="text-center" width="15%">Program Studi</th>
                                    <th class="text-center" width="10%">Kelas</th>
                                    <th class="text-center" width="35%">
                                        Status Kehadiran
                                        <div class="btn-group btn-group-xs pull-right">
                                            <button type="button" class="btn btn-default" id="tandaiHadirSemua">Hadir
                                                Semua</button>
                                            <button type="button" class="btn btn-default" id="tandaiAlpaSemua">Alpa
                                                Semua</button>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Gunakan variabel $abs sesuai dengan controller Anda --}}
                                @forelse ($abs as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item->nama }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $item->nim }}</small>
                                        </td>
                                        <td>{{ $item->prodi }}</td>
                                        <td class="text-center">{{ $item->kelas }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                {{-- Logika @if ditambahkan untuk menentukan status 'active' dan 'checked' dari
                                                database --}}

                                                {{-- Tombol Hadir --}}
                                                <label
                                                    class="btn btn-success {{ ($item->absensi ?? '') == 'ABSEN' ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ABSEN" {{ ($item->absensi ?? '') == 'ABSEN' ? 'checked' : '' }}> Hadir
                                                </label>

                                                {{-- Tombol Izin --}}
                                                <label
                                                    class="btn btn-warning {{ ($item->absensi ?? '') == 'IZIN' ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},IZIN" {{ ($item->absensi ?? '') == 'IZIN' ? 'checked' : '' }}> Izin
                                                </label>

                                                {{-- Tombol Sakit --}}
                                                <label
                                                    class="btn btn-info {{ ($item->absensi ?? '') == 'SAKIT' ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},SAKIT" {{ ($item->absensi ?? '') == 'SAKIT' ? 'checked' : '' }}> Sakit
                                                </label>

                                                {{-- Tombol Alpa --}}
                                                <label
                                                    class="btn btn-danger {{ ($item->absensi ?? '') == 'ALFA' ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ALFA" {{ ($item->absensi ?? '') == 'ALFA' ? 'checked' : '' }}> Alpa
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <p class="text-muted">Tidak ada data absensi yang dapat diedit.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer">
                        <button id="simpan" class="btn btn-primary btn-lg pull-right" type="submit"><i
                                class="fa fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </section>
    @endsection

    @section('script')
        <script>
            $(document).ready(function () {
                // =================================================================
                // LOGIKA UNTUK MEMBUAT TOMBOL BERFUNGSI SECARA MANUAL
                // =================================================================
                $('#tabel-absensi .btn-group label').on('click', function () {
                    var label = $(this);
                    var radio = label.find('input[type=radio]');
                    radio.prop('checked', true);
                    label.siblings().removeClass('active');
                    label.addClass('active');
                });

                // =================================================================
                // FUNGSI "TANDAI SEMUA"
                // =================================================================
                $('#tandaiHadirSemua').click(function () {
                    $('#tabel-absensi .btn-group label.btn-success').click();
                });

                $('#tandaiAlpaSemua').click(function () {
                    $('#tabel-absensi .btn-group label.btn-danger').click();
                });

                // =================================================================
                // FUNGSI UNTUK MENCEGAH DOUBLE SUBMIT
                // =================================================================
                $('form').submit(function () {
                    $('#simpan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                });
            });
        </script>
    @endsection