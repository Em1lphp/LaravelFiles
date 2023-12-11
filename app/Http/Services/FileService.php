<?php

namespace App\Http\Services;

use App\Models\File;
use Illuminate\Support\Str;

class FileService
{
    public function fileStore($request)
    {
        if (!$request->hasFile('file')) {
            return;
        }

        $file = $request->file('file');
        $chunkSize = 1024 * 1024; // 1 МБ

        $handle = fopen($file->getRealPath(), 'r');
        $uuid = Str::uuid()->toString();
        $chunkNumber = 0;

        while (!feof($handle)) {
            $chunk = fread($handle, $chunkSize);
            $chunkPath = "/chunks/{$uuid}/chunk{$chunkNumber}";
            $file->storeAs($chunkPath, "chunk{$chunkNumber}", 'public');
            $chunkNumber++;
        }

        fclose($handle);

        $fileId = File::max('id') + 1;
        $data = ['file' => "/chunks/{$uuid}", 'id' => $fileId];
        File::updateOrCreate(['id' => $fileId], $data);
    }
}
