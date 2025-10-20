<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function preview($filename)
    {
        // Validate the file path
        $filePath = public_path("pdf_documents/{$filename}");
        if (!file_exists($filePath)) {
            abort(404, 'PDF file not found.');
        }

        return view('pdf-preview', compact('filename'));
    }
}
