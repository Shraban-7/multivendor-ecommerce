<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fintech Checkout - Payment Selection (Radio Buttons)</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for input fields */
        .input-field {
            @apply block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition-colors duration-200 ease-in-out;
        }
        /* Custom styles for selected state */
        .card-selected {
            @apply border-2 border-orange-500 shadow-md; /* Highlight border on selection */
        }
        /* Hide original radio buttons if we were using them */
        .hidden-radio {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="max-w-4xl mx-auto px-4 py-12 md:py-20">

        <!-- Step 1: Select Payment Method -->
        <section class="mb-20">
            <div class="text-center mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">Select a Payment Method</h2>
                <p class="text-sm text-gray-600 max-w-lg mx-auto">
                    Choose one of the available options below to continue your checkout.
                </p>
            </div>

            <!-- Compact Payment Methods Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"> <!-- Even tighter gap -->

                <!-- Payment Method Card: Bkash (Radio-button style) -->
                <div class="relative bg-white rounded-lg border border-gray-200 shadow-sm hover:border-orange-400 hover:shadow-md transition-all duration-300 ease-in-out p-3 flex items-center cursor-pointer group" onclick="selectPaymentMethod('Bkash', '017XXXXXXXX', 'John Doe', 'https://www.logo.wine/a/logo/BKash/BKash-Icon2-Logo.wine.svg', 'Bkash Personal')">
                    <input type="radio" name="paymentMethod" id="bkash" class="hidden-radio">
                    <div class="flex-shrink-0 mr-3"> <!-- Logo container -->
                        <img src="https://www.logo.wine/a/logo/BKash/BKash-Icon2-Logo.wine.svg" alt="Bkash Logo" class="w-10 h-10 object-contain">
                    </div>
                    <div class="flex-grow"> <!-- Text content -->
                        <h3 class="text-base font-semibold text-gray-800 leading-tight">Bkash Personal</h3>
                        <p class="text-gray-600 text-xs leading-tight">Send money to number.</p>
                    </div>
                    <div class="ml-3 flex-shrink-0"> <!-- Status badge -->
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Active
                        </span>
                    </div>
                </div>

                <!-- Payment Method Card: Nagad (Radio-button style) -->
                <div class="relative bg-white rounded-lg border border-gray-200 shadow-sm hover:border-orange-400 hover:shadow-md transition-all duration-300 ease-in-out p-3 flex items-center cursor-pointer group" onclick="selectPaymentMethod('Nagad', '018XXXXXXXX', 'Jane Smith', 'https://www.logo.wine/a/logo/Nagad/Nagad-Logo.wine.svg', 'Nagad Personal')">
                    <input type="radio" name="paymentMethod" id="nagad" class="hidden-radio">
                    <div class="flex-shrink-0 mr-3">
                        <img src="https://www.logo.wine/a/logo/Nagad/Nagad-Logo.wine.svg" alt="Nagad Logo" class="w-10 h-10 object-contain">
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-base font-semibold text-gray-800 leading-tight">Nagad Personal</h3>
                        <p class="text-gray-600 text-xs leading-tight">Send money to number.</p>
                    </div>
                    <div class="ml-3 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Active
                        </span>
                    </div>
                </div>

                <!-- Payment Method Card: Bank Transfer (Radio-button style) -->
                <div class="relative bg-white rounded-lg border border-gray-200 shadow-sm hover:border-orange-400 hover:shadow-md transition-all duration-300 ease-in-out p-3 flex items-center cursor-pointer group" onclick="selectPaymentMethod('Bank Transfer', '1234567890123', 'Fintech Innovations Ltd.', 'https://images.seeklogo.com/logo-png/21/1/bangladesh-bank-logo-png_seeklogo-219689.png', 'Bank: Global Trust Bank<br>Swift: GTBLBDDH')">
                    <input type="radio" name="paymentMethod" id="banktransfer" class="hidden-radio">
                    <div class="flex-shrink-0 mr-3">
                        <img src="https://images.seeklogo.com/logo-png/21/1/bangladesh-bank-logo-png_seeklogo-219689.png" alt="Bank Logo" class="w-10 h-10 object-contain">
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-base font-semibold text-gray-800 leading-tight">Bank Transfer</h3>
                        <p class="text-gray-600 text-xs leading-tight">To our corporate account.</p>
                    </div>
                    <div class="ml-3 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Active
                        </span>
                    </div>
                </div>

                <!-- Card for Unavailable Method (Radio-button style) -->
                <div class="relative bg-white rounded-lg border border-gray-200 shadow-sm p-3 opacity-70 cursor-not-allowed flex items-center">
                    <input type="radio" name="paymentMethod" id="upay" class="hidden-radio" disabled>
                    <div class="flex-shrink-0 mr-3">
                        <img src="https://images.seeklogo.com/logo-png/40/1/upay-logo-png_seeklogo-404483.png" alt="UPay Logo" class="w-10 h-10 object-contain">
                    </div>
                    <div class="flex-grow">
                        <h3 class="text-base font-semibold text-gray-800 leading-tight">UPay</h3>
                        <p class="text-gray-600 text-xs leading-tight">Unavailable.</p>
                    </div>
                    <div class="ml-3 flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Unavailable
                        </span>
                    </div>
                </div>

            </div>
        </section>

        <!-- Step 2: Confirm Payment (Dynamically updated via JS) -->
        <!-- This section will only appear after a valid method is clicked -->
        <section id="payment-form-section" class="hidden bg-white rounded-lg border border-gray-200 shadow-md p-6 sm:p-8">
            <div class="text-center mb-6 sm:mb-8">
                <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">Confirm Your Payment</h2>
                <p class="text-sm text-gray-600">Please provide the details for your <span id="selected-method-name" class="font-medium text-orange-600"></span> payment.</p>
            </div>

            <!-- Selected Method Summary - Dynamically populated -->
            <div id="selected-method-summary" class="flex items-center justify-center sm:justify-start mb-6 p-3 bg-gray-50 rounded-lg border border-gray-100">
                <img id="selected-method-logo" src="" alt="Method Logo" class="w-8 h-8 object-contain mr-3 flex-shrink-0">
                <div class="flex-grow text-center sm:text-left">
                    <p id="selected-method-display-name" class="text-gray-800 font-semibold text-base"></p>
                    <p id="selected-method-details" class="text-gray-600 text-xs"></p>
                </div>
                <button onclick="hidePaymentForm()" class="ml-auto text-sm text-orange-500 hover:text-orange-700 font-medium underline flex-shrink-0">Change</button>
            </div>

            <!-- Payment Proof Form -->
            <form onsubmit="handlePaymentSubmit(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="sender-account" class="block text-sm font-medium text-gray-700 mb-1">Sender Account/Number</label>
                        <input type="text" id="sender-account" placeholder="Your number or bank name" class="input-field" required>
                    </div>
                    <div>
                        <label for="transaction-id" class="block text-sm font-medium text-gray-700 mb-1">Transaction ID</label>
                        <input type="text" id="transaction-id" placeholder="e.g., ABC123XYZ" class="input-field" required>
                    </div>
                    <div>
                        <label for="amount-paid" class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                        <input type="number" id="amount-paid" placeholder="e.g., 1500.00" class="input-field" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="payment-screenshot" class="block text-sm font-medium text-gray-700 mb-1">Upload Screenshot</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="payment-screenshot" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <div class="flex flex-col items-center justify-center pt-3 pb-4">
                                    <svg class="w-6 h-6 mb-2 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A53.86 53.86 0 0 0 16 6.5 59.82 59.82 0 0 0 2 5.923 6.525 6.525 0 0 0 0 10.529 7.4 7.4 0 0 0 2.875 21.278M10 0v12m0 0 4-4m-4 4L6 12"/>
                                    </svg>
                                    <p class="mb-1 text-xs text-gray-500"><span class="font-semibold">Click to upload</span></p>
                                    <p class="text-xs text-gray-500">PNG, JPG (Max 5MB)</p>
                                </div>
                                <input id="payment-screenshot" type="file" class="hidden" accept=".png,.jpg,.jpeg">
                            </label>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea id="notes" rows="2" placeholder="Any additional info." class="input-field resize-none"></textarea>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <button type="submit" class="px-6 py-2 rounded-lg text-base font-semibold text-white bg-orange-500 hover:bg-orange-600 transition-colors duration-300 ease-in-out shadow-sm focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-opacity-50">
                        Submit Proof
                    </button>
                </div>
            </form>

            <p class="mt-8 text-center text-xs text-gray-500">
                We will verify your payment and confirm your order.
            </p>
        </section>

    </div>

    <script>
        const paymentFormSection = document.getElementById('payment-form-section');
        const selectedMethodNameSpan = document.getElementById('selected-method-name');
        const selectedMethodLogoImg = document.getElementById('selected-method-logo');
        const selectedMethodDisplayNameP = document.getElementById('selected-method-display-name');
        const selectedMethodDetailsP = document.getElementById('selected-method-details');

        let previouslySelectedCard = null; // Keep track of the currently selected card element

        function selectPaymentMethod(methodName, accountNumber, accountName, logoUrl, additionalDetails = '') {
            // Update form section details
            selectedMethodNameSpan.textContent = methodName;
            selectedMethodLogoImg.src = logoUrl;
            selectedMethodDisplayNameP.textContent = methodName;

            let detailsText = `Account: ${accountNumber}`;
            if (accountName && accountName !== 'N/A') {
                detailsText += ` (${accountName})`;
            }
            if (additionalDetails) {
                detailsText += `<br>${additionalDetails}`;
            }
            selectedMethodDetailsP.innerHTML = detailsText;

            // Show the form section
            paymentFormSection.classList.remove('hidden');
            paymentFormSection.classList.add('block');

            // Scroll to the form section
            paymentFormSection.scrollIntoView({ behavior: 'smooth' });
        }

        function hidePaymentForm() {
            paymentFormSection.classList.remove('block');
            paymentFormSection.classList.add('hidden');
            // Deselect the card visually
            if (previouslySelectedCard) {
                previouslySelectedCard.classList.remove('card-selected');
                previouslySelectedCard = null;
            }
        }

        function handlePaymentSubmit(event) {
            event.preventDefault();
            alert('Payment proof submitted (simulated)!');
            // In a real app, you'd collect form data and submit it.
        }

        // Event listeners for card selection
        const cards = document.querySelectorAll('.relative.cursor-pointer.group'); // Select only clickable cards
        cards.forEach(card => {
            card.addEventListener('click', (event) => {
                // If the click was on an 'input[type="radio"]' (hidden), do nothing extra
                // This check might not be necessary if the radio is truly hidden and not targetable.
                if (event.target.type === 'radio') {
                    return;
                }

                // Deselect previous card
                if (previouslySelectedCard && previouslySelectedCard !== card) {
                    previouslySelectedCard.classList.remove('card-selected');
                }

                // Toggle selection for the current card
                if (card.classList.contains('card-selected')) {
                    // If already selected, deselect and hide the form
                    card.classList.remove('card-selected');
                    hidePaymentForm();
                    previouslySelectedCard = null;
                } else {
                    // Select this card
                    card.classList.add('card-selected');
                    previouslySelectedCard = card; // Store reference to the selected card

                    // Trigger the data population and form display
                    // We need to get the data from the onclick attribute OR from data attributes on the card
                    // For simplicity, let's re-extract or assume it's accessible.
                    // A better approach is to use `data-*` attributes.

                    // Re-extracting data from the current HTML structure:
                    const methodName = card.getAttribute('onclick').split(',')[0].split("'")[1]; // Get Bkash/Nagad etc.
                    const accountNumber = card.getAttribute('onclick').split(',')[1].split("'")[1];
                    const accountName = card.getAttribute('onclick').split(',')[2].split("'")[1];
                    const logoUrl = card.getAttribute('onclick').split(',')[3].split("'")[1];
                    const additionalDetails = card.getAttribute('onclick').split(',')[4] ? card.getAttribute('onclick').split(',')[4].split("'")[1] : '';

                    selectPaymentMethod(methodName, accountNumber, accountName, logoUrl, additionalDetails);
                }
            });
        });

    </script>

</body>
</html>