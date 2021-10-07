<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fun;
use Illuminate\Support\Facades\DB;

class ShippingController extends Controller
{

     public  function getShippingMethod(Request $request)
     {
        $currency=Fun::fetch_details(['is_default' => 1], 'ec_currencies', 'symbol');
        $_currency=isset($currency[0]->symbol)?$currency[0]->symbol:'';

        $res = DB::table('ec_shipping_rules')->selectRaw('id,name,`from`,`to`,price')->where('type','base_on_price')->get();
        foreach ($res as $key => $value) {
           
            $res[$key]->price=($res[$key]->price)<0?strval($res[$key]->price*-1):$res[$key]->price;
            $res[$key]->name=$res[$key]->name.'  '.$res[$key]->price.' '.$_currency;
        }

        $this->response['error'] = false;
        $this->response['message'] = 'Shipping Method Retrieved Successfully';
        $this->response['data'] = $res;
        return response()->json($this->response);
     }    
    
}