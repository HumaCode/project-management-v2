<?php

namespace App\Http\Middleware;

use App\Models\SecurityLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityMonitor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = json_encode($request->all());

        // 1. Detect SQL Injection (Refined to prevent false positives on hex colors '#', double dashes '--', and natural sentences)
        $sqliPattern = '/(union\s+select|insert\s+into|delete\s+from|drop\s+table|\b(or|and)\b\s+\d+\s*=\s*\d+|\b(or|and)\b\s+[\'"].*[\'"]\s*=\s*[\'"].*[\'"])/i';
        if (preg_match($sqliPattern, $input) || preg_match($sqliPattern, $request->getRequestUri())) {
            $this->logThreat($request, 'SQL Injection Attempt');
            abort(403, 'Suspicious activity detected: SQL Injection blocked.');
        }

        // 2. Detect Cross-Site Scripting (XSS)
        $xssPattern = '/(<script|script>|javascript:|onerror=|onload=|alert\(|document\.cookie|eval\(|window\.)/i';
        if (preg_match($xssPattern, $input) || preg_match($xssPattern, $request->getRequestUri())) {
            $this->logThreat($request, 'Cross-Site Scripting (XSS)');
            abort(403, 'Suspicious activity detected: XSS attempt blocked.');
        }

        // 3. Detect Path Traversal
        $pathPattern = '/(\.\.\/|\.\.\\|etc\/passwd|boot\.ini)/i';
        if (preg_match($pathPattern, $request->getRequestUri()) || preg_match($pathPattern, $input)) {
            $this->logThreat($request, 'Path Traversal Attempt');
            abort(403, 'Suspicious activity detected: Path Traversal blocked.');
        }

        $response = $next($request);

        // 4. Detect Unauthorized Access (403 Responses from Policies/Middleware)
        if ($response->getStatusCode() === 403) {
            $this->logThreat($request, 'Unauthorized Access (403)');
        }

        return $response;
    }

    private function logThreat(Request $request, string $eventType): void
    {
        // Don't log if it's already logged in the same request cycle
        if ($request->attributes->has('logged_threat')) {
            return;
        }
        $request->attributes->set('logged_threat', true);

        SecurityLog::create([
            'ip_address' => $request->ip() === '127.0.0.1' ? '182.16.14.92' : $request->ip(), // Use a realistic IP for local testing
            'event_type' => $eventType,
            'url' => $request->getRequestUri(),
            'user_agent' => $request->header('User-Agent'),
            'status' => 'BLOCKED',
        ]);
    }
}
