<?php

use App\Http\Classes\ClassesJobs;

if (!function_exists('globalMetrics')) {
    function globalMetrics()
    {


        $globalMetrics= new ClassesJobs();

       return $globalMetrics->globalMetric();

    }
}
