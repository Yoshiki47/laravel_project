<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Kyslik\ColumnSortable\Sortable;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;
use Config\FlashMessage;

class ProductController extends Controller
{
    use Sortable;

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
     * @param Request $request
     * @return view
     */
    public function showProductList(Request $request)
    {
        // キーワード検索
        // 検索フォームで入力された値を取得
        $keyword = $request->input('keyword');
        $selected_name = $request->input('company_id');

        // 価格範囲検索
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');        

        // 在庫数範囲検索
        $min_stock = $request->input('min_stock');
        $max_stock = $request->input('max_stock');
        
        try {
            $products = Product::sortable()->paginate(10);
            $companies = $this->company->companyData();

            // キーワード、メーカー、価格範囲、在庫数範囲検索
            if (!empty($keyword) || !empty($selected_name) || !empty($min_price) || !empty($max_price) || !empty($min_stock) || !empty($max_stock) || !empty($keyword) && !empty($selected_name) && !empty($min_price) && !empty($max_price) && !empty($min_stock) && !empty($max_stock) || !empty($min_price) && !empty($max_price) || !empty($min_stock) && !empty($max_stock)) {
                $products = $this->product->searchKeyword($keyword, $selected_name, $min_price, $max_price, $min_stock, $max_stock);                
                return response()->json($products);
            }
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage());
        }

        $data = [
            'products' => $products,
            'companies' => $companies,
            'keyword' => $keyword,
            'selected_name' => $selected_name,
            'min_price' => $min_price,
            'max_price' => $max_price,
            'min_stock' => $min_stock,
            'max_stock' => $max_stock,
        ];

        return view('product', compact('data'));
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
     * @param Request $request $id
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
            \Session::flash('err_msg', FlashMessage::PRODUCT_DELETE_MESSAGE);
            return response()->json(null, 204);
        } catch(\Throwable $e) {
            \DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }
}
