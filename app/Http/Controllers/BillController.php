<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Product;
use App\Http\Requests;
use App\Models\BillDetail;
use App\Http\Requests\BillRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bill::all();
        return view('bills.index', compact('bills'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bills.create');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\BillRequest $request hold data from request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(BillRequest $request)
    {
        try {
            $size = count($request->product_id);
            $bill = new Bill($request->all());
            $bill->user_id = Auth::user()->id;
            $bill->save();
            $product;
            for ($i=0; $i < $size; $i++) {
                $billDetail = new BillDetail;
                $billDetail->bill_id = $bill->id;
                $billDetail->product_id = $request->product_id[$i];
                $billDetail->amount = $request->amount[$i];
                $product = Product::findOrFail($request->product_id[$i]);
                if ($request->amount[$i] <= $product->remaining_amount) {
                    $product->remaining_amount = $product->remaining_amount - $request->amount[$i];
                    $product->save();
                    $billDetail->cost = $request->amount[$i] * $product->price;
                    $billDetail->save();
                    $i++;
                } else {
                    $bill->delete();
                    return redirect()->route('bill.create')
                    ->withErrors(trans('errors.beyond_remaining_amount'));
                }
            }
            return redirect()->route('bill.create')
                             ->withMessage(trans('bills.create.successfull_message'));
        } catch (ModelNotFoundException $saveException) {
            return redirect()->route('bill.create')
                             ->withErrors(trans('bills.create.error_message'));
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id bill id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $bill = Bill::findOrFail($id);
            $billDetails = $bill->billDetails;
            return view('bills.show', [
                'bill' => $bill,
                'billDetails' => $billDetails
            ]);
        } catch (ModelNotFoundException $ex) {
            return redirect()->route('bill.index')
                           ->withErrors(trans('bills.common.error_message'));
        }
    }
}
