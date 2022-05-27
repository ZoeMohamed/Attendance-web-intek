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
        //
        'api/recive/data',
        'api/reciveV2/data',
        'api/list/attend',
        'api/login',
        '/images/upload/struk',
        '/store/databudget'
    ];
}
