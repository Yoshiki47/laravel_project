@extends('layouts.app')
@section('title', '商品詳細')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 d-flex flex-column col-md-offset-2">
            <span>商品番号:{{ $product->id }}</span>
            <span>商品画像:<img src="{{ asset('/storage/' .$product->img_path) }}" width="150" height="150"></span>
            <span>商品名:{{ $product->name }}</span>
            <span>値段:{{ $product->price }}</span>
            <span>在庫:{{ $product->stock }}</span>
            <span>メーカー:{{ $company->company_name }}</span>
        </div>
    </div>
</div>

@endsection