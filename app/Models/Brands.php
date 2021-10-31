<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use RvMedia;
use App\Models\Fun;
class Brands extends Model
{
  
    protected $table = 'ec_brands';

   public static  function get_brands_json_data( $id = NULL, $limit = '', $offset = '', $sort = 'order', $order = 'ASC', $has_child_or_item = 'true')
   {
      
    $level = 0;        

    $where = (isset($id) && !empty($id)) ? ['id' => $id,'status' => 'published'] : ['status' =>'published'];
        
    $query=DB::table('ec_brands')->select('id','name','status','order as row_order','logo as image')
    ->where($where)
    ->orWhere(['is_featured'=>1]);
    
  
      
    if (!empty($limit) || !empty($offset)) {
        $query->offset($offset);
        $query->limit($limit);
    }

    $brands= $query->orderBy($sort, $order)->get();
 
    $count_res = Brands::count();  

  
        foreach ($brands as $key=> $brand) {
           
            $brands[$key]->parent_id="0";
            $brands[$key]->slug=str_replace(' ', '_', $brand->name);
            $brands[$key]->status="1";
            $brands[$key]->children =[];
            $brands[$key]->text = Fun::output_escaping($brand->name);
            $brands[$key]->name =Fun:: output_escaping($brand->name);
            $brands[$key]->state = ['opened' => true];
            $brands[$key]->icon = "jstree-folder";
            $brands[$key]->level ="0";
            $brands[$key]->image = RvMedia::getImageUrl($brand->image, 'small', false, RvMedia::getDefaultImage());
            $brands[$key]->banner = RvMedia::getImageUrl($brand->image, null, RvMedia::getDefaultImage());
        }
        if(isset($brands[0])){
			$brands[0]->total =$key;
        }
        
 
    return  $brands;
   }
  
 
}
