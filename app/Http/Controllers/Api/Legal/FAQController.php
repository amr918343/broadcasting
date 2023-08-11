<?php

namespace App\Http\Controllers\Api\Legal;

use App\Enums\Api\ResponseMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Legal\FAQResource;
use App\Models\FrequentAskedQuestion;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function getFAQ() {
        $FAQ = FrequentAskedQuestion::take(30)->get();
        $additional_data = [
            'faq_title' => nova_get_setting('faq_page_title_' . app()->getLocale()),
            'faq_description' => nova_get_setting('faq_page_description_' . app()->getLocale())
        ];
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM_COLLECTION, resource: FAQResource::class, data_passed: $FAQ, additional_data: $additional_data );
    }
}
