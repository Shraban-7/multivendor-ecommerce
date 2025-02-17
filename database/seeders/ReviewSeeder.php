<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $reviews = [
            [
                'product_id' => 1,
                'user_id' => 1,
                'rating' => 5,
                'review_text' => 'Great product! Highly recommend it. The quality is amazing and works as expected.',
            ],
            [
                'product_id' => 1,
                'user_id' => 2,
                'rating' => 4,
                'review_text' => 'Good quality but a bit expensive. Overall, a solid purchase.',
            ],
            [
                'product_id' => 2,
                'user_id' => 3,
                'rating' => 3,
                'review_text' => 'Average product. It works fine but there are better options in the market.',
            ],
            [
                'product_id' => 3,
                'user_id' => 1,
                'rating' => 4,
                'review_text' => 'Great camera lens, but the price is on the higher side. Still, a good investment for professionals.',
            ],
            [
                'product_id' => 4,
                'user_id' => 2,
                'rating' => 2,
                'review_text' => 'Not as expected. The quality could be much better, especially for the price.',
            ],
            [
                'product_id' => 5,
                'user_id' => 3,
                'rating' => 5,
                'review_text' => 'Excellent product! Very durable and the design is top-notch.',
            ],
        ];

        foreach ($reviews as $review) {
            Review::insert([
                'product_id' => $review['product_id'],
                'user_id' => $review['user_id'],
                'rating' => $review['rating'],
                'review_text' => $review['review_text'],
            ]);
        }
    }
}
