<?php

use Illuminate\Support\Facades\Storage;
use App\Enums\Api\ResponseMethodEnum;
use App\Models\OrderItemMedia;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\File as File;
use Illuminate\Support\Str;

function uploadImg($files, $url = 'images', $key = 'image', $width = null, $height = null)
{
    $dist = storage_path('app/public/' . $url . "/");
    if ($url != 'images' && !File::isDirectory(storage_path('app/public/images/' . $url . "/"))) {
        File::makeDirectory(storage_path('app/public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $url . DIRECTORY_SEPARATOR), 0777, true);
        $dist = storage_path('app/public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $url . DIRECTORY_SEPARATOR);
    } elseif (File::isDirectory(storage_path('app/public/images/' . $url . "/"))) {
        $dist = storage_path('app/public/images/' . $url . "/");
    }
    $image = "";
    if (!is_array($files)) {
        $dim = getimagesize($files);
        $width = $width ?? $dim[0];
        $height = $height ?? $dim[1];
    }

    if (gettype($files) == 'array') {
        $image = [];
        foreach ($files as $img) {
            $dim = getimagesize($img);
            $width = $width ?? $dim[0];
            $height = $height ?? $dim[1];

            if ($img && $dim['mime'] != "image/gif") {
                Image::make($img)->resize($width, $height, function ($cons) {
                    $cons->aspectRatio();
                })->save($dist . $img->hashName());
                $image[][$key] = $img->hashName();
            } elseif ($img && $dim['mime'] == "image/gif") {
                $image = uploadGIFImg($img, $dist);
            }
        }
    } elseif ($dim && $dim['mime'] == "image/gif") {
        $image = uploadGIFImg($files, $dist);
    } else {
        Image::make($files)->resize($width, $height, function ($cons) {
            $cons->aspectRatio();
        })->save($dist . $files->hashName());
        $image = $files->hashName();
    }
    return $image;
}

function uploadGIFImg($gif_image, $dist)
{
    $file_name = Str::uuid() . "___" . $gif_image->getClientOriginalName();
    if ($gif_image->move($dist, $file_name)) {
        return $file_name;
    }
}


function uploadFile($files, $url = 'files', $key = 'file', $model = null)
{
    $dist = storage_path('app/public/' . $url);
    if ($url != 'images' && !File::isDirectory(storage_path('app/public/files/' . $url . "/"))) {
        File::makeDirectory(storage_path('app/public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $url . DIRECTORY_SEPARATOR), 0777, true);
        $dist = storage_path('app/public' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . $url . DIRECTORY_SEPARATOR);
    } elseif (File::isDirectory(storage_path('app/public/files/' . $url . "/"))) {
        $dist = storage_path('app/public/files/' . $url . "/");
    }
    $file = '';

    if (gettype($files) == 'array') {
        $file = [];
        foreach ($files as $new_file) {
            $file_name = time() . "___file_" . $new_file->getClientOriginalName();
            if ($new_file->move($dist, $file_name)) {
                $file[][$key] = $file_name;
            }
        }
    } else {
        $file = $files;
        $file_name = time() . "___file_" . $file->getClientOriginalName();
        if ($file->move($dist, $file_name)) {
            $file =  $file_name;
        }
    }

    return $file;
}

function generalApiResponse(
    ResponseMethodEnum $method,
    $resource = null,
    $data_passed = null,
    $custom_message = null,
    $custom_status_msg = 'success',
    $custom_status = 200,
    $additional_data = null
) {

    return match ($method) {
        ResponseMethodEnum::CUSTOM_SINGLE => !is_null($additional_data) ? $resource::make($data_passed)->additional(['status' => $custom_status_msg, 'message' => $custom_message, 'additional_data' => $additional_data], $custom_status) : $resource::make($data_passed)->additional(['status' => $custom_status_msg, 'message' => $custom_message], $custom_status),

        ResponseMethodEnum::CUSTOM_COLLECTION => !is_null($additional_data) ? $resource::collection($data_passed)->additional(['status' => $custom_status_msg, 'message' => $custom_message, 'additional_data' => $additional_data], $custom_status) : $resource::collection($data_passed)->additional(['status' => $custom_status_msg, 'message' => $custom_message], $custom_status),

        ResponseMethodEnum::CUSTOM => !is_null($additional_data) ? response()->json(['status' => $custom_status_msg, 'data' => $data_passed, 'message' => $custom_message, 'additional_data' => $additional_data], $custom_status) : response()->json(['status' => $custom_status_msg, 'data' => $data_passed, 'message' => $custom_message], $custom_status),

        default => throw new InvalidArgumentException('Invalid response method'),
    };
}

function createSlug(Model $model, $title, $lang)
{
    $slug = strtolower(str_replace(' ', '-', $title));

    // Check if the slug already exists in the table
    $isUnique = !$model::where('slug->' . $lang, $slug)->exists();
    // If the slug is not unique, append a number to make it unique
    if (!$isUnique) {
        $counter = 2;
        while (!$isUnique) {
            $newSlug = $slug . '-' . $counter;
            $isUnique = !$model::where('slug->' . $lang, $newSlug)->exists();
            $counter++;
        }
        $slug = $newSlug;
    }
    return $slug;
}

function isProduction() {
    return $is_production = env('APP_ENV') == 'production' ?: false;
}



function uploadOrderImages($order_item, $cart_item)
    {
        if ($cart_item->images) {
            $old_images = [];
            if ($order_item->medias()->where('option', 'image')->count() > 0) {
                $old_images = $order_item->medias()->where('option', 'image')->get();
                foreach ($old_images as $old_image) {
                    if (file_exists(storage_path('app/public/images/orders/multi_images/' . $old_image->media))) {
                        \File::delete(storage_path('app/public/images/orders/multi_images/' . $old_image->media));
                    }
                    $old_image->delete();
                }
            }
            foreach($cart_item->images as $image) {
                $image_cart_path = str_replace(env('APP_URL') . '/storage/', 'public/', $image);
                $image_base_name = basename($image_cart_path);
                $image_order_path = str_replace('carts', 'orders', $image_cart_path);
                Storage::copy($image_cart_path, $image_order_path);
                OrderItemMedia::create(['order_mediaable_type' => 'App\Models\OrderITem', 'order_mediaable_id' => $order_item->id, 'media' => $image_base_name, 'media_type' => 'image', 'option' => 'image']);
            }
        }

        if ($cart_item->logo) {
            if ($order_item->medias()->where('option', 'logo')->count() > 0) {
                $logo = $order_item->medias()->where('option', 'logo')->first();
                if (file_exists(storage_path('app/public/images/orders/logos/' . $logo->media))) {
                    \File::delete(storage_path('app/public/images/orders/logos/' . $logo->media));
                }
                $logo->delete();
            }
            // copying
            $logo_cart_path = str_replace(env('APP_URL') . '/storage/', 'public/', $cart_item->logo);
            $logo_base_name = basename($logo_cart_path);
            $logo_order_path = str_replace('carts', 'orders', $logo_cart_path);
            Storage::copy($logo_cart_path, $logo_order_path);

            OrderItemMedia::create(['order_mediaable_type' => 'App\Models\OrderItem', 'order_mediaable_id' => $order_item->id, 'media' => $logo_base_name, 'media_type' => 'image', 'option' => 'logo']);
        }
    }
