<?php

use App\Providers\AppServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use IcehouseVentures\LaravelChartjs\Providers\ChartjsServiceProvider;

return [
    AppServiceProvider::class,
    ExcelServiceProvider::class,
    PermissionServiceProvider::class,
    ChartjsServiceProvider::class
];