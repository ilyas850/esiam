@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Absensi Mahasiswa</h3>
            </div>
            <form action="{{ url('save_edit_absensi_kprd') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_bap" value="{{ $id }}">
                <input type="hidden" name="id_kurperiode" value="{{ $idk }}">
                <div class="box-body">
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Remark : Pilih sesuai aktual kehadiran</p>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM </center>
                                </th>
                                <th>
                                    <center>Nama</center>
                                </th>
                                <th>
                                    <center>Program Studi</center>
                                </th>
                                <th>
                                    <center>Kelas</center>
                                </th>
                                <th>
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Hadir</center>
                                </th>
                                <th>
                                    <center>Alpa</center>
                                </th>
                                <th>
                                    <center>Izin</center>
                                </th>
                                <th>
                                    <center>Sakit</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($abs as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->nim }}</center>
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>
                                        <center>{{ $item->kelas }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->angkatan }}</center>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                @if ($item->absensi == 'ABSEN')
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ABSEN" checked>
                                                @else
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ABSEN">
                                                @endif
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                @if ($item->absensi == 'ALFA')
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ALFA" checked>
                                                @else
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ALFA">
                                                @endif
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                @if ($item->absensi == 'IZIN')
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},IZIN" checked>
                                                @else
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},IZIN">
                                                @endif
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                @if ($item->absensi == 'SAKIT')
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},SAKIT" checked>
                                                @else
                                                    <input type="radio"
                                                        name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},SAKIT">
                                                @endif
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <button id="simpan" class="btn btn-success btn-block" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </section>
    {{-- <script>
        $(document).ready(function() {
            $('#simpan').click(function() {
                // Menonaktifkan tombol setelah diklik
                $(this).prop('disabled', true);

                // Mencegah pengguna mengklik tombol lagi
                $(this).unbind('click');
            });
        });
    </script> --}}
@endsection
