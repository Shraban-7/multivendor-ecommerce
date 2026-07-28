<?php

use App\Domain\Affiliate\Providers\AffiliateServiceProvider;
use App\Domain\Bundle\Providers\BundleServiceProvider;
use App\Domain\BulkUpload\Providers\BulkUploadServiceProvider;
use App\Domain\Media\Providers\MediaServiceProvider;
use App\Domain\Tax\Providers\TaxServiceProvider;
use App\Domain\Order\Providers\OrderServiceProvider;
use App\Domain\Payment\Providers\PaymentServiceProvider;
use App\Domain\Product\Providers\ProductServiceProvider;
use App\Domain\Review\Providers\ReviewServiceProvider;
use App\Domain\Shipping\Providers\ShippingServiceProvider;
use App\Domain\Vendor\Providers\VendorServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    EventServiceProvider::class,
    ShippingServiceProvider::class,
    VendorServiceProvider::class,
    ProductServiceProvider::class,
    ReviewServiceProvider::class,
    OrderServiceProvider::class,
    PaymentServiceProvider::class,
    MediaServiceProvider::class,
    AffiliateServiceProvider::class,
    TaxServiceProvider::class,
    BulkUploadServiceProvider::class,
    BundleServiceProvider::class,
];
