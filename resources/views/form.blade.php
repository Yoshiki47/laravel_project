@extends('layouts.app')
@section('title', '商品登録')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <h2>商品登録フォーム</h2>
            <form method="POST" action="{{ route('store') }}" onSubmit="return checkSubmit()">
                @csrf
                <div class="form-group">
                    <label for="product_name">
                        商品名
                    </label>
                    <input id="product_name" name="product_name" class="form-control" value="{{ old('product_name') }}" type="text">
                    @if ($errors->has('product_name'))
                    <div class="text-danger">
                        {{ $errors->first('product_name') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="company_id">
                        メーカー
                    </label>
                    <select class="form-select" name="company_id">
                        <option selected="selected" value="" style="display: none;">メーカーを選択してください</option>
                        @foreach ($companies as $company)
                        <option id="company_id" value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('company_id'))
                    <div class="text-danger">
                        {{ $errors->first('company_id') }}
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label for="price">
                        値段
                    </label>
                    <input id="price" name="price" class="form-control" value="{{ old('price') }}" type="text">
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
                    <input id="stock" name="stock" class="form-control" value="{{ old('stock') }}" type="text">
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
                    <textarea id="comment" name="comment" class="form-control" rows="4">{{ old('comment') }}</textarea>
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
                    <input type="file" id="img_path" name="img_path" class="form-control">{{ old('img_path') }}</input>
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
                        登録する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection