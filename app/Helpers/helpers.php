<?php

use App\Enums\AdminRole;
use App\Enums\DiscountType;
use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Notification;
use App\Models\PaymentOption;
use App\Models\SocialLink;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


if (! function_exists('currency_symbol')) {
    function currency_symbol()
    {
        return "৳";
    }
}

if (! function_exists('redirect_intended')) {
    function redirect_intended($default = '/')
    {
        return redirect()->intended($default);
    }
}

if (! function_exists('str_slug')) {
    function str_slug($table, $column, $title, $separator = '-')
    {
        $slug         = Str::slug($title, $separator);
        $originalSlug = $slug;
        $count        = 1;

        while (DB::table($table)->where($column, $slug)->exists()) {
            $slug = "{$originalSlug}{$separator}{$count}";
            $count++;
        }

        return $slug;
    }
}

if (! function_exists('sendValidationError')) {
    function sendValidationError($errors)
    {
        return response()->json([
            'status'  => false,
            'message' => $errors->first(),
        ], 422);
    }
}

if (! function_exists('generateFileName')) {
    function generateFileName($file)
    {
        return time() . rand(1, 9999) . '.' . $file->extension();
    }
}

if (! function_exists('upload_file')) {
    function upload_file($file, $directory, $disk = 'public')
    {
        if (! Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->makeDirectory($directory);
        }

        // $fileName = time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
        // $path     = $directory . '/' . $fileName;
        // Storage::disk($disk)->put($path, File::get($file));

        $fileName = time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $fileName;
        $file->storeAs($directory, $fileName, $disk);

        return $path;
    }
}

if (! function_exists('upload_with_watermark')) {
    function upload_with_watermark($file, $directory, $disk = 'public')
    {
        $watermarkPath = 'images/watermark.png';
        $tempPath      = upload_file($file, 'images/temp', $disk);
        $fileName      = basename($tempPath);
        $finalPath     = $directory . '/' . $fileName;
        $fullFinalPath = Storage::disk($disk)->path($finalPath);

        if (! Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->makeDirectory($directory);
        }

        $image = Image::read(Storage::disk($disk)->get($tempPath));

        if (Storage::disk($disk)->exists($watermarkPath)) {
            $watermark = Image::read(Storage::disk($disk)->get($watermarkPath));

            $imageWidth = $image->width();
            $offset_x   = (int) ($imageWidth * 0.06);
            $offset_y   = 10;

            $image->place(
                element: $watermark,
                position: 'bottom-right',
                offset_x: $offset_x,
                offset_y: $offset_y,
                opacity: 70
            );
        }

        $image->save($fullFinalPath);
        delete_file($tempPath);

        return $finalPath;
    }
}

if (! function_exists('storage_url')) {
    function storage_url($file, $disk = 'public')
    {
        return Storage::disk($disk)->url($file);
    }
}

if (! function_exists('delete_file')) {
    function delete_file($file)
    {
        Storage::disk('public')->delete($file);
    }
}

if (! function_exists('all_categories')) {
    function all_categories()
    {
        return Category::category()->orderBy('name', 'ASC')->with('subcategories')->get();
    }
}

if (! function_exists('nav_categories')) {
    function nav_categories()
    {
        return Category::nav()->orderBy('id', 'ASC')->get();
    }
}

if (! function_exists('all_department_categories')) {
    function all_department_categories()
    {
        $categories = Category::allDepartment()
            ->category()
            ->orderBy('name', 'ASC')
            ->with('subcategories')
            ->get();

        return $categories;
    }
}

if (! function_exists('currency')) {
    function currency($key = 'symbol')
    {
        $currency = [
            'name'   => 'BDT',
            'symbol' => '৳',
        ];

        return $currency[$key];
    }
}

if (! function_exists('number_shorten_format')) {
    function number_shorten_format($number, $precision = 1, $divisors = null)
    {
        if (! isset($divisors)) {
            $divisors = [
                pow(1000, 0) => '',
                pow(1000, 1) => 'K',
                pow(1000, 2) => 'M',
                pow(1000, 3) => 'B',
            ];
        }
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                break;
            }
        }
        if ($number <= 1000) {
            $precision = 0;
        }
        return number_format($number / $divisor, $precision) . $shorthand;
    }
}

if (! function_exists('datetime_format')) {
    function datetime_format($time)
    {
        $carbonTime    = Carbon::parse($time);
        $days          = $carbonTime->diffInDays(Carbon::now()) > 0 ? $carbonTime->diffInDays(Carbon::now()) . ':' : '';
        $formattedTime = substr($carbonTime->format('H:i:s.u'), 0, -3);
        return $days . $formattedTime;
    }
}

if (! function_exists('percentage')) {
    function percentage($number)
    {
        if (! is_numeric($number)) {
            return '0%';
        }

        return $number * 100 . '%';
    }
}

if (! function_exists('seller')) {
    function seller()
    {
        return Auth::guard('seller')->user();
    }
}

if (! function_exists('employee')) {
    function employee()
    {
        return Auth::guard('employee')->user();
    }
}

if (! function_exists('apiResponse')) {
    function apiResponse(object | array $data, string | null $message = null, int $statusCode = 200)
    {
        $response['status'] = true;

        if (isset($message)) {
            $response['message'] = $message;
        }

        if (! empty($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
}

if (! function_exists('successResponse')) {
    function successResponse(string $message, int $statusCode = 200)
    {
        $response['status'] = true;

        if (isset($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }
}

if (! function_exists('errorResponse')) {
    function errorResponse(string $message, int $statusCode = 400)
    {
        return response()->json([
            'status'  => false,
            'message' => $message ?? 'Something went wrong!',
        ], $statusCode);
    }
}

if (! function_exists('apiResourceResponse')) {
    function apiResourceResponse(object $collection, string | null $message = null, array $extraData = [], int $statusCode = 200)
    {
        $response['status'] = true;
        if (isset($message)) {
            $response['message'] = $message;
        }

        if (! empty($extraData)) {
            $response['extraData'] = $extraData;
        }

        if (! empty($collection)) {
            $collection = $collection->additional($response)->response()->getData();
        }

        return response()->json($collection, $statusCode);
    }
}

if (! function_exists('validateRequest')) {
    function validateRequest(Request $request, array $rules)
    {
        return Validator::make($request->all(), $rules);
    }
}

if (! function_exists('removeZeroFromDecimal')) {
    function removeZeroFromDecimal($number, $dataType = 'string')
    {
        if (is_null($number)) {
            return null;
        }

        $decimal = explode('.', $number);
        if (isset($decimal[1]) && $decimal[1] == '00') {
            $number = str_replace('.00', '', $number);
        }

        if ($dataType == 'string') {
            return (string) $number;
        }

        return (int) $number;
    }
}

if (! function_exists('money')) {
    function money($amount, $showCurrency = true)
    {
        $money = number_format($amount, 2);

        if(!$showCurrency) {
            return removeZeroFromDecimal($money);
        }

        return currency_symbol() . ' ' . removeZeroFromDecimal($money);
    }
}

if (! function_exists('social_links')) {
    function social_links()
    {
        return SocialLink::where('status', 1)->get();
    }
}

if (! function_exists('payment_options')) {
    function payment_options()
    {
        return PaymentOption::where('status', 1)->get();
    }
}

if (! function_exists('admin')) {
    function admin()
    {
        return Auth::guard('admin')->user();
    }
}

if (! function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        return admin()->role->name == AdminRole::SUPER_ADMIN->value ? true : false;
    }
}

if (! function_exists('hasPermission')) {
    function hasPermission($permissionKey)
    {
        $admin = admin();

        return Cache::remember("permissions_" . $admin->role->name, 6, function () use ($admin, $permissionKey) {
            $permissions = $admin->role->permissionNames;

            return in_array($permissionKey, $permissions);
        });
    }
}

if (! function_exists('settings')) {
    function settings()
    {
        return SystemSetting::first();
    }
}

if (! function_exists('app_name')) {
    function app_name()
    {
        return SystemSetting::first()->app_name;
    }
}

if (! function_exists('calculate_discounted_price')) {
    function calculate_discounted_price(float $price, ?string $type, ?float $value): float
    {
        if (! $type || ! $value) {
            return $price;
        }

        $discountAmount = match ($type) {
            DiscountType::PERCENTAGE->value => ($price * $value) / 100,
            DiscountType::FLAT->value => $value,
            default => 0,
        };

        $finalPrice = $price - $discountAmount;

        return max(round($finalPrice, 2), 0);
    }
}

if (! function_exists('calculate_discount_amount')) {
    function calculate_discount_amount(float $price, ?string $type, ?float $value): ?float
    {
        if (! $type || ! $value) {
            return null;
        }

        return match ($type) {
            DiscountType::PERCENTAGE->value => round(($price * $value) / 100, 2),
            DiscountType::FLAT->value => round($value, 2),
            default => null,
        };
    }
}

if (! function_exists('downloadImageFromUrl')) {
    function downloadImageFromUrl(string $url, string $folder, $fileName = null)
    {
        try {
            $response = Http::get($url);

            if (! $response->ok()) {
                return null;
            }

            $imageContent = $response->body();
            $extension    = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);

            $fileName = is_null($fileName) ? Str::uuid() : $fileName;

            $path = "{$folder}/{$fileName}.{$extension}";

            Storage::disk('public')->put($path, $imageContent);

            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (! function_exists('convert_number_to_words_bdt')) {
    function convert_number_to_words_bdt($number)
    {
        $number = (int) $number;

        $words = [
            0  => '',
            1  => 'One',
            2  => 'Two',
            3  => 'Three',
            4  => 'Four',
            5  => 'Five',
            6  => 'Six',
            7  => 'Seven',
            8  => 'Eight',
            9  => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
        ];

        $units = [
            '',
            'Thousand',
            'Lakh',
            'Crore',
        ];

        if ($number == 0) {
            return 'Zero';
        }

        $result = '';

        $numStr = str_pad($number, 9, '0', STR_PAD_LEFT);

        $crore    = (int) substr($numStr, 0, 2);
        $lakh     = (int) substr($numStr, 2, 2);
        $thousand = (int) substr($numStr, 4, 2);
        $hundred  = (int) substr($numStr, 6, 1);
        $rest     = (int) substr($numStr, 7, 2);

        if ($crore) {
            $result .= number_to_words_bdt($crore, $words) . ' Crore ';
        }
        if ($lakh) {
            $result .= number_to_words_bdt($lakh, $words) . ' Lakh ';
        }
        if ($thousand) {
            $result .= number_to_words_bdt($thousand, $words) . ' Thousand ';
        }
        if ($hundred) {
            $result .= $words[$hundred] . ' Hundred ';
        }
        if ($rest) {
            $result .= ($result != '' ? 'and ' : '') . number_to_words_bdt($rest, $words);
        }

        return trim($result);
    }

    function number_to_words_bdt($num, $words)
    {
        if ($num < 21) {
            return $words[$num];
        } else {
            $tens  = ((int) ($num / 10)) * 10;
            $units = $num % 10;
            return $words[$tens] . ($units ? ' ' . $words[$units] : '');
        }
    }
}

if (! function_exists('notify_user')) {
    function notify_user($userId, $title, $message, $targetType = null, $targetId = null, $sendPush = false)
    {
        return \App\Services\NotificationService::send("user_id", $userId, $title, $message, $targetType, $targetId, $sendPush);
    }
}

if (! function_exists('notify_seller')) {
    function notify_seller($sellerID, $title, $message, $targetType = null, $targetId = null, $sendPush = false)
    {
        return \App\Services\NotificationService::send("seller_id", $sellerID, $title, $message, $targetType, $targetId, $sendPush);
    }
}

if (! function_exists('notificationCount')) {
    function notificationCount()
    {
        if (!auth('web')->check() && !auth()->guard('seller')->check()) {
            return 0;
        }
        if (auth()->guard('seller')->check()) {
            return Notification::where('seller_id', auth('seller')->id())->where('is_read', 0)->count();
        }

        if (auth('web')->check()) {
            return Notification::where('user_id', auth('web')->id())->where('is_read', 0)->count();
        }
    }
}

if (! function_exists('affiliate')) {
    function affiliate()
    {
        $user = Auth::guard('web')->user();
        return $user && $user->role == UserRole::AFFILIATE->value;
    }
}

if (! function_exists('get_seller_routes')) {
    function get_seller_routes(): array
    {
        $prefix = "seller.";

        $excludedRoutes = [
            'seller.signup',
            'seller.logout',
            'seller.employees.set_permissions',
        ];

        $routes = [];
        foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
            $routeName = $route->getName();
            if ($routeName != null && str_starts_with($routeName, $prefix) && !in_array($routeName, $excludedRoutes)) {
                $routes[] = $routeName;
            }
        }

        sort($routes);

        $permissions = [];

        foreach ($routes as $route) {
            $title = str_replace($prefix, '', $route);
            $title = ucwords(str_replace('.', ' > ', $title));

            $permissions[] = [
                'name'  => $route,
                'title' => $title,
            ];
        }

        return $permissions;
    }
}

if (! function_exists('get_seller_id')) {
    function get_seller_id()
    {
        if(seller()) {
            return seller()->id;
        }

        return employee()->seller_id;
    }
}

if (!function_exists('calculate_vat')) {
    /**
     * Calculate VAT amount based on percentage and price.
     *
     * @param float $vatPercentage  VAT percentage (e.g., 15 for 15%)
     * @param float $price          Product price (excluding VAT)
     * @return float                VAT amount
     */
    function calculate_vat(float $vatPercentage, float $price): float
    {
        return round(($vatPercentage / 100) * $price, 2);
    }
}
