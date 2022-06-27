<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Politeknik META Industri Cikarang</title>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <link rel="icon" type="image/png" href="{{ asset('images/Logo Meta.png') }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/skins/_all-skins.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet"
        href="{{ asset('adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/AdminLTE.min.css') }}">
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- daterange picker -->
    <link rel="stylesheet"
        href="{{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/select2/dist/css/select2.min.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet"
        href="{{ asset('adminlte/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/iCheck/all.css') }}">

    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet"
        href="{{ asset('adminlte/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">

    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/timepicker/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">


    <style>
        .example-modal .modal {
            position: relative;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            display: block;
            z-index: 1;
        }

        .example-modal .modal {
            background: transparent !important;
        }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="/" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>M</b>IP</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Politeknik</b> META Industri</span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        @yield('notif_user')
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                @if (Auth::user()->role == 3)
                                    <img src="{{ asset('/foto_mhs/' . Auth::user()->username . '.jpg') }}"
                                        class="user-image" alt="User Image">
                                @else
                                    <img src="/adminlte/img/default.jpg" class="user-image" alt="User Image">
                                @endif

                                <span class="hidden-xs">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    @if (Auth::user()->role == 3)
                                        <img src="{{ asset('/foto_mhs/' . Auth::user()->username . '.jpg') }}"
                                            class="img-circle" alt="User Image">
                                    @else
                                        <img src="/adminlte/img/default.jpg" class="img-circle" alt="User Image">
                                    @endif
                                    <p>
                                        @if (Auth::user()->role == 1)
                                            Super Admin
                                        @elseif (Auth::user()->role == 2)
                                            Dosen
                                        @elseif (Auth::user()->role == 3)
                                            Mahasiswa
                                        @elseif (Auth::user()->role == 4)
                                            Mahasiswa
                                        @elseif (Auth::user()->role == 5)
                                            Dosen
                                        @elseif (Auth::user()->role == 6)
                                            Kaprodi
                                        @elseif (Auth::user()->role == 7)
                                            WADIR 1
                                        @elseif (Auth::user()->role == 11)
                                            PraUSTA
                                        @endif
                                    </p>
                                </li>
                                @if (Auth::user()->role == 4)
                                    <li class="user-footer">
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 3)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pwd/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 2)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pwd_dsn/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 1)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pass/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 5)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pass_dsn_luar/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 6)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pass_kaprodi/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 7)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pass_wadir1/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 11)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pass_prausta/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @elseif (Auth::user()->role == 9)
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="/change_pass_prodi/{{ Auth::user()->id }}"
                                                class="btn btn-default btn-flat">Ubah Password</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"
                                                class="btn btn-default btn-flat">Keluar</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </li>

                    </ul>
                </div>
            </nav>
        </header>

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                        @if (Auth::user()->role == 3)
                            <img src="{{ asset('/foto_mhs/' . Auth::user()->username . '.jpg') }}"
                                class="img-circle" alt="User Image">
                        @else
                            <img src="/adminlte/img/default.jpg" class="img-circle" alt="User Image">
                        @endif
                    </div>
                    <div class="pull-left info">
                        <p>{{ Auth::user()->name }}</p>
                        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                    </div>
                </div>
                @yield('side')
            </section>
            <!-- /.sidebar -->
        </aside>
        @include('sweetalert::alert')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            @yield('content_header')
            <!-- Main content -->
            @yield('content')
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> 2.4.13
            </div>
            <strong>Copyright &copy; 2020-2022 <a href="#">Politeknik META Industri Cikarang</a>.</strong> All
            rights
            reserved.
        </footer>

    </div>

    <!-- jQuery 3 -->
    <script src="{{ asset('adminlte/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{ asset('adminlte/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('adminlte/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>
    <!-- bootstrap color picker -->
    <script src="{{ asset('adminlte/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}">
    </script>
    <!-- bootstrap time picker -->
    <script src="{{ asset('adminlte/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <!-- SlimScroll -->
    <script src="{{ asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- iCheck 1.0.1 -->
    {{-- <script src="{{ asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script> --}}
    <!-- FastClick -->
    <script src="{{ asset('adminlte/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('adminlte/dist/js/demo.js') }}"></script>
    <!-- CK Editor -->
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ asset('adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.sidebar-menu').tree()
        })
    </script>

    <script>
        $(function() {
            $('#example1').DataTable()
            $('#example3').DataTable()
            $('#example2').DataTable({
                'paging': true,
                'lengthChange': false,
                'searching': false,
                'ordering': true,
                'info': true,
                'autoWidth': false
            })
            $('#example5').DataTable({
                'paging': false,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true
            })

            $('#example7').DataTable({
                'paging': false,
                'lengthChange': true,
                'searching': false,
                'ordering': true,
                'info': false,
                'autoWidth': true,
                scrollX: true,
                scrollCollapse: true,
            })

            $('#example8').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true,
                scrollX: true,
                scrollCollapse: true,
            })
        })

        $(document).ready(function() {
            var table = $('#example4').DataTable({
                // scrollY: "465px",
                scrollX: true,
                scrollCollapse: true

            });
        });

        $(document).ready(function() {
            var table = $('#example6').DataTable({
                // scrollY: "465px",
                scrollX: true,
                scrollCollapse: true,

                'paging': false,
                'lengthChange': true,
                'searching': false,
                'ordering': false,
                'info': true,
                'autoWidth': false

            });
        });

        $(document).ready(function() {
            var table = $('#example9').DataTable({
                // scrollY: "465px",
                scrollX: true,
                scrollCollapse: true,

                'paging': false,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false

            });
        });

        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'))
                }
            )

            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            })

            //iCheck for checkbox and radio inputs
            // $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
            //     checkboxClass: 'icheckbox_minimal-blue',
            //     radioClass: 'iradio_minimal-blue'
            // })
            //Red color scheme for iCheck
            // $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
            //     checkboxClass: 'icheckbox_minimal-red',
            //     radioClass: 'iradio_minimal-red'
            // })
            //Flat red color scheme for iCheck
            // $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            //     checkboxClass: 'icheckbox_flat-green',
            //     radioClass: 'iradio_flat-green'
            // })

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            //Timepicker
            $('.timepicker').timepicker({
                showInputs: false
            })
        })
    </script>


</body>

</html>
