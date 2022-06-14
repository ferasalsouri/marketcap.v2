<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\ClassesJobs;
use App\Http\Classes\PaginatorData;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompareMarketCapV3Resource;
use App\Http\Resources\DataMarketCapResource;
use App\Http\Resources\GlobalMetricsResource;
use App\Http\Resources\MarketCapApiRersource;
use App\Http\Resources\MarketCapApiV3Rersource;
use App\Http\Resources\MarketCapResource;
use App\Http\Resources\MarketCapV3Resource;
use App\Models\Coins;
use CoinMarketCap\Features\GlobalMetrics;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{


    protected $cmc = '';


    public function __construct(ClassesJobs $api)
    {

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

        $search = $request->input('search')['value'];

        $Length = $request->input("length");
        $Start = $request->input("start");
        $Draw = $request->input("draw");
        $response = $this->cmc->cryptocurrency()->listingsLatest(['limit' => 5000, 'convert' => 'USD']);

        $marketCap = MarketCapResource::collection($response->data)->resolve();
        if (!empty($search)) {
            $items = collect($marketCap)
                ->filter(function ($item) use ($search) {
                    return stripos(strtolower($item['name']), strtolower($search)) !== false
                        || stripos(strtolower($item['symbol']), strtolower($search)) !== false
                        || stripos($item['symbol'], $search) !== false;
                });


            $data = array_values($items->skip($Start)->take($Length)->toArray());
            $totalCount = count($items);
        } else {
            $items = collect($marketCap)->skip($Start)->take($Length)->whereNotNull('old_market_cap.price');
            $data = array_values($items->toArray());

            $totalCount = count($marketCap);
        }


        return response()
            ->json(['draw' => $Draw, "recordsTotal" => $totalCount,
                "recordsFiltered" => $totalCount,
                "data" => $data]);

    }

    public function loadIndex()
    {
        return view('admin.coinV3.index');
    }

    public function ajaxdata(Request $request)
    {

        $search = $request->input('search')['value'];

        $Length = $request->input("length");
        $Start = $request->input("start");
        $Draw = $request->input("draw");
        $response = Coins::get();

        $marketCap = DataMarketCapResource::collection($response)->resolve();


        if (!empty($search)) {
            $items = collect($marketCap)
                ->filter(function ($item) use ($search) {
                    return stripos(strtolower($item['name']), strtolower($search)) !== false
                        || stripos(strtolower($item['symbol']), strtolower($search)) !== false
                        || stripos($item['symbol'], $search) !== false;
                });


            $data = array_values($items->skip($Start)->take($Length)->toArray());
            $totalCount = count($items);
        } else {
            $items = collect($marketCap)->skip($Start)->take($Length);
            $data = array_values($items->toArray());

            $totalCount = count($marketCap);
        }

        return response()
            ->json(['draw' => $Draw, "recordsTotal" => $totalCount,
                "recordsFiltered" => $totalCount,
                "data" => $data]);

    }

    public function loadData(Request $request)
    {


        $response = $this->cmc->cryptocurrency()->quotesLatest(['id' => '' . $request->data . '', 'convert' => 'USD']);

        $marketCap = array_values(collect($response->data)->toArray());

        $marketCapdata = MarketCapApiRersource::collection($marketCap)->resolve();


        return response()
            ->json(["data" => $marketCapdata, 'success' => true]);

    }

    public function ajaxdataV3(Request $request)
    {

        $search = $request->input('search')['value'];

        $Length = $request->input("length");
        $Start = $request->input("start");
        $Draw = $request->input("draw");

        $response = $this->cmc->cryptocurrency()->listingsLatest(['limit' => 5000, 'convert' => 'USD']);
        $marketCap = MarketCapV3Resource::collection($response->data)->resolve();


        if (!empty($search)) {
            $items = collect($marketCap)
                ->filter(function ($item) use ($search) {
                    return stripos(strtolower($item['name']), strtolower($search)) !== false
                        || stripos(strtolower($item['symbol']), strtolower($search)) !== false
                        || stripos($item['symbol'], $search) !== false;
                });


            $data = array_values($items->sortBy('id')->skip($Start)->take($Length)->toArray());
            $totalCount = count($items);
        } else {
            $items = collect($marketCap)->sortBy('id')->skip($Start)->take($Length);
            $data = array_values($items->toArray());

            $totalCount = count($marketCap);
        }

        return response()
            ->json(['draw' => $Draw, "recordsTotal" => $totalCount,
                "recordsFiltered" => $totalCount,
                "data" => $data]);

    }

    public function loadDataV3(Request $request)
    {

        $response = Coins::whereIn('IDs', $request->data)->get();

        $marketCapdata = MarketCapApiV3Rersource::collection($response)->resolve();

        return response()
            ->json(["data" => $marketCapdata, 'success' => true]);

    }

    public function ajaxdtV3(Request $request)
    {

        $Length = $request->input("length");
        $Start = $request->input("start");
        $Draw = $request->input("draw");

        $response = $this->cmc->cryptocurrency()->listingsLatest(['limit' => 5000, 'convert' => 'USD']);

        $marketCap = CompareMarketCapV3Resource::collection(collect($response->data))->resolve();
        $data=  collect($marketCap)
        ->reject(function ($value, $key) {
        return $value['db_dominance'] > $value['dominance'];
    })->skip($Start)->take($Length);

//       $data= collect($marketCap)->sortBy('id')->whereNotNull('db_dominance')->where('dominance','<','db_dominance');

        $totalCount = count($data);

        return response()
            ->json(['draw' => $Draw, "recordsTotal" => $totalCount,
                "recordsFiltered" => $totalCount,
                "data" => array_values($data->toArray())]);

    }

    public function globalMetrics()
    {
        $response = $this->cmc->globalMetrics()->quotesLatest(['convert' => 'USD']);
        $marketCapdata = new GlobalMetricsResource($response->data);

        return response()
            ->json(["data" => $marketCapdata]);

    }


}
