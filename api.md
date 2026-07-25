# Complete API Specification & Route Reference

**Project:** Multi-Vendor E-Commerce Platform  
**Generated:** July 25, 2026  
**Document Version:** 1.0.0  

---

## Table of Contents

1. [Overview & Standards](#1-overview--standards)
2. [User (Customer) Panel APIs](#2-user-customer-panel-apis)
   - [Authentication](#21-authentication)
   - [Catalog & Public Storefront](#22-catalog--public-storefront)
   - [Cart Management](#23-cart-management)
   - [Order & Review Management](#24-order--review-management)
   - [Customer Profile & Address Book](#25-customer-profile--address-book)
   - [Notifications & Seller Chat](#26-notifications--seller-chat)
3. [Seller (Vendor) Panel Endpoints](#3-seller-vendor-panel-endpoints)
   - [Authentication & Account](#31-seller-authentication--account)
   - [Dashboard & Reports](#32-seller-dashboard--reports)
   - [Product & Inventory Management](#33-product--inventory-management)
   - [Order Processing & Fulfillment](#34-order-processing--fulfillment)
   - [Point of Sale (POS) & Sales](#35-point-of-sale-pos--sales)
   - [Staff & Employee Management](#36-staff--employee-management)
   - [Expenses, Plans & Settings](#37-expenses-plans--settings)
4. [Admin Panel Administrative Endpoints](#4-admin-panel-administrative-endpoints)
   - [Admin Auth & Profile](#41-admin-auth--profile)
   - [Customer & User Administration](#42-customer--user-administration)
   - [Roles & Permissions (RBAC)](#43-roles--permissions-rbac)
   - [System Admins Management](#44-system-admins-management)
   - [Settings, Media & Static Content](#45-settings-media--static-content)
5. [Webhook & Integration Services](#5-webhook--integration-services)
   - [Payment Listener Devices](#51-payment-listener-devices)
   - [Mobipay Gateway Webhook](#52-mobipay-gateway-webhook)
6. [Response Formats & Error Handling](#6-response-formats--error-handling)

---

## 1. Overview & Standards

### Base URLs
- **REST API Base URL:** `https://your-domain.com/api`
- **Seller Panel Base URL:** `https://your-domain.com/seller`
- **Admin Panel Base URL:** `https://your-domain.com/admin`

### Common Request Headers
```http
Accept: application/json
Content-Type: application/json
Authorization: Bearer <sanctum_token>  # Required for protected API endpoints
X-CSRF-TOKEN: <csrf_token>             # Required for web/session-authenticated POST/PUT/DELETE
```

---

## 2. User (Customer) Panel APIs

### 2.1 Authentication
**Prefix:** `/api/auth`

| Method | Endpoint | Description | Middleware | Request Body |
|---|---|---|---|---|
| `POST` | `/api/auth/check-phone` | Check if customer phone number exists | `guest` | `{"phone": "01700000000"}` |
| `POST` | `/api/auth/verify-otp` | Verify OTP code sent via SMS | `guest` | `{"phone": "01700000000", "code": "1234"}` |
| `POST` | `/api/auth/login` | Authenticate customer with phone/password | `guest` | `{"phone": "01700000000", "password": "secret"}` |
| `POST` | `/api/auth/register` | Register new customer account | `guest` | `{"name": "Jane", "phone": "01700000000", "password": "secret"}` |
| `POST` | `/api/logout` | Revoke active Sanctum token | `auth:sanctum` | Header token required |

### 2.2 Catalog & Public Storefront
**Prefix:** `/api`

| Method | Endpoint | Description | Query/Params | Response |
|---|---|---|---|---|
| `GET` | `/api/settings` | Retrieve public website configuration | None | `SettingResource` |
| `GET` | `/api/categories` | Get category hierarchy (top-level + subcategories) | None | `CategoryResource` collection |
| `GET` | `/api/dashboard` | Get homepage banners, brands, featured & trending products | None | Composite dashboard payload |
| `POST` | `/api/search` | Search products and vendor shops by keyword | `{"keyword": "phone"}` | Search result array |
| `GET` | `/api/products` | Paginated product listing with filters | `category_id`, `subcategory_id`, `seller_id`, `name`, `sort_by`, `limit` | `ProductListResource` collection |
| `GET` | `/api/products/{product}` | Detailed view of single product, seller & reviews | Route parameter: product ID/slug | `ProductResource`, `SellerResource`, `ReviewResource` |
| `GET` | `/api/sellers` | List approved seller shops | `page`, `limit` | `SellerResource` collection |
| `GET` | `/api/sellers/{seller}` | Detailed seller profile & seller product categories | Route parameter: seller ID | `SellerResource` & `CategoryResource` |
| `GET` | `/api/shops` | General storefront filter reference data (categories & brands) | None | Categories & Brands payload |
| `GET` | `/api/data/divisions` | List shipping divisions | None | `DivisionResource` collection |
| `GET` | `/api/data/districts` | List districts by division ID | `division_id=1` | `DistrictResource` collection |
| `GET` | `/api/data/payment-gateways` | List active payment gateways | None | `PaymentGatewayResource` collection |

### 2.3 Cart Management
**Prefix:** `/api/cart`  
**Authentication:** `auth:sanctum`

| Method | Endpoint | Description | Request Body / Query |
|---|---|---|---|
| `GET` | `/api/cart` | Retrieve current user's cart grouped by seller | None |
| `GET` | `/api/cart/suggestions` | Get recommended products based on cart items | `page=1` |
| `POST` | `/api/cart/store` | Add item/variant to cart | `{"product_id": 1, "variant_id": 2, "quantity": 1, "is_default": false}` |
| `POST` | `/api/cart/items/{item}/delete` | Delete item from cart | Route parameter: cart_item ID |
| `POST` | `/api/cart/items/{item}/update-quantity` | Update quantity of a cart item | `{"quantity": 3}` |

### 2.4 Order & Review Management
**Prefix:** `/api/orders`, `/api/reviews`  
**Authentication:** `auth:sanctum`

| Method | Endpoint | Description | Request Payload |
|---|---|---|---|
| `GET` | `/api/orders` | List user order history | `status=pending/completed` |
| `POST` | `/api/orders/store` | Place order for selected seller's cart | `{"seller_id": 1, "billing_address_id": 5}` |
| `GET` | `/api/orders/{order}` | View order details | Route parameter: order ID |
| `GET` | `/api/orders/{order}/invoice` | Get formatted invoice data | Route parameter: order ID |
| `POST` | `/api/orders/{order}/pay-now` | Re-initiate payment gateway redirect | Route parameter: order ID |
| `POST` | `/api/reviews/store` | Submit product review & rating for order item | `{"order_item_id": 10, "rating": 5, "description": "Great!", "images": []}` |

### 2.5 Customer Profile & Address Book
**Prefix:** `/api/profile`, `/api/billing-addresses`  
**Authentication:** `auth:sanctum`

| Method | Endpoint | Description | Request Payload |
|---|---|---|---|
| `GET` | `/api/profile` | Get logged-in user profile details | None |
| `POST` | `/api/profile` | Update customer account profile | `{"name": "Jane", "email": "jane@example.com"}` |
| `GET` | `/api/billing-addresses` | List customer billing/shipping addresses | None |
| `POST` | `/api/billing-addresses/store` | Save new billing address | `{"customer_name": "Jane", "customer_phone": "017...", "division_id": 1, "district_id": 2, "address": "Road 1", "type": 1, "is_default": 1}` |
| `POST` | `/api/billing-addresses/{address}/update` | Update existing billing address | Route parameter + address fields |

### 2.6 Notifications & Seller Chat
**Authentication:** `auth:sanctum`

| Method | Endpoint | Description | Middleware / Query |
|---|---|---|---|
| `GET` | `/api/notifications/count` | Get unread notification counter | `auth:sanctum` |
| `GET` | `/api/notifications` | Get paginated user notifications | `auth:sanctum`, `markReadAuto` |
| `GET` | `/api/chat/messages` | Get chat messages with seller | `auth:sanctum`, `seller_id=1` |
| `POST` | `/api/chat/send` | Send direct message to seller | `auth:sanctum`, `{"seller_id": 1, "message": "Hi"}` |

---

## 3. Seller (Vendor) Panel Endpoints

### 3.1 Seller Authentication & Account
**Prefix:** `/seller`

| Method | Endpoint | Description | Middleware |
|---|---|---|---|
| `POST` | `/seller/logout` | Terminate seller session | `seller` |
| `GET` \| `POST` | `/seller/profile` | View or update seller business profile | `seller` |
| `GET` | `/seller/profile-info/{username}` | View public business info preview | `seller` |
| `GET` | `/seller/profile-info/update` | Update profile information | `seller` |

### 3.2 Seller Dashboard & Reports
**Prefix:** `/seller`

| Method | Endpoint | Description | Middleware |
|---|---|---|---|
| `GET` | `/seller/dashboard` | Main vendor dashboard with sales, orders & metrics | `seller` |
| `GET` | `/seller/reports/overview` | High-level sales and operational overview | `seller` |
| `GET` | `/seller/reports/financial` | Financial report (revenue, profit margin, product costs) | `seller` |
| `GET` | `/seller/reports/sales` | Period-based sales performance analytics | `seller` |
| `GET` | `/seller/reports/customers` | Customer acquisition & repeat buyer report | `seller` |

### 3.3 Product & Inventory Management
**Prefix:** `/seller/products`, `/seller/stock`

| Method | Endpoint | Description | Middleware |
|---|---|---|---|
| `GET` | `/seller/products` | List seller products with category & variant status | `seller` |
| `GET` | `/seller/products/create` | Render product creation form | `seller` |
| `POST` | `/seller/products/store` | Create new product with variants & images | `seller` |
| `GET` | `/seller/products/{slug}/edit` | Render product edit form | `seller` |
| `POST` | `/seller/products/{slug}/update` | Update product details & pricing | `seller` |
| `POST` | `/seller/products/{slug}/update-seo` | Update product search engine meta tags | `seller` |
| `POST` | `/seller/products/{product}/stock-update` | Adjust stock level directly (ADD / REMOVE / SET) | `seller` |
| `DELETE` | `/seller/products/delete-variant/{variant}` | Delete specific product variant | `seller` |
| `POST` | `/seller/products/images/upload` | Upload additional gallery images | `seller` |
| `DELETE` | `/seller/products/images/{image}/delete` | Delete gallery image | `seller` |
| `DELETE` | `/seller/products/{product}/delete` | Remove product | `seller` |
| `GET` | `/seller/products/inventory` | Inventory tracking list | `seller` |
| `GET` | `/seller/products/print-barcode` | Barcode label generation view | `seller` |
| `GET` | `/seller/products/print-labels` | Print barcode labels for product/SKU | `seller` |
| `GET` | `/seller/stock/history` | Log of all stock adjustments | `seller` |
| `POST` | `/seller/stock/update` | Bulk update stock quantities | `seller` |

### 3.4 Order Processing & Fulfillment
**Prefix:** `/seller/orders`

| Method | Endpoint | Description | Status Filter |
|---|---|---|---|
| `GET` | `/seller/orders` | All vendor orders | All |
| `GET` | `/seller/orders/pending` | Pending orders awaiting approval | Pending |
| `GET` | `/seller/orders/shipped` | Orders in shipping transit | Shipped |
| `GET` | `/seller/orders/delivered` | Delivered orders | Delivered |
| `GET` | `/seller/orders/cancelled` | Cancelled orders | Cancelled |
| `GET` | `/seller/orders/refunded` | Refunded orders | Refunded |
| `GET` | `/seller/orders/returned` | Returned orders | Returned |
| `GET` | `/seller/orders/pos-orders` | POS-placed store orders | POS |
| `GET` | `/seller/orders/{invoice_id}/details` | Full invoice and item breakdown | Route param |
| `POST` | `/seller/orders/{order}/update-status` | Update order status and log state | Route param |

### 3.5 Point of Sale (POS) & Sales
**Prefix:** `/seller/pos`

| Method | Endpoint | Description | Action |
|---|---|---|---|
| `GET` | `/seller/pos` | Render POS terminal interface | Index |
| `POST` | `/seller/pos/cart-add` | Add product to POS terminal cart | Cart Add |
| `POST` | `/seller/pos/cart/update` | Update POS cart item quantity/discount | Cart Update |
| `POST` | `/seller/pos/cart-item/remove` | Remove item from POS cart | Item Remove |
| `POST` | `/seller/pos/cart-clear` | Empty current POS cart | Cart Clear |
| `POST` | `/seller/pos/place-order` | Process instant POS order | Checkout |
| `POST` | `/seller/pos/save-draft` | Save POS cart as pending draft | Draft |
| `POST` | `/seller/pos/draft-clear/{draft}` | Delete saved POS draft | Clear Draft |
| `GET` | `/seller/pos/customers/search` | Quick lookup customer for POS sale | Search |
| `GET` | `/seller/pos/sales` | List all POS walk-in sales records | Sales List |
| `POST` | `/seller/pos/sales/{order}/pay` | Collect payment for unpaid POS order | Pay |

### 3.6 Staff & Employee Management
**Prefix:** `/seller/employees`

| Method | Endpoint | Description | Middleware |
|---|---|---|---|
| `GET` | `/seller/employees` | List shop employees | `seller` |
| `GET` | `/seller/employees/create` | Render add employee form | `seller` |
| `POST` | `/seller/employees/store` | Save employee account | `seller` |
| `GET` | `/seller/employees/sales-report` | Individual employee sales contributions | `seller` |
| `GET` | `/seller/employees/{id}/edit` | Edit employee account | `seller` |
| `POST` | `/seller/employees/{id}/update` | Update employee information | `seller` |
| `POST` | `/seller/employees/{id}/toggle-active` | Enable/disable employee account | `seller` |
| `POST` | `/seller/employees/{employee}/set-permissions` | Assign permission routes | `seller` |

### 3.7 Expenses, Plans & Settings
**Prefix:** `/seller`

| Method | Endpoint | Description | Section |
|---|---|---|---|
| `GET` \| `POST` | `/seller/expenses` | List or store operational business expenses | Expenses |
| `POST` | `/seller/expenses/{expense}/update` | Update expense record | Expenses |
| `POST` | `/seller/expenses/{expense}/destroy` | Delete expense record | Expenses |
| `GET` | `/seller/plans` | Subscription plans list | Subscriptions |
| `POST` | `/seller/plans/{plan}/subscribe` | Subscribe to vendor plan | Subscriptions |
| `GET` \| `POST` | `/seller/settings` | Vendor shop configuration | Settings |
| `POST` | `/seller/banner-image/{image}` | Delete shop banner image | Settings |
| `GET` | `/seller/notifications` | Seller panel notifications | Notifications |
| `GET` | `/seller/customers` | Seller customer list | Customers |
| `GET` \| `POST` | `/seller/chat/*` | Chat threads and messaging | Customer Service |

---

## 4. Admin Panel Administrative Endpoints

### 4.1 Admin Auth & Profile
**Prefix:** `/admin`

| Method | Endpoint | Description | Middleware |
|---|---|---|---|
| `GET` \| `POST` | `/admin/signup` | Admin registration (if enabled) | `guest` |
| `GET` \| `POST` | `/admin/login` | System administrator login | `guest` |
| `POST` | `/admin/logout` | Logout admin session | `admin` |
| `GET` | `/admin/dashboard` | Main system administration metrics | `admin` |
| `GET` \| `POST` | `/admin/profile` | View or update admin profile | `admin` |

### 4.2 Customer & User Administration
**Prefix:** `/admin/customers`  
**Middleware:** `admin`

| Method | Endpoint | Description | Payload / Action |
|---|---|---|---|
| `GET` | `/admin/customers` | List system customer accounts | Filter / Paginate |
| `POST` | `/admin/customers/update` | Update customer account status or details | Account Update |
| `GET` | `/admin/customers/{customer:username}/profile` | View detailed customer activity profile | Customer Overview |

### 4.3 Roles & Permissions (RBAC)
**Prefix:** `/admin/roles`  
**Middleware:** `admin`

| Method | Endpoint | Description | Action |
|---|---|---|---|
| `GET` | `/admin/roles` | List all system roles and permission sets | Index |
| `POST` | `/admin/roles/store` | Create new role with permission matrix | Store |
| `GET` | `/admin/roles/{role}/edit` | Render role edit view | Edit |
| `POST` | `/admin/roles/{role}/update` | Update role permissions | Update |

### 4.4 System Admins Management
**Prefix:** `/admin/admins`  
**Middleware:** `admin`

| Method | Endpoint | Description | Action |
|---|---|---|---|
| `GET` | `/admin/admins` | List administrative users | Index |
| `GET` | `/admin/admins/add` | Render form to add admin | Add |
| `POST` | `/admin/admins/store` | Save new admin user | Store |
| `GET` | `/admin/admins/{admin}/edit` | Edit admin user details | Edit |
| `POST` | `/admin/admins/{admin}/update` | Update admin user profile/role | Update |
| `DELETE` | `/admin/admins/{admin}/delete` | Delete admin user | Delete |

### 4.5 Settings, Media & Static Content
**Prefix:** `/admin`  
**Middleware:** `admin`

| Method | Endpoint | Description | Section |
|---|---|---|---|
| `GET` \| `POST` | `/admin/settings/social-links` | Manage footer social media links | Social Links |
| `POST` | `/admin/settings/social-links/update/{socialLink}` | Update specific social media link | Social Links |
| `GET` \| `POST` | `/admin/settings` | System-wide configuration settings | Settings |
| `GET` \| `POST` | `/admin/images` | Media library browser and file upload | Media Manager |
| `DELETE` | `/admin/images/delete-all` | Batch remove media items | Media Manager |
| `POST` | `/admin/images/cropped-image` | Process and store cropped image | Media Manager |
| `DELETE` | `/admin/images/delete-cropped-image` | Delete cropped image asset | Media Manager |
| `GET` \| `POST` | `/admin/static-pages` | Create and manage custom static pages | Page Builder |
| `GET` \| `PUT` | `/admin/static-pages/{slug}/edit` | Edit static page content | Page Builder |

---

## 5. Webhook & Integration Services

### 5.1 Payment Listener Devices
**Prefix:** `/api/payment-listener` (or `/seller/payment-listener`)

| Method | Endpoint | Purpose | Payload |
|---|---|---|---|
| `POST` | `/api/payment-listener/connect` | Register/pair local SMS listener device | `{"code": "XYZ123", "device_name": "Android-POS"}` |
| `POST` | `/api/payment-listener/check-device` | Heartbeat & connection check | `{"device_id": 1}` |
| `POST` | `/api/payment-listener/disconnect` | Unpair listener device | `{"device_id": 1}` |
| `POST` | `/api/payment-listener/trigger` | Ingest parsed payment SMS notification | `{"device_id": 1, "sender": "bkash", "message": "..."}` |

### 5.2 Mobipay Gateway Webhook
**Prefix:** `/api/mobipay`

| Method | Endpoint | Purpose | Handling |
|---|---|---|---|
| `GET` \| `POST` | `/api/mobipay/webhook` | Automatic payment gateway notification callback | Verifies hash, updates order status to COMPLETED |
| `GET` \| `POST` | `/api/mobipay/store` | Direct SMS backup ingestion endpoint | Process SMS logs |

---

## 6. Response Formats & Error Handling

### Standard Success Response (`200 OK`)
```json
{
  "status": true,
  "message": "Operation completed successfully",
  "data": {
    "id": 1,
    "name": "Sample Product"
  }
}
```

### Standard Paginated Resource Response (`200 OK`)
```json
{
  "status": true,
  "data": [
    { "id": 1, "name": "Item 1" }
  ],
  "links": {
    "first": "https://domain.com/api/products?page=1",
    "last": "https://domain.com/api/products?page=10",
    "prev": null,
    "next": "https://domain.com/api/products?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 10,
    "per_page": 15,
    "to": 15,
    "total": 150
  }
}
```

### Validation Error Response (`422 Unprocessable Entity`)
```json
{
  "status": false,
  "message": "Validation Error",
  "errors": {
    "phone": [
      "The phone field is required."
    ]
  }
}
```

### Authentication / Rate Limit Error Responses
- **`401 Unauthorized`**: Missing or invalid Sanctum bearer token.
- **`403 Forbidden`**: User or seller lacks permission for the resource.
- **`429 Too Many Requests`**: Rate limit threshold exceeded. Retry after seconds specified in `Retry-After` header.
