<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateTripayWebhookSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header('X-Callback-Signature');
        if (! $signature) {
            return response()->json([
                'success' => false,
                'message' => 'Missing X-Callback-Signature header',
            ], 403);
        }

        $privateKey = config('services.tripay.private_key') ?? env('TRIPAY_PRIVATE_KEY', '');
        $rawJson = $request->getContent();

        if (! empty($privateKey)) {
            $expectedSignature = hash_hmac('sha256', $rawJson, $privateKey);
            if (! hash_equals($expectedSignature, $signature)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid webhook signature',
                ], 403);
            }
        }

        return $next($request);
    }
}
