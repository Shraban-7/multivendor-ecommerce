<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── Orders ───
        $this->addIndexIfMissing('orders', ['invoice_id'], 'orders_invoice_id_index');
        $this->addIndexIfMissing('orders', ['status'], 'orders_status_index');
        $this->addIndexIfMissing('orders', ['payment_status'], 'orders_payment_status_index');
        $this->addIndexIfMissing('orders', ['delivery_status'], 'orders_delivery_status_index');
        $this->addIndexIfMissing('orders', ['affiliate_id'], 'orders_affiliate_id_index');
        $this->addIndexIfMissing('orders', ['seller_employee_id'], 'orders_seller_employee_id_index');
        $this->addIndexIfMissing('orders', ['seller_id', 'status', 'created_at'], 'orders_seller_id_status_created_at_index');
        $this->addIndexIfMissing('orders', ['user_id', 'status', 'created_at'], 'orders_user_id_status_created_at_index');

        // ─── Order Items ───
        $this->addIndexIfMissing('order_items', ['product_variant_id'], 'order_items_product_variant_id_index');

        // ─── Order Billing Addresses ───
        $this->addIndexIfMissing('order_billing_addresses', ['order_id'], 'order_billing_addresses_order_id_index');
        $this->addIndexIfMissing('order_billing_addresses', ['division_id'], 'order_billing_addresses_division_id_index');
        $this->addIndexIfMissing('order_billing_addresses', ['district_id'], 'order_billing_addresses_district_id_index');

        // ─── Order Status Logs ───
        $this->addIndexIfMissing('order_status_logs', ['order_id'], 'order_status_logs_order_id_index');
        $this->addIndexIfMissing('order_status_logs', ['order_item_id'], 'order_status_logs_order_item_id_index');

        // ─── Order Trackings ───
        $this->addIndexIfMissing('order_trackings', ['order_id'], 'order_trackings_order_id_index');
        $this->addIndexIfMissing('order_trackings', ['status_id'], 'order_trackings_status_id_index');
        $this->addIndexIfMissing('order_trackings', ['seller_id'], 'order_trackings_seller_id_index');
        $this->addIndexIfMissing('order_trackings', ['user_id'], 'order_trackings_user_id_index');
        $this->addIndexIfMissing('order_trackings', ['order_id', 'created_at'], 'order_trackings_order_id_created_at_index');

        // ─── Reviews ───
        $this->addIndexIfMissing('reviews', ['product_id'], 'reviews_product_id_index');
        $this->addIndexIfMissing('reviews', ['user_id'], 'reviews_user_id_index');
        $this->addIndexIfMissing('reviews', ['seller_id'], 'reviews_seller_id_index');
        $this->addIndexIfMissing('reviews', ['order_id'], 'reviews_order_id_index');
        $this->addIndexIfMissing('reviews', ['order_item_id'], 'reviews_order_item_id_index');

        // ─── Products ───
        $this->addIndexIfMissing('products', ['status'], 'products_status_index');
        $this->addIndexIfMissing('products', ['subcategory_id'], 'products_subcategory_id_index');
        $this->addIndexIfMissing('products', ['brand_id'], 'products_brand_id_index');
        $this->addIndexIfMissing('products', ['seller_id', 'status', 'created_at'], 'products_seller_id_status_created_at_index');
        $this->addIndexIfMissing('products', ['category_id', 'subcategory_id'], 'products_category_id_subcategory_id_index');

        // ─── Product Variants ───
        $this->addIndexIfMissing('product_variants', ['product_id'], 'product_variants_product_id_index');

        // ─── Product Images ───
        $this->addIndexIfMissing('product_images', ['product_id'], 'product_images_product_id_index');

        // ─── Product Stocks ───
        $this->addIndexIfMissing('product_stocks', ['product_id'], 'product_stocks_product_id_index');
        $this->addIndexIfMissing('product_stocks', ['user_id'], 'product_stocks_user_id_index');
        $this->addIndexIfMissing('product_stocks', ['seller_id'], 'product_stocks_seller_id_index');
        $this->addIndexIfMissing('product_stocks', ['product_variant_id'], 'product_stocks_product_variant_id_index');

        // ─── Carts ───
        $this->addIndexIfMissing('carts', ['user_id'], 'carts_user_id_index');
        $this->addIndexIfMissing('carts', ['seller_id'], 'carts_seller_id_index');
        $this->addIndexIfMissing('carts', ['user_id', 'seller_id'], 'carts_user_id_seller_id_index');

        // ─── Cart Items ───
        $this->addIndexIfMissing('cart_items', ['product_id'], 'cart_items_product_id_index');
        $this->addIndexIfMissing('cart_items', ['product_variant_id'], 'cart_items_product_variant_id_index');
        $this->addIndexIfMissing('cart_items', ['cart_id', 'product_id'], 'cart_items_cart_id_product_id_index');

        // ─── Payments ───
        $this->addIndexIfMissing('payments', ['user_id'], 'payments_user_id_index');
        $this->addIndexIfMissing('payments', ['status'], 'payments_status_index');
        $this->addIndexIfMissing('payments', ['gateway_trxid'], 'payments_gateway_trxid_index');

        // ─── Billing Addresses ───
        $this->addIndexIfMissing('billing_addresses', ['user_id'], 'billing_addresses_user_id_index');
        $this->addIndexIfMissing('billing_addresses', ['division_id'], 'billing_addresses_division_id_index');
        $this->addIndexIfMissing('billing_addresses', ['district_id'], 'billing_addresses_district_id_index');
        $this->addIndexIfMissing('billing_addresses', ['user_id', 'is_default'], 'billing_addresses_user_id_is_default_index');

        // ─── Affiliate ───
        $this->addIndexIfMissing('affiliate_commissions', ['user_id'], 'affiliate_commissions_user_id_index');
        $this->addIndexIfMissing('affiliate_commissions', ['order_id'], 'affiliate_commissions_order_id_index');
        $this->addIndexIfMissing('affiliate_commissions', ['product_id'], 'affiliate_commissions_product_id_index');
        $this->addIndexIfMissing('affiliate_commissions', ['affiliate_id'], 'affiliate_commissions_affiliate_id_index');
        $this->addIndexIfMissing('affiliate_commissions', ['affiliate_id', 'status'], 'affiliate_commissions_affiliate_id_status_index');
        $this->addIndexIfMissing('affiliate_clicks', ['affiliate_id'], 'affiliate_clicks_affiliate_id_index');
        $this->addIndexIfMissing('affiliate_clicks', ['product_id'], 'affiliate_clicks_product_id_index');
        $this->addIndexIfMissing('affiliate_payouts', ['affiliate_id'], 'affiliate_payouts_affiliate_id_index');

        // ─── Sellers ───
        $this->addIndexIfMissing('sellers', ['division_id'], 'sellers_division_id_index');
        $this->addIndexIfMissing('sellers', ['district_id'], 'sellers_district_id_index');
        $this->addIndexIfMissing('sellers', ['status'], 'sellers_status_index');

        // ─── Seller Employees ───
        $this->addIndexIfMissing('seller_employees', ['seller_id'], 'seller_employees_seller_id_index');
        $this->addIndexIfMissing('seller_employees', ['is_active'], 'seller_employees_is_active_index');

        // ─── Seller Chats ───
        $this->addIndexIfMissing('seller_chats', ['seller_id'], 'seller_chats_seller_id_index');
        $this->addIndexIfMissing('seller_chats', ['user_id'], 'seller_chats_user_id_index');

        // ─── Seller Chat Messages ───
        $this->addIndexIfMissing('seller_chat_messages', ['seller_chat_id'], 'seller_chat_messages_seller_chat_id_index');
        $this->addIndexIfMissing('seller_chat_messages', ['seller_id'], 'seller_chat_messages_seller_id_index');
        $this->addIndexIfMissing('seller_chat_messages', ['user_id'], 'seller_chat_messages_user_id_index');

        // ─── Seller Followers ───
        $this->addIndexIfMissing('seller_followers', ['seller_id'], 'seller_followers_seller_id_index');
        $this->addIndexIfMissing('seller_followers', ['user_id'], 'seller_followers_user_id_index');

        // ─── Seller Expenses ───
        $this->addIndexIfMissing('seller_expenses', ['seller_id'], 'seller_expenses_seller_id_index');
        $this->addIndexIfMissing('seller_expenses', ['seller_expense_category_id'], 'seller_expenses_seller_expense_category_id_index');

        // ─── Seller Expense Categories ───
        $this->addIndexIfMissing('seller_expense_categories', ['seller_id'], 'seller_expense_categories_seller_id_index');

        // ─── Seller Banner Images ───
        $this->addIndexIfMissing('seller_banner_images', ['seller_id'], 'seller_banner_images_seller_id_index');

        // ─── Payment Listener ───
        $this->addIndexIfMissing('payment_listener_devices', ['seller_id'], 'payment_listener_devices_seller_id_index');
        $this->addIndexIfMissing('payment_listener_payments', ['seller_id'], 'payment_listener_payments_seller_id_index');

        // ─── Customers ───
        $this->addIndexIfMissing('customers', ['seller_id'], 'customers_seller_id_index');

        // ─── Categories ───
        $this->addIndexIfMissing('categories', ['category_id'], 'categories_category_id_index');
        $this->addIndexIfMissing('categories', ['status'], 'categories_status_index');

        // ─── Category Options ───
        $this->addIndexIfMissing('category_options', ['category_id'], 'category_options_category_id_index');
        $this->addIndexIfMissing('category_options', ['option_id'], 'category_options_option_id_index');

        // ─── Category Banners ───
        $this->addIndexIfMissing('category_banners', ['category_id'], 'category_banners_category_id_index');

        // ─── Category Seller (pivot) ───
        $this->addIndexIfMissing('category_seller', ['seller_id'], 'category_seller_seller_id_index');
        $this->addIndexIfMissing('category_seller', ['category_id'], 'category_seller_category_id_index');

        // ─── Wishlists ───
        $this->addIndexIfMissing('wishlists', ['user_id'], 'wishlists_user_id_index');
        $this->addIndexIfMissing('wishlists', ['product_id'], 'wishlists_product_id_index');
        $this->addIndexIfMissing('wishlists', ['user_id', 'product_id'], 'wishlists_user_id_product_id_index');

        // ─── Districts / Upazilas / Unions ───
        $this->addIndexIfMissing('districts', ['division_id'], 'districts_division_id_index');
        $this->addIndexIfMissing('upazilas', ['district_id'], 'upazilas_district_id_index');
        $this->addIndexIfMissing('unions', ['upazila_id'], 'unions_upazila_id_index');

        // ─── Stock Histories ───
        $this->addIndexIfMissing('stock_histories', ['product_id'], 'stock_histories_product_id_index');
        $this->addIndexIfMissing('stock_histories', ['product_variant_id'], 'stock_histories_product_variant_id_index');

        // ─── POS Carts ───
        $this->addIndexIfMissing('pos_carts', ['user_id'], 'pos_carts_user_id_index');
        $this->addIndexIfMissing('pos_carts', ['seller_id'], 'pos_carts_seller_id_index');
        $this->addIndexIfMissing('pos_carts', ['order_id'], 'pos_carts_order_id_index');
        $this->addIndexIfMissing('pos_carts', ['seller_id', 'is_draft', 'created_at'], 'pos_carts_seller_id_is_draft_created_at_index');

        // ─── POS Cart Items ───
        $this->addIndexIfMissing('pos_cart_items', ['pos_cart_id'], 'pos_cart_items_pos_cart_id_index');
        $this->addIndexIfMissing('pos_cart_items', ['product_id'], 'pos_cart_items_product_id_index');
        $this->addIndexIfMissing('pos_cart_items', ['product_variant_id'], 'pos_cart_items_product_variant_id_index');

        // ─── Review Images ───
        $this->addIndexIfMissing('review_images', ['review_id'], 'review_images_review_id_index');

        // ─── Report Reviews ───
        $this->addIndexIfMissing('report_reviews', ['user_id'], 'report_reviews_user_id_index');
        $this->addIndexIfMissing('report_reviews', ['seller_id'], 'report_reviews_seller_id_index');
        $this->addIndexIfMissing('report_reviews', ['review_id'], 'report_reviews_review_id_index');

        // ─── Users ───
        $this->addIndexIfMissing('users', ['division_id'], 'users_division_id_index');
        $this->addIndexIfMissing('users', ['district_id'], 'users_district_id_index');

        // ─── Admins ───
        $this->addIndexIfMissing('admins', ['role_id'], 'admins_role_id_index');

        // ─── Verification Codes ───
        $this->addIndexIfMissing('verification_codes', ['user_id'], 'verification_codes_user_id_index');

        // ─── Option Values ───
        $this->addIndexIfMissing('option_values', ['option_id'], 'option_values_option_id_index');
    }

    public function down(): void
    {
        // Indexes are additive; drop individually only when needed during targeted rollbacks.
    }

    /**
     * @param  array<int, string>  $columns
     */
    private function addIndexIfMissing(string $table, array $columns, string $index): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return;
            }
        }

        if (Schema::hasIndex($table, $index)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $index) {
            $blueprint->index($columns, $index);
        });
    }
};
