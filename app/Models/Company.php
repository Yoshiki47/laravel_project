<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    // テーブル名
    protected $table = 'companies';

    // 可変項目
    protected $fillable =
    [        
        'company_name',
        'street_address',
        'representative_name',
    ];

    // 1対多（company:主, product:従)
    /**
     * 
     * @return void
     */
    public function products() {
        return $this->hasMany('App\Models\Product');
    }

    // companyデータ取得
    /**
     * 
     * @return $company
     */
    public function companyData() {
        $company = DB::table('companies')
            ->select(
                'id',
                'company_name',
            )
            ->orderBy('id', 'asc')
            ->get();

        return $company;
    }
}
