<?php

namespace App\Http\Controllers\Api\Contacts;

use Laravel\Nova\Notifications\NovaNotification;
use App\Enums\Api\ResponseMethodEnum;
use App\Models\Contact;
use Laravel\Nova\URL;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(){

        $form_info = [
            'contact_title' => nova_get_setting('contact_title_' . app()->getLocale()),
            'contact_description' => nova_get_setting('contact_description_' . app()->getLocale()),
            'contact_main_image'=> nova_get_setting('contact_main_image') ? url('storage/'. nova_get_setting('contact_main_image')) : null,

        ];
        $locate_us=[
            'locate_title' => nova_get_setting('locate_title_' . app()->getLocale()),
            'locate_description' => nova_get_setting('locate_description_' . app()->getLocale()),
            'locate_us_phone' => nova_get_setting('locate_us_phone'),
            'locate_us_email' => nova_get_setting('locate_us_email'),
            'locate_location' => nova_get_setting('locate_location'),
            'locate_us_address' => nova_get_setting('locate_us_address_' . app()->getLocale()),


        ];
        $social_media=[
            'social_twitter' => nova_get_setting('social_twitter'),
            'social_instagram' => nova_get_setting('social_instagram'),
            'social_facebook' => nova_get_setting('social_facebook'),
            'social_youtube' => nova_get_setting('social_youtube'),

        ];

        $footer = [
            ' Main_Description' => nova_get_setting('Footer_main_description_'.app()->getLocale()),
            'copyright' => nova_get_setting('footer_copyright_'.app()->getLocale()),
        ];

        $data = [
            'form_info' => $form_info,
            'locate_us' => $locate_us,
            'social_media' => $social_media,
            'footer' => $footer
        ];
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM, additional_data: $data);
    }
    public function store(ContactRequest $request)
    {
        try {
            DB::beginTransaction();
            $contact = Contact::create($request->validated());
            $admins = Admin::get();
            // Send notification to all admins
            foreach($admins as $admin) {
                $admin->notify( NovaNotification::make()
                ->message(__('New contact message'))
                ->action(__('view'),  URL::remote((url(config('nova.path') . '/resources/contacts') . '/' . $contact->id)))
                ->icon('view')
                ->type('info'));
            }
            DB::commit();
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Thank your for contacting us'), custom_status: 201);
        } catch(\Exception $e) {
            DB::rollBack();
            info($e);
            return generalApiResponse(method: ResponseMethodEnum::CUSTOM, custom_message: __('Server error'), custom_status: 500);
        }
    }
}
