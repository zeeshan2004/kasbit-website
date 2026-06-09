<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('footer_settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->text('address_3')->nullable();
            $table->json('useful_links')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->json('gallery_images')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->string('map_title')->nullable();
            $table->string('copyright_text')->nullable();
            $table->string('background_color')->default('#2756a5');
            $table->string('bottom_bar_color')->default('#064f80');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('footer_settings')->insert([
            'address_1' => '84-B, S.M.C.H.S, Off Shahrah-e-Faisal, Karachi-74400, Pakistan. SMCHS',
            'address_2' => 'D-15, Block D, Hyderi, North Nazimabad, Karachi, Pakistan. Hyderi',
            'address_3' => 'B-257, Block 5, Scheme No. 24, Gulshan-e-Iqbal, Karachi, Pakistan. Gulshan',
            'useful_links' => json_encode([
                ['label' => 'About', 'url' => '#'],
                ['label' => 'Admission Policy', 'url' => '#'],
                ['label' => 'Scholarship', 'url' => '#'],
                ['label' => 'Facilities & Services', 'url' => '#'],
            ]),
            'gallery_images' => json_encode([null, null, null, null, null]),
            'map_embed_url' => 'https://www.google.com/maps?q=KASBIT%20Karachi&output=embed',
            'map_title' => 'Location Map',
            'copyright_text' => '© ' . date('Y') . ' KASB Institute of Technology (PVT) Ltd. All Rights Reserved',
            'background_color' => '#2756a5',
            'bottom_bar_color' => '#064f80',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_settings');
    }
};
