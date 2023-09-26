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
            <form action="{{ url('save_absensi_kprd') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_kurperiode" value="{{ $idk }}">
                <input type="hidden" name="id_bap" value="{{ $id }}">
                <div class="box-body">
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Remark : Pilih sesuai aktual kehadiran</p>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="4%">
                                    <center>No</center>
                                </th>
                                <th width="8%">
                                    <center>NIM </center>
                                </th>
                                <th width="20%">
                                    <center>Nama</center>
                                </th>
                                <th width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th width="8%">
                                    <center>Kelas</center>
                                </th>
                                <th width="8%">
                                    <center>Angkatan</center>
                                </th>
                                <th width="8%">
                                    <center>Pilih</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($absen as $item)
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
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_studentrecord[]"
                                                value="{{ $item->id_studentrecord }}">
                                            <select name="absensi[]" class="form-control">
                                                <option value="{{ $item->id_studentrecord }},HADIR"></option>
                                                <option value="{{ $item->id_studentrecord }},ABSEN">Hadir</option>
                                                <option value="{{ $item->id_studentrecord }},IZIN">Izin</option>
                                                <option value="{{ $item->id_studentrecord }},SAKIT">Sakit</option>
                                                <option value="{{ $item->id_studentrecord }},ALFA">Alfa</option>
                                            </select>
                                        </center>
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
