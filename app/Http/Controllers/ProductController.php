<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;

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
        $this->product = new Product();
        $this->company = new Company();
    }

    /**
     * データベースに渡すデータの生成
     *
     * @param ProductRequest $request
     * @return $result
     */
    public function createData(ProductRequest $request)
    {
        $img_path = $request->file('img_path');

        if (!empty($img_path)) {
            $image = $img_path->getPathname();
            $img_path->storeAs('', $image, 'public');
        }

        $result = [];
        $result['id'] = $request->input('id');
        $result['company_id'] = $request->input('company_id');
        $result['product_name'] = $request->input('product_name');
        $result['price'] = $request->input('price');
        $result['stock'] = $request->input('stock');
        $result['comment'] = $request->input('comment');
        $result['img_path'] = $img_path;

        return $result;
    }

    /**
     * 商品一覧を表示する
     * 
     * @return view
     */
    public function showProductList()
    {
        $products = $this->product->productList();
        $company = $this->company->companyData();

        return view('product', compact('products', 'company'));
    }

    /**
     * 商品詳細を表示する
     * @param int $id
     * @return view
     */
    public function showDetail($id)
    {
        $product = Product::find($id);
        $company = $product->company;

        if (is_null($product)) {
            \Session::flash('err_msg', 'データがありません');
            return redirect(route('products'));
        }

        return view('detail', compact('product', 'company'));
    }

    /**
     * 商品登録画面を表示する
     * 
     * @return view
     */
    public function showCreate()
    {
        try {
            $companies = $this->company->companyData();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        return view('form', compact('companies'));
    }

    /**
     * 商品登録する
     * 
     * @param ProductRequest $request
     * @return view
     */
    public function exeStore(ProductRequest $request)
    {
        // 商品のデータを受け取る
        $insert_data = $this->createData($request);

        \DB::beginTransaction();
        try {
            // 商品を登録
            $this->product->createProduct($insert_data);            
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
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
        $companies = Company::all();

        if (is_null($product)) {
            \Session::flash('err_msg', 'データがありません');
            return redirect(route('products'));
        }

        return view('edit', compact('product', 'companies'));
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
                'company_id' => $inputs['company_id'],
                'price' => $inputs['price'],
                'stock' => $inputs['stock'],
                'comment' => $inputs['comment'],
                'img_path' => $inputs['img_path'],
            ]);

            $product->save();
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
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
            $this->product->deleteProduct($id);
        } catch(\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        \Session::flash('err_msg', '削除しました。');
        return redirect(route('products'));
    }
}
