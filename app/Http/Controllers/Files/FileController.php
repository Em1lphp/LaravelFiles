<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Files\StoreRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FileController extends BaseController
{
    public function index(): View
    {
        return view('files.upload');
    }


    public function store(Request $request)
    {
        $this->service->fileStore($request);
        return redirect()->route('file.index');
    }


}
