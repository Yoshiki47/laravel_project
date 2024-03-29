@extends('layouts.app')
@section('title', '商品一覧')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="card">
                <h2 class="card-header">商品一覧</h2>
                <!-- 検索フォーム -->
                <div class="form-gruop mt-3 my-2 lg-0 ml-4">
                    <form method="GET" action="{{ route('products') }}" id="searchForm" class="form-inline">
                        <!-- キーワード検索 -->
                        <div class="search-form">
                            <input type="text" class="form-control mr-sm-2" id="keyword" name="keyword" value="{{ $data['keyword'] }}" placeholder="キーワードを入力してください">
                        </div>
                        <!-- セレクトボックス検索 -->
                        <div class="search-dropDpwn">
                            <select id="select_company" name="company_id">
                                <option value="" selected>メーカーを選択してください</option>
                                @foreach ($data['companies'] as $company)
                                @if (request('coompany_id') == $company->id)
                                <option id="company_id" name="company_id" value="{{ $company->id }}">
                                    {{ $company->company_name}}
                                </option>
                                @else
                                <option id="company_id" name="company_id" value="{{ $company->id }}">
                                    {{ $company->company_name}}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <!-- 価格検索 -->
                        <div class="search_price">
                            価格:
                            <input type="number" name="min_price" id="min_price" class="form-control" value="{{ $data['min_price'] }}">円
                            ~
                            <input type="number" name="max_price" id="max_price" class="form-control" value="{{ $data['max_price'] }}">円
                        </div>
                        <!-- 在庫数検索 -->
                        <div class="search_stock">
                            在庫数:
                            <input type="number" name="min_stock" id="min_stock" class="form-control" value="{{ $data['min_stock'] }}">個
                            ~
                            <input type="number" name="max_stock" id="max_stock" class="form-control" value="{{ $data['max_stock'] }}">個
                        </div>
                        <!-- 検索ボタン -->
                        <div class="ml-2">
                            <button class="btn btn-primary search-btn" type="button">商品を探す</button>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if (session('err_msg'))
                    <p class="text-danger">
                        {{ session('err_msg') }}
                    </p>
                    @endif

                    <table class="table table-striped">
                        <tr>
                            <th>@sortablelink('id', '商品番号')</th>
                            <th>@sortablelink('img_path', '商品画像')</th>
                            <th>@sortablelink('product_name', '商品名')</th>
                            <th>@sortablelink('price', '値段')</th>
                            <th>@sortablelink('stock', '在庫')</th>
                            <th>@sortablelink('companyName', 'メーカー名')</th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tbody id="ajax_table">
                            @foreach ($data['products'] as $product)
                            <tr class="product-table-tr">
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
                                    <form>
                                        @csrf
                                        <input type="hidden" value="{{ $product->id }}" name="_method">
                                        <button type="submit" class="btn btn-primary del-btn" name="product_id" data-product-id="{{ $product->id }}" onclick="">削除</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- ページネーションリンク(ページ移動しても検索条件保持) -->
    {{ $data['products']->appends(request()->input())->links('vendor.pagination.bootstrap-4') }}
</div>
@endsection