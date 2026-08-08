<!-- Thank You and Additional Details Section -->
<div class="thank-you-message mt-5">
    @if (!request()->routeIs('orderAdminDetail'))
        <h3 class="text-center">Next Steps</h3>
        <p class="text-center">
            You will be contacted shortly for the remaining details including the final price, service day(s), service
            time, and more.
        </p>
        <p class="text-center">
            <strong>For Clients Who Selected Cleaning:</strong> Please ensure you have the necessary materials ready,
            including:
        </p>
        <ul class="text-center list-inline">
            <li class="list-inline-item">Mop</li>
            <li class="list-inline-item">Broom</li>
            <li class="list-inline-item">Hand Towels</li>
            <li class="list-inline-item">Detergent</li>
            <li class="list-inline-item">Hypo</li>
            <li class="list-inline-item">Harpic</li>
            <li class="list-inline-item">Water</li>
        </ul>

        <hr>
    @endif

    <h4 class="text-center mt-4">Help Lines</h4>
    <p class="text-center">
        <a href="tel:+2349167696525">+234 916 7696 525</a> |
        <a href="tel:+2347066663868">+234 706 6663 868</a>
    </p>
</div>

<div class="text-center mt-4">
    <a href="/" class="btn btn-secondary">Go to Home</a>

    @if (request()->routeIs('orderAdminDetail'))
        <a class="btn btn-info" href="javascript:history.back()">Go Back</a>
    @endif

    @if (request()->routeIs('orderreceipt'))
        @php
            // Decode serviceName if it's a JSON string
$services = is_string($order->serviceName) ? json_decode($order->serviceName, true) : $order->serviceName;

// Check if $services is an array, and convert it to a comma-separated string
$serviceNames = is_array($services) ? implode(', ', $services) : $services;
        @endphp

        <a href="https://api.whatsapp.com/send?phone=2349167696525&text={{ urlencode('I placed an order with Details: ID: ' . $order->id . ', Service: ' . $serviceNames . ', Grand Total: ₦' . number_format($order->discounted_cost, 2)) }}"
            target="_blank" class="btn btn-success">
            Share to WhatsApp
        </a>
    @endif
    <button class="btn btn-info" onclick="window.print()">Print</button>
</div>
