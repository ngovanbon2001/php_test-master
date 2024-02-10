<?php

namespace App\Http\Controllers;

use App\Service\Contract\IExample;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public $iExample;

    public function __construct(IExample $iExample)
    {
        $this->iExample = $iExample;
    }

    public function index(Request $request)
    {
        $request->validate([
            'username' => 'required',
        ]);
        return $this->iExample->getAll();
    }
}
