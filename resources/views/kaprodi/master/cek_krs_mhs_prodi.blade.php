@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Data Validasi KRS
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>

            <li class="active">Data validasi krs</li>
        </ol>
    </section>
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data KRS mahasiswa </h3>
            </div>
            <div class="box-body">
                <div class="row" style="margin-bottom: 15px;">
                    <div class="col-sm-6">
                        <div class="form-inline">
                            <label for="per_page">Tampilkan</label>
                            <select id="per_page" class="form-control input-sm" style="margin: 0 8px;">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span>data</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" id="search_krs" class="form-control pull-right"
                                placeholder="Cari nama atau NIM" value="{{ request('q') }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                <a href="{{ url('krs_mahasiswa_kprd') }}" id="reset_search" class="btn btn-default">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="krs-table-container">
                    @include('kaprodi.master.partials.cek_krs_mhs_prodi_table', ['data' => $data])
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function() {
            var debounceTimer = null;

            function loadKrsData(pageUrl) {
                $.ajax({
                    url: pageUrl || "{{ url('krs_mahasiswa_kprd') }}",
                    type: 'GET',
                    data: {
                        q: $('#search_krs').val(),
                        per_page: $('#per_page').val()
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $('#krs-table-container').html(response.html);

                        var url = new URL(pageUrl || "{{ url('krs_mahasiswa_kprd') }}", window.location.origin);
                        url.searchParams.set('q', $('#search_krs').val());
                        url.searchParams.set('per_page', $('#per_page').val());
                        window.history.replaceState({}, '', url.toString());
                    }
                });
            }

            $('#per_page').on('change', function() {
                loadKrsData();
            });

            $('#search_krs').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    loadKrsData();
                }, 400);
            });

            $(document).on('click', '#krs-table-container .pagination a', function(e) {
                e.preventDefault();
                loadKrsData($(this).attr('href'));
            });

            $(document).on('click', '#reset_search', function(e) {
                e.preventDefault();
                $('#search_krs').val('');
                loadKrsData("{{ url('krs_mahasiswa_kprd') }}");
            });
        });
    </script>
@endsection
