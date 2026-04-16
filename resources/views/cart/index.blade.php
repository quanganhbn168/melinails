@extends('layouts.master')
@section('title', 'Giỏ hàng của bạn')
@section('content')
<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>
    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" colspan="2">Sản phẩm</th>
                            <th scope="col" class="text-center">Đơn giá</th>
                            <th scope="col" class="text-center">Số lượng</th>
                            <th scope="col" class="text-end">Tạm tính</th>
                            <th scope="col" class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody id="cart-items-container">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Tóm tắt đơn hàng</h5>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                            Tạm tính
                            <span id="summary-subtotal">0đ</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            Phí vận chuyển
                            <span>Miễn phí</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                            <div>
                                <strong>Tổng cộng</strong>
                            </div>
                            <span><strong id="summary-total">0đ</strong></span>
                        </li>
                    </ul>
                    <a href="{{ route('checkout.index') }}" class="btn bg-main w-100 mt-3">
                        Tiến hành Thanh toán
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<template id="cart-item-template">
    <tr class="cart-item-row" data-id="__ID__">
        <td style="width: 80px;">
            <img src="__IMAGE__" class="img-fluid rounded" alt="__NAME__">
        </td>
        <td>
            <h3 class="mb-0">__NAME__</h3>
            <div class="text-muted small">__VARIANT__</div>
        </td>
        <td class="text-center price-per-item" data-price="__PRICE_RAW__">__PRICE__</td>
        <td style="width: 150px;" class="text-center">
            <div class="input-group input-group-sm mx-auto" style="max-width: 120px;">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-minus" type="button">-</button>
                </div>
                <input type="number" class="form-control text-center quantity-input" value="__QUANTITY__" min="0">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary btn-plus" type="button">+</button>
                </div>
            </div>
        </td>
        <td class="text-end item-subtotal">__SUBTOTAL__</td>
        <td class="text-center">
            <a href="#!" class="text-danger remove-item-btn"><i class="fas fa-trash"></i></a>
        </td>
    </tr>
</template>
@endsection
