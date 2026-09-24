<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateDigiflazzWebhook
{
    /**
     * Default list of known Digiflazz Callback IPs.
     */
    protected array $defaultWhitelistedIps = [
        '139.180.220.73',
        '139.180.220.74',
        '149.28.136.108',
        '149.28.150.141',
        '127.0.0.1',
        '::1',
    ];

    /**
     * Handle an incoming Digiflazz webhook callback.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        // 1. IP Whitelisting Check
        $configuredIps = config('services.digiflazz.webhook_ips', env('DIGIFLAZZ_WEBHOOK_IPS', ''));
        $whitelisted = array_filter(array_map('trim', explode(',', (string) $configuredIps)));
        if (empty($whitelisted)) {
            $whitelisted = $this->defaultWhitelistedIps;
        }

        // Allow all IPs only if explicitly disabled in local/testing with wildcard '*'
        if (! in_array('*', $whitelisted, true) && ! in_array($clientIp, $whitelisted, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Client IP '.$clientIp.' is not authorized',
            ], 403);
        }

        // 2. Validate Event Header
        $event = $request->header('X-Digiflazz-Event');
        if (! $event && ! app()->environment('testing')) {
            return response()->json([
                'success' => false,
                'message' => 'Missing X-Digiflazz-Event header',
            ], 403);
        }

        // 3. Optional Secret / HMAC validation if configured
        $secret = config('services.digiflazz.webhook_secret', env('DIGIFLAZZ_WEBHOOK_SECRET', ''));
        $hubSignature = $request->header('X-Hub-Signature');

        if (! empty($secret) && ! empty($hubSignature)) {
            $expectedSignature = 'sha1='.hash_hmac('sha1', $request->getContent(), $secret);
            if (! hash_equals($expectedSignature, $hubSignature)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid webhook signature',
                ], 403);
            }
        }

        return $next($request);
    }
}
