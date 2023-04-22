<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VnpayController extends Controller
{
    //
    public function index()
    {
        return view('vnpay_php.vnpay_php.index');
    }

    public function vnpay_pay(Request $request)
    {
        $user_id = Auth::id();
        $fname = $request->input('fname');
        $lname = $request->input('lname');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $address1 = $request->input('address1');
        $address2 = $request->input('address2');
        $city = $request->input('city');
        $state = $request->input('state');
        $country = $request->input('country');
        $pincode = $request->input('pincode');
        $payment_mode = $request->input('payment_mode');
        $payment_id = $request->input('payment_id');
        $total_price = $request->input('totalprice');
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
        return view('vnpay_php.vnpay_php.vnpay_pay',compact(
                	    'total',
                        'user_id',
                        'fname',
                        'lname',
                        'email',
                        'phone',
                        'address1',
                        'address2',
                        'city',
                        'state',
                        'country',
                        'pincode',
                        'payment_mode',
                        'payment_id',
        ));
    }

    public function return()
    {
        // date_default_timezone_set('Asia/Ho_Chi_Minh');

        // $vnp_HashSecret = "PJZLTAFYHGEMOBTTUVTTIQKJZXJXUPKP"; //Secret key

        // $inputData = array();
        // $returnData = array();
        // foreach ($_GET as $key => $value) {
        //             if (substr($key, 0, 4) == "vnp_") {
        //                 $inputData[$key] = $value;
        //             }
        //         }

        // $vnp_SecureHash = $inputData['vnp_SecureHash'];
        // unset($inputData['vnp_SecureHash']);
        // ksort($inputData);
        // $i = 0;
        // $hashData = "";
        // foreach ($inputData as $key => $value) {
        //     if ($i == 1) {
        //         $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
        //     } else {
        //         $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        //         $i = 1;
        //     }
        // }

        // $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        // $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
        // $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
        // $vnp_Amount = $inputData['vnp_Amount']/100; // Số tiền thanh toán VNPAY phản hồi

        // $Status = 0; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo URL thanh toán.
        // $orderId = $inputData['vnp_TxnRef'];

        // try {
        //     //Check Orderid    
        //     //Kiểm tra checksum của dữ liệu
        //     if ($secureHash == $vnp_SecureHash) {
        //         //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId            
        //         //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch
        //         //Giả sử: $order = mysqli_fetch_assoc($result);   

        //         $order = NULL;
        //         if ($order != NULL) {
        //             if($order["Amount"] == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền kiểm tra là đúng. //$order["Amount"] == $vnp_Amount
        //             {
        //                 if ($order["Status"] != NULL && $order["Status"] == 0) {
        //                     if ($inputData['vnp_ResponseCode'] == '00' && $inputData['vnp_TransactionStatus'] == '00') {
        //                         $Status = 1; // Trạng thái thanh toán thành công
        //                     } else {
        //                         $Status = 2; // Trạng thái thanh toán thất bại / lỗi
        //                     }
        //                     //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB
        //                     //
        //                     //
        //                     //
        //                     //Trả kết quả về cho VNPAY: Website/APP TMĐT ghi nhận yêu cầu thành công                
        //                     $returnData['RspCode'] = '00';
        //                     $returnData['Message'] = 'Confirm Success';
        //                 } else {
        //                     $returnData['RspCode'] = '02';
        //                     $returnData['Message'] = 'Order already confirmed';
        //                 }
        //             }
        //             else {
        //                 $returnData['RspCode'] = '04';
        //                 $returnData['Message'] = 'invalid amount';
        //             }
        //         } else {
        //             $returnData['RspCode'] = '01';
        //             $returnData['Message'] = 'Order not found';
        //         }
        //     } else {
        //         $returnData['RspCode'] = '97';
        //         $returnData['Message'] = 'Invalid signature';
        //     }
        // } catch (Exception $e) {
        //     $returnData['RspCode'] = '99';
        //     $returnData['Message'] = 'Unknow error';
        // }
         //Trả lại VNPAY theo định dạng JSON
        // echo json_encode($returnData);
        return view('vnpay_php.vnpay_php.vnpay_return');
    }

    public function vnpay_create_payment(Request $request)
    {
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
        $order->country = $request->input('country');
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

        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        /**
         * 
         *
         * @author CTT VNPAY
         */
        $vnp_TmnCode = "3C5MVIN9"; //Mã định danh merchant kết nối (Terminal Id)
        $vnp_HashSecret = "PJZLTAFYHGEMOBTTUVTTIQKJZXJXUPKP"; //Secret key
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://controller-app.test/return";
        $vnp_apiUrl = "http://sandbox.vnpayment.vn/merchant_webapi/merchant.html";
        $apiUrl = "https://sandbox.vnpayment.vn/merchant_webapi/api/transaction";
        //Config input format
        //Expire
        $startTime = date("YmdHis");
        $expire = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));

        $vnp_TxnRef = rand(1,10000); //Mã giao dịch thanh toán tham chiếu của merchant
        $vnp_Amount = $_POST['amount']; // Số tiền thanh toán
        $vnp_Locale = $_POST['language']; //Ngôn ngữ chuyển hướng thanh toán
        $vnp_BankCode = $_POST['bankCode']; //Mã phương thức thanh toán
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; //IP Khách hàng thanh toán
        
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount* 100,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" =>$vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate"=>$expire,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

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
        header('Location: ' . $vnp_Url);
        die();
        }
}
