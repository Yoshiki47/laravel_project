@extends('layouts.app')
@section('title', '商品一覧')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="card">
                <h2 class="card-header">商品一覧</h2>

                <div class="card-body">
                    @if (session('err_msg'))
                    <p class="text-danger">
                        {{ session('err_msg') }}
                    </p>
                    @endif

                    <table class="table table-striped">
                        <tr>
                            <th>商品番号</th>
                            <th>商品画像</th>
                            <th>商品名</th>
                            <th>値段</th>
                            <th>在庫</th>
                            <th>メーカー名</th>
                            <th></th>
                            <th></th>
                        </tr>
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @if ($product->img_path === null)
                                <img src="{{ asset('/storage/noimage.png') }}" alt="noimage" width="150" height="150">
                                @else
                                <img src="{{ asset('/storage/' .$product->img_path) }}" width="150" height="150">
                                @endif
                            </td>
                            <td><a href="/product/{{ $product->id }}">{{ $product->product_name }}</a></td>
                            <td>{{ $product->price }}円</td>
                            <td>{{ $product->stock }}個</td>
                            <td>{{ $product->company_name }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" onclick="location.href='/product/edit/{{ $product->id }}'">編集</button>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('delete') }}" onsubmit="return checkSubmit('削除してよろしいですか？')">
                                    @csrf
                                    <input type="hidden" value="{{ $product->id }}" name="product_id">
                                    <button type="submit" class="btn btn-primary" onclick="">削除</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ページネーションリンク -->
    {{ $products->links('vendor.pagination.bootstrap-4') }}
</div>
@endsection