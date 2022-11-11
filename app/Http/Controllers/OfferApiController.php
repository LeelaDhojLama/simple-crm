<?php

namespace App\Http\Controllers;

use App\Models\OfferClaims;
use App\Models\Offers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(["data" => Offers::get()], 200);
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
        $offer = new Offers;
        $offer->title = $request->title;
        $offer->minimum_amount = $request->minimum_amount;
        $offer->minimum_time = $request->minimum_time;
        $offer->validity = $request->validity;


        $is_offer_active = Offers::select('id')->where("validity", '<', Carbon::now())->first();
        if ($is_offer_active != null) {
            return response()->json([
                "data" => "Offer Already Exists"
            ], 409);
        } else {
            $saveOffers = $offer->save();
            if ($saveOffers == 1) {
                return response()->json($request);
            }
        }

        return response()->json("{error:Data Not Saved}");
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
     * @return \Illuminate\Http\Response
     */
    public function getActiveOfferForCustomer($customer_id)
    {


        $offer = new OfferApiController();
        if ($offer->isUserReadyForOfferClaim($customer_id)) {
            $active_offer = Offers::select('id', 'title', 'minimum_time', 'minimum_amount')->where('validity', '>=', Carbon::now())->first();
            return response()->json($active_offer, 200);
        } else {
            return response()->json([
                "message" => "Sorry customer not eligable for offer"
            ], 412);
        }
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

        $updatedData = Offers::where('id', $id)
            ->update([
                'title' => $request->title,
                'minimum_amount' => $request->minimum_amount,
                'minimum_time' => $request->minimum_time,
                'validity' => $request->validity
            ]);

        $is_offer_active = Offers::select('id')->where("validity", '<', $request->validity)->first();
        if ($is_offer_active != null) {
            return response()->json([
                "data" => "Offer Already Exists"
            ], 409);
        } else {

            if ($updatedData == 1) {
                return response()->json($updatedData);
            }
        }

        return response()->json("{error:Data Not Saved}");


        return response()->json($updatedData);
    }


    public function isUserReadyForOfferClaim($customer_id)
    {

        $is_offer_active = Offers::select('id', 'validity', 'created_at', 'minimum_amount', 'minimum_time')
            ->where('validity', '>=', Carbon::now())
            ->first();
        $is_any_offer_active = false;


        if ($is_offer_active != null) {
            $is_any_offer_active = true;
        } else {
            $is_any_offer_active = false;
            // return response()->json($is_offer_active);
        }
        if ($is_any_offer_active) {

            if (!$this->isOfferAlreadyClaimed($customer_id, $is_offer_active->id)) {

                $data = DB::select("SELECT count(sales.customer_id) as total_purchase from sales 
                WHERE sales.customer_id = $customer_id
                        and sales.created_at>='" . $is_offer_active->created_at .
                    "'and sales.created_at<='" . $is_offer_active->validity .
                    "'and sales.amount >=" . $is_offer_active->minimum_amount);

                if ($data[0]->total_purchase >= $is_offer_active->minimum_time) {

                    return true;
                }
            }
        } else {
            return false;
        }

        return false;
    }

    public function isOfferAlreadyClaimed($customer_id, $offer_id)
    {
        $check_is_offer_already_claimed = OfferClaims::select('id')
            ->where('customer_id', '=', $customer_id)
            ->where('offer_id', '=', $offer_id)->first();


        $is_offer_already_claimed = false;
        // return response()->json($is_offer_already_claimed);

        if ($check_is_offer_already_claimed != null) {
            return true;
        } else {
            return false;
        }
    }

    public  function offersClaimedReport($startDate, $endDate)
    {
        $offer_claims = DB::table('offers')
            ->select('offers.title', 'customers.name')
            ->join('offer_claims', 'offer_claims.offer_id', '=', 'offers.id')
            ->join('sales', 'sales.id', '=', 'offer_claims.sales_id')
            ->join('customers', 'customers.id', '=', 'sales.customer_id')
            ->where('sales.created_at', '>=', $startDate)
            ->where('sales.created_at', '<=', $endDate)
            ->get();

        return response()->json(
            $offer_claims
        );
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
