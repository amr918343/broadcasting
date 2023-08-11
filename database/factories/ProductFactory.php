<?php

namespace Database\Factories;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Enums\ProductVariant\Size;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {

        $image = UploadedFile::fake()->image($this->faker->name() . '.jpg');
        $path = Storage::putFile('public/images/products', $image);

        $categoryIds = Category::pluck('id')->toArray();
        $categoryId = $categoryIds[$this->faker->numberBetween(0, count($categoryIds) - 1)];

        $adminIds = Admin::pluck('id')->toArray();
        $adminId = $adminIds[$this->faker->numberBetween(0, count($adminIds) - 1)];

        return [
            'image' => $this->faker->imageUrl(),
            'added_by_id' => $adminId,
            'title' => ['ar' => $this->faker->sentence(4), 'en' => $this->faker->sentence(4)],
            'description' => ['ar' => $this->faker->paragraph(3), 'en' => $this->faker->paragraph(3)],
            'price' => $this->faker->randomFloat(2, 10, 100),
            'price_before_discount' => $this->faker->randomFloat(2, 100, 150),
            'ordering' => $this->faker->numberBetween(1, 100),
            'category_id' => $categoryId,
            'is_active' => $this->faker->boolean(80),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $variants = $this->variantData();
            $image = $this->faker->imageUrl();
            foreach ($variants as $variant) {
                $variantModel = $product->variants()->create($variant);
                $variantModel->addMediaFromUrl($image)->toMediaCollection('images');
            }
        });
    }

    private function variantData()
    {
        $variants = [];

        for ($i = 0; $i < 3; $i++) {
            $variant = [
                'color' => $this->faker->hexColor(),
                'size' => Size::getRandomValue(),
                'quantity' => $this->faker->numberBetween(1, 100),
                'description' => ['ar' => $this->faker->paragraph(2), 'en' => $this->faker->paragraph(2)],
            ];

            $variants[] = $variant;
        }

        return $variants;
    }

    // private function reviewData(Product $product)
    // {
    //     $reviews = [];

    //     for ($i = 0; $i < 3; $i++) {
    //         $review = [
    //             'product_id' => $product->id,
    //             'user_id' => array_rand(User::get()->pluck('id')->toArray()),
    //             'comment' => $this->faker->numberBetween(1, 100),
    //             'rating' => ['ar' => $this->faker->paragraph(2), 'en' => $this->faker->paragraph(2)],
    //         ];

    //         $variants[] = $variant;
    //     }

    //     return $variants;
    // }
}
