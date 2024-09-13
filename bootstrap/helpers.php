<?php

if (!function_exists('dd')) {
    /**
     * Die and Dump function to output and stop the script.
     * Try to simulate the Laravel's dd() function.
     */
    function dd(mixed ...$variables) {
        foreach ($variables as $variable) {
            error_log(print_r($variable, true));
        }

        die(1);
    }
}
