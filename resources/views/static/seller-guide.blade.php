@extends('frontend.layouts.app')

@section('content')

<section class="max-w-5xl mx-auto px-6 py-10 text-gray-800">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-orange-500 mb-4">
            🧭 Seller Guide – Your Path to Marketplace Success
        </h1>
        <p class="text-lg text-gray-600">
            A step-by-step handbook to help you set up, sell faster, and stay compliant
            with our marketplace policies.
        </p>
    </div>

    <!-- Goal of the Guide -->
    <div class="bg-orange-50 border-l-4 border-orange-400 rounded-md p-6 mb-10">
        <h2 class="text-2xl font-semibold text-orange-600 mb-2">
            Why This Guide?
        </h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Educate vendors quickly and clearly</li>
            <li>Reduce repetitive support questions</li>
            <li>Set clear expectations from day one</li>
            <li>Encourage early success and confidence</li>
        </ul>
    </div>

    <!-- Section 1 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            👋 1. Welcome to the Marketplace
        </h2>
        <p class="text-gray-700">
            Welcome aboard! As part of our growing vendor community, you’ll gain access
            to a thriving buyer network and flexible tools to manage your business. Our
            goal? Helping you turn your products into profit — faster and easier.
        </p>
        <p class="mt-2 text-gray-700">
            Your store is your brand. Make it shine by customizing your profile and
            offering great customer service.
        </p>
    </div>

    <!-- Section 2 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            🏪 2. Setting Up Your Store
        </h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Choose a unique <strong>Business Name</strong> that reflects your brand.</li>
            <li>Upload your <strong>Logo and Banner</strong> for instant recognition.</li>
            <li>Write a clear <strong>Store Description</strong> — what makes you special?</li>
            <li>Define your <strong>Store Policies</strong> (returns, shipping, etc.).</li>
        </ul>
        <p class="mt-3 text-sm text-gray-600 italic">
            ✅ Tip: Visual branding builds trust. Use consistent colors and styles.
        </p>
    </div>

    <!-- Section 3 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            📦 3. Adding Your First Products
        </h2>
        <p class="text-gray-700 mb-2">
            Add your first products by filling out:
        </p>
        <ul class="list-disc list-inside text-gray-700">
            <li>Product name, description, images, and pricing</li>
            <li>Stock quantity and categories/tags</li>
            <li>Variations (size, color, material, etc.) — if available</li>
        </ul>
        <p class="text-sm text-gray-600 italic mt-2">
            💡 High-quality photos and keyword-rich descriptions boost sales!
        </p>
    </div>

    <!-- Section 4 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            💸 4. Understanding Commission & Payouts
        </h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Standard commission: for example, <strong>10–20%</strong> per sale</li>
            <li>Payout schedule: <strong>weekly or monthly</strong></li>
            <li>Set up your payment info securely (bank or PayPal)</li>
        </ul>
    </div>

    <!-- Section 5 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            🚚 5. Shipping & Order Management
        </h2>
        <p class="text-gray-700">
            Offer clear shipping options and realistic delivery times. Use the Vendor
            Dashboard to fulfill, track, and update orders. Always communicate delays
            proactively — transparency drives loyalty.
        </p>
    </div>

    <!-- Section 6 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            📈 6. Managing Sales & Inventory
        </h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Monitor performance from your dashboard (sales, top products).</li>
            <li>Update stock levels frequently to avoid overselling.</li>
            <li>Handle returns or refunds quickly and professionally.</li>
        </ul>
    </div>

    <!-- Section 7 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            💬 7. Messaging & Customer Communication
        </h2>
        <p class="text-gray-700 mb-2">
            Excellent communication = happy customers. Respond promptly to inquiries,
            reviews, and complaints.
        </p>
        <p class="text-sm text-gray-600 italic">
            💡 Tip: Kind responses turn negative feedback into opportunities.
        </p>
    </div>

    <!-- Section 8 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            🛡️ 8. Vendor Policies & Rules
        </h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>No counterfeit or prohibited items</li>
            <li>Follow platform rules and category guidelines</li>
            <li>Dispute resolution and appeal process</li>
            <li>Policy violations may result in account suspension</li>
        </ul>
    </div>

    <!-- Section 9 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            🚀 9. Tips for Vendor Success
        </h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Use quality visuals and detailed descriptions</li>
            <li>Run occasional promotions or discounts</li>
            <li>Encourage reviews and respond to feedback</li>
            <li>Engage with our social media or featured listings</li>
        </ul>
    </div>

    <!-- Section 10 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">❓ 10. FAQs</h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Approval typically takes 1–2 business days</li>
            <li>Having trouble setting up? Check the Help Center</li>
            <li>Contact our support team anytime for onboarding help</li>
        </ul>
    </div>

    <!-- Section 11 -->
    <div class="mb-12">
        <h2 class="text-2xl font-semibold text-orange-600 mb-3">
            📬 11. Need Help?
        </h2>
        <p class="text-gray-700">
            We’re here for you! Reach us via
            <a href="/contact-support" class="text-orange-600 font-medium">Support Page</a>
            or join the
            <a href="/vendor-community" class="text-orange-600 font-medium">Vendor Community Forum</a>
            for shared tips and advice.
        </p>
    </div>

    <!-- Optional: Tech Tips -->
    <div class="bg-gray-50 border-l-4 border-orange-400 rounded-md p-6">
        <h2 class="text-xl font-semibold text-orange-600 mb-2">🧱 Tech Tips</h2>
        <ul class="list-disc list-inside text-gray-700">
            <li>Ensure image files are under 2MB (JPG or PNG).</li>
            <li>For best results, use desktop for your initial setup.</li>
            <li>If upload fails, try refreshing or reducing file size.</li>
        </ul>
    </div>
</section>

@endsection