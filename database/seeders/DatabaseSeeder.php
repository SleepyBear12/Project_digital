<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@pos.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@pos.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $products = [
            ['name' => 'Beras Merah 5kg', 'barcode' => '899100111001', 'price' => 65000, 'stock' => 50, 'unit' => 'karung'],
            ['name' => 'Gula Pasir 1kg', 'barcode' => '899100111002', 'price' => 15000, 'stock' => 100, 'unit' => 'pack'],
            ['name' => 'Minyak Goreng 1L', 'barcode' => '899100111003', 'price' => 18000, 'stock' => 80, 'unit' => 'botol'],
            ['name' => 'Telur Ayam 1kg', 'barcode' => '899100111004', 'price' => 28000, 'stock' => 60, 'unit' => 'pack'],
            ['name' => 'Sabun Mandi', 'barcode' => '899100111005', 'price' => 5000, 'stock' => 200, 'unit' => 'pcs'],
            ['name' => 'Odol Pepsodent', 'barcode' => '899100111006', 'price' => 8500, 'stock' => 150, 'unit' => 'pcs'],
            ['name' => 'Shampoo Clear', 'barcode' => '899100111007', 'price' => 12500, 'stock' => 120, 'unit' => 'pcs'],
            ['name' => 'Susu UHT 1L', 'barcode' => '899100111008', 'price' => 16500, 'stock' => 70, 'unit' => 'kotak'],
            ['name' => 'Kopi Kapal Api', 'barcode' => '899100111009', 'price' => 12000, 'stock' => 90, 'unit' => 'pack'],
            ['name' => 'Tepung Terigu 1kg', 'barcode' => '899100111010', 'price' => 11000, 'stock' => 100, 'unit' => 'pack'],
            ['name' => 'Mie Instan Indomie', 'barcode' => '899100111011', 'price' => 3500, 'stock' => 500, 'unit' => 'pcs'],
            ['name' => 'Kecap Manis', 'barcode' => '899100111012', 'price' => 14000, 'stock' => 75, 'unit' => 'botol'],
            ['name' => 'Saos Sambal', 'barcode' => '899100111013', 'price' => 13000, 'stock' => 75, 'unit' => 'botol'],
            ['name' => 'Sarden Kaleng', 'barcode' => '899100111014', 'price' => 18000, 'stock' => 50, 'unit' => 'kaleng'],
            ['name' => 'Deterjen Rinso', 'barcode' => '899100111015', 'price' => 22000, 'stock' => 60, 'unit' => 'pack'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}

