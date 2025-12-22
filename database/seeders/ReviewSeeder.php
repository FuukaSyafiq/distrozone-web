<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Kaos;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // dd(User::getUserByUsername("aril")->id_user);
        $datas = [
            [
                "review" => "Tempat yang sangat nyaman dan bersih. Sangat direkomendasikan!",
                "star" => 5,
                "id_customer" => User::getUserByUsername("aril")->id_user,
                "id_kaos" => Kaos::getKaosByName('Kaos hitam polos lengan pendek Cotton')->id_kaos,
            ],
        ];

        foreach ($datas as $data) {
            Review::create($data);
        }
    }

    public static function down()
    {
        Review::query()->delete();
    }
}
