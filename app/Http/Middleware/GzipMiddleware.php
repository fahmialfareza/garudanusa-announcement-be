<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GzipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if the client accepts Gzip encoding
        if (stripos($request->header('Accept-Encoding'), 'gzip') !== false) {
            // Compress the content
            $content = gzencode($response->getContent(), 9);

            // Set the appropriate Gzip headers
            $response->header('Content-Encoding', 'gzip');
            $response->header('Content-Length', strlen($content));

            // Set the compressed content to the response
            $response->setContent($content);
        }

        return $response;
    }
}
