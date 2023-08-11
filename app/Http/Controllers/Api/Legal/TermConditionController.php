<?php

namespace App\Http\Controllers\Api\Legal;

use App\Enums\Api\ResponseMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Legal\TermConditionResource;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class TermConditionController extends Controller
{
    public function getTermsConditions() {
        $terms_conditions = TermCondition::take(30)->get();
        $data = [
            'terms_conditions_header_title' => nova_get_setting('terms_conditions_header_title_' . app()->getLocale()),
        ];
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM_COLLECTION, resource: TermConditionResource::class, data_passed: $terms_conditions, additional_data: $data);
    }
}
