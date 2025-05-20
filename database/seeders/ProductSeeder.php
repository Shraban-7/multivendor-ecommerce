<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\ProductUnit;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{

    public function run(): void
    {
        $majorCategories = [
            'automotive'         => 5,
            'home-appliances'    => 5,
            'electronics'        => 5,
            'fashion'            => 4,
            'skin-care'          => 3,
            'grocery-essentials' => [1, 2],
        ];

        $brandIds = \App\Models\Brand::pluck('id')->toArray();

        $featuredVideos = [
            'video-product-1.mp4',
            'video-product-2.mp4',
            'video-product-3.mp4',
        ];

        $productAttributes = ProductAttribute::with('options')->get()->keyBy('name');

        DB::transaction(function () use ($majorCategories, $brandIds, $featuredVideos) {

            foreach ($majorCategories as $slug => $seller) {
                $category = Category::where('slug', $slug)->with('subcategories')->first();
                if (! $category) {
                    continue;
                }

                $subcategories = $category->subcategories->isNotEmpty()
                ? $category->subcategories
                : collect([(object) ['id' => null, 'name' => $category->name]]);

                foreach ($subcategories as $sub) {
                    for ($i = 1; $i <= 15; $i++) {
                        $productData = $this->getProduct($slug, $sub->name);

                        $product = Product::create([
                            'name'                 => $productData['name'],
                            'slug'                 => Str::slug($productData['name']) . '-' . uniqid(),
                            'thumbnail'            => $productData['thumbnail'],
                            'short_description'    => "High-quality " . strtolower($sub->name),
                            'description'          => "Premium and reliable " . strtolower($sub->name),
                            'buying_price'         => $productData['buying_price'],
                            'selling_price'        => $productData['selling_price'],
                            'discount_type'        => 'percentage',
                            'discount_amount'      => rand(5, 25),
                            'unit_value'           => rand(1, 5),
                            'unit_id'              => $this->getUnitId($slug),
                            'category_id'          => $category->id,
                            'subcategory_id'       => $sub->id,
                            'brand_id'             => $brandIds ? $brandIds[array_rand($brandIds)] : null,
                            'seller_id'            => is_array($seller) ? $seller[array_rand($seller)] : $seller,
                            'sku'                  => strtoupper(substr($category->name, 0, 2)) . '-' . strtoupper(Str::random(6)),
                            'barcode'              => 'BAR-' . strtoupper(Str::random(10)),
                            'is_trending'          => rand(0, 1),
                            'is_community'         => rand(0, 1),
                            'is_interest'          => rand(0, 1),
                            'is_lightdeal'         => rand(0, 1),
                            'lightdeal_expired_at' => now()->addDays(rand(1, 30)),
                            'best_selling'         => rand(0, 1),
                            'is_featured'          => rand(0, 1),
                            'stock_in'             => rand(10, 100),
                            'stock_out'            => rand(0, 5),
                            'shipping_cost'        => rand(10, 50),
                            'tax'                  => rand(5, 12),
                            'views'                => rand(100, 5000),
                            'video'                => 'videos/products/' . $featuredVideos[($i - 1) % count($featuredVideos)],
                        ]);

                        // Insert product images
                        if (! empty($productData['images'])) {
                            $imagesToInsert = array_map(fn($image) => [
                                'product_id' => $product->id,
                                'image'      => $image,
                            ], $productData['images']);
                            ProductImage::insert($imagesToInsert);
                        }

                        if (! empty($productData['variants'])) {
                            foreach ($productData['variants'] as $key => $options) {
                                $productAttribute = ProductAttribute::where('category_id', $product->category_id)
                                    ->where('name', $key)
                                    ->with('options')
                                    ->first();

                                if (! $productAttribute) {
                                    continue;
                                }

                                foreach ($options as $optionValue) {
                                    $option = $productAttribute->options->firstWhere('value', $optionValue);

                                    if ($option) {
                                        ProductVariant::create([
                                            'product_id'       => $product->id,
                                            'option_id'        => $option->id,
                                            'sku'              => $product->sku . '-' . strtoupper(Str::random(4)),
                                            'additional_price' => round(rand(10, 50)),
                                            'stock_in'         => rand(20, 30),
                                            'stock_out'         => rand(10, 15),
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        });
    }

    private static array $unitMap = [];

    private function loadUnitMap(): void
    {
        if (empty(self::$unitMap)) {
            self::$unitMap = ProductUnit::pluck('id', 'short_name')->toArray();
        }
    }

    private function getUnitId(string $slug): int
    {
        $this->loadUnitMap();

        $unitShort = match ($slug) {
            'grocery-essentials' => ['kg', 'g', 'pk'][array_rand(['kg', 'g', 'pk'])],
            'electronics', 'automotive', 'home-appliances' => 'pc',
            'fashion'   => ['pc', 'dz'][array_rand(['pc', 'dz'])],
            'skin-care' => ['ml', 'L', 'pk'][array_rand(['ml', 'L', 'pk'])],
            default     => 'pc',
        };

        return self::$unitMap[$unitShort] ?? self::$unitMap['pc'];
    }

    private function getProduct(string $slug, string $subName): array
    {
        $products = [
            'automotive'         => [
                [
                    'name'          => 'All-Terrain Car Tire',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-1.jpg',
                    'images'        => ['images/products/automotive1.jpg', 'images/products/automotive2.jpg', 'images/products/automotive3.jpg'],
                    'buying_price'  => 8500,
                    'selling_price' => 10500,
                    'variants'      => [
                        'Size' => ['15 Inch', '16 Inch', '17 Inch'],
                    ],
                ],
                [
                    'name'          => 'Synthetic Engine Oil – 5L',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-2.jpg',
                    'images'        => ['images/products/automotive4.jpg', 'images/products/automotive5.jpg', 'images/products/automotive6.jpg'],
                    'buying_price'  => 2800,
                    'selling_price' => 3500,
                    'variants'      => [
                        'Viscosity' => ['5W-30', '10W-40', '15W-40'],
                    ],
                ],
                [
                    'name'          => 'LED Headlight Pair – 6000K',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-3.jpg',
                    'images'        => ['images/products/automotive7.jpg', 'images/products/automotive8.jpg', 'images/products/automotive9.jpg'],
                    'buying_price'  => 1500,
                    'selling_price' => 2200,
                    'variants'      => [
                        'Color' => ['White', 'Blue'],
                    ],
                ],
                [
                    'name'          => 'Brake Pad Set – Ceramic',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-4.jpg',
                    'images'        => ['images/products/automotive10.jpg', 'images/products/automotive11.jpg', 'images/products/automotive12.jpg'],
                    'buying_price'  => 2200,
                    'selling_price' => 3000,
                    'variants'      => [
                        'Material' => ['Ceramic', 'Semi-metallic'],
                    ],
                ],
                [
                    'name'          => 'Motorcycle Helmet – Matte Black',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-5.jpg',
                    'images'        => ['images/products/automotive13.jpg', 'images/products/automotive14.jpg', 'images/products/automotive15.jpg'],
                    'buying_price'  => 1200,
                    'selling_price' => 1800,
                    'variants'      => [
                        'Size'  => ['M', 'L', 'XL'],
                        'Color' => ['Black', 'Red'],
                    ],
                ],
                [
                    'name'          => 'Windshield Wiper Blades – 24in',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-6.jpg',
                    'images'        => ['images/products/automotive16.jpg', 'images/products/automotive17.jpg', 'images/products/automotive18.jpg'],
                    'buying_price'  => 600,
                    'selling_price' => 850,
                    'variants'      => [
                        'Size' => ['24 Inch', '27 Inch'],
                    ],
                ],
                [
                    'name'          => 'Car Air Freshener – Lemon',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-7.jpg',
                    'images'        => ['images/products/automotive19.jpg', 'images/products/automotive20.jpg', 'images/products/automotive21.jpg'],
                    'buying_price'  => 100,
                    'selling_price' => 200,
                    'variants'      => [
                        'Fragrance' => ['Lemon', 'Lavender', 'Ocean Breeze'],
                        'Type'      => ['Gel', 'Spray', 'Card'],
                    ],
                ],
                [
                    'name'          => 'Car Battery – 12V 70Ah',
                    'thumbnail'     => 'images/products/thumb/automotive-thumb-8.jpg',
                    'images'        => ['images/products/automotive22.jpg', 'images/products/automotive23.jpg', 'images/products/automotive24.jpg'],
                    'buying_price'  => 9500,
                    'selling_price' => 11500,
                    'variants'      => [
                        'Capacity' => ['12V 60Ah', '12V 70Ah', '12V 80Ah'],
                        'Type'     => ['Lead-Acid', 'AGM', 'Gel'],
                    ],
                ],
            ],

            'home-appliances'    => [
                [
                    'name'          => 'Smart Air Conditioner – 1.5 Ton',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-1.jpg',
                    'images'        => ['images/products/appliance1.jpg', 'images/products/appliance2.jpg', 'images/products/appliance3.jpg'],
                    'buying_price'  => 48000,
                    'selling_price' => 55000,
                ],
                [
                    'name'          => 'Microwave Oven – 20L Digital',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-2.jpg',
                    'images'        => ['images/products/appliance4.jpg', 'images/products/appliance5.jpg', 'images/products/appliance6.jpg'],
                    'buying_price'  => 8500,
                    'selling_price' => 11000,
                ],
                [
                    'name'          => 'Water Purifier – RO+UV+UF',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-3.jpg',
                    'images'        => ['images/products/appliance7.jpg', 'images/products/appliance8.jpg', 'images/products/appliance9.jpg'],
                    'buying_price'  => 15500,
                    'selling_price' => 18000,
                ],
                [
                    'name'          => 'Double Door Refrigerator – 600L',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-4.jpg',
                    'images'        => ['images/products/appliance10.jpg', 'images/products/appliance11.jpg', 'images/products/appliance12.jpg'],
                    'buying_price'  => 62000,
                    'selling_price' => 72000,
                ],
                [
                    'name'          => 'Automatic Washing Machine – 7kg',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-5.jpg',
                    'images'        => ['images/products/appliance13.jpg', 'images/products/appliance14.jpg', 'images/products/appliance15.jpg'],
                    'buying_price'  => 25000,
                    'selling_price' => 29500,
                ],
                [
                    'name'          => 'Electric Kettle – 1.5L',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-6.jpg',
                    'images'        => ['images/products/appliance16.jpg', 'images/products/appliance17.jpg', 'images/products/appliance18.jpg'],
                    'buying_price'  => 1300,
                    'selling_price' => 1700,
                ],
                [
                    'name'          => 'Induction Cooktop – 2000W',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-7.jpg',
                    'images'        => ['images/products/appliance19.jpg', 'images/products/appliance20.jpg', 'images/products/appliance21.jpg'],
                    'buying_price'  => 3200,
                    'selling_price' => 4200,
                ],
                [
                    'name'          => 'Room Heater – Quartz Element',
                    'thumbnail'     => 'images/products/thumb/appliance-thumb-8.jpg',
                    'images'        => ['images/products/appliance22.jpg', 'images/products/appliance23.jpg', 'images/products/appliance24.jpg'],
                    'buying_price'  => 2600,
                    'selling_price' => 3400,
                ],
            ],

            'fashion'            => [
                [
                    'name'          => 'The Iconic Doeskin Blazer',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-1.jpg',
                    'images'        => [
                        'images/products/fashion-1.jpg',
                        'images/products/fashion-2.jpg',
                        'images/products/fashion-3.jpg',
                    ],
                    'buying_price'  => 1800,
                    'selling_price' => 2500,
                    'variants'      => [
                        'Color'    => ['Black', 'Gray'],
                        'Size'     => ['M', 'L', 'XL'],
                        'Material' => ['Wool', 'Polyester'],
                    ],
                ],
                [
                    'name'          => 'Clark Navy Dial Watch',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-2.jpg',
                    'images'        => [
                        'images/products/fashion-4.jpg',
                        'images/products/fashion-5.jpg',
                        'images/products/fashion-6.jpg',
                    ],
                    'buying_price'  => 1200,
                    'selling_price' => 1650,
                    'variants'      => [
                        'Color'    => ['Blue', 'Black'],
                        'Material' => ['Leather'],
                    ],
                ],
                [
                    'name'          => 'Sports Sneakers White/Black',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-3.jpg',
                    'images'        => [
                        'images/products/fashion-7.jpg',
                        'images/products/fashion-8.jpg',
                        'images/products/fashion-9.jpg',
                    ],
                    'buying_price'  => 900,
                    'selling_price' => 1300,
                    'variants'      => [
                        'Color'    => ['White', 'Black'],
                        'Size'     => ['M', 'L', 'XL'],
                        'Material' => ['Leather'],
                    ],
                ],
                [
                    'name'          => 'Traditional Red Silk Saree',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-4.jpg',
                    'images'        => [
                        'images/products/fashion-10.jpg',
                        'images/products/fashion-11.jpg',
                        'images/products/fashion-12.jpg',
                    ],
                    'buying_price'  => 2200,
                    'selling_price' => 2900,
                    'variants'      => [
                        'Color' => ['Red', 'Maroon'],
                    ],
                ],
                [
                    'name'          => 'Leather Handbag Camel Brown',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-5.jpg',
                    'images'        => [
                        'images/products/fashion-13.jpg',
                        'images/products/fashion-14.jpg',
                        'images/products/fashion-15.jpg',
                    ],
                    'buying_price'  => 1500,
                    'selling_price' => 2000,
                    'variants'      => [
                        'Color'    => ['Camel Brown', 'Black'],
                        'Material' => ['Leather'],
                    ],
                ],
                [
                    'name'          => 'Reebok Classic Leather Sneakers',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-6.jpg',
                    'images'        => [
                        'images/products/fashion-16.jpg',
                        'images/products/fashion-17.jpg',
                        'images/products/fashion-18.jpg',
                    ],
                    'buying_price'  => 2200,
                    'selling_price' => 2900,
                    'variants'      => [
                        'Color'    => ['White', 'Black'],
                        'Size'     => ['M', 'L'],
                        'Material' => ['Leather'],
                    ],
                ],
                [
                    'name'          => 'Classic Men Fleece Shorts',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-7.jpg',
                    'images'        => [
                        'images/products/fashion-19.jpg',
                        'images/products/fashion-20.jpg',
                        'images/products/fashion-21.jpg',
                    ],
                    'buying_price'  => 600,
                    'selling_price' => 950,
                    'variants'      => [
                        'Color'    => ['Gray', 'Black'],
                        'Size'     => ['M', 'L', 'XL'],
                        'Material' => ['Cotton'],
                    ],
                ],
                [
                    'name'          => 'Tommy Hilfiger Polo T-Shirt',
                    'thumbnail'     => 'images/products/thumb/fashion-thumb-8.jpg',
                    'images'        => [
                        'images/products/fashion-22.jpg',
                        'images/products/fashion-23.jpg',
                        'images/products/fashion-24.jpg',
                    ],
                    'buying_price'  => 900,
                    'selling_price' => 1350,
                    'variants'      => [
                        'Color'    => ['Blue', 'White'],
                        'Size'     => ['M', 'L', 'XL'],
                        'Material' => ['Cotton'],
                    ],
                ],
            ],

            'electronics'        => [
                [
                    'name'          => '24-inch Curved Monitor',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-1.jpg',
                    'images'        => [
                        'images/products/electronics-1.jpg',
                        'images/products/electronics-2.jpg',
                        'images/products/electronics-3.jpg',
                    ],
                    'buying_price'  => 7500,
                    'selling_price' => 9500,
                    'variants'      => [
                        'Size' => ['22 Inch', '27 Inch'],
                    ],
                ],
                [
                    'name'          => 'Bluetooth Portable Speaker',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-2.jpg',
                    'images'        => [
                        'images/products/electronics-4.jpg',
                        'images/products/electronics-5.jpg',
                        'images/products/electronics-6.jpg',
                    ],
                    'buying_price'  => 1800,
                    'selling_price' => 2500,
                    'variants'      => [
                        'Color' => ['Black', 'Blue', 'Red'],
                    ],
                ],
                [
                    'name'          => 'Wireless Noise Cancelling Headphones',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-3.jpg',
                    'images'        => [
                        'images/products/electronics-7.jpg',
                        'images/products/electronics-8.jpg',
                        'images/products/electronics-9.jpg',
                    ],
                    'buying_price'  => 3000,
                    'selling_price' => 4000,
                    'variants'      => [
                        'Color' => ['Black', 'White'],
                    ],
                ],
                [
                    'name'          => 'RGB Mechanical Gaming Keyboard',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-4.jpg',
                    'images'        => [
                        'images/products/electronics-10.jpg',
                        'images/products/electronics-11.jpg',
                        'images/products/electronics-12.jpg',
                    ],
                    'buying_price'  => 2200,
                    'selling_price' => 3000,
                    'variants'      => [
                        'Color' => ['Black', 'White'],
                    ],
                ],
                [
                    'name'          => '4K Ultra HD Smart TV',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-5.jpg',
                    'images'        => [
                        'images/products/electronics-13.jpg',
                        'images/products/electronics-14.jpg',
                        'images/products/electronics-15.jpg',
                    ],
                    'buying_price'  => 22000,
                    'selling_price' => 27000,
                    'variants'      => [
                        'Size' => ['22 Inch', '27 Inch'],
                    ],
                ],
                [
                    'name'          => 'Smartwatch with Heart Rate Monitor',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-6.jpg',
                    'images'        => [
                        'images/products/electronics-16.jpg',
                        'images/products/electronics-17.jpg',
                        'images/products/electronics-18.jpg',
                    ],
                    'buying_price'  => 3200,
                    'selling_price' => 4000,
                    'variants'      => [
                        'Color' => ['Black', 'Silver'],
                    ],
                ],
                [
                    'name'          => 'WiFi Wireless Router 1200Mbps',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-7.jpg',
                    'images'        => [
                        'images/products/electronics-19.jpg',
                        'images/products/electronics-20.jpg',
                        'images/products/electronics-21.jpg',
                    ],
                    'buying_price'  => 1600,
                    'selling_price' => 2200,
                ],
                [
                    'name'          => 'Type-C Fast Charging Power Bank 20,000mAh',
                    'thumbnail'     => 'images/products/thumb/electronics-thumb-8.jpg',
                    'images'        => [
                        'images/products/electronics-22.jpg',
                        'images/products/electronics-23.jpg',
                        'images/products/electronics-24.jpg',
                    ],
                    'buying_price'  => 1800,
                    'selling_price' => 2400,
                    'variants'      => [
                        'Color' => ['Black', 'White'],
                    ],
                ],
            ],

            'skin-care'          => [
                [
                    'name'          => 'Aloe Vera Hydrating Face Gel',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-1.jpg',
                    'images'        => [
                        'images/products/skincare-1.jpg',
                        'images/products/skincare-2.jpg',
                        'images/products/skincare-3.jpg',
                    ],
                    'buying_price'  => 180,
                    'selling_price' => 250,
                    'variants'      => [
                        'Size'        => ['50ml', '100ml'],
                        'Skin Type'   => ['All', 'Dry'],
                        'Formulation' => ['Gel'],
                    ],
                ],
                [
                    'name'          => 'Vitamin C Brightening Serum',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-2.jpg',
                    'images'        => [
                        'images/products/skincare-4.jpg',
                        'images/products/skincare-5.jpg',
                        'images/products/skincare-6.jpg',
                    ],
                    'buying_price'  => 300,
                    'selling_price' => 400,
                    'variants'      => [
                        'Size'        => ['30ml', '50ml'],
                        'Skin Type'   => ['All', 'Oily'],
                        'Formulation' => ['Serum'],
                    ],
                ],
                [
                    'name'          => 'SPF 50+ Sunscreen Lotion',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-3.jpg',
                    'images'        => [
                        'images/products/skincare-7.jpg',
                        'images/products/skincare-8.jpg',
                        'images/products/skincare-9.jpg',
                    ],
                    'buying_price'  => 220,
                    'selling_price' => 300,
                ],
                [
                    'name'          => 'Charcoal Deep Clean Face Wash',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-4.jpg',
                    'images'        => [
                        'images/products/skincare-10.jpg',
                        'images/products/skincare-11.jpg',
                        'images/products/skincare-12.jpg',
                    ],
                    'buying_price'  => 160,
                    'selling_price' => 220,
                ],
                [
                    'name'          => 'Daily Moisturizing Lotion',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-5.jpg',
                    'images'        => [
                        'images/products/skincare-13.jpg',
                        'images/products/skincare-14.jpg',
                        'images/products/skincare-15.jpg',
                    ],
                    'buying_price'  => 140,
                    'selling_price' => 200,
                ],
                [
                    'name'          => 'Green Tea Pore Cleanser',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-6.jpg',
                    'images'        => [
                        'images/products/skincare-16.jpg',
                        'images/products/skincare-17.jpg',
                        'images/products/skincare-18.jpg',
                    ],
                    'buying_price'  => 190,
                    'selling_price' => 260,
                ],
                [
                    'name'          => 'Anti-Aging Night Cream',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-7.jpg',
                    'images'        => [
                        'images/products/skincare-19.jpg',
                        'images/products/skincare-20.jpg',
                        'images/products/skincare-21.jpg',
                    ],
                    'buying_price'  => 350,
                    'selling_price' => 470,
                ],
                [
                    'name'          => 'Under Eye Repair Serum',
                    'thumbnail'     => 'images/products/thumb/skincare-thumb-8.jpg',
                    'images'        => [
                        'images/products/skincare-22.jpg',
                        'images/products/skincare-23.jpg',
                        'images/products/skincare-24.jpg',
                    ],
                    'buying_price'  => 280,
                    'selling_price' => 360,
                ],
            ],

            'grocery-essentials' => [
                [
                    'name'          => 'Fresh Chicken Meat (1kg)',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-1.jpg',
                    'images'        => [
                        'images/products/grocery-1.jpg',
                        'images/products/grocery-2.jpg',
                        'images/products/grocery-3.jpg',
                    ],
                    'buying_price'  => 280,
                    'selling_price' => 320,
                ],
                [
                    'name'          => 'Organic Basmati Rice 5kg',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-2.jpg',
                    'images'        => [
                        'images/products/grocery-4.jpg',
                        'images/products/grocery-5.jpg',
                        'images/products/grocery-6.jpg',
                    ],
                    'buying_price'  => 700,
                    'selling_price' => 850,
                ],
                [
                    'name'          => 'Mixed Seasonal Veg Frozen Pack',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-3.jpg',
                    'images'        => [
                        'images/products/grocery-7.jpg',
                        'images/products/grocery-8.jpg',
                        'images/products/grocery-9.jpg',
                    ],
                    'buying_price'  => 180,
                    'selling_price' => 220,
                ],
                [
                    'name'          => 'Sunflower Cooking Oil 1L',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-4.jpg',
                    'images'        => [
                        'images/products/grocery-10.jpg',
                        'images/products/grocery-11.jpg',
                        'images/products/grocery-12.jpg',
                    ],
                    'buying_price'  => 165,
                    'selling_price' => 200,
                ],
                [
                    'name'          => 'Whole Wheat Bread Loaf',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-5.jpg',
                    'images'        => [
                        'images/products/grocery-13.jpg',
                        'images/products/grocery-14.jpg',
                        'images/products/grocery-15.jpg',
                    ],
                    'buying_price'  => 40,
                    'selling_price' => 50,
                ],
                [
                    'name'          => 'Premium Mosurer Dal 1kg',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-6.jpg',
                    'images'        => [
                        'images/products/grocery-16.jpg',
                        'images/products/grocery-17.jpg',
                        'images/products/grocery-18.jpg',
                    ],
                    'buying_price'  => 110,
                    'selling_price' => 130,
                ],
                [
                    'name'          => 'Sprite Soft Drink 1.25L',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-7.jpg',
                    'images'        => [
                        'images/products/grocery-19.jpg',
                        'images/products/grocery-20.jpg',
                        'images/products/grocery-21.jpg',
                    ],
                    'buying_price'  => 55,
                    'selling_price' => 70,
                ],
                [
                    'name'          => 'Nutella Chocolate Spread 350g',
                    'thumbnail'     => 'images/products/thumb/grocery-thumb-8.jpg',
                    'images'        => [
                        'images/products/grocery-22.jpg',
                        'images/products/grocery-23.jpg',
                        'images/products/grocery-24.jpg',
                    ],
                    'buying_price'  => 380,
                    'selling_price' => 450,
                ],
            ],
        ];

        $list = $products[$slug] ?? [[
            'name'      => "{$subName} Product",
            'thumbnail' => 'images/products/thumb/default.png',
            'images'    => [
                'images/products/default1.jpg',
                'images/products/default2.jpg',
                'images/products/default3.jpg',
            ],
        ]];

        return $list[array_rand($list)];
    }
}
