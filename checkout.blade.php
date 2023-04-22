@extends('layouts.home')

@section('title')
    Thanh toán
@endsection

@section('content')
    <div class="container mt-4">
        <form action="{{ url('place-order') }}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-body">
                            <h6>
                                Thông tin khách hàng
                            </h6>
                            <hr>
                            <div class="row checkout-form">
                                <div class="col-md-6">
                                    <label for="firstName">Họ, tên đệm:</label>
                                    <input type="text" class="form-control firstname" value="{{ Auth::user()->lname }}" name="fname" placeholder="Nhập họ, tên đệm của bạn"name="" id="" required>
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
                                    <input type="text" class="form-control lastname" value="{{ Auth::user()->name }}" name="lname" placeholder="Nhập tên của bạn"name="" id="" required>
                                    <span id="lname_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Email:</label>
                                    <input type="email" class="form-control email" value="{{ Auth::user()->email }}" name="email" placeholder="Nhập email của bạn"name="" id="" required>
                                    <span id="email_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Số điện thoại:</label>
                                    <input type="number" class="form-control phone" value="{{ Auth::user()->phone }}" name="phone" placeholder="Nhập số điện thoại của bạn"name="" id="" required>
                                    <span id="phone_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Số nhà, tên đường,.. :</label>
                                    <input type="text" class="form-control address1" value="{{ Auth::user()->address1 }}" name="address1" placeholder="Nhập địa chỉ nhận hàng của bạn" name="" id="" required>
                                    <span id="address1_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Thị trấn / Huyện:</label>
                                    <input type="text" class="form-control address2" value="{{ Auth::user()->address2 }}" name="address2" placeholder="Nhập địa chỉ nhận hàng của bạn"name="" id="" required>
                                    <span id="address2_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Tỉnh / Thành phố:</label>
                                    <input type="text" class="form-control city" value="{{ Auth::user()->city }}" name="city" placeholder="Nhập tỉnh, thành phố của bạn"name="" id="" required>
                                    <span id="city_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">State:</label>
                                    <input type="text" class="form-control state" value="{{ Auth::user()->state }}" name="state" placeholder="Nhập state"name="" id="" required>
                                    <span id="state_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Đất nước:</label>
                                    <input type="text" class="form-control country" value="{{ Auth::user()->country }}" name="country" placeholder="Viet Nam" name="" id="" readonly>
                                    <input type="hidden" name="country1" value="Viet Nam">
                                    <span id="country_error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstName">Mã bưu điện:</label>
                                    <input type="number" class="form-control pincode" value="{{ Auth::user()->pincode }}" name="pincode" placeholder="Mã bưu điện" name="" id="" >
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
                                    {{-- <input type="hidden" class="totalprice" name="totalprice" value="{{ $total }}"> --}}
                            {{-- <a name="total_price">Tổng: {{ number_format($total, 0, '', '.') }}</a> --}}
                            
                            <hr>
                            <input type="hidden" name="payment_mode" value="COD">
                            <button type="submit" class="btn btn-success w-100">Đặt hàng | COD</button>
                            <a href="/vnpay-check" type="button" class="btn btn-primary w-100 mt-3 mb-2 vnpay">Thanh toán bằng VNPAY</a>
                            <a href="/momo" type="button" class="btn btn-primary w-100 mt-3 mb-2 momo">Thanh toán bằng Mono</a>
                            <div id="paypal-button-container"></div>

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
@section('scripts')
    <script src="https://www.paypal.com/sdk/js?client-id=AXNrzTIJOPHOYT0_praLeVyIzrJs4ZFybaZErlmRa3FFDDjdT2ZZzo_rloF3IanEnPxmuEMgMFf2CH-C"></script>
    <script>
        paypal.Buttons({
        createOrder:function(data, actions) {
            return actions.order.create({
                purchase_units:[{
                    amount:{
                        value:'{{ $total }}'
                    }
                }]
            });
        },
        onApprove: function(data, actions){
            return actions.order.capture().then(function(details)
            {
                var firstname = $('.firstname').val();
                var lastname = $('.lastname').val();
                var email = $('.email').val();
                var phone = $('.phone').val();
                var address1 = $('.address1').val();
                var address2 = $('.address2').val();
                var city = $('.city').val();
                var state = $('.state').val();
                var country = $('.country').val();
                var pincode = $('.pincode').val();
                var totalprice = $('.totalprice').val();
                
                $.ajax({
                    method: "POST",
                    url: "/place-order",
                    data: {
                        'fname': firstname,
                        'lname': lastname,
                        'email': email,
                        'phone': phone,
                        'address1': address1,
                        'address2': address2,
                        'city': city,
                        'state': state,
                        'country': country,
                        'pincode': pincode,
                        'payment_mode':"Paypal",
                        'payment_id':details.id,
                        'totalprice':totalprice,
                    },
                    success: function (response) {

                        swal(response.status);
                        window.location.href ="/my-orders";
                    }
                });
            });
        }
        // },
        // onCancel(data) {
        //     // Show a cancel page, or return to cart
        //     window.location.href = "/checkout";
        // },
        // onError(err) {
        //     window.location.href = "/cart";
        // }
        }).render('#paypal-button-container');
      </script>
    @endsection
