<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class FileHelper
{
    public static function fileNameUnique($path, $fileName)
    {
        $count = 0;
        $originalFileName = $fileName;

        while (File::exists($path . '/' . $fileName)) {
            $count++;
            $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '(' . $count . ').' . pathinfo($originalFileName, PATHINFO_EXTENSION);
        }

        return $fileName;
    }

    public static function downloadFile($model, $id)
    {
        $fileInfo = $model::findOrFail($id);
        $filePath = storage_path('app/public/' . $fileInfo->file_location . $fileInfo->name);

        if (File::exists($filePath)) {
            return response()->file($filePath, [
                'Content-Disposition' => 'attachment: filename="' . $fileInfo->original_name . '"'
            ]);
        } else {
            abort(404);
        }
    }
    public static function viewFile($model, $id)
    {
        $fileInfo = $model::findOrFail($id);
        $filePath = storage_path('app/public/' . $fileInfo->file_location . $fileInfo->name);

        return File::exists($filePath) ?
            response()->file($filePath, ['Content-Disposition' => 'inline; filename="' . $fileInfo->original_name . '"']) :
            abort(404);
    }

    public static function saveFile($file, $path)
    {
        $count = 0;
        $originalFileName = $file->getClientOriginalName();
        $fileName = $originalFileName;

        while (File::exists($path . '/' . $fileName)) {
            $count++;
            $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '(' . $count . ').' . pathinfo($originalFileName, PATHINFO_EXTENSION);
        }

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
        }

        $file->move($path, $fileName);

        if (File::exists($path . '/' . $fileName)) {
            return $fileName;
        } else {
            return false;
        }
    }
}
