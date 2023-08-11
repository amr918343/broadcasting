<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('partners');
        // Blog::truncate();
        DB::table('partners')->delete();
        $partner1 = new UploadedFile(public_path('assets/about/dior.png'),'dior.png');
        $partner2 = new UploadedFile(public_path('assets/about/gucci.png'),'gucci.png');
        $partner3 = new UploadedFile(public_path('assets/about/puma.png'),'puma.png');
        $partner4 = new UploadedFile(public_path('assets/about/chanel.png'),'chanel.png');

        Partner::create([
            'image'  => Storage::disk('public')->putFile('images/partners', $partner1),
           'title' => [
               'ar' => 'dior',
               'en' => ' dior ',
           ],

           'created_at'=> now(),
           'updated_at'=> now(),]);
        Partner::create([
            'image'  => Storage::disk('public')->putFile('images/partners', $partner2),
           'title' => [
               'ar' => 'gucci ',
               'en' => 'gucci ',
           ],

           'created_at'=> now(),
           'updated_at'=> now(),]);
        Partner::create([
            'image'  => Storage::disk('public')->putFile('images/partners', $partner3),
           'title' => [
               'ar' => 'puma',
               'en' => 'puma ',
           ],

           'created_at'=> now(),
           'updated_at'=> now(),]);
        Partner::create([
            'image'  => Storage::disk('public')->putFile('images/partners', $partner4),
           'title' => [
               'ar' => 'chanel',
               'en' => ' chanel ',
           ],

           'created_at'=> now(),
           'updated_at'=> now(),]);
    }
}
