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

    .loading {
        left: 50%;
        margin-left: 50%;
    }

</style>
@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-6">
                                <strong class="text-dark">
                                    Total Market Cap: <span class="text-primary total_market_cap">$ 00.00</span>
                                </strong>
                            </div>
                            <div class="col-6">
                                <strong class="text-dark">
                                    Total Market Cap yesterday: <span class="text-primary total_market_cap_yesterday">$ 00.00</span>
                                </strong>
                            </div>
                        </div>


                    </div>

                    <div class="card-body">
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
                                <th scope="col">market cap (now)</th>
                                <th scope="col"> dominance</th>
                                <th scope="col">current price (now)</th>
                                <th scope="col">difference</th>

                                <th scope="col">open price</th>

                                <th scope="col">open market cap</th>
                                <th scope="col">open market cap (price * cs)</th>
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
                ' <span class="dtr-data">$ '+`${d.old_market_cap}`+'</span>' +
                '</li>' +
                // '<li data-dtr-index="7" data-dt-row="0" data-dt-column="7">' +
                // '<span class="dtr-title">open market cap (price * cs)</span>' +
                // ' <span class="dtr-data"><span class="text-success  text-center">$ '+((`${d.market_cap}`/$('.total_market_cap').text())*100)+'</span>' +
                // '</span>' +
                // '</li>' +
                '<li data-dtr-index="7" data-dt-row="0" data-dt-column="7">' +
                '<span class="dtr-title">dominance</span>' +
                ' <span class="dtr-data"><span class="text-success  text-center"> '+`${d.dominance}`+'</span>' +
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
            $.post("{{route('coin-market.globalMetrics')}}", function (data, status) {

                $('.total_market_cap').text('$ ' + data.data.total_market_cap)
                $('.total_market_cap_yesterday').text('$ ' + data.data.total_market_cap_yesterday)
            });

            let oTable;
            var tr;
            var row;

            $(function () {
                BindDataTable();
            });

            $('<div class="loading spinner-border" role="status"> <span >Loading...</span></div>').appendTo('.card-body');


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
                        "bSort": true,
                        "bStateSave": true,
                        serverSide: true,
                        dom: 'Bflrtip',


                        select: {
                            style: 'api'
                        },

                        columns: [

                            {data: 'id', name: 'id', width: "4%"},
                            {
                                data: 'name', name: 'name', "render": function (data, type, row) {

                                    return row["name"] + " (" + `${row['symbol']}` + ")"
                                }
                            },
                            {
                                name: 'old_market_cap', data: 'old_market_cap', "render": function (data, type, row) {

                                    return "<span class='text-info'>" + '$ ' + row['old_market_cap'] + "</span>";

                                }
                            },

                            {
                                data: function (data, type, row) {

                                    if (data['market_cap'] == null) {
                                        return "<span class='text-info' data-price=" + data['id'] + ">doesn't exists in DB</span>";
                                    }


                                }
                            },

                            {
                                data: function (data, type, row) {
                                    if (data['market_cap'] == null) {
                                        return "<span class='text-info' data-dominance=" + data['id'] + ">doesn't exists in DB</span>";
                                    }


                                }
                            },
                            {
                                data: function (data, type, row) {
                                    if (data['market_cap'] == null) {
                                        return "<span class='text-info' data-def=" + data['id'] + ">doesn't exists in DB</span>";
                                    }


                                }
                            },

                            {
                                name: 'price', data: 'price', "render": function (data, type, row) {
                                    if (row['old_market_cap'] == null) {
                                        return "<span class='text-info'>doesn't exists in DB</span>";
                                    }
                                    return '$ ' + row['price'];
                                }
                            },

                            {
                                name: 'old_market_cap', data: 'old_market_cap', "render": function (data, type, row) {
                                    if (row['old_market_cap'] == null) {
                                        return "<span class='text-info'>doesn't exists in DB</span>";
                                    }
                                    return '$ ' + row['old_market_cap'];
                                }
                            },
                            {
                                name: 'open_market_cap2', data: 'old_market_cap', "render": function (data, type, row) {
                                    if (row['old_market_cap'] == null) {
                                        return "<span class='text-info'>doesn't exists in DB</span>";
                                    }

                                    return "<span class='text-success  text-center'>" + '$ ' + (row['circulating_supply'] * row['price']) + "</span>";
                                }
                            },
                            {data: 'dominance', name: 'dominance', width: "4%"},

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

                                        // console.log(data, ids)
                                        let market_cap = $("table tr").find(`[data-id='${data.id}']`)
                                        market_cap.text('$ ' + collection['market_cap']);

                                        let price = $("table tr").find(`[data-price='${data.id}']`)
                                        price.text('$ ' + collection['current_price'])

                                        let def = $("table tr").find(`[data-def='${data.id}']`)
                                        def.text('$ ' + (data['old_market_cap'] - collection['market_cap']))
                                        let dominance = $("table tr").find(`[data-dominance='${data.id}']`)
                                        // dominance.text('$ ' + '1')
                                        dominance.text( ((collection['market_cap']/ '{{globalMetrics()->quote->USD->total_market_cap}}')*100))

                                    });

                                }
                            })

                        },
                        "initComplete": function () {
                            $('.loading').remove()

                            $('#tblAjax tbody').on('click', 'td.dtr-control', function () {

                                if (tr !== null && tr !== undefined && row !== null) {
                                    row.child.hide();
                                    tr.removeClass('parent');
                                }

                                tr = $(this).closest('tr');

                                row = oTable.row(tr);
                                // console.log(row.child.isShown())
                                if (row.child.isShown()) {
                                    // This row is already open - close it
                                    row.child.hide();
                                    $('.dt-hasChild').removeClass('parent');
                                } else {
                                    // Open this row
                                    row.child(format(row.data())).show();
                                    $('.dt-hasChild').addClass('parent');
                                }

                            });
                            // setInterval(function () {
                            //
                            //
                            //     oTable.ajax.reload(null, false);
                            //
                            // }, 5000);


                        }

                    });


                $.fn.dataTable.ext.errMode = 'none';

                setInterval(function () {
                    oTable.ajax.reload(function () {
                        if (row.child.isShown()) {
                            // This row is already open - close it
                            row.child.hide();
                            $('.dt-hasChild').removeClass('parent');
                        } else {
                            if (tr.hasClass('parent')) {
                                // Open this row
                                row.child(format(row.data())).show();
                                $('.dt-hasChild').addClass('parent')
                            }
                        }
                    });
                }, 5000);

                $('table td .dtr-control').html('<button><i class="fa fa-plus"></i></button>')
            }


            // setInterval(() => {
            //     oTable.ajax.reload(null, false);
            //     // $('#tblAjax').DataTable().draw()
            //
            //
            // }, 5000);




        });

    </script>
@endsection
