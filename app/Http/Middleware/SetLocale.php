<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 获取请求头中的 Accept-Language 值
        $locale = $request->header('Accept-Language', 'en'); // 默认为 'en'

        // 定义前端语言与后端语言的映射
        $localeMap = [
            'zhHant' => 'zh-Hant',
            'zhHans' => 'zh-Hans',
            // 这里可以添加更多的映射
        ];

        // 将前端语言映射为后端语言
        $locale = $localeMap[$locale] ?? $locale;

        // 设置 Laravel 应用的语言环境
        App::setLocale($locale);

        // 继续处理请求
        return $next($request);
    }
}