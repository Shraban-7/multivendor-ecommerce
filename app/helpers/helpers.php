<?php

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

define('CURRENCY_SYMBOL', '$');

if (!function_exists('redirect_intended')) {
    function redirect_intended($default = '/')
    {
        return redirect()->intended($default);
    }
}

if (!function_exists('str_slug')) {
    function str_slug($table, $column, $title, $separator = '-')
    {
        $slug = Str::slug($title, $separator);
        $originalSlug = $slug;
        $count = 1;

        while (DB::table($table)->where($column, $slug)->exists()) {
            $slug = "{$originalSlug}{$separator}{$count}";
            $count++;
        }

        return $slug;
    }
}


if (!function_exists('generateFileName')) {
    function generateFileName($file)
    {
        return time() . rand(1, 9999) . '.' . $file->extension();
    }
}

if (!function_exists('upload_file')) {
    function upload_file($file, $directory, $disk = 'public')
    {
        if (!Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->makeDirectory($directory);
        }

        $fileName =  time() . rand(1, 9999) . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $fileName;
        Storage::disk($disk)->put($path, File::get($file));

        return $path;
    }
}

if (!function_exists('storage_url')) {
    function storage_url($file, $disk = 'public')
    {
        return Storage::disk($disk)->url($file);
    }
}

if (!function_exists('delete_file')) {
    function delete_file($file)
    {
        Storage::disk('public')->delete($file);
    }
}

if (!function_exists('nav_categories')) {
    function nav_categories()
    {
        return Category::nav()->orderBy('id', 'DESC')->get();
    }
}

if (!function_exists('all_department_categories')) {
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

if (!function_exists('currency')) {
    function currency($value)
    {
        return Number::currency($value);
    }
}

if (!function_exists('number_shorten_format')) {
    function number_shorten_format($number, $precision = 1, $divisors = null)
    {
        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '',
                pow(1000, 1) => 'K',
                pow(1000, 2) => 'M',
                pow(1000, 3) => 'B',
            );
        }
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                break;
            }
        }
        if($number<= 1000)
        {
            $precision = 0;
        }
        return number_format($number / $divisor, $precision) . $shorthand;
    }
}

if (!function_exists('datetime_format')) {
    function datetime_format($time)
    {
        $carbonTime = Carbon::parse($time);
        $days = $carbonTime->diffInDays(Carbon::now()) > 0 ? $carbonTime->diffInDays(Carbon::now()) . ':' : '';
        $formattedTime = substr($carbonTime->format('H:i:s.u'), 0, -3);
        return $days . $formattedTime;
    }
}

if (!function_exists('percentage')) {
    function percentage($number)
    {
        return Number::percentage($number);
    }
}


