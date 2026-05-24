@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Silahkan Filter</h3>
            </div>
            {{-- Form for Filtering --}}
            <form id="filter-form" class="form" role="form">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" id="id_periodetahun">
                                <option value="">-- Pilih Periode Tahun --</option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}" {{ $thn->status == 'ACTIVE' ? 'selected' : '' }}>
                                        {{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" id="id_periodetipe">
                                <option value="">-- Pilih Tipe --</option>
                                @foreach ($tipe as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}" {{ $tipee->status == 'ACTIVE' ? 'selected' : '' }}>{{ $tipee->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Program Studi</label>
                            <select class="form-control" name="kodeprodi" id="kodeprodi">
                                <option value="">-- Semua Prodi --</option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" id="btn-filter" class="btn btn-info">Cari Mahasiswa</button>
                    {{-- Export button submits a separate form or handles via JS --}}
                    <button type="button" id="btn-export" class="btn btn-success pull-right">Export Excel</button>
                </div>
            </form>

            {{-- Hidden Form for Export --}}
            <form id="export-form" action="{{ url('export_data_mhs_aktif_filter') }}" method="POST" style="display:none;">
                {{ csrf_field() }}
                <input type="hidden" name="id_periodetahun" id="export_id_periodetahun">
                <input type="hidden" name="id_periodetipe" id="export_id_periodetipe">
                <input type="hidden" name="kodeprodi" id="export_kodeprodi">
            </form>
        </div>

        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Data mahasiswa aktif all Prodi</h3>
            </div>
            <div class="box-body">

                <div class="table-responsive">
                    <table id="table-mhs" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM</center>
                                </th>
                                <th>
                                    <center>Nama Mahasiswa</center>
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
                                    <center>Intake</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data via AJAX --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @section('script')
        <script>
            $(function () {
                var table = $('#table-mhs').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('data_mahasiswa_aktif_json') }}",
                        type: 'POST',
                        data: function (d) {
                            d._token = "{{ csrf_token() }}";
                            d.id_periodetahun = $('#id_periodetahun').val();
                            d.id_periodetipe = $('#id_periodetipe').val();
                            d.kodeprodi = $('#kodeprodi').val();
                        }
                    },
                    columns: [
                        {
                            data: null, searchable: false, orderable: false, render: function (data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        { data: 'nim', name: 'mhs.nim' },
                        { data: 'nama', name: 'mhs.nama' },
                        { data: 'prodi', name: 'prd.prodi' },
                        { data: 'kelas', name: 'kls.kelas' },
                        { data: 'angkatan', name: 'ang.angkatan' },
                        { data: 'intake', name: 'intake', searchable: false }
                    ],
                    order: [[1, 'asc']]
                });

                $('#btn-filter').click(function () {
                    table.draw();
                });

                $('#btn-export').click(function () {
                    // Populate hidden form and submit
                    $('#export_id_periodetahun').val($('#id_periodetahun').val());
                    $('#export_id_periodetipe').val($('#id_periodetipe').val());
                    $('#export_kodeprodi').val($('#kodeprodi').val());
                    $('#export-form').submit();
                });
            });
        </script>
    @endsection
    {{-- End of content section --}}
@endsection
