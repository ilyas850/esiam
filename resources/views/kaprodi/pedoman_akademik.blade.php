@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">

        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bookmark"></i> Pedoman Akademik Kaprodi</h3>
                <p class="text-muted" style="margin: 8px 0 0;">
                    Halaman ini menampilkan pedoman aktif dengan pencarian cepat dan unduh file langsung.
                </p>
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
                            <input type="text" id="search_pedoman" class="form-control pull-right"
                                placeholder="Cari nama pedoman atau tahun akademik" value="{{ request('q') }}">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                <a href="{{ url('pedoman_akademik_dsn_kprd') }}" id="reset_search"
                                    class="btn btn-default">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pedoman-list-container">
                    @include('kaprodi.partials.pedoman_akademik_list', ['pedoman' => $pedoman])
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function() {
            var debounceTimer = null;
            var baseUrl = "{{ url('pedoman_akademik_dsn_kprd') }}";

            function loadPedomanData(pageUrl) {
                $.ajax({
                    url: pageUrl || baseUrl,
                    type: 'GET',
                    data: {
                        q: $('#search_pedoman').val(),
                        per_page: $('#per_page').val()
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        $('#pedoman-list-container').html(response.html);

                        var url = new URL(pageUrl || baseUrl, window.location.origin);
                        url.searchParams.set('q', $('#search_pedoman').val());
                        url.searchParams.set('per_page', $('#per_page').val());
                        window.history.replaceState({}, '', url.toString());
                    }
                });
            }

            $('#per_page').on('change', function() {
                loadPedomanData();
            });

            $('#search_pedoman').on('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    loadPedomanData();
                }, 400);
            });

            $(document).on('click', '#pedoman-list-container .pagination a', function(e) {
                e.preventDefault();
                loadPedomanData($(this).attr('href'));
            });

            $(document).on('click', '#reset_search', function(e) {
                e.preventDefault();
                $('#search_pedoman').val('');
                loadPedomanData(baseUrl);
            });
        });
    </script>
@endsection
