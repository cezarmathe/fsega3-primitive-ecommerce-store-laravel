@extends('layout')
@section('content')

<table id="order" class="table table-hover table-condensed">
    <thead>
        <tr>
            <th style="width:80%"Total></th>
            <th style="width:20%"></th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $i => $order)
        <tr data-id="{{ $order['id'] }}">
            <td data-th="Total">{{ $order['total'] }}</td>
            <td class="actions" data-th="">
                <button class="btn btn-sm"><a href="{{ route('order:get', $order['id']) }}">View order></a></i></button>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" class="text-right">
                <a href="{{ route('products:index') }}" class="btn btn-warning"><i class="fa fa-angle-left"></i>Continue Shopping</a>
            </td>
        </tr>
    </tfoot>
</table>

@endsection
