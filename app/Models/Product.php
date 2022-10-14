<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
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
     * 商品一覧表示
     * 
     * @return $products
     */

    public function productList()
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
            ->orderBy('products.id', 'asc')
            ->paginate(10);

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
    public function updateProduct($param) {
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
}
