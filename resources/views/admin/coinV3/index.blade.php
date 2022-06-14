@extends('layouts.app')
<link href="{{asset('css/bootstrap.css')}}" rel="stylesheet"
      type="text/css"/>
<link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css"/>
<link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap4.min.css" rel="stylesheet"
      type="text/css"/>

<style>
    table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting {
        padding-right: 15px;
    }

    input[type=search] {
        float: left
    }

    #tblAjax_paginate {
        float: right;
    }

    .loading1 {
        left: 50%;
        margin-left: 50%;
    }

    .loading2 {
        left: 50%;
        margin-left: 50%;
    }

    #tblAjax2 > tr:td(2) {

    }
</style>
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-sm-6">
                                <small class="text-dark">
                                    Total MarketCap<b> today: <span class="text-primary total_market_cap">$ 00.00</span></b>
                                </small>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-dark">
                                    Total MarketCap <b>yesterday: <span class="text-primary total_market_cap_yesterday">$ 00.00</span></b>
                                </small>
                            </div>
                        </div>


                    </div>

                    <div class="card-body card-body1">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table id="tblAjax" class="table table-striped table-bordered dt-responsive nowrap"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">coin</th>
                                <th scope="col">difference</th>

                                <th scope="col"> dominance</th>
                                <th scope="col">market cap (now)</th>

                                <th scope="col">current price (now)</th>
                                {{--                                <th scope="col">difference</th>--}}
                                <th scope="col">open dominance</th>

                                <th scope="col">open price</th>

                                {{--                                <th scope="col">open market cap</th>--}}
                                {{--                                <th scope="col">open market cap (price * cs)</th>--}}


                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">

                <div class="card">
                    <div class="card-header">

                        <small class="text-dark">
                            alert data
                        </small>


                    </div>

                    <div class="card-body card-body2">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table id="tblAjax2" class="table table-striped table-bordered dt-responsive nowrap"
                               style="width:100%">
                            <thead>
                            <tr>

                                <th scope="col">coin</th>
                                <th scope="col"> dominance</th>

                                <th scope="col">open dominance</th>


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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"
            type="text/javascript"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/responsive.bootstrap4.min.js"
            type="text/javascript"></script>
    <script>
        function format(d) {
            // `d` is the original data object for the row

// console.log(d)
            return '<tr class="child">' +
                '<td class="child" colspan="6">' +
                '<ul data-dtr-index="0" class="dtr-details">' +
                '<li data-dtr-index="6" data-dt-row="0" data-dt-column="6">' +
                '<span class="dtr-title">open market cap</span>' +
                ' <span class="dtr-data">$ ' + `${d.old_market_cap}` + '</span>' +
                '</li>' +
                // '<li data-dtr-index="7" data-dt-row="0" data-dt-column="7">' +
                // '<span class="dtr-title">open market cap (price * cs)</span>' +
                // ' <span class="dtr-data"><span class="text-success  text-center">$ '+((`${d.market_cap}`/$('.total_market_cap').text())*100)+'</span>' +
                // '</span>' +
                // '</li>' +
                '<li data-dtr-index="7" data-dt-row="0" data-dt-column="7">' +
                '<span class="dtr-title">dominance</span>' +
                ' <span class="dtr-data"><span class="text-success  text-center"> ' + `${d.dominance}` + '</span>' +
                '</span>' +
                '</li>' +
                '</ul>' +
                '</td>' +
                '</tr>';
        }

        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function PublicMarketCap() {
                $.post("{{route('coin-market.globalMetrics')}}", function (data, status) {

                    $('.total_market_cap').text('$ ' + data.data.total_market_cap)
                    $('.total_market_cap_yesterday').text('$ ' + data.data.total_market_cap_yesterday)
                });
            }

            let oTable;
            var tr;
            var row;

            $(function () {
                BindDataTable();
                PublicMarketCap();
                BindDataTable2()
            });

            $('<div class="loading1 spinner-border" role="status"> <span >Loading...</span></div>').appendTo('.card-body1');
            $('<div class="loading2 spinner-border" role="status"> <span >Loading...</span></div>').appendTo('.card-body2');


            //هذه تختلف حسب الصفحة

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
                        // "bSort": true,
                        "bStateSave": true,
                        serverSide: true,

                        "ordering": false,
                        select: {
                            style: 'api'
                        },
                        colReorder: {
                            realtime: false
                        },

                        columns: [

                            {data: 'id', name: 'id', width: "4%"},
                            {
                                data: 'name', name: 'name', "render": function (data, type, row) {

                                    return row["name"] + " (" + `${row['symbol']}` + ")"
                                }
                            },
                            {
                                data: function (data, type, row) {
                                    let diff = $('#diff' + data['id']).text()

                                    return "<span class='text-info'  id='diff" + data['id'] + "'   >" + diff + "</span>";


                                }
                            },
                            {
                                name: ' dominance', data: function (data, type, row) {

                                    return "<span class='text-info'>" + ((data['market_cap'] / '{{!empty(globalMetrics()->quote->USD->total_market_cap) ? globalMetrics()->quote->USD->total_market_cap : 0}}') * 100) + "</span>";

                                }
                            },
                            {
                                data: 'market_cap',
                                name: 'market_cap',
                                width: "4%",
                                "render": function (data, type, row) {
                                    return '$ ' + row["market_cap"];
                                }
                            },
                            {
                                data: 'price', name: 'price', width: "4%", "render": function (data, type, row) {
                                    return "$ " + row["price"];
                                }
                            },

                            {
                                data: function (data, type, row) {
                                    let dominance;
                                    if (data["market_cap"])
                                        dominance = $("table tr").find(`[data-dominance='${data['id']}']`).text()
                                    else
                                        dominance = "doesn't exists in DB"
                                    return "<span class='text-info' data-dominance=" + data['id'] + ">" + dominance + "</span>";


                                }
                            },
                            {
                                data: function (data, type, row) {
                                    let price = $('#price' + data['id']).text()

                                    return "<span class='text-info'  id='price" + data['id'] + "'   >" + '$ ' + price + "</span>";


                                }
                            },


                        ],
                        ajax: {
                            type: "POST",
                            contentType: "application/json",
                            url: '{{route('coin.ajaxdataV3')}}',

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
                                url: "{{route('coinmarket.reloadDataV3')}}",
                                data: {
                                    // "data": collect.toString()
                                    "data": collect
                                },
                                success: function (response) {
                                    let ids = response.data


                                    api.rows().data().each(function (data) {
                                        let collection = ids.find(el => el.id === data.id);


                                        // Dominance
                                        let dominance = $("table tr").find(`[data-dominance='${data.id}']`)
                                        dominance.text(collection['previous_dominance'])


                                        var price = $('#price' + `${data.id}`) //$("table tr").find(`[data-dominance='${data.id}']`)
                                        price.text(collection['previous_price']);


                                        // differnce market cap
                                        var diff = $('#diff' + `${data.id}`) //$("table tr").find(`[data-dominance='${data.id}']`)

                                        diff.text(`${data.market_cap}` - collection['previous_market_cap']);

                                    });

                                }
                            })


                        },
                        "initComplete": function (setting) {
                            $('.loading1').remove()
                            // var table = new $.fn.dataTable.Api('#tblAjax');
                            var table = new $.fn.dataTable.Api('#tblAjax');



                            setInterval(() => {
                                oTable.ajax.reload(null, false);
                                // $('#tblAjax').DataTable().draw()


                            }, 5000);
                            //
                            // var api = this.api();
                            //
                            // // Output the data for the visible rows to the browser's console
                            // $('#tblAjax').DataTable().columns( 0 )
                            //     .data()
                            //     .flatten()
                            //     .filter( function ( value, index ) {
                            //         return value > 20 ? true : false;
                            //     } );
                            //
                        },


                        // fnRowCallback: function (setting) {
                        //
                        //     // setInterval(() => {
                        //     //    console.log(2)
                        //     //
                        //     // }, 2000);
                        // },


                    });


            }

            setInterval(() => {
                PublicMarketCap();

            }, 7000);


            function BindDataTable2() {
                oTable2 = $('#tblAjax2').DataTable(
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
                        // "bSort": true,
                        "bStateSave": true,
                        serverSide: true,

                        "ordering": false,
                        select: {
                            style: 'api'
                        },
                        colReorder: {
                            realtime: false
                        },

                        columns: [


                            {
                                data: 'name', name: 'name', "render": function (data, type, row) {

                                    return row["name"] + " (" + `${row['symbol']}` + ")"
                                }
                            },


                            {name: 'dominance', data: 'dominance'},
                            {name: 'db_dominance', data: 'db_dominance'},


                        ],
                        ajax: {
                            type: "POST",
                            contentType: "application/json",
                            url: '{{route('ajaxdtV3')}}',

                            data: function (d) {
                                d._token = "{{csrf_token()}}";

                                return JSON.stringify(d);
                            },
                            error: function (xhr, status, error) {
                                var err = eval("(" + xhr.responseText + ")");
                                alert(err.message);
                            },


                        },
                        "createdRow": function( row, data, dataIndex ) {
                            if(dataIndex <= 2){

                                $(row).addClass('bg-info')
                            }

                        },

                        "initComplete": function (setting) {
                            $('.loading2').remove()
                            // var table = new $.fn.dataTable.Api('#tblAjax');


                            setInterval(() => {
                                oTable2.ajax.reload(null, false);


                            }, 30000);

                        }

                    });


            }
        });

    </script>
@endsection
