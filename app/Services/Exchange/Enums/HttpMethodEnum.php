<?php

namespace App\Services\Exchange\Enums;

enum HttpMethodEnum:string
{
    case POST = 'post';
    case GET = 'get';
    case PUT = 'put';
    case DELETE = 'delete';
}
