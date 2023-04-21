@extends('backend.layouts.backend')
@section('content')
    @push('styles')
        <link href="{{ asset('backend/assets/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/DataTables/datatables.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet" />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {
                packages: ['corechart']
            });
        </script>
        <style>
            .select2-selection__arrow {
                top: 15px !important;
            }

            .select2-selection__rendered {
                line-height: 10px !important;
            }

        </style>
    @endpush
    <div>
        @if (Auth::user()->hasAnyRole('super-admin', 'developer-admin', 'marketing-manager', 'branch-manager','customer','collection-executive','marketing-executive','collection-manager','store-admin'))
            @php
                $user = Auth::user();
                $userRole = $user->roles->pluck('name')->first();
            @endphp
            <div class="row">
                @hasanyrole('super-admin|developer-admin|branch-manager|marketing-manager|collection-manager')
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Today Joining</h6>
                            <div class="h2 mb-0 font-weight-normal">
                                +{{ $data['todayCustomer'] }}
                            </div><i
                                class="fas fa-users data-widget-icon"></i>
                        </div>
                    </div>
                </div>
                @endhasanyrole
                @hasanyrole('super-admin|developer-admin|branch-manager|marketing-manager|collection-manager')
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Total Executives</h6>
                            <i class="fas fa-user-tie data-widget-icon"></i>
                            <div class="row">
                                <div class="col-sm-4">
                                    <h6>C.E</h6>
                                    <div class="h2 mb-0 font-weight-normal">{{ $data['ce'] }}</div>
                                </div>
                                <div class="col-sm-4">
                                    <h6>M.E</h6>
                                    <div class="h2 mb-0 font-weight-normal">{{ $data['me'] }}</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                @endhasanyrole
                @hasanyrole('super-admin|developer-admin|branch-manager|marketing-manager|collection-manager')
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Today Collection</h6>
                            <div class="h2 mb-0 font-weight-normal">+{{ $data['todayCollection'] }}</div><i
                                class="fas fa-rupee-sign data-widget-icon"></i>
                        </div>
                    </div>
                </div>
                @endhasanyrole
                @hasrole('store-admin')
                   <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Products </h6>
                            <div class="h2 mb-0 font-weight-normal">{{ $data['products'] }} &nbsp;</div>
                           &nbsp; <i class="fas fa-rupee-sign data-widget-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Customers </h6>
                            <div class="h2 mb-0 font-weight-normal">{{ $data['customers'] }} &nbsp;</div>
                           &nbsp; <i class="fas fa-rupee-sign data-widget-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Total Orders </h6>
                            <div class="h2 mb-0 font-weight-normal">{{ $data['totalOrders'] }} &nbsp;</div>
                           &nbsp; <i class="fas fa-rupee-sign data-widget-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Today Orders </h6>
                            <div class="h2 mb-0 font-weight-normal">{{ $data['todayOrders'] }} &nbsp;</div>
                           &nbsp; <i class="fas fa-rupee-sign data-widget-icon"></i>
                        </div>
                    </div>
                </div>

                @endhasrole
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3 font-15 text-muted font-weight-normal">Total Collection </h6>
                            <div class="h2 mb-0 font-weight-normal">{{ $data['totalCollection'] }} &nbsp;</div>
                           &nbsp; <i class="fas fa-rupee-sign data-widget-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        @if ($userRole == 'branch-manager')
                            @php
                                $user = auth()->user();
                                $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                            @endphp
                            <select name="branch_id" class="select2" id="branch_id">
                                @isset($branches)
                                    @foreach ($branches as $branch)
                                        @isset($manager)
                                            @if ($manager->branch_id == $branch->id)
                                                <option value="{{ $branch->id }}" selected>
                                                    {{ $branch->branch_name }}</option>
                                            @else
                                                <option value="{{ $branch->id }}" disabled>
                                                    {{ $branch->branch_name }}</option>
                                            @endif
                                        @endisset
                                    @endforeach
                                @endisset
                            </select>
                        @elseif($userRole == 'super-admin' || $userRole == 'developer-admin' || $userRole == 'marketing-manager')
                            <select name="branch_id" class="select2" id="branch_id">
                                <option value="0">---All Branch---</option>
                                @isset($branches)
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        @endif
                        <div id="BranchColectionpiechart">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        @if ($userRole == 'branch-manager')
                            @php
                                $user = auth()->user();
                                $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                            @endphp
                            <select name="attendence_branch_id" class="select2" id="attendence_branch_id">
                                @isset($branches)
                                    @foreach ($branches as $branch)
                                        @isset($manager)
                                            @if ($manager->branch_id == $branch->id)
                                                <option value="{{ $branch->id }}" selected>
                                                    {{ $branch->branch_name }}</option>
                                            @else
                                                <option value="{{ $branch->id }}" disabled>
                                                    {{ $branch->branch_name }}</option>
                                            @endif
                                        @endisset
                                    @endforeach
                                @endisset
                            </select>
                        @elseif($userRole == 'super-admin' || $userRole == 'developer-admin' || $userRole == 'marketing-manager')
                            <select name="attendence_branch_id" class="select2" id="attendence_branch_id">
                                <option value="0">---All Branch---</option>
                                @isset($branches)
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        @endif
                        <div id="BranchAttendencepiechart">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @if ($userRole == 'collection-manager')
                <div class="col-lg-7">
                    <div class="card card-fullheight">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-4">
                                <div>
                                    <h5 class="box-title mb-2">List of Executives</h5>
                                </div>
                                <div><a href="/admin/master/executives"><button class="btn btn-floating btn-sm btn-light" data-toggle="tooltip" title="View More"><i class="ti-export"></i></button></a></div>
                            </div>
                            <div class="card-fullwidth-block custom-scroll position-relative" style="max-height: 480px">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-default thead-lg">
                                            <tr>
                                                <th>#</th>
                                                <th>Exectuive ID</th>
                                                <th>Name</th>
                                                <th>Branch</th>
                                                <th>Mobile</th>

                                                <th class="no-sort">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="exlist">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="col-sm-12">
                        <div  id="chart" class="card">
                            @if ($userRole == 'collection-manager')
                            @php
                                    $user = auth()->user();
                                    $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                                    @endphp
                                <select name="branch_target_id" class="select2" id="branch_target_id">

                                    @isset($branches)
                                        @foreach ($branches as $branch)
                                            @isset($manager)
                                                @if ($manager->branch_id == $branch->id)
                                                    <option value="{{ $branch->id }}" selected>
                                                        {{ $branch->branch_name }}</option>
                                                @else
                                                    <option value="{{ $branch->id }}" disabled>
                                                        {{ $branch->branch_name }}</option>
                                                @endif
                                            @endisset
                                        @endforeach
                                    @endisset
                                </select>
                            @elseif($userRole == 'super-admin' || $userRole == 'developer-admin' || $userRole == 'marketing-manager' )
                                <select name="branch_target_id" class="select2" id="branch_target_id">
                                    @isset($branches)
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            @endif

                        </div>
                    </div>
                </div>
                @endif

            </div>
        <div class="row">
                @if ($userRole == 'super-admin' || $userRole == 'developer-admin' || $userRole == 'marketing-manager')
                    <div class="col-sm-12">
                        <div class="card">
                            <div id="chart_div" ></div>
                        </div>
                    </div>
                @endif
                @if ($userRole == 'super-admin' || $userRole == 'developer-admin' || $userRole == 'marketing-manager')
                <div class="col-sm-12">
                    <div class="card">

                        @if ($userRole == 'branch-manager')
                        @php
                                $user = auth()->user();
                                $manager = \App\Models\Master\Manager::where('user_id', $user->id)->first();
                                @endphp
                            <select name="branch_target_id" class="select2" id="branch_target_id">

                                @isset($branches)
                                    @foreach ($branches as $branch)
                                        @isset($manager)
                                            @if ($manager->branch_id == $branch->id)
                                                <option value="{{ $branch->id }}" selected>
                                                    {{ $branch->branch_name }}</option>
                                            @else
                                                <option value="{{ $branch->id }}" disabled>
                                                    {{ $branch->branch_name }}</option>
                                            @endif
                                        @endisset
                                    @endforeach
                                @endisset
                            </select>
                            </select>
                        @elseif($userRole == 'super-admin' || $userRole == 'developer-admin' || $userRole == 'marketing-manager' )
                            <select name="branch_target_id" class="select2" id="branch_target_id">
                                @isset($branches)
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        @endif
                        <div id="chart">

                              {!! $chart1->container() !!}
                        </div>

                    </div>
                </div>
                @endif
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('backend/assets/vendors/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script src="{{ $chart1->cdn() }}"></script>

{{ $chart1->script() }}
    <script language="JavaScript">
        function setTable(data) {
            console.log(data);
            n =1;
            data.forEach(element => {
                var html = '<tr>';
                html += '<td>' + n+ '</td>';
                html += '<td>' + element.executive_id + '</td>';
                html += '<td>' + element.name + '</td>';
                html += '<td>' + element.branch.branch_name + '</td>';
                html += '<td>' + element.phone + '</td>';
                if (element.status == 1) {
                    html += '<td><span class="badge badge-success">Active</span></td>';
                } else {
                    html += '<td><span class="badge badge-danger">Inactive</span></td>';
                }
                html += '</tr>';
                n++;
                $('.exlist').append(html);
            });


                 }
        $(document).ready(function() {
            $.ajax({
                    type: 'GET',
                    url: "/admin/get-executives-list",
                    data: {
                        branch_id: branch_id,
                    },
                    success: function(data) {
                     setTable(data);


                    }
                });


            $('.select2').select2({});
            $('#branch_id').on('change', function(e) {
                var branch_id = $("#branch_id :selected").val();
                getData(branch_id);
            });
            var branch_id = $("#branch_id :selected").val();;
            if (branch_id != '') {
                getData(branch_id);
            }
            $('#attendence_branch_id').on('change', function(e) {
                var attendence_branch_id = $("#attendence_branch_id :selected").val();
                getAttendanceData(attendence_branch_id);
            });
            var attendence_branch_id = $("#attendence_branch_id :selected").val();;
            if (attendence_branch_id != '') {
                getAttendanceData(attendence_branch_id);
            }
            $('#scheme_branch_id').on('change', function(e) {
                var scheme_branch_id = $("#scheme_branch_id :selected").val();
                getSchemeData(scheme_branch_id);
            });
            var scheme_branch_id = $("#scheme_branch_id :selected").val();;
            if (scheme_branch_id != '') {
                getSchemeData(scheme_branch_id);
            }
            var branch_target_id = $("#branch_target_id :selected").val();;
            // console.log(branch_target_id);
            if (branch_target_id != '') {
                getBranchTarget(branch_target_id);
            }
            $('#branch_target_id').on('change', function(e) {
                $('#chart').empty();
                var branch_target_id = $("#branch_target_id :selected").val();
                getBranchTarget(branch_target_id);
            });
        });
        //### start Branch Collection status Data and draw chart
        function getData(branch_id) {
            if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "/admin/get-collection-data",
                    data: {
                        branch_id: branch_id,
                    },
                    success: function(data) {
                        var pie_data = data;

                        google.charts.load('current', {
                            'packages': ['corechart']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable(pie_data);
                            var options = {
                                title: 'TODAY BRANCH COLLECTION',
                                is3D: true,
                            };
                            var chart = new google.visualization.PieChart(document.getElementById(
                                'BranchColectionpiechart'));
                            chart.draw(data, options);
                        }
                    },
                });
            }
        }
        //### end Branch Collection status Data and draw chart
        //### start Branch staff Attendence Data and draw chart
        function getAttendanceData(branch_id) {
            if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'GET',
                    url: "/admin/get-attendence-data",
                    data: {
                        branch_id: branch_id,
                    },
                    success: function(data) {
                        var attendence_pie_data = data;

                        google.charts.load('current', {
                            'packages': ['corechart']
                        });
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable(attendence_pie_data);
                            var options = {
                                title: 'TODAY BRANCH STAFF ATTENDENCE',
                                is3D: true,
                            };
                            var chart = new google.visualization.PieChart(document.getElementById(
                                'BranchAttendencepiechart'));
                            chart.draw(data, options);
                        }
                    },
                });
            }
        }
        //### end Branch staff Attendence Data and draw chart
        //### start Branch Vs No of Customers
        google.charts.load("current", {
            packages: ['corechart']
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["Branch", "No of Customers", {
                    role: "style"
                }],
                <?php echo $columnChartData; ?>
            ]);
            // console.log(data);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation"
                },
                2
            ]);
            var options = {
                title: "BRANCH Vs NO: OF CUSTOMERS",

                height: 400,
                bar: {
                    groupWidth: "95%"
                },
                legend: {
                    position: "none"
                },
            };
            var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
            chart.draw(view, options);
        }
        //### start Branch Vs No of Customers
        //### start Branch Scheme Data and draw chart
        function getSchemeData(branch_id) {
            if (branch_id) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                url: "/admin/get-scheme-data",
                    $.ajax({
                        type: 'GET',
                        data: {
                            branch_id: branch_id,
                        },
                        success: function(data) {
                            var scheme_pie_data = data;

                            google.charts.load('current', {
                                'packages': ['corechart']
                            });
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                                var data = google.visualization.arrayToDataTable(scheme_pie_data);

                                var options = {
                                    title: 'BRANCH SCHEME Vs NO: OF CUSTOMERS',
                                    pieHole: 0.4,
                                };
                                var chart = new google.visualization.PieChart(document.getElementById(
                                    'BranchSchemepiechart'));
                                chart.draw(data, options);
                            }
                        },
                    });
            }
        }

        //### end Branch scheme Data and draw chart
        //### start Branch Target chart
       function getBranchTarget(branch_target_id){

        if (branch_target_id) {
            console.log(branch_target_id)
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                        url: "/admin/get-branch-target",
                        type: 'GET',
                        data: {
                            branch_target_id: branch_target_id,
                        },

                        success: function(data) {
                            var chart = new ApexCharts(
                        document.querySelector("#chart"),
                        data.bar.original.options
                    );
                    chart.render();
                        },
                    });
        }
       }
        //### end Branch Target chart
    </script>
@endpush
