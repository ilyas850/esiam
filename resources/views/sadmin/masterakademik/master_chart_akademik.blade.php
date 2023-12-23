@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Mahasiswa Aktif (KRS)</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="barChart" style="height:250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Mahasiswa Tidak KRS</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="areaChart" style="height:250px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(function() {
            var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
            // This will get the first returned node in the jQuery collection.
            var areaChart = new Chart(areaChartCanvas)

            var areaChartData = {
                labels: ['{{ $tahun[0]->periode_tahun }}', '{{ $tahun[1]->periode_tahun }}',
                    '{{ $tahun[2]->periode_tahun }}', '{{ $tahun[3]->periode_tahun }}',
                    '{{ $tahun[4]->periode_tahun }}', '{{ $tahun[5]->periode_tahun }}',
                    '{{ $tahun[6]->periode_tahun }}', '{{ $tahun[7]->periode_tahun }}'

                ],

                datasets: [{
                        label: 'TRPL',
                        fillColor: 'rgba(43, 191, 254)',
                        strokeColor: 'rgba(43, 191, 254)',
                        pointColor: 'rgba(43, 191, 254)',
                        pointStrokeColor: '#00BFFF',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(43, 191, 254)',
                        data: ['{{ $dataTRPL[0]->jml_trpl }}', '{{ $dataTRPL[1]->jml_trpl }}',
                            '{{ $dataTRPL[2]->jml_trpl }}', '{{ $dataTRPL[3]->jml_trpl }}',
                            '{{ $dataTRPL[4]->jml_trpl }}', '{{ $dataTRPL[5]->jml_trpl }}',
                            '{{ $dataTRPL[6]->jml_trpl }}', '{{ $dataTRPL[7]->jml_trpl }}'
                        ]
                    },
                    {
                        label: 'Farmasi',
                        fillColor: 'rgba(250, 69, 1)',
                        strokeColor: 'rgba(250, 69, 1)',
                        pointColor: '#DC143C',
                        pointStrokeColor: 'rgba(250, 69, 1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(250, 69, 1)',
                        data: ['{{ $dataFA[0]->jml_fa }}', '{{ $dataFA[1]->jml_fa }}',
                            '{{ $dataFA[2]->jml_fa }}', '{{ $dataFA[3]->jml_fa }}',
                            '{{ $dataFA[4]->jml_fa }}', '{{ $dataFA[5]->jml_fa }}',
                            '{{ $dataFA[6]->jml_fa }}', '{{ $dataFA[7]->jml_fa }}'
                        ]
                    },
                    {
                        label: 'Teknik Industri',
                        fillColor: 'rgba(250, 69, 1)',
                        strokeColor: 'rgba(250, 69, 1)',
                        pointColor: '#00a65a',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: ['{{ $dataTI[0]->jml_ti }}', '{{ $dataTI[1]->jml_ti }}',
                            '{{ $dataTI[2]->jml_ti }}', '{{ $dataTI[3]->jml_ti }}',
                            '{{ $dataTI[4]->jml_ti }}', '{{ $dataTI[5]->jml_ti }}',
                            '{{ $dataTI[6]->jml_ti }}', '{{ $dataTI[7]->jml_ti }}'
                        ]
                    }
                ]
            }

            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChart = new Chart(barChartCanvas)
            var barChartData = areaChartData
            barChartData.datasets[1].fillColor = '#00a65a'
            barChartData.datasets[1].strokeColor = '#00a65a'
            barChartData.datasets[1].pointColor = '#00a65a'
            var barChartOptions = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: 'rgba(0,0,0,.05)',
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing: 1,
                //String - A legend template
                legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
                //Boolean - whether to make the chart responsive
                responsive: true,
                maintainAspectRatio: true
            }
            barChartOptions.datasetFill = false
            barChart.Bar(barChartData, barChartOptions)
        })
    </script>
@endsection
