@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

<style>
    #tabel-absensi .btn-group label input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    #tabel-absensi .btn-group label.btn {
        background-color: #fff;
        border: 1px solid;
        font-weight: bold;
        transition: all 0.2s ease-in-out;
    }

    #tabel-absensi .btn-group label.btn-success { color: #00a65a; }
    #tabel-absensi .btn-group label.btn-warning { color: #f39c12; }
    #tabel-absensi .btn-group label.btn-info { color: #00c0ef; }
    #tabel-absensi .btn-group label.btn-danger { color: #dd4b39; }

    #tabel-absensi .btn-group label.btn.active,
    #tabel-absensi .btn-group label.btn:hover {
        color: #fff !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-1px);
    }

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
                <button type="button" class="btn btn-warning btn-sm pull-right" data-toggle="modal" data-target="#modal-lintas-kelas">
                    <i class="fa fa-user-plus"></i> Tambah Mahasiswa Lintas Kelas
                </button>
            </div>
            <form action="{{ url('save_edit_absensi_kprd') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_bap" value="{{ $id }}">
                <input type="hidden" name="id_kurperiode" value="{{ $idk }}">

                <div class="box-body">
                    <div class="callout callout-info">
                        <h4><i class="fa fa-info-circle"></i> Petunjuk</h4>
                        <p>Ubah status kehadiran mahasiswa sesuai dengan data yang benar. Gunakan tombol <strong>Tambah Mahasiswa Lintas Kelas</strong> jika ada mahasiswa dari kelas paralel lain yang mengikuti sesi perkuliahan ini.</p>
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
                            @forelse ($abs as $item)
                                <tr class="{{ !empty($item->hadir_di_kelas_lain) ? 'info row-hadir-kelas-lain' : '' }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $item->nama }}</strong>
                                        @if(!empty($item->hadir_di_kelas_lain))
                                            <span class="label label-primary pull-right" style="font-size:10px;"><i class="fa fa-exchange"></i> Lintas Kelas</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">{{ $item->nim }}</small>
                                        @if(!empty($item->hadir_di_kelas_lain))
                                            <br>
                                            <span class="label label-info" style="display:inline-block; margin-top:3px; font-size:11px;">
                                                <i class="fa fa-check-circle"></i> {{ $item->keterangan_kelas_lain }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $item->prodi }}</td>
                                    <td class="text-center">{{ $item->kelas }}</td>
                                    <td class="text-center">
                                        @php
                                            $statusAktif = $item->absensi ?? '';
                                            if (empty($statusAktif) && !empty($item->hadir_di_kelas_lain)) {
                                                $statusAktif = 'ABSEN';
                                            }
                                        @endphp
                                        <div class="btn-group btn-group-sm">
                                            <label class="btn btn-success {{ $statusAktif == 'ABSEN' ? 'active' : '' }}">
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},ABSEN" {{ $statusAktif == 'ABSEN' ? 'checked' : '' }}> Hadir
                                            </label>
                                            <label class="btn btn-warning {{ $statusAktif == 'IZIN' ? 'active' : '' }}">
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},IZIN" {{ $statusAktif == 'IZIN' ? 'checked' : '' }}> Izin
                                            </label>
                                            <label class="btn btn-info {{ $statusAktif == 'SAKIT' ? 'active' : '' }}">
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},SAKIT" {{ $statusAktif == 'SAKIT' ? 'checked' : '' }}> Sakit
                                            </label>
                                            <label class="btn btn-danger {{ $statusAktif == 'ALFA' ? 'active' : '' }}">
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},ALFA" {{ $statusAktif == 'ALFA' ? 'checked' : '' }}> Alpa
                                            </label>
                                        </div>
                                        @if(!empty($item->hadir_di_kelas_lain))
                                            <br>
                                            <small class="text-success" style="font-weight: bold;">
                                                <i class="fa fa-check-circle"></i> Otomatis Hadir (Lintas Kelas)
                                            </small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <p class="text-muted">Tidak ada data absensi yang dapat diedit.</p>
                                    </td>
                                </tr>
                            @endforelse

                            {{-- Mahasiswa Lintas Kelas yang tersimpan pada BAP ini --}}
                            @if(isset($cross_absen) && count($cross_absen) > 0)
                                @foreach ($cross_absen as $item)
                                    <tr class="warning baris-lintas-kelas" data-sr-id="{{ $item->id_studentrecord }}">
                                        <td class="text-center">{{ count($abs) + $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $item->nama }}</strong>
                                            <span class="label label-warning pull-right"><i class="fa fa-exchange"></i> Lintas Kelas</span>
                                            <br>
                                            <small class="text-muted">{{ $item->nim }}</small>
                                            @php
                                                $curTarget = 0;
                                                if (!empty($item->keterangan) && preg_match('/Target P-(\d+)/', $item->keterangan, $m)) {
                                                    $curTarget = (int) $m[1];
                                                }
                                            @endphp
                                            <div style="margin-top: 5px;">
                                                <label style="font-size: 11px; font-weight: normal; margin-bottom: 2px; color: #555;">
                                                    <i class="fa fa-bullseye text-primary"></i> <strong>Target di Kelas Asal:</strong>
                                                </label>
                                                <select name="target_pertemuan[{{ $item->id_studentrecord }}]" class="form-control input-sm" style="height: 28px; padding: 2px 8px; font-size: 12px; width: 140px; display: inline-block;">
                                                    @for($p = 1; $p <= 16; $p++)
                                                        <option value="{{ $p }}" {{ $curTarget == $p ? 'selected' : '' }}>Pertemuan {{ $p }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            @if(!empty($item->keterangan))
                                                <br><small class="text-muted"><i class="fa fa-tag"></i> {{ $item->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->prodi }}</td>
                                        <td class="text-center">{{ $item->kelas }}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <label class="btn btn-success {{ ($item->absensi == 'ABSEN' || empty($item->absensi)) ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ABSEN" {{ ($item->absensi == 'ABSEN' || empty($item->absensi)) ? 'checked' : '' }}> Hadir
                                                </label>
                                                <label class="btn btn-warning {{ $item->absensi == 'IZIN' ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},IZIN" {{ $item->absensi == 'IZIN' ? 'checked' : '' }}> Izin
                                                </label>
                                                <label class="btn btn-info {{ $item->absensi == 'SAKIT' ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},SAKIT" {{ $item->absensi == 'SAKIT' ? 'checked' : '' }}> Sakit
                                                </label>
                                                <label class="btn btn-danger {{ $item->absensi == 'ALFA' ? 'active' : '' }}">
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ALFA" {{ $item->absensi == 'ALFA' ? 'checked' : '' }}> Alpa
                                                </label>
                                            </div>
                                            <button type="button" class="btn btn-danger btn-xs hapus-baris-lintas" title="Hapus dari absensi kelas ini" style="margin-left: 5px;"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <button id="simpan" class="btn btn-primary btn-lg pull-right" type="submit"><i
                            class="fa fa-save"></i> Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <!-- Modal Tambah Mahasiswa Lintas Kelas -->
        <div class="modal fade" id="modal-lintas-kelas" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-yellow">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-exchange"></i> Tambah Mahasiswa Lintas Kelas (Sit-in / Titip Hadir)</h4>
                    </div>
                    <div class="modal-body">
                        <div class="callout callout-warning">
                            <p><i class="fa fa-info-circle"></i> Cari mahasiswa yang memprogram mata kuliah yang sama dari kelas paralel lain (misal: mahasiswa Kelas B yang hadir di kelas ini). Saat absensi disimpan, kehadiran otomatis disinkronkan ke rekap kelas asalnya.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="input-group">
                                    <input type="text" id="cari-mhs-input" class="form-control input-lg" placeholder="Ketik NIM atau Nama Mahasiswa...">
                                    <span class="input-group-btn">
                                        <button type="button" id="btn-cari-mhs" class="btn btn-primary btn-lg"><i class="fa fa-search"></i> Cari</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 20px;">
                            <div id="loading-cari" style="display: none;" class="text-center">
                                <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data mahasiswa...
                            </div>
                            <div class="table-responsive" id="tabel-hasil-cari-wrapper" style="display: none; max-height: 350px; overflow-y: auto;">
                                <table class="table table-bordered table-striped" id="tabel-hasil-cari">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th width="15%">NIM</th>
                                            <th width="30%">Nama Mahasiswa</th>
                                            <th width="20%">Program Studi</th>
                                            <th width="15%" class="text-center">Kelas Asal</th>
                                            <th width="20%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="pesan-kosong" style="display: none;" class="alert alert-warning text-center">
                                Mahasiswa tidak ditemukan di kelas paralel manapun untuk mata kuliah ini.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {

            // Event handler untuk tombol absensi (delegated agar berlaku untuk baris baru)
            $(document).on('click', '#tabel-absensi .btn-group label', function () {
                var label = $(this);
                var radio = label.find('input[type=radio]');
                radio.prop('checked', true);
                label.siblings().removeClass('active');
                label.addClass('active');
            });

            // Tandai semua Hadir
            $('#tandaiHadirSemua').click(function () {
                $('#tabel-absensi .btn-group label.btn-success').click();
            });

            // Tandai semua Alpa (kecuali mahasiswa yang sudah hadir di kelas lain)
            $('#tandaiAlpaSemua').click(function () {
                $('#tabel-absensi tbody tr').each(function () {
                    if (!$(this).hasClass('row-hadir-kelas-lain')) {
                        $(this).find('.btn-group label.btn-danger').click();
                    }
                });
            });

            // Mencegah double submit
            $('form').submit(function () {
                $('#simpan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            });

            // Hapus baris mahasiswa lintas kelas
            $(document).on('click', '.hapus-baris-lintas', function () {
                $(this).closest('tr').remove();
                updateNomorUrut();
            });

            function updateNomorUrut() {
                $('#tabel-absensi tbody tr').each(function (index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            // Pencarian Mahasiswa Lintas Kelas
            $('#btn-cari-mhs').click(function () {
                cariMahasiswa();
            });

            $('#cari-mhs-input').keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    cariMahasiswa();
                }
            });

            function cariMahasiswa() {
                var q = $('#cari-mhs-input').val().trim();
                $('#loading-cari').show();
                $('#tabel-hasil-cari-wrapper').hide();
                $('#pesan-kosong').hide();

                $.ajax({
                    url: "{{ url('cari_mhs_lintas_kelas_kprd/' . $id) }}",
                    type: "GET",
                    data: { q: q },
                    dataType: "json",
                    success: function (data) {
                        $('#loading-cari').hide();
                        if (!data || data.length === 0) {
                            $('#pesan-kosong').show();
                            return;
                        }

                        var tbody = $('#tabel-hasil-cari tbody');
                        tbody.empty();

                        $.each(data, function (index, mhs) {
                            var isAlreadyInTable = $("input[name='absensi_radio[" + mhs.id_studentrecord + "]']").length > 0;
                            var btnAksi = isAlreadyInTable 
                                ? '<span class="label label-default"><i class="fa fa-check"></i> Sudah Ada</span>'
                                : '<button type="button" class="btn btn-success btn-xs btn-pilih-mhs" data-mhs=\'' + JSON.stringify(mhs) + '\'><i class="fa fa-plus"></i> Masukkan</button>';

                            var row = '<tr>' +
                                '<td><strong>' + mhs.nim + '</strong></td>' +
                                '<td>' + mhs.nama + '</td>' +
                                '<td>' + mhs.prodi + '</td>' +
                                '<td class="text-center"><span class="label label-primary">' + mhs.kelas + '</span></td>' +
                                '<td class="text-center">' + btnAksi + '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });

                        $('#tabel-hasil-cari-wrapper').show();
                    },
                    error: function () {
                        $('#loading-cari').hide();
                        alert('Gagal memuat data mahasiswa.');
                    }
                });
            }

            // Pilih Mahasiswa dari Modal
            $(document).on('click', '.btn-pilih-mhs', function () {
                var mhs = $(this).data('mhs');
                var isAlreadyInTable = $("input[name='absensi_radio[" + mhs.id_studentrecord + "]']").length > 0;
                if (isAlreadyInTable) {
                    alert('Mahasiswa ini sudah ada dalam daftar absensi!');
                    return;
                }

                var selectTargetHtml = '<div style="margin-top: 5px;">' +
                    '<label style="font-size: 11px; font-weight: normal; margin-bottom: 2px; color: #555;">' +
                    '<i class="fa fa-bullseye text-primary"></i> <strong>Target di Kelas Asal:</strong>' +
                    '</label> ' +
                    '<select name="target_pertemuan[' + mhs.id_studentrecord + ']" class="form-control input-sm" style="height: 28px; padding: 2px 8px; font-size: 12px; width: 140px; display: inline-block;">';
                for (var p = 1; p <= 16; p++) {
                    var isSelected = (p === parseInt("{{ $bap->pertemuan ?? 1 }}")) ? 'selected' : '';
                    selectTargetHtml += '<option value="' + p + '" ' + isSelected + '>Pertemuan ' + p + '</option>';
                }
                selectTargetHtml += '</select></div>';

                var noUrut = $('#tabel-absensi tbody tr').length + 1;
                var newRow = '<tr class="warning baris-lintas-kelas" data-sr-id="' + mhs.id_studentrecord + '">' +
                    '<td class="text-center">' + noUrut + '</td>' +
                    '<td><strong>' + mhs.nama + '</strong> ' +
                    '<span class="label label-warning pull-right"><i class="fa fa-exchange"></i> Lintas Kelas</span><br>' +
                    '<small class="text-muted">' + mhs.nim + '</small>' +
                    selectTargetHtml +
                    '</td>' +
                    '<td>' + mhs.prodi + '</td>' +
                    '<td class="text-center">' + mhs.kelas + '</td>' +
                    '<td class="text-center">' +
                    '<div class="btn-group btn-group-sm">' +
                    '<label class="btn btn-success active">' +
                    '<input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',ABSEN" checked> Hadir' +
                    '</label>' +
                    '<label class="btn btn-warning">' +
                    '<input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',IZIN"> Izin' +
                    '</label>' +
                    '<label class="btn btn-info">' +
                    '<input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',SAKIT"> Sakit' +
                    '</label>' +
                    '<label class="btn btn-danger">' +
                    '<input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',ALFA"> Alpa' +
                    '</label>' +
                    '</div>' +
                    '<button type="button" class="btn btn-danger btn-xs hapus-baris-lintas" title="Hapus dari absensi kelas ini" style="margin-left: 5px;"><i class="fa fa-trash"></i></button>' +
                    '</td>' +
                    '</tr>';

                $('#tabel-absensi tbody').append(newRow);
                $(this).replaceWith('<span class="label label-default"><i class="fa fa-check"></i> Sudah Ada</span>');
                updateNomorUrut();
                $('#modal-lintas-kelas').modal('hide');
            });

        });
    </script>
@endsection
