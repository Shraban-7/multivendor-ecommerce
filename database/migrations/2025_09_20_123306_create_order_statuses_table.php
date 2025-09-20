<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $this->seedOrderStatuses();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }

    private function seedOrderStatuses(): void
    {
        $statuses = [
            [
                'name' => 'Order Placed',
                'description' => 'Order has been placed by customer',
                'is_active' => true
            ],
            [
                'name' => 'Order Confirmed',
                'description' => 'Order has been confirmed by seller',
                'is_active' => true
            ],
            [
                'name' => 'Processing',
                'description' => 'Order is being processed',
                'is_active' => true
            ],
            [
                'name' => 'Shipped',
                'description' => 'Order has been shipped',
                'is_active' => true
            ],
            [
                'name' => 'Out for Delivery',
                'description' => 'Order is out for delivery',
                'is_active' => true
            ],
            [
                'name' => 'Delivered',
                'description' => 'Order has been delivered',
                'is_active' => true
            ],
            [
                'name' => 'Cancelled',
                'description' => 'Order has been cancelled',
                'is_active' => true
            ],
            [
                'name' => 'Returned',
                'description' => 'Order has been returned',
                'is_active' => true
            ],
            [
                'name' => 'Refunded',
                'description' => 'Order amount has been refunded',
                'is_active' => true
            ],
        ];

        DB::table('order_statuses')->insert($statuses);
    }
};
