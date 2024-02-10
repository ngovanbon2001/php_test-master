<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use App\Services\Contracts\IDishService;
use Exception;

class DishService implements IDishService
{
    public function __construct()
    {
    }

    public function getLishRes(mixed $request)
    {
        try {
            $jsonPath = public_path('data\dishes.json');

            if (File::exists($jsonPath)) {
                $jsonData = File::get($jsonPath);
                $dishes = json_decode($jsonData, true);

                $collection = collect($dishes["dishes"] ?? []);
                if ($request->input("availableMeals")) {
                    $collection = $collection->filter(function ($item) use ($request) {
                        return in_array($request->input("availableMeals"), $item['availableMeals']);
                    });
                };

                return $collection->all();
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }
    }

    public function getLishDish(mixed $request)
    {
        try {
            $jsonPath = public_path('data\dishes.json');

            if (File::exists($jsonPath)) {
                $jsonData = File::get($jsonPath);
                $dishes = json_decode($jsonData, true);

                $collection = collect($dishes["dishes"] ?? []);
                if ($request->input("availableMeals")) {
                    $collection = $collection->filter(function ($item) use ($request) {
                        return in_array($request->input("availableMeals"), $item['availableMeals']);
                    });
                };

                if ($request->input("restaurant")) {
                    $collection = $collection->where("restaurant", $request->input("restaurant"));
                }
                return $collection->all();
            } else {
                return false;
            }
        } catch (Exception $exception) {
            return false;
        }
    }
}
