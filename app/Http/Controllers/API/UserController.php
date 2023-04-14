<?php

namespace App\Http\Controllers\API;

use App\Models\Car;
use App\Models\Mark;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function cars()
    {
        $cars = Car::query()
            ->where('published', true)
            ->with(['model', 'model.mark', 'thumbnail'])
            ->get();

        $marks = Mark::query()
            ->whereHas('models', function ($q) {
                return $q->whereHas('cars');
            })
            ->with('models', function($q) {
                return $q->whereHas('cars');
            })
            ->get();

        return response()->json(['cars' => $cars, 'marks' => $marks]);
    }
}
