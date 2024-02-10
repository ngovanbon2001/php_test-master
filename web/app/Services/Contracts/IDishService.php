<?php

namespace App\Services\Contracts;

interface IDishService
{
    public function getLishRes(mixed $request);
    public function getLishDish(mixed $request);
}