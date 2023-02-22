<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    // 一覧画面のソート
    use Sortable;

    // テーブル名
    protected $table = 'products';

    // 可変項目
    protected $fillable =
    [
        'company_id',
        'product_name',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public $sortable = [
        'id',
        'img_path',
        'product_name',
        'price',
        'stock',
        'company_name',
    ];


    // (company:主, product:従)
    /**
     * 
     * @return void
     */

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    // 1対多（prouct:主, sale:従）
    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }


    /**
     * メーカー名でソートする機能
     * 
     * @return $products
     */

    public function companyNameSortable($query, $direction)
    {
        $products = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'products.id',
                'products.img_path',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'companies.company_name',
            )
            ->orderBy('companies.company_name', $direction);

        return $products;
    }


    /**
     * 商品詳細表示
     * 
     * @param $id
     * @return $product
     */

    public function productDetail($id)
    {
        $product = DB::table('products')
            ->join('companies', 'products.company_id', '=', 'companies.id')
            ->select(
                'products.id',
                'products.img_path',
                'products.product_name',
                'products.price',
                'products.stock',
                'products.comment',
                'products.company_id',
                'companies.company_name',
            )
            ->where('products.id', $id)
            ->first();

        return $product;
    }


    /**
     * 商品登録
     * 
     * @param param
     */
    public function createProduct($param)
    {
        DB::table('products')->insert([
            'company_id' => $param['company_id'],
            'product_name' => $param['product_name'],
            'price' => $param['price'],
            'stock' => $param['stock'],
            'comment' => $param['comment'],
            'img_path' => $param['img_path'],
        ]);
    }


    /**
     * 商品情報を更新
     * 
     * @param $param
     */
    public function updateProduct($param)
    {
        DB::table('products')->where('id', $param['id'])
            ->update([
                'company_id' => $param['company_id'],
                'product_name' => $param['product_name'],
                'price' => $param['price'],
                'stock' => $param['stock'],
                'comment' => $param['comment'],
                'img_path' => $param['img_path'],
            ]);
    }


    /**
     * 商品情報を削除
     *
     * @param $id
     */
    public function deleteProduct($id)
    {
        DB::table('products')->where('id', $id)->delete();
    }


    /**
     * 
     * 
     */
    public function joinAndSelect()
    {
        $sql = DB::table('products')->join('companies', 'products.company_id', '=', 'companies.id')->select(
            'products.id',
            'products.img_path',
            'products.product_name',
            'products.price',
            'products.stock',
            'products.comment',
            'companies.company_name',
        );

        return $sql;
    }


    /**
     * キーワード検索
     * 
     */
    public function searchKeyword($keyword, $company_name, $min_price, $max_price, $min_stock, $max_stock)
    {
        $query = $this->joinAndSelect();
        // キーワード検索
        if (!empty($keyword)) {
            $query->where('product_name', 'like', '%' . $keyword . '%')->orderBy('id', 'asc');
        }

        // メーカー検索
        if (!empty($company_name)) {
            $query->where('company_id', $company_name);
        }

        // 価格範囲検索
        if (!empty($min_price) || !empty($max_price) || !empty($min_price) && !empty($max_price)) {
            $query->whereBetween('price', [$min_price, $max_price])->orderBy('price', 'asc');
        }

        // 在庫数範囲検索
        if (!empty($min_stock) || !empty($max_stock) || !empty($min_stock) && !empty($max_stock)) {
            $query->whereBetween('stock', [$min_stock, $max_stock])->orderBy('stock', 'asc');
        }

        // キーワードとメーカー両方指定した場合
        if (!empty($keyword) && !empty($company_name)) {
            $query->where('product_name', 'like', '%' . $keyword . '%')->where('company_id', $company_name);
        }

        $searchResult = $query->paginate(5);

        return $searchResult;
    }
}
