<?php

use Carbon\Carbon;

if (!function_exists('now_np')) {
    function now_np()
    {
        return Carbon::now('Asia/Kathmandu');
    }
}
