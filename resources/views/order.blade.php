@extends('layout')
@section('content')

<table id="order" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th style="width:50%">Product</th>
            <th style="width:10%">Price</th>
            <th style="width:8%">Quantity</th>
            <th style="width:22%" class="text-center">Subtotal</th>
            <th style="width:10%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($orderItems as $i => $orderItem)
        <tr data-id="{{ $orderItem['id'] }}">
            <td data-th="Product">
                <div class="row">
                    <div class="col-sm-3 hidden-xs"><img src="{{ $orderItem['product']['image'] }}" width="100" height="100" class="img-responsive" /></div>
                    <div class="col-sm-9">
                        <h4 class="nomargin">{{ $orderItem['product']['name'] }}</h4>
                    </div>
                </div>
            </td>
            <td data-th="Price">${{ $orderItem['product']['price'] }}</td>
            <td data-th="Quantity"> {{ $orderItem['quantity'] }} </td>
            <td data-th="Subtotal" class="text-center">${{ $orderItem['product']['price'] * $orderItem['quantity'] }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right">
                <h3><strong>Total ${{ $order['total'] }}</strong></h3>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="text-right">
                <a href="{{ route('products:index') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a>
            </td>
        </tr>
    </tfoot>
</table>

@endsection
