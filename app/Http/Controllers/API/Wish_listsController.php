<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ec_wish_list;
use App\Models\Ec_product;
class Wish_listsController extends Controller
{

        //get_favorites
        public function _getFav(Request $request)
        {
            $error=false;
            $filters=[];
            $validator = Validator::make($request->all(), [
                'user_id'=>'required',    
                'limit'=>'nullable|integer',
                'offset'=>'nullable|integer',
              
              ]);
            
            if ($validator->fails()) {
                $this->response['error'] = true;
                $this->response['message'] = $validator->errors()->first();
                $this->response['data'] = array();
            } else {
                
                $limit = (isset($request->limit)  && !empty(trim($request->limit))) ? $request->limit : 25;
                $offset = (isset($request->offset) && !empty(trim($request->offset))) ? $request->offset: 0;
                $customer_id = (isset($request->user_id) && !empty(trim($request->user_id))) ? $request->user_id: null;
                // $res = $this->db->select('(select count(id) from favorites where user_id=' . $_POST['user_id'] . ') as total, f.*')->where('user_id', $_POST['user_id'])->limit($limit, $offset)->get('favorites f')->result_array();
    
                $query=DB::table('ec_wish_lists')
                ->where('customer_id',$customer_id)
                ->groupBy('customer_id')
                ->selectRaw('group_concat(DISTINCT(product_id)) as product_ids,group_concat(DISTINCT(id)) as ids');
                $total=$query;
                $total=$total->count();
                $query=$query->get();
                $res= $query->toarray();
               $data=[];
               
                if (!empty($res)) {
                    $product_ids = explode(',', $res[0]->product_ids);
                    $product_ids = array_filter($product_ids);
                    $ids = explode(',', $res[0]->ids);
                    $ids = array_filter($ids);
                    //$pro_details = Ec_product::get_products_By_ids($query,$customer_id,$total);
                    $pro_details = Ec_product::fetch_product_json_data($customer_id,null,$product_ids, null, $request->limit, $request->offset, null, null);
                  
                    $total=$pro_details['total'];
                    foreach ($pro_details['product'] as $key => $value) {
                        $id=$ids[$key];
                        $data[$key]['id'] ="$id";
                        $data[$key]['user_id'] = $customer_id;
                        $data[$key]['product_id']=$pro_details['product'][$key]['id'];
                        $data[$key]['product_details'] = [$value];
                    }
                    
                   
                  
                } else {
                    $this->response['error'] = true;
                    $this->response['message'] = 'No Favourite(s) Product Are Added';
                    $this->response['total'] = [];
                    $this->response['data'] = [];
                    return response()->json($this->response);
                
                }
    
                $this->response['error'] = false;
                $this->response['message'] = 'Data Retrieved Successfully';
                $this->response['total'] = $total;
                $this->response['data'] = $data;
            }
            return response()->json($this->response);
        }
    
    public function _setFav(Request $request)
    {
        $error=false;
      
        $customer_id = $request->input('user_id');
        $product_id = $request->input('product_id');
  
        $data=[
            'customer_id'=>$customer_id,    
            'product_id'=>$product_id,
            ];
            
  
        $ec_wish_lists=Ec_wish_list::create($data);
    
             if($ec_wish_lists){
                $message="ok to favorite";
            }
            else{
                $message="Not Added to favorite";
                $error=true;
            }
            
            return response()->json(
                [
                    'error'=>$error,
                    'message'=>$message,
                    'data'=>[],
                ], 200);

    }  
    
    public function _removeFav(Request $request)
    {
        $error=false;
        $customer_id = $request->input('user_id');
        $product_id = $request->input('product_id');
        $data=[
            'customer_id'=>$customer_id,    
            'product_id'=>$product_id,
            ];
            
  
        $ec_wish_lists=Ec_wish_list::where('customer_id',$customer_id)->where('product_id',$product_id)->delete();
    
             if($ec_wish_lists){
                $message="Removed from favorite";
            }
            else{
                $message="Not Removed from favorite";
                $error=true;
            }
            
            return response()->json(
                [
                    'error'=>$error,
                    'message'=>$message,
                    'data'=>[],
                ], 200);

    }  
}
