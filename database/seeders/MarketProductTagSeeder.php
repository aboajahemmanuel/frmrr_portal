<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MarketProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            [
                'name' => 'Fixed Income',
                'slug' => 'fixed-income',
                'description' => 'Products related to fixed income securities and bonds',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Equities',
                'slug' => 'equities',
                'description' => 'Equity-based market products and securities',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Derivatives',
                'slug' => 'derivatives',
                'description' => 'Derivative instruments and contracts',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Foreign Exchange',
                'slug' => 'foreign-exchange',
                'description' => 'Foreign exchange and currency products',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Money Market',
                'slug' => 'money-market',
                'description' => 'Short-term debt instruments and money market products',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Commodities',
                'slug' => 'commodities',
                'description' => 'Commodity-based market products',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Structured Products',
                'slug' => 'structured-products',
                'description' => 'Complex structured financial products',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Treasury Bills',
                'slug' => 'treasury-bills',
                'description' => 'Government treasury bills and short-term securities',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Commercial Paper',
                'slug' => 'commercial-paper',
                'description' => 'Short-term unsecured promissory notes',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
            [
                'name' => 'Corporate Bonds',
                'slug' => 'corporate-bonds',
                'description' => 'Corporate debt securities and bonds',
                'group_id' => 1,
                'status' => 1,
                'admin_status' => 1,
            ],
        ];

        foreach ($tags as $tag) {
            $tag['created_at'] = now();
            $tag['updated_at'] = now();
            DB::table('market_product_tags')->insert($tag);
        }
    }
}
