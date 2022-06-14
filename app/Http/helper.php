<?php

use App\Http\Classes\ClassesJobs;

// from api
if (!function_exists('globalMetrics')) {
    function globalMetrics()
    {


        try {
            $globalMetrics = new ClassesJobs();
            return $globalMetrics->globalMetric();
        } catch (Exception $exceptione) {
            return $exceptione;
        }

    }
}

// from database
if (!function_exists('databaseGlobalMetrics')) {
    function databaseGlobalMetrics()
    {


        $globalMetrics = new ClassesJobs();

        return $globalMetrics->databaseGlobalMetrics();

    }
}
