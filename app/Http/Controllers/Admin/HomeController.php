<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\ClassesJobs;
use App\Http\Classes\PaginatorData;
use App\Http\Controllers\Controller;
use App\Http\Resources\MarketCapResource;
use App\Models\Coins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    protected $cmc = '';

    public function __construct(ClassesJobs $api)
    {
        $this->middleware('auth');

        $this->cmc = $api->ConnectionCoinMarketCap();

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.coin.index');
    }

    public function ajaxdt(Request $request)
    {


        $Length = $request->input("length");
        $Start = $request->input("start");
        $Draw=$request->input("Draw");

        $response = $this->cmc->cryptocurrency()->listingsLatest([ 'limit' => 5000, 'convert' => 'USD']);

        $marketCap = MarketCapResource::collection($response->data)->resolve();


        $items =collect($marketCap)->take($Length)->skip($Start);

        $totalCount = $marketCap->count();
//        $Draw = $marketCapdata->currentPage();

        return response()
            ->json(['draw' => $Draw, "recordsTotal" => $totalCount,
                "recordsFiltered" => $totalCount,
                "data" => $items]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        dd(Coins::count());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
