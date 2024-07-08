<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:create-dummy-ticket')->everyMinute();
Schedule::command('app:process-ticket')->everyFiveMinutes();