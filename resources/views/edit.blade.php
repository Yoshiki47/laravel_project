@extends('layouts.app')
@section('title', '商品編集')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <h2>商品編集フォーム</h2>
            <form method="POST" action="{{ route('update') }}" onSubmit="return checkSubmit()">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <div class="form-group">
                    <label for="product_name">
                        商品名
                    </label>
                    <input id="product_name" name="product_name" class="form-control" value="{{ $product->product_name }}" type="text">
                    @if ($errors->has('product_name'))
                    <div class="text-danger">
                        {{ $errors->first('product_name') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="maker">
                        メーカー
                    </label>
                    <input id="maker" name="maker" class="form-control" value="{{ $product->maker }}" type="text">
                    @if ($errors->has('maker'))
                    <div class="text-danger">
                        {{ $errors->first('maker') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="price">
                        値段
                    </label>
                    <input id="price" name="price" class="form-control" value="{{ $product->price }}" type="text">
                    @if ($errors->has('price'))
                    <div class="text-danger">
                        {{ $errors->first('price') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="stock">
                        在庫数
                    </label>
                    <input id="stock" name="stock" class="form-control" value="{{ $product->stock }}" type="text">
                    @if ($errors->has('stock'))
                    <div class="text-danger">
                        {{ $errors->first('stock') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="comment">
                        コメント
                    </label>
                    <textarea id="comment" name="comment" class="form-control" rows="4">{{ $product->comment }}</textarea>
                    @if ($errors->has('comment'))
                    <div class="text-danger">
                        {{ $errors->first('comment') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="img_path">
                        商品画像
                    </label>
                    <input type="file" id="img_path" name="img_path" class="form-control">{{ $product->img_path }}</input>
                    @if ($errors->has('img_path'))
                    <div class="text-danger">
                        {{ $errors->first('img_path') }}
                    </div>
                    @endif
                </div>
                <div class="mt-5">
                    <a class="btn btn-secondary" href="{{ route('products') }}">
                        戻る
                    </a>
                    <button type="submit" class="btn btn-primary">
                        更新する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function checkSubmit() {
        if (window.confirm('更新してよろしいですか？')) {
            return true;
        } else {
            return false;
        }
    }
</script>
@endsection