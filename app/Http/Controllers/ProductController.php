<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Config\FlashMessage;

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
        $image = $request->file('img_path');
        $img_path = $request->file('img_path');

        if (!empty($img_path)) {
            $file_name = $img_path->getClientOriginalName();
            $image = $img_path->storeAs('', $file_name, 'public');
        }

        $result = [];
        $result['id'] = $request->input('id');
        $result['company_id'] = $request->input('company_id');
        $result['product_name'] = $request->input('product_name');
        $result['price'] = $request->input('price');
        $result['stock'] = $request->input('stock');
        $result['comment'] = $request->input('comment');
        $result['img_path'] = $image;

        return $result;
    }

    /**
     * 商品一覧を表示する
     * 
     * @return view
     */
    public function showProductList()
    {
        try {
            $products = $this->product->productList();
            $company = $this->company->companyData();
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        return view('product', compact('products', 'company'));
    }

    /**
     * 商品詳細画面を表示する
     * 
     * @param $id
     * @return view
     */
    public function showDetail($id)
    {
        try {
            $product = $this->product->productDetail($id);

            if (is_null($product)) {
                \Session::flash('err_msg', FlashMessage::ERROR_MESSAGE);
                return redirect(route('products'));
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        return view('detail', compact('product'));
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

        \Session::flash('err_msg', FlashMessage::PRODUCT_REGISTER_MESSAGE);
        return redirect(route('products'));
    }

    /**
     * 商品編集フォームを表示する
     * @param int $id
     * @return view
     */
    public function showEdit($id)
    {
        try {
            $product = $this->product->productDetail($id);
            $companies = $this->company->companyData();
        
            if (is_null($product)) {
                \Session::flash('err_msg', FlashMessage::ERROR_MESSAGE);
                return redirect(route('products'));
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        return view('edit', compact('product', 'companies'));
    }

    /**
     * 商品を更新する
     * 
     * @param ProductRequest $request
     * @return view
     */
    public function exeUpdate(ProductRequest $request)
    {
        // 商品のデータを受け取る
        $update_data = $this->createData($request);

        \DB::beginTransaction();
        try {
            // 商品を更新する
            $this->product->updateProduct($update_data);            
            \DB::commit();
        } catch (\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }

        \Session::flash('err_msg', FlashMessage::PRODUCT_UPDATE_MESSAGE);
        return redirect(route('products'));
    }

    /**
     * 商品削除
     * 
     * @param $id
     */
    public function exeDelete(Request $request)
    {                
        if (empty($request->product_id)) {
            \Session::flash('err_msg', FlashMessage::ERROR_MESSAGE);
            return redirect(route('products'));
        }

        \DB::beginTransaction();
        try {
            // 商品を削除
            $this->product->deleteProduct($request->product_id);
            \DB::commit();
        } catch(\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }

        \Session::flash('err_msg', FlashMessage::PRODUCT_DELETE_MESSAGE);
        return redirect(route('products'));
    }
}
