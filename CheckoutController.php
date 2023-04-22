<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    //
    public function index()
    {
        $old_cartitems = Cart::where('user_id', Auth::id())->get();
        foreach($old_cartitems as $item)
        {
            if(!Product::where('id', $item->prod_id)->where('qty','>=', $item->prod_qty)->exists())
            {
                $removeItem = Cart::where('user_id', Auth::id())->where('prod_id', $item->prod_id)->first();
                $removeItem->delete();
            }
        }

        $cartitems = Cart::where('user_id', Auth::id())->get();
        return view('home.checkout',compact('cartitems'));
    }

    public function placeoder (Request $request) 
    {
        $country = $request->input('country1');
        $order = new Order();
        $order->user_id = Auth::id();
        $order->fname = $request->input('fname');
        $order->lname = $request->input('lname');
        $order->email = $request->input('email');
        $order->phone = $request->input('phone');
        $order->address1 = $request->input('address1');
        $order->address2 = $request->input('address2');
        $order->city = $request->input('city');
        $order->state = $request->input('state');
        $order->country = $country;
        $order->pincode = $request->input('pincode');
        $order->payment_mode = $request->input('payment_mode');
        $order->payment_id = $request->input('payment_id');

        $total = 0;
        $cartitems_total = Cart::where('user_id', Auth::id())->get();
        foreach ($cartitems_total as $prod) 
        {
            if($prod->products->selling_price == 0) 
            {
                $total += $prod->products->original_price * $prod->prod_qty;
                
            }else
            {
                $total += $prod->products->selling_price * $prod->prod_qty;
                
            }
        }
        $order->total_price = $total;
        // $order->total_price = $request->input('totalprice');

        $order->tracking_no = 'sharma'.rand(1111,9999);
        $order->save();

        $order->id;

        $cartitems = Cart::where('user_id', Auth::id())->get();
        foreach($cartitems as $item)
        {
            if($item->products->selling_price > 0)
            {
                OrderItem::create([
                    'order_id' => $order->id,
                    'prod_id' => $item->prod_id,
                    'qty' => $item->prod_qty,
                    'price' => $item->products->selling_price,
                ]);
            }else
            OrderItem::create([
                'order_id' => $order->id,
                'prod_id' => $item->prod_id,
                'qty' => $item->prod_qty,
                'price' => $item->products->original_price,
            ]);

            $prod = Product::where('id',$item->prod_id)->first();
            $prod->qty = $prod->qty - $item->prod_qty;
            $prod->update();
        }

        if(Auth::user()->address1 == null)
        {
            $user = User::where('id',Auth::id())->first();
            $user->lname = $request->input('lname');
            $user->phone = $request->input('phone');
            $user->address1 = $request->input('address1');
            $user->address2 = $request->input('address2');
            $user->city = $request->input('city');
            $user->state = $request->input('state');
            $user->country = $request->input('country');
            $user->pincode = $request->input('pincode');
            $user->update();
        }

        $cartitems = Cart::where('user_id', Auth::id())->get();
        Cart::destroy($cartitems);

        if($request->input('payment_mode') == "Paypal")
        {
            return response()->json(['status'=>"Đặt hàng thành công! Cảm ơn quý khách đã đặt hàng, kiện hàng sẽ được chuyển đến quý khách nhanh nhất."]);
        }
        return redirect('/')->with('status', 'Đặt hàng thành công! Cảm ơn quý khách đã đặt hàng, kiện hàng sẽ được chuyển đến quý khách nhanh nhất.');
    }

    public function vnpay23(Request $request)
    {
        $cartitems = Cart::where('user_id', Auth::id())->get();
        $total_price = 0;
        foreach($cartitems as $item)
        {
            if($item->products->selling_price > 0)
            {
                $total_price += $item->products->selling_price * $item->prod_qty;  
            }else
            {
                $total_price += $item->products->original_price * $item->prod_qty;  
            }
            
        }
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address1 = $request->input('address1');
        $address2 = $request->input('address2');
        $city = $request->input('city');
        $state = $request->input('state');
        $country = $request->input('country');
        $pincode = $request->input('pincode');



    }

    public function vnpay_check()
    {
        $old_cartitems = Cart::where('user_id', Auth::id())->get();
        foreach($old_cartitems as $item)
        {
            if(!Product::where('id', $item->prod_id)->where('qty','>=', $item->prod_qty)->exists())
            {
                $removeItem = Cart::where('user_id', Auth::id())->where('prod_id', $item->prod_id)->first();
                $removeItem->delete();
            }
        }

        $cartitems = Cart::where('user_id', Auth::id())->get();
        return view('home.vnpaycheck',compact('cartitems'));
    }

    public function vnpay2(Request $request)
    {
        $cartitems = Cart::where('user_id', Auth::id())->get();
        $total_price = 0;
        foreach($cartitems as $item)
        {
            if($item->products->selling_price > 0)
            {
                $total_price += $item->products->selling_price * $item->prod_qty;  
            }else
            {
                $total_price += $item->products->original_price * $item->prod_qty;  
            }
            
        }

        $firstname = $request->input('fname');
        $lastname = $request->input('lname');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address1 = $request->input('address1');
        $address2 = $request->input('address2');
        $city = $request->input('city');
        $state = $request->input('state');
        $country = $request->input('country');
        $pincode = $request->input('pincode');
        $payment_mode = "VNPAY";


        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://controller-app.test/checkout";
        $vnp_TmnCode = "3C5MVIN9";//Mã website tại VNPAY 
        $vnp_HashSecret = "PJZLTAFYHGEMOBTTUVTTIQKJZXJXUPKP"; //Chuỗi bí mật

        $vnp_TxnRef = rand(1,1000); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'order_desc';
        $vnp_OrderType = 'order_type';
        $vnp_Amount = $total_price * 100000;
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';

        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            
        }
        $returnData = array('code' => '00'
            , 'message' => 'success'
            , 'data' => $vnp_Url);
            if (isset($_POST['redirect'])) {
                header('Location: ' . $vnp_Url);
                die();
                
            } else {
                echo json_encode($returnData);
            }
            // vui lòng tham khảo thêm tại code demo
            }
    
        
        }
