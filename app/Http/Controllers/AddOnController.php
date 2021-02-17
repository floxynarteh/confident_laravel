<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Http\Request\CreateAddOnRequest;

class AddOnController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(\Illuminate\Http\Request $request )
    {
        return view('add-ons.index', [
            'user' =>$request->user(),
            'order' => Order::where('user_id', $request->user()->id)->exists()
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return  \Illuminate\Http\Response
     */

    public function store(CreateAddOnRequest $request)
    {
        try{
            \Strips\Stripe::setApiKey(config('services.strips.secret'));

            $charge = \Stripe\Charge::create([
                "amount" => $addon['total']*100,
                "currency" => "usd",
                "source" => $request->get('stripeToken'),
                "description" => "Confident Laravel -" .$addon['name'],
                "receipt_email" => $request->user()->email
            ]);

            $order->product_id = $addon['update'];
            $order->save();
        } catch (\Stripe\Error\Card $e){
            $data = $e->getJsonBody();
            Log::error('Card failed: ', $data);
            $templatw = 'partials.errors.charge_failed';
            $data = $data['error'];

            return back()->withInput($request->input())->with('error', compact('template', 'data'));
        }catch (\PDOException $e){
            Log::error($e);

            return back()->withInput($request->input())->with('error', ['template' => 'partials.errors.order_save_failed']);

        }catch (\Exception $e){
            Log::error($e);
            $template = 'partials.errors.order_unknown_failure';
            $data = ['code' => $e->getCode()];

            return back()->withInput($request->input())->with('error',  compact('template', 'data'));
        }
        $request->session()->flash('addon');
        return redirect()->route('dashboard.index');
    }
}
