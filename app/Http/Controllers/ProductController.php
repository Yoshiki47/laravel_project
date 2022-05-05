<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * 商品一覧を表示する
     * 
     * @return view
     */
    public function showProductList()
    {
        $products = Product::all();

        return view('product', ['products' => $products]);
    }

    /**
     * 商品詳細を表示する
     * @param int $id
     * @return view
     */
    public function showDetail($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            \Session::flash('err_msg', 'データがありません');
            return redirect(route('products'));
        }

        return view('detail', ['product' => $product]);
    }

    /**
     * 商品登録画面を表示する
     * 
     * @return view
     */
    public function showCreate()
    {
        return view('form');
    }

    /**
     * 商品登録する
     * 
     * @return view
     */
    public function exeStore(ProductRequest $request)
    {
        // 商品のデータを受け取る
        $inputs = $request->all();

        \DB::beginTransaction();
        try {
            // 商品を登録        
            Product::create($inputs);
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            abort(500);
        }

        \Session::flash('err_msg', '商品を登録しました');
        return redirect(route('products'));
    }

    /**
     * 商品編集フォームを表示する
     * @param int $id
     * @return view
     */
    public function showEdit($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            \Session::flash('err_msg', 'データがありません');
            return redirect(route('products'));
        }

        return view('edit', ['product' => $product]);
    }

    /**
     * 商品を更新する
     * 
     * @return view
     */
    public function exeUpdate(ProductRequest $request)
    {
        // 商品のデータを受け取る
        $inputs = $request->all();

        \DB::beginTransaction();
        try {
            // 商品を更新する
            $product = Product::find($inputs['id']);
            $product->fill([
                'product_name' => $inputs['product_name'],
                'maker' => $inputs['maker'],
                'price' => $inputs['price'],
                'stock' => $inputs['stock'],
                'comment' => $inputs['comment'],
            ]);
            $product->save();
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            abort(500);
        }

        \Session::flash('err_msg', '商品を更新しました');
        return redirect(route('products'));
    }

    /**
     * 商品削除
     * @param int $id
     * @return view
     */
    public function exeDelete($id)
    {        
        if (empty($id)) {
            \Session::flash('err_msg', 'データがありません');
            return redirect(route('products'));
        }

        try {
            // 商品を削除
            Product::destroy($id);
        } catch(\Throwable $e) {
            abort(500);
        }

        \Session::flash('err_msg', '削除しました。');
        return redirect(route('products'));
    }
}
