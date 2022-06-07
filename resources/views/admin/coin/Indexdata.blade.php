@extends('layouts.app')


<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>

<style>
    table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting {
        padding-right: 15px;
    }

    input[type=search] {
        float: left
    }

</style>
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
                                <th scope="col">#</th>
                                <th scope="col">coin</th>
                                <th scope="col">num market pairs</th>
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

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script>
        // $(document).ready(function () {

        // });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let oTable;
        $(function () {
            BindDataTable();


        });

        function refreshTable() {
            setInterval(() => {
                $('#tblAjax').DataTable().ajax.reload(null, false);
                // $('#tblAjax').DataTable().draw()


            }, 10000);
        }

        // var intervalId = window.setInterval(function(){
        //     $('#tblAjax').data.reload();
        //
        // }, 5000);
        // setInterval( function () {
        //     $('#tblAjax').DataTable().ajax.reload(null,false );
        // }, 10000 );

        //هذه تختلف حسب الصفحة
        let dataCollect = [];

        function BindDataTable() {
            oTable = $('#tblAjax').DataTable(
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
                    deferRender: true,
                    "bSort": true,
                    "bStateSave": true,
                    serverSide: true,
                    dom: 'Bflrtip',
                    columns: [

                        {data: 'id', name: 'id'},
                        {
                            name: 'name', "render": function (data, type, row) {

                                return row["name"] + " (" + `${row['symbol']}` + ")"
                            }
                        },
                        {data: 'num_market_pairs', name: 'num_market_pairs'},
                        {
                            name: 'old_market_cap', "render": function (data, type, row) {
                                if (row['old_market_cap'] == null) {
                                    return "<span class='text-info'>doesn't exists in DB</span>";
                                }
                                return '$ ' + row['old_market_cap'];
                            }
                        },
                        {
                            name: 'price', "render": function (data, type, row) {
                                if (row['old_market_cap'] == null) {
                                    return "<span class='text-info'>doesn't exists in DB</span>";
                                }
                                return '$ ' + row['price'];
                            }
                        },
                        {
                            name: 'market_cap', "render": function (data, type, row) {
                                if (row['market_cap'] == null) {
                                    return "<span class='text-info' data-id=" + row['id'] + ">doesn't exists in DB</span>";
                                }
                                return "<span class='text-success '>" + '$ ' + row['market_cap'] + "</span>";

                                {{--$.ajax({--}}
                                {{--    method: "POST",--}}
                                {{--    async: false,--}}
                                {{--    url: "{{route('coin-market.reloadData')}}",--}}
                                {{--    data: {--}}
                                {{--        "data": row['id']--}}
                                {{--    },--}}
                                {{--    success: function (response) {--}}
                                {{--        console.log(JSON.stringify(response['data']).market_cap)--}}

                                {{--    }--}}
                                {{--})--}}


                            }
                        },
                    ],
                    ajax: {
                        type: "POST",
                        contentType: "application/json",
                        url: '{{route('coin.ajaxdata')}}',

                        data: function (d) {
                            d._token = "{{csrf_token()}}";

                            return JSON.stringify(d);
                        },
                        error: function (xhr, status, error) {
                            var err = eval("(" + xhr.responseText + ")");
                            alert(err.message);
                        },


                    },

                    "drawCallback": function (settings) {
                        var api = this.api();
                        var collect = []
                        // Output the data for the visible rows to the browser's console
                        api.rows().data().each(function (data) {
                            collect.push(data.id)

                        });

                        $.ajax({
                            method: "POST",
                            async: false,
                            url: "{{route('coin-market.reloadData')}}",
                            data: {
                                "data": collect.toString()
                            },
                            success: function (response) {
                                let ids = response.data


                                api.rows().data().each(function (data) {
                                    let collection = ids.find(el => el.id === data.id);


                                    let market_cap = $("table tr").find(`[data-id='${data.id}']`)
                                    market_cap.text(collection['market_cap'])

                                });

                            }
                        })

                    }

                });


            setInterval(() => {
                oTable.ajax.reload(null, false);
                // $('#tblAjax').DataTable().draw()


            }, 5000);


        }


        //


    </script>
@endsection
