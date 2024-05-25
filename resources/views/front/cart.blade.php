@extends('front.layouts.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                <li class="breadcrumb-item active">Cart</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <div class="row">
            @if (Session::has('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ Session::get('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (Session::has('error'))
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ Session::get('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if ($cartContent->count() > 0)
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartContent as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->options->productImage)
                                                    <img class="card-img-top" src="{{ asset('uploads/product/small/'.$item->options->productImage->image) }}" alt="Product Image">
                                                @else
                                                    <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="Default Image">
                                                @endif
                                                <h2>{{ $item->name }}</h2>
                                            </div>
                                        </td>
                                        <td>{{ $item->price }}$</td>
                                        <td>
                                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{ $item->rowId }}">
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control form-control-sm border-0 text-center qty" value="{{ $item->qty }}" readonly>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{ $item->rowId }}">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->price * $item->qty }}$</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger remove" onclick="deleteItem('{{ $item->rowId }}');"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="col-md-8">
                    <div class="alert alert-warning" role="alert">
                        No items in the cart.
                    </div>
                </div>
            @endif

            <div class="col-md-4">
                <div class="card cart-summary">
                    <div class="sub-title">
                        <h2 class="bg-white">Cart Summary</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between pb-2">
                            <div>Subtotal</div>
                            <div>{{ Cart::subtotal() }}$</div>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <div>Shipping</div>
                            <div>0$</div>
                        </div>
                        <div class="d-flex justify-content-between summary-end">
                            <div>Total</div>
                            <div>{{ Cart::subtotal() }}$</div>
                        </div>
                        <div class="pt-5">
                            <a href="#" class="btn btn-dark btn-block w-100">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
                <div class="input-group apply-coupon mt-4">
                    <input type="text" placeholder="Coupon Code" class="form-control">
                    <button class="btn btn-dark" type="button" id="button-addon2">Apply Coupon</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $(document).on('click', '.add', function() {
        var qtyElement = $(this).closest('.quantity').find('.qty');
        var qtyValue = parseInt(qtyElement.val());
        if (qtyValue < 10) {
            qtyElement.val(qtyValue + 1);
            updateCart($(this).data('id'), qtyValue + 1);
        }
    });

    $(document).on('click', '.sub', function() {
        var qtyElement = $(this).closest('.quantity').find('.qty');
        var qtyValue = parseInt(qtyElement.val());
        if (qtyValue > 1) {
            qtyElement.val(qtyValue - 1);
            updateCart($(this).data('id'), qtyValue - 1);
        }
    });

    function updateCart(rowId, qty) {
        $.ajax({
            url: '{{ route("front.updateCart") }}',
            type: 'POST',
            data: {
                rowId: rowId,
                qty: qty,
                _token: '{{ csrf_token() }}'
            },
            dataType: 'json',
            success: function(response) {
               window.location.href = "{{ route('front.cart') }}";
            },
            error: function(xhr, status, error) {
                console.error('Error updating cart:', error);
            }
        });
    }

    function deleteItem(rowId) {
        if (confirm("Are you sure you want to delete?")) {
            $.ajax({
                url: '{{ route("front.deleteItem.cart") }}',
                type: 'POST',
                data: {
                    rowId: rowId,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function(response) {
                    window.location.href = "{{ route('front.cart') }}";
                },
                error: function(xhr, status, error) {
                    console.error('Error deleting item:', error);
                }
            });
        }
    }
</script>
@endsection
