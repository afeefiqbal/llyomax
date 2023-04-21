<?php

namespace App\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerScheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->roles->pluck('name')->first();
        if ($userRole == 'customer'){

         //   $customerScheme = CustomerScheme::where('id', $id)->first();
            $customer = Customer::where('user_id', $user->id)->with('area')->first();

            $customerSchemes = CustomerScheme::where('customer_id', $customer->id)->get();
            // dd($customerSchemes);
            return view('backend.customers.customer.customer-dashboard')->with(compact('customer', 'customerSchemes'));
        //    return view('backend.customers.customer.show-customer')->with(compact('customer', 'customerSchemes'));
        }

        }
        public function dashboard()
        {
            # code...

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
