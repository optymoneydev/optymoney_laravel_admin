<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "/augmont/orderResponse",
        "/augmont/subscriptionCharged",
        "/augmont/sipOrderResponse",
        "/augmont/paymentAuthorized",
        "/augmont/subscriptionActivated",
        "/augmont/subscriptionAuthenticated",
        "/augmont/subscriptionStatus",
        "/augmont/paymentFailed",
        "/augmont/paymentCaptured",
        "/augmont/invoiceEvents"
    ];
}
