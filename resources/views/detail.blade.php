@extends('layouts.app')
@section('title', '商品詳細')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <span>商品番号:{{ $product->id }}</span>
            <span>商品画像{{ $product->img }}</span>
            <span>商品名:{{ $product->name }}</span>
            <span>値段:{{ $product->price }}</span>
            <span>在庫:{{ $product->stock }}</span>
            <span>メーカー:{{ $product->maker }}</span>
        </div>
    </div>
</div>

<script>
    function checkDelete() {
        if (window.confirm('削除してよろしいですか？')) {
            return true;
        } else {
            return false;
        }
    }
</script>
@endsection