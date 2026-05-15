<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bookmark"></i> Pedoman Akademik Dosen Luar</h3>
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
                                <option value="10" <?php echo e(request('per_page', 10) == 10 ? 'selected' : ''); ?>>10</option>
                                <option value="25" <?php echo e(request('per_page') == 25 ? 'selected' : ''); ?>>25</option>
                                <option value="50" <?php echo e(request('per_page') == 50 ? 'selected' : ''); ?>>50</option>
                                <option value="100" <?php echo e(request('per_page') == 100 ? 'selected' : ''); ?>>100</option>
                            </select>
                            <span>data</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group input-group-sm">
                            <input type="text" id="search_pedoman" class="form-control pull-right"
                                placeholder="Cari nama pedoman atau tahun akademik" value="<?php echo e(request('q')); ?>">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                <a href="<?php echo e(url('pedoman_akademik_dsn_luar')); ?>" id="reset_search"
                                    class="btn btn-default">Reset</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="pedoman-list-container">
                    <?php echo $__env->make('dosenluar.partials.pedoman_akademik_table', ['pedoman' => $pedoman], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script>
        $(function() {
            var debounceTimer = null;
            var baseUrl = "<?php echo e(url('pedoman_akademik_dsn_luar')); ?>";

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosenluar/pedoman_akademik.blade.php ENDPATH**/ ?>