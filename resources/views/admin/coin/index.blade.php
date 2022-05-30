@extends('layouts.app')


{{--    <link href="{{asset('datatables/datatables.bundle.rtl.css')}}" rel="stylesheet" type="text/css"/>--}}

{{--    <style>--}}
{{--        table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting {--}}
{{--            padding-right: 15px;--}}
{{--        }--}}
{{--    </style>--}}
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table id="tblAjax" class="table table-hover table-responsive-sm">
                            <thead>
                            <tr>
                                <th scope="col">coin</th>
                                <th scope="col">status</th>
                                <th scope="col">open market cap</th>
                                <th scope="col">open price</th>

                                <th scope="col">market cap (now)</th>

                                {{--<th scope="col">created date</th>--}}
                                {{--<th scope="col">received date</th>--}}
                                {{--<th scope="col">rank</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"--}}
{{--            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"--}}
{{--            crossorigin="anonymous"></script>--}}
    <script src="{{asset('datatables/datatables.bundle.js')}}" type="text/javascript"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var oTable;
        $(function () {
            BindDataTable();
        });

        //هذه تختلف حسب الصفحة
        function BindDataTable() {
            oTable = $('#tblAjax').dataTable(
                {
                    language: {
                        aria: {
                            sortAscending: ": فعال لترتيب العمود تصاعديا",
                            sortDescending: ": فعال لترتيب العمود تنازليا"
                        }
                        ,
                        emptyTable: "لا يوجد بيانات لعرضها",
                        info: "عرض _START_ الى _END_ من _TOTAL_ صف",
                        infoEmpty: "لا يوجد نتائج لعرضها",
                        infoFiltered: "(filtered1 من _MAX_ اجمالي صفوف)",
                        lengthMenu: "_MENU_ صف",
                        search: "بحث",
                        zeroRecords: "لا يوجد نتائج لعرضها",
                        paginate: {sFirst: "الاول", sLast: "الاخير", sNext: "التالي", sPrevious: "السابق"}
                    },
                    "iDisplayLength": 10,
                    "sPaginationType": "full_numbers",
                    "bFilter": true,
                    "bDestroy": true,
                    "bSort": true,
                    "bStateSave": true,
                    serverSide: true,
                    columns: [
                        {data: 'name', name: 'name'},
                        {data: 'symbol', name: 'symbol'},
                        // {data: 'old_market_cap', name: 'old_market_cap'},
                        {
                            name: 'old_market_cap', "render": function (data, type, row) {

                                return row["market_cap"];
                            }
                        },
                        {
                            name: 'old_market_cap', "render": function (data, type, row) {
                                var dataRender;
                                if (row['old_market_cap'] == null) {
                                    return "null";
                                }
                                return row['old_market_cap']['price'];
                            }
                        },
                        {data: 'num_market_pairs', name: 'num_market_pairs'},
                    ],
                    ajax: {
                        type: "POST",
                        contentType: "application/json",
                        url: '{{route('coin-market.ajaxdt')}}',
                        data: function (d) {
                            d._token = "{{csrf_token()}}";


                            return JSON.stringify(d);
                        }
                    },
                    fnDrawCallback: function () {

                    }
                });
        }
    </script>
@endsection
