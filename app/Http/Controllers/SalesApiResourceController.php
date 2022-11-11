<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PhpMailerController;
use App\Models\OfferClaims;
use App\Models\Offers;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;

class SalesApiResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $sales = new Sales();
        $sales->customer_id = $request->customer_id;
        $sales->description = $request->description;
        $sales->amount = $request->amount;

        $saveSales = $sales->save();

        if ($saveSales == 1) {
            $php_mailer_controller = new PhpMailerController();
            $php_mailer_controller->sendEmail($request->email);
            return response()->json($sales);
        }
        return response()->json($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        return response()->json(Sales::filter_id($id), 200);
    }

    public function showAllSalesByCustomerID($customer_id)
    {
        return response()->json(Sales::where('customer_id', $customer_id)->get(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showSalesByCustomerID($customer_id)
    {
        //
        $offer = new OfferApiController();
        $offer_claim = new OfferClaimApiController();

        $is_offer_active = Offers::select('id', 'validity', 'created_at', 'minimum_amount', 'minimum_time', 'created_at')
            ->where('validity', '>=', Carbon::now())
            ->first();

        $is_any_offer_active = false;
        $offer_claimed = null;
        $is_offer_already_claimed = false;

        if ($is_offer_active != null) {
            $is_any_offer_active = true;
        } else {
            $is_any_offer_active = false;
        }

        if ($is_any_offer_active) {
            $get_is_offer_already_claimed = OfferClaims::select('id', 'created_at')
                ->where('customer_id', '=', $customer_id)
                ->where('offer_id', '=', $is_offer_active->id)->first();

            if ($get_is_offer_already_claimed != null) {
                $is_offer_already_claimed = true;
                $offer_claimed = $get_is_offer_already_claimed;
            } else {
                $is_offer_already_claimed = false;
            }
        }

        if ($is_offer_already_claimed) {
            return response()->json(
                [
                    "sales_history" => Sales::where('customer_id', $customer_id)->where('created_at', '>=', $offer_claimed->created_at)->get(),
                    "offer_status" => $offer->isUserReadyForOfferClaim($customer_id),
                    "is_offered_claimed" =>  true,
                    "is_offer_active" => $is_any_offer_active
                ],
                200
            );
        } else {
            return response()->json(
                [
                    "sales_history" => Sales::where('customer_id', $customer_id)->get(),
                    "offer_status" => $offer->isUserReadyForOfferClaim($customer_id),
                    "is_offered_claimed" =>  false,
                    "is_offer_active" => $is_any_offer_active
                ],
                200
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerReport()
    {
        // $customers = DB::table('customers')
        //     ->join('sales', 'customers.id', '=', 'sales.customer_id')
        //     ->select('customers.id', 'customers.name', 'customers.contact', 'sales.description', 'sales.amount')
        //     ->get();

        $customers = DB::select('SELECT c.id, c.name,c.contact, s.description,amount, sa.total_amount, s.created_at FROM customers c 
        JOIN (SELECT customer_id,SUM(amount)as total_amount, MAX(created_at) as MaxPID FROM sales GROUP BY customer_id) sa 
        ON c.id = sa.customer_id JOIN sales s ON s.created_at = sa.MaxPID');

        return response()->json([
            'data' => $customers,
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function weeklyReport()
    {
        $current_date = Carbon::now();
        $interval = DateInterval::createFromDateString('7 day');
        $startDate = Carbon::now()->subWeek();
        $endDate = Carbon::now();
        $period = new DatePeriod($startDate, CarbonInterval::day(), $endDate);

        $salesReport = DB::table('sales as w')
            ->select(array(DB::Raw('sum(w.amount) as amount'), DB::Raw('DATE(w.created_at) day')))
            ->whereDate('created_at', '<=', $current_date->format('Y-m-d'))
            ->whereDate('created_at', '>=', Carbon::now()->startOfWeek()->subWeek()->format('Y-m-d'))
            ->groupBy('day')
            ->orderBy('w.created_at')
            ->get();

        return response()->json($salesReport);
    }


    public function customDateRangeSalesReport($startDate, $endDate)
    {

        $salesReport = DB::table('sales')
            ->select('sales.created_at', 'customers.name', 'sales.description', 'sales.amount')
            ->join('customers', 'sales.customer_id', '=', 'customers.id')
            ->where('sales.created_at', '>=', $startDate)
            ->where('sales.created_at', '<=', $endDate)
            ->get();

        $total_amount = DB::table('sales')
            ->selectRaw('sum(sales.amount) as total_amount')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->where('sales.created_at', '>=', $startDate)
            ->where('sales.created_at', '<=', $endDate)
            ->first();

        return response()->json(
            [
                "sales_report" => $salesReport,
                "total_amount" => $total_amount->total_amount
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
