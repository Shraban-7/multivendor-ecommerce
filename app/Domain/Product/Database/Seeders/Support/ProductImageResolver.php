<?php

namespace App\Domain\Product\Database\Seeders\Support;

use Illuminate\Support\Str;

/**
 * Resolve product-suitable Unsplash images from product name / category keywords.
 */
class ProductImageResolver
{
    /**
     * Keyword pattern => curated image URL pool (product-like photos).
     * More specific patterns MUST come first.
     *
     * @var array<string, list<string>>
     */
    private static array $pools = [
        // Audio / wearables first so "Type-C Earphone" does not fall into the cable pool.
        'neckband|sports.?wireless' => [
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
        ],
        'earphone|earphones|headphone|earbuds|buds|airpod|audiopro' => [
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?auto=format&fit=crop&w=800&q=80',
        ],
        'speaker|bluetooth.?speaker|boomer|boombeat|sound.?box' => [
            'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1589003077984-894e133dabab?auto=format&fit=crop&w=800&q=80',
        ],
        'hd.?music|\bmusic\b|soundpulse' => [
            'https://images.unsplash.com/photo-1614613535308-eb5fbd3d2c17?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1484704849700-f032a568e944?auto=format&fit=crop&w=800&q=80',
        ],
        'charger|adapter|charging|power.?bank|pd\s|wall.?wart|fastcharge' => [
            'https://images.unsplash.com/photo-1609091839311-b95efa3ac83c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1591290619762-c588f7cfc7a2?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1622445275463-afa2ab738741?auto=format&fit=crop&w=800&q=80',
        ],
        'cable|braided|micro.?usb|hdmi|usb.?hub|(usb|type-?c|lightning).{0,24}cable|cable.{0,24}(usb|type-?c|lightning|micro)' => [
            'https://images.unsplash.com/photo-1583863788434-e58a363eb8a3?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1625948515291-69613efd2566?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1618577608107-04993916452b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=800&q=80',
        ],
        'watch|smart.?watch' => [
            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=800&q=80',
        ],
        'sunglass|glasses' => [
            'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80',
        ],
        'phone|smartphone|iphone|galaxy|mobile(?!\s+accessor)' => [
            'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?auto=format&fit=crop&w=800&q=80',
        ],
        'laptop|notebook|macbook|computer' => [
            'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?auto=format&fit=crop&w=800&q=80',
        ],
        't-?shirt|shirt|polo|top|hoodie|jacket|kurta|panjabi' => [
            'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=800&q=80',
        ],
        'pant|trousers|jeans|chino' => [
            'https://images.unsplash.com/photo-1542272604-787c3835535d?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?auto=format&fit=crop&w=800&q=80',
        ],
        'dress|sharee|saree|gown|abaya' => [
            'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1566174053879-31528523f8ae?auto=format&fit=crop&w=800&q=80',
        ],
        'slider|sandal|shoe|footwear|sneaker|boot' => [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?auto=format&fit=crop&w=800&q=80',
        ],
        'book|stationery|pen|pencil' => [
            'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80',
        ],
        'rice|oil|spice|tea|coffee|honey|snack|grocery|food|500g|1kg' => [
            'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?auto=format&fit=crop&w=800&q=80',
        ],
        'cream|lotion|soap|shampoo|beauty|skincare|makeup|perfume|fragrance' => [
            'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1571781926291-c77df8097b1f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=800&q=80',
        ],
        'sports|fitness|yoga|gym|ball|cricket|football|dumbbell' => [
            'https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1517649763962-0c623066027c?auto=format&fit=crop&w=800&q=80',
        ],
        'toy|puzzle|kids|baby|action.?figure' => [
            'https://images.unsplash.com/photo-1558060370-d644479cb6f7?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=800&q=80',
        ],
        'car|auto|tire|engine.?oil|motorcycle' => [
            'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?auto=format&fit=crop&w=800&q=80',
        ],
        'kitchen|cook|pan|pot|bottle|mug|cup|utensil|home|decor' => [
            'https://images.unsplash.com/photo-1556911220-bff31c812dba?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80',
        ],
        'electronics|gadget|device' => [
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1468495244123-6c6c332eeece?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=800&q=80',
        ],
        'fashion|clothing|cotton|casual|women|men' => [
            'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=800&q=80',
        ],
    ];

    private static array $fallback = [
        'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=800&q=80',
    ];

    public static function primary(string $name, ?string $category = null): string
    {
        return self::gallery($name, $category, 1)[0];
    }

    /**
     * @return list<string>
     */
    public static function gallery(string $name, ?string $category = null, int $count = 2): array
    {
        $pool = self::poolFor($name, $category);
        $count = max(1, $count);
        $seed = abs(crc32(Str::lower(trim($name)))) ?: 1;
        $images = [];

        for ($i = 0; $i < $count; $i++) {
            $images[] = $pool[($seed + $i) % count($pool)];
        }

        return array_values(array_unique($images));
    }

    /**
     * Prefer name-matched images. Optional JSON urls are ignored when $forceNameMatch is true
     * (DummyJSON pools often assign Echo/AirPods to cables).
     *
     * @param  list<string>|null  $jsonImages
     * @return list<string>
     */
    public static function forProduct(
        string $name,
        ?string $category = null,
        ?string $jsonThumbnail = null,
        ?array $jsonImages = null,
        int $minImages = 2,
        bool $forceNameMatch = true,
    ): array {
        if ($forceNameMatch) {
            return self::gallery($name, $category, $minImages);
        }

        $images = collect($jsonImages ?? [])
            ->filter(fn ($url) => is_string($url) && trim($url) !== '')
            ->map(fn ($url) => trim($url))
            ->unique()
            ->values();

        if ($jsonThumbnail && ! $images->contains($jsonThumbnail)) {
            $images = $images->prepend(trim($jsonThumbnail))->unique()->values();
        }

        if ($images->isEmpty()) {
            return self::gallery($name, $category, $minImages);
        }

        while ($images->count() < $minImages) {
            foreach (self::gallery($name, $category, $minImages) as $candidate) {
                if (! $images->contains($candidate)) {
                    $images->push($candidate);
                }
                if ($images->count() >= $minImages) {
                    break;
                }
            }
            break;
        }

        return $images->take(max($minImages, $images->count()))->values()->all();
    }

    /**
     * @return list<string>
     */
    private static function poolFor(string $name, ?string $category = null): array
    {
        $haystack = Str::lower(trim($name.' '.($category ?? '')));

        foreach (self::$pools as $pattern => $urls) {
            if (preg_match('/'.$pattern.'/i', $haystack)) {
                return $urls;
            }
        }

        return self::$fallback;
    }
}
