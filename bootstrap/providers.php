<?php

use App\Providers\AppServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

return [
    AppServiceProvider::class,
    ExcelServiceProvider::class,
    PermissionServiceProvider::class
];
