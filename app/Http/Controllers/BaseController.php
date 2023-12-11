<?php

namespace App\Http\Controllers;

use App\Http\Services\FileService;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }
}
