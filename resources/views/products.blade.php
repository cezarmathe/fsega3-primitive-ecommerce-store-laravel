@extends('layouts.app')
@section('content')
<div class="container">
    <div class="mb-5 mt-4">
    </div>
    <div class="row">

        @foreach($products as $product)
        <div class="row-xs-18 row-sm-6 row-md-3">
            <div class="thumbnail my-3" style="display: flex">
                <img src="{{ $product->image }}" alt="" width=200 height=300>
                <div class="caption px-5 py-3">
                    <h4>{{ $product->name }}</h4>
                    <p>{{ $product->description }}</p>
                    <div class="">
                        <p><strong>Price: </strong> {{ $product->price }}$</p>
                        <form action="{{ route('cart:addItem') }}" method="POST">
                            @csrf

                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <p class="btn-holder">
                            <input type="submit" value="Add to cart" class="btn btn-warning btn-block text-center" />
                            </p>
                        </form>
                        <!-- <p class="btn-holder"><a href="{{ route('cart:addItem', $product->id) }}" class="btn btn-warning btn-block text-center" role="button">Add to cart</a> </p> -->
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="countainer fixed-bottom ">
    <div class="float-right">
        <a href="{{route('products.index')}} " type="button" class="btn btn-primary mt-5 ">CRUD</a>
    </div>
</div>
@endsection
