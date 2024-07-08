<?php

namespace App\Http\Controllers;

use App\Services\StatsService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatsController extends Controller
{
    public function index(StatsService $service)
    {
        $stats = $service->calculate();

        return new JsonResource($stats);
    }
}
