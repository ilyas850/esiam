@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
       
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data List Mahasiswa</h3>
            </div>
            <div class="box-body">
                @if ($nilai == null)
                    <button type="button" class="btn btn-primary mr-5">
                        Belum Ada Setting Persentase Nilai
                    </button>
                @else
                    <a href="/input_kat_admin/{{ $id }}" class="btn btn-success btn-sm">Input Nilai KAT
                        ({{ $nilai->kat }}%)</a>
                    <a href="/input_uts_admin/{{ $id }}" class="btn btn-info btn-sm">Input Nilai UTS
                        ({{ $nilai->uts }}%)</a>
                    <a href="/input_uas_admin/{{ $id }}" class="btn btn-warning btn-sm">Input Nilai UAS
                        ({{ $nilai->uas }}%)</a>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-danger">
                        Generate Nilai Akhir
                    </button>
                @endif
                <br><br>
                <div class="modal modal-danger fade" id="modal-danger">
                    <div class="modal-dialog">
                        <form action="{{ url('generate_nilai_akhir_admin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_kurperiode" value="{{ $id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Generate Nilai Akhir</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Anda yakin akan menyimpan nilai matakuliah ini ? &hellip;</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-outline">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <table id="example1" class="table table-bordered table-striped">
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
                                <center>Nilai KAT</center>
                            </th>
                            <th>
                                <center>Nilai UTS</center>
                            </th>
                            <th>
                                <center>Nilai UAS</center>
                            </th>
                            <th>
                                <center>Nilai AKHIR</center>
                            </th>
                            <th>
                                <center>Nilai HURUF</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
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
                                    <center>{{ $item->nilai_KAT }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_UTS }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_UAS }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_AKHIR_angka }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_AKHIR }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
