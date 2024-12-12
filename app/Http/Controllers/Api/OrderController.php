<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMech;
use App\Models\Customer;
use App\Models\OrderHeader;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{


    public function create_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'order_items' => 'required',
            'shipping_contact_mech_id' => 'required',
            'billing_contact_mech_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 402);
        }
        
        $order=new OrderHeader();
        $order->order_date=date("Y/m/d");
        $order->customer_id=$request->customer_id;
        $order->shipping_contact_mech_id=$request->shipping_contact_mech_id;
        $order->billing_contact_mech_id=$request->billing_contact_mech_id;
        
        $order->save();
        $order_items = $request->order_items;
        Log::info($order_items);
        if ($order_items) {
            

            foreach ($order_items as $item) {
            
            $items=new OrderItem();
            $items->order_id=$order->order_id;
            $items->product_id=$item['product_id'];
            $items->quantity=$item['quantity'];
            $items->status=$item['status'];
            $items->save();    
        }

        }
        $order_itemss= []; 
        foreach ($order_items as $item) {
            $product_name=Product::where('product_id',$item['product_id'])->value('product_name');
            $order_itemss[] = [
                'product_id' => $product_name,
                'quantity' => $item['quantity'],
                'status' => $item['status']
            ];
        }
        $order->items=$order_itemss;
        return response()->json(['message' => 'Order Placed Successfully',"Order"=>$order], 200);

        
    }

    public function get_order($id)
    {
        $order=OrderHeader::where('order_id',$id)->first();
        if(!$order)
        {
            return response()->json(['success' => false, 'message' => 'order not found'], 404);
        }    
        $items=OrderItem::where('order_id',$id)->get();
        $order_items= []; 
        foreach ($items as $item) {
            $product_name=Product::where('product_id',$item['product_id'])->value('product_name');
            $order_items[] = [
                'product_id' => $product_name,
                'quantity' => $item['quantity'],
                'status' => $item['status']
            ];
        }
        $order->items=$order_items;
        $customer=Customer::where('customer_id',$order->customer_id)->get();
        $order->customer=$customer;
        return response()->json(['message' => 'Order Details', 'data' => $order], 200);
         
    
    }

    public function update_order(Request $request,$id)
    {
        $order=OrderHeader::where('order_id',$id)->first();
        if(!$order)
        {
            return response()->json(['success' => false, 'message' => 'order not found'], 404);
        }    
        $validator = Validator::make($request->all(), [
            'shipping_contact_mech_id' => 'required',
            'billing_contact_mech_id' => 'required',      
            ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 402);
        }

        $order->shipping_contact_mech_id=$request->shipping_contact_mech_id;
        $order->billing_contact_mech_id=$request->billing_contact_mech_id;
        $order->save();

        return response()->json(['message' => 'Order updated successfully',"order"=>$order], 200);
    } 

    public function delete_order($id)
    {
        $order=OrderHeader::where('order_id',$id)->first();
        // return response()->json(['message' => $order], 200);
        if(!$order)
        {
            return response()->json(['success' => false, 'message' => 'order not found'], 404);
        }    
        $order->delete();

    return response()->json(['message' => 'Order deleted successfully'], 200);
    }
   
    public function add_item(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'quantity' => 'required',
            'status' => 'required',

        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 402);
        }

        $order=OrderHeader::where('order_id',$id)->first();
        if(!$order)
        {
            return response()->json(['success' => false, 'message' => 'order not found'], 404);
        } 
        
        $items=new OrderItem();
        $items->order_id=$id;
        $items->product_id=$request->product_id;
        $items->quantity=$request->quantity;
        $items->status=$request->status;
        $items->save();    
       
        
        return response()->json(['message' => 'Item Added Successfully','item'=>$items], 200);

        
    }
    public function update_item(Request $request,$id,$item_id)
    {

        $validator = Validator::make($request->all(), [
            'quantity' => 'required',
            'status' => 'required',

        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 402);
        }

        $order=OrderHeader::where('order_id',$id)->first();
        if(!$order)
        {
            return response()->json(['success' => false, 'message' => 'order not found'], 404);
        } 
        
        $order_item=OrderItem::where('order_item_seq_id',$item_id)->first();
        // return response()->json(['success' => false, 'message' => $order_item], 404);
        if(!$order_item)
        {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        } 
        
        $order_item->quantity=$request->quantity;
        $order_item->status=$request->status;
        $order_item->save();    
       
        
        return response()->json(['message' => 'Item Updated Successfully','item'=>$order_item], 200);

        
    }

    public function delete_item($order_id,$order_item_seq_id)
    {
        $order=OrderHeader::where('order_id',$order_id)->first();
        // return response()->json(['message' => $order], 200);
        if(!$order)
        {
            return response()->json(['success' => false, 'message' => 'order not found'], 404);
        }  
        $order_item=OrderItem::where('order_item_seq_id',$order_item_seq_id)->first();
        // return response()->json(['success' => false, 'message' => $order_item], 404);
        if(!$order_item)
        {
            return response()->json(['success' => false, 'message' => 'Item not found'], 404);
        } 
        $order_item->delete();

    return response()->json(['message' => 'Item deleted successfully'], 200);
    }

    public function update_contact_mech(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 402);
        }
        
        $shipping=ContactMech::where('contact_mech_id',$id)->first();
        if(!$shipping)
        {
            return response()->json(['success' => false, 'message' => 'Contact not found'], 404);
        }  
        $shipping->street_address=$request->street_address;
        $shipping->city=$request->city;
        $shipping->state=$request->state;
        $shipping->postal_code=$request->postal_code;
        $shipping->email=$request->email;
        $shipping->phone_number=$request->phone_number;        
        $shipping->save();


        return response()->json(['message' => 'Contact updated successfully',"Details"=>$shipping], 200);
    }
    
    
    
    

}
