<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Outl1ne\NovaSettings\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('nova_settings');

        Settings::truncate();
        $about_main =new UploadedFile(public_path('assets/about/about_main.png'), 'about_main.png');
        $benefit_main =new UploadedFile(public_path('assets/about/benefit_main.png'), 'benefit_main.png');
        $style_life =new UploadedFile(public_path('assets/about/style-life.png'), 'style-life.png');
        $contact_main_image =new UploadedFile(public_path('assets/about/contact_main_image.png'), 'contact_main_image.png');

        //  nova_set_setting_value('main_image_file',Storage::disk('public')->putFile('images/nova_settings',$about_main));
        // Done : about
        nova_set_setting_value('about_title_en','wear the favorite outfit without worries');
        nova_set_setting_value('about_title_ar','ارتداء الزي المفضل دون قلق');
        nova_set_setting_value('about_description_ar','نحن نتفهم أهمية الشعور بالثقة والراحة في الزي المفضل لديك. نعتقد أن كل شخص يستحق التعبير عن أسلوبه الشخصي دون أي قلق أو قلق. لهذا السبب نسعى جاهدين لتوفير تجربة تسوق فريدة تلبي احتياجات الأفراد الذين يرغبون في ارتداء ملابسهم المفضلة براحة بال تامة.');
        nova_set_setting_value('about_description_en','we understand the importance of feeling confident and comfortable in your favorite outfit. We believe that everyone deserves to express their personal style without any worries or concerns. Thats why we strive to provide a unique shopping experience that caters to individuals who want to wear their favorite outfits with complete peace of mind.');
        nova_set_setting_value('about_main_image',Storage::disk('public')->putFile('images/nova_settings',$about_main));

        //
        nova_set_setting_value('main_image',Storage::disk('public')->putFile('images/nova_settings',$benefit_main));
        nova_set_setting_value('benefit_title_en','we’re unique no matter how you put it.');
        nova_set_setting_value('benefit_title_ar','نحن متميزون بغض النظر عن كيفية وضعها.');
        nova_set_setting_value('benefit_description_ar','مجموعتنا منسقة بعناية لنقدم لك مجموعة متنوعة من السلع الاستثنائية. يروي كل عنصر قصة ويحمل في طياته لمسة إبداعية تميزه عن المعتاد.');
        nova_set_setting_value('benefit_description_en','our collection is carefully curated to offer you a diverse selection of exceptional goods. Each item tells a story and carries with it a touch of creativity that sets it apart from the ordinary.');

        nova_set_setting_value('partner_title_en','brands we partnered with');
        nova_set_setting_value('partner_title_ar','العلامات التجارية التي عقدنا شراكة معها');
        nova_set_setting_value('partner_description_en','We believe in supporting brands that share our values and contribute to a better future. Through these partnerships, we aim to offer our customers products that not only meet their needs but also align with their values and aspirations.');
        nova_set_setting_value('partner_description_ar','نحن نؤمن بدعم العلامات التجارية التي تشاركنا قيمنا وتساهم في مستقبل أفضل. من خلال هذه الشراكات ، نهدف إلى تقديم منتجات لعملائنا لا تلبي احتياجاتهم فحسب ، بل تتوافق أيضًا مع قيمهم وتطلعاتهم.');

        // Done : banner
        nova_set_setting_value('banner_Subtitle_en','brands we partnered with');
        nova_set_setting_value('banner_Subtitle_ar','العلامات التجارية التي عقدنا شراكة معها');
        nova_set_setting_value('banner_description_ar','العلامات التجارية التي عقدنا شراكة معها');
        nova_set_setting_value('banner_description_en','We believe in supporting brands that share our values and contribute to a better future. Through these partnerships, we aim to offer our customers products that not only meet their needs but also align with their values and aspirations.');
        nova_set_setting_value('banner_title_ar','العلامات التجارية التي عقدنا شراكة معها');
        nova_set_setting_value('banner_title_en','We believe in supporting brands that share our values and contribute to a better future. Through these partnerships, we aim to offer our customers products that not only meet their needs but also align with their values and aspirations.');
        nova_set_setting_value('banner_photo',Storage::disk('public')->putFile('images/nova_settings',$about_main));
        // Done : site

        // nova_set_setting_value('site_logo',Storage::disk('public')->putFile('images/nova_settings',$about_main));
        // nova_set_setting_value('site_icon',Storage::disk('public')->putFile('images/nova_settings',$about_main));

        nova_set_setting_value('site_name_ar','سيلكتفز');
        nova_set_setting_value('site_name_en','selective store');
        nova_set_setting_value('site_description_ar','نحن نتفهم أهمية الشعور بالثقة والراحة في الزي المفضل لديك.');
        nova_set_setting_value('site_description_en','We believe in supporting brands that share our values and contribute to a better future. Through these partnerships, we aim to offer our customers products that not only meet their needs but also align with their values and aspirations.');
        nova_set_setting_value('site_phone','+966 123456789');
        nova_set_setting_value('site_analytics','########');


        // Done : style life
        nova_set_setting_value('style_title_ar','اختار الزي المفضل');
        nova_set_setting_value('style_title_en','choose your favorite style');
        nova_set_setting_value('style_description_ar','نحن نتفهم أهمية الشعور بالثقة والراحة في الزي المفضل لديك.');
        nova_set_setting_value('style_description_en','We believe in supporting brands that share our values and contribute to a better future. Through these partnerships, we aim to offer our customers products that not only meet their needs but also align with their values and aspirations.');
        // nova_set_setting_value('style_photo',Storage::disk('public')->putFile('images/nova_settings',$style_life));
        nova_set_setting_value('style_photo',Storage::disk('public')->putFile('images/nova_settings',$style_life));


        // Done : social media
        nova_set_setting_value('social_twitter','https://twitter.com');
        nova_set_setting_value('social_youtube','https://youtube.com');
        nova_set_setting_value('social_facebook','https://www.facebook.com');
        nova_set_setting_value('social_instagram','https://www.instagram.com');

        // Done : locate us
        nova_set_setting_value('locate_title_ar','مصر');
        nova_set_setting_value('locate_title_en','Egypt');
        nova_set_setting_value('locate_description_ar','جمهوريه مصر العربيه');
        nova_set_setting_value('locate_description_en','Egypt');
        nova_set_setting_value('locate_us_phone','+966 123456789');
        nova_set_setting_value('locate_us_email','exampl@example.com');
        nova_set_setting_value('locate_location','Egypt');
        nova_set_setting_value('locate_us_address_ar','Egypt');
        nova_set_setting_value('locate_us_address_en','Egypt');

        // Done : contact us
        nova_set_setting_value('contact_title_ar','تواصل معانا ');
        nova_set_setting_value('contact_title_en','contact us');
        nova_set_setting_value('contact_description_ar','تواصل معانا ');
        nova_set_setting_value('contact_description_en','contact us');
        nova_set_setting_value('contact_main_image',Storage::disk('public')->putFile('images/nova_settings',$contact_main_image));


        // Done : footer
        nova_set_setting_value('Footer_main_description_ar','الألم في حد ذاته مهم جدا ، سيتبعه المطور ، لكن هذا هو الوقت الذي يكون فيه الألم نفسه مهمًا ، فسيتم اتباعه');
        nova_set_setting_value('Footer_main_description_en','Lorem ipsum dolor sit amet, consectetur ng elit, sed do eius tempor inLorem ipsum dolor sit amet, consectetur ');
        nova_set_setting_value('footer_copyright_ar','حقوق الطبع والنشر © متجر انتقائي 2023');
        nova_set_setting_value('footer_copyright_en','Copyright © 2023 selective store');

        // Done : Partners
        nova_set_setting_value('partner_title_ar','العلامات التجارية التي عقدنا شراكة معها ');
        nova_set_setting_value('partner_title_en','brands we partnered with');
        nova_set_setting_value('partner_description_ar','لقد عقدنا شراكه مع بعض العلامات التجارية ');
        nova_set_setting_value('partner_description_en','We have partnered with some brands');


    }
}
