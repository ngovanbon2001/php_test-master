<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\IDishService;
use Illuminate\Http\Request;

class DishController extends Controller
{
    protected $iDishService;
    public function __construct(IDishService $iDishService)
    {
        $this->iDishService = $iDishService;
    }

    public function getLishDish(Request $request)
    {
        return $this->handleResponse($this->iDishService->getLishDish($request));
    }

    public function getLishRes(Request $request)
    {
        return $this->handleResponse($this->iDishService->getLishRes($request));
    }

    public function order(Request $request)
    {
        dd($request->all());
    }
}
