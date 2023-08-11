<?php

namespace App\Http\Controllers\Api\Legal;

use App\Enums\Api\ResponseMethodEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Legal\PolicyResource;
use App\Http\Resources\Api\Legal\TermConditionResource;
use App\Models\Policy;
use App\Models\TermCondition;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function getPolicies() {
        $policies = Policy::take(30)->get();
        $data = [
            'policies_header_title' => nova_get_setting('policies_header_title_' . app()->getLocale()),
        ];
        return generalApiResponse(method: ResponseMethodEnum::CUSTOM_COLLECTION, resource: PolicyResource::class, data_passed: $policies, additional_data: $data);
    }
}
