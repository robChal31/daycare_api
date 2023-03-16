<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;


use \App\Models\User;
use \App\Models\Children;
use \App\Models\Merchant;
use \App\Models\Employe;
use \App\Models\Position;
use \App\Models\ServiceHeader;
use \App\Models\Service;
use \App\Models\Bookmark;
use \App\Models\Rating;
use \App\Models\Order;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory(3)->has(Children::factory(2))->create();
        $position = Position::factory(6)->create();
        $merchant = Merchant::factory(2)->hasEmployes(6, function (array $attributes, Merchant $merchant) use($position) {
            return ['merchant_id' => $merchant->id, 'position_id' => $position[rand(0,5)]->id];
        })->create();
        $service_headers = ServiceHeader::factory(4)->hasServices(6, function (array $attributes,  ServiceHeader $service_header) use($merchant) {
            return ['service_header_id' => $service_header->id, 'merchant_id' => $merchant[rand(0,1)]->id];
        })->create();

        Bookmark::factory(3)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'service_id' => $service_headers[rand(0,3)]->services[rand(0,5)]->id,
                    'user_id' => $user[rand(0,2)]->id
                ],
            ))->create();

        $orders = Order::factory(20)
            ->state(new Sequence(
                fn (Sequence $sequence) => [
                    'service_id' => $service_headers[rand(0,3)]->services[rand(0,5)]->id,
                    'user_id' => $user[rand(0,2)]->id,
                    'children_id' => $user[rand(0,2)]->childrens[rand(0,1)]->id,
                ],
            ))->create();

        Rating::factory(3)
        ->state(new Sequence(
            fn (Sequence $sequence) => [
                'order_id' => $orders[rand(0,19)]->id,
                'user_id' => $user[rand(0,2)]->id
            ],
        ))->create();
    }
}
