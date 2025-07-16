<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class lang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale'); // يجيب القيمة من رابط الراوت مثل 'ar' أو 'en'

        // فقط تعيين اللغة إذا كانت من اللغات المدعومة
        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        } else {
            // يمكن تعيين لغة افتراضية
            app()->setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
