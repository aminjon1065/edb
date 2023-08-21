<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtherFilesController extends Controller
{
    public function store()
    {
        $file = request()->file('filename');
        $filename = $file->getClientOriginalName();
        $user = auth()->user();

        // Сохранение файла
        $folderName = $user->first_name . '_' . $user->last_name;
        $path = $file->storeAs("other_files/{$folderName}", $filename, 'public');

        // Сохранение информации о файле в базе данных
        $user->otherFiles()->create([
            'filename' => $filename,
            'path' => $path
        ]);

        // Генерация полного URL файла
        $fileUrl = asset("storage/other_files/{$folderName}/$filename");  // Обновленный URL файла

        return response()->json([
            'message' => 'Файл успешно загружен',
            'url' => $fileUrl  // Возвращаем URL файла
        ]);
    }
}
