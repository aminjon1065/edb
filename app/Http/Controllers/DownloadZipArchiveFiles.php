<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\File;
use ZipArchive;

class DownloadZipArchiveFiles extends Controller
{
    public function downloadAllFiles($uuid)
    {
        $zip = new ZipArchive();
        $zipFileName = 'archive-' . date('d-m-Y-H-i-s') . '.zip';
        $document = Document::where('uuid', $uuid)->with(['user'])->first();

        if (!$document) {
            return response()->json(['error' => 'Document not found'], 404);
        }

        $files = $document->file()->get();
        $zipFilePath = public_path($zipFileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $file) {
                $filePath = ('storage/documents/' . $document->user->region . '/' . $document->uuid . '/' . $file->name);
                if (!File::exists($filePath)) {
                    return response()->json(['error' => 'File not found: ' . $file->name], 404);
                }
                $zip->addFile($filePath, basename($filePath)); // Use basename() to get just the file name
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip archive'], 500);
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }
}
