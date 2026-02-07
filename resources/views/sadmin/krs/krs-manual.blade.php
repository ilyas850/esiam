@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data KRS Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="krs-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM - Nama Mahasiswa</center>
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
                                <center>Dosen Pembimbing</center>
                            </th>
                            <th>
                                <center>Jml. SKS</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data will be populated by DataTables via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#krs-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('krs-manual') }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'no', name: 'no', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'nim_nama', name: 'student.nim' }, // search maps to name
                    { data: 'prodi', name: 'prodi.prodi' },
                    { data: 'kelas', name: 'kelas.kelas' },
                    { data: 'angkatan', name: 'angkatan', orderable: false, searchable: false },
                    { data: 'dosen_pembimbing', name: 'dosen_pembimbing', orderable: false, searchable: false },
                    { data: 'jml_sks', name: 'jml_sks', orderable: false, searchable: false },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[1, 'desc']] // Default sort by NIM/Name (which maps to student.kodeprodi in controller default logic effectively)
            });
        });
    </script>
@endsection