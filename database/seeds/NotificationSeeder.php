<?php

use App\NotificationMod;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        NotificationMod::create([
            "id" => 1,
            "title" => "INFO PENTING",
            "images" => "https://la-att.intek.co.id/images/clock.png",
            "contents"=> "Di usahakan kalian hadir pukul 08:30 untuk menghindari telat ,toleransi waktu 30 menit yaitu sampai 09:00, terimakasih by ~HRD"
        ]);
        NotificationMod::create([
            "id" => 2,
            "title" => "~ QUOTE ~ ",
            "images"=> "https://la-att.intek.co.id/images/quote.jpg",
            "contents" => "Meditation brings wisdom; lack of mediation leaves ignorance. Know well what leads you forward and what hold you back, and choose the path that leads to wisdom. QuoteBy: Buddha"
        ]);
    }
}
