@extends('layouts.home')

@section('title')
    Thanh toán VNPAY
@endsection

@section('content')
    <div class="container mt-4">
        <form action="{{ url('/vnpay_php/vnpay_pay.php') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <h6>
                                Xác nhận thông tin khách hàng.
                            </h6>
                            <hr>
                            <div class="row checkout-form">
                                <div class="col-md-6">
                                    <label for="firstName">Họ, tên đệm:</label>
                                    <input type="text" class="form-control firstname" value="{{ Auth::user()->name }}" name="fname" placeholder="Nhập họ, tên đệm của bạn"name="" id="">
                                    <style>
                                        span{
                                            font-size: 11px;
                                            color: red;
                                        }
                                    </style>
                                    <span id="fname_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Tên:</label>
                                    <input type="text" class="form-control lastname" value="{{ Auth::user()->lname }}" name="lname" placeholder="Nhập tên của bạn"name="" id="">
                                    <span id="lname_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Email:</label>
                                    <input type="email" class="form-control email" value="{{ Auth::user()->email }}" name="email" placeholder="Nhập email của bạn"name="" id="">
                                    <span id="email_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Số điện thoại:</label>
                                    <input type="number" class="form-control phone" value="{{ Auth::user()->phone }}" name="phone" placeholder="Nhập số điện thoại của bạn"name="" id="">
                                    <span id="phone_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Số nhà, tên đường,.. :</label>
                                    <input type="text" class="form-control address1" value="{{ Auth::user()->address1 }}" name="address1" placeholder="Nhập địa chỉ nhận hàng của bạn" name="" id="">
                                    <span id="address1_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Thị trấn / Huyện:</label>
                                    <input type="text" class="form-control address2" value="{{ Auth::user()->address2 }}" name="address2" placeholder="Nhập địa chỉ nhận hàng của bạn"name="" id="">
                                    <span id="address2_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Tỉnh / Thành phố:</label>
                                    <input type="text" class="form-control city" value="{{ Auth::user()->city }}" name="city" placeholder="Nhập tỉnh, thành phố của bạn"name="" id="">
                                    <span id="city_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">State:</label>
                                    <input type="text" class="form-control state" value="{{ Auth::user()->state }}" name="state" placeholder="Nhập state"name="" id="">
                                    <span id="state_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Đất nước:</label>
                                    <input type="text" class="form-control country" value="{{ Auth::user()->country }}" name="country" value="VietNamese" placeholder="Viet Nam" name="" id="" readonly>
                                    <span id="country_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Mã bưu điện:</label>
                                    <input type="number" class="form-control pincode" value="{{ Auth::user()->pincode }}" name="pincode" placeholder="Mã bưu điện" name="" id="">
                                    <span id="pincode_error"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card">
                        @if ($cartitems->count() > 0)
                        <div class="card-body">
                            
                            <h6>Thông tin đơn hàng</h6>
                            <hr>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tên</th>
                                        <th>Số lượng</th>
                                        <th>Giá tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cartitems as $item)
                                    <tr>
                                        <td>
                                        {{ $item->products->name }}  
                                        </td>
                                        <td>
                                            {{ $item->prod_qty }}
                                        </td>
                                        @if ($item->products->selling_price > 0)
                                            <td>
                                            {{ $item->products->selling_price }}  
                                        </td>
                                        @else
                                            <td>
                                                {{ $item->products->original_price }}  
                                            </td>
                                        @endif
                                        <td>
                                        </td>
                                        
                                    </tr>
                                    
                                    @endforeach
                                    
                                </tbody>
                                
                            </table>
                            @php
                                $total = 0;
                            @endphp
                            
                            @foreach ($cartitems as $item)
                            @php
                                if ($item->products->selling_price > 0)
                                $total += $item->products->selling_price * $item->prod_qty;
                                else {
                                    $total += $item->products->original_price * $item->prod_qty;
                                }
                            @endphp 
                            @endforeach
                            <label for="firstName">Tổng:</label>
                                    <a type="number">{{ number_format($total, 0, '', '.') }} </a>
                            {{-- <a name="total_price">Tổng: {{ number_format($total, 0, '', '.') }}</a> --}}
                                {{-- <input type="hidden" class="totalprice" name="totalprice" value="{{ $total }}"> --}}
                                
                            <hr>
                            <input type="hidden" name="payment_mode" value="VNPAY">
                            <button type="submit" class="btn btn-primary w-100 mt-3">Thanh toán bằng ví điện tử VNPAY</button>
                        </div> 
                        @else
                            <div class="card-body">
                                <h6>Thông tin đơn hàng:</h6>
                                <hr>
                                <h6 class="text-center">Giỏ hàng của bạn đang trống!</h6>
                            </div>
                        @endif
                        
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection