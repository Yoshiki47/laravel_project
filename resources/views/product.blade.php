@extends('layouts.app')
@section('title', '商品一覧')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="card">
                <h2 class="card-header">商品一覧</h2>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
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
                            <td>{{ $product->img }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->price }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->maker }}</td>
                            <td><button type="button" class="btn btn-primary" onclick="location.href='/blog/edit/{{ $product->id }}'">編集</button></td>
                            <form method="POST" action="{{ route('delete', $product->id) }}" onSubmit="return checkDelete()">
                                @csrf
                                <td><button type="submit" class="btn btn-primary" onclick=>削除</button></td>
                            </form>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
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