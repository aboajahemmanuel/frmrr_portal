<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\PdfToImage\Pdf;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function preview($filename)
    {
        // Define the path to the PDF file in the storage folder
        $pdfPath = storage_path("app/public/pdf_documents/{$filename}");

        // Check if the PDF file exists
        if (!file_exists($pdfPath)) {
            abort(404, 'PDF file not found.');
        }

        // Create a new instance of the Pdf class
        $pdf = new Pdf($pdfPath);

        // Define the path where the preview image will be saved
        $imagePath = storage_path("app/public/pdf_documents/preview_{$filename}.jpg");

        // Convert the first page of the PDF to an image and save it
        $pdf->setPage(1)->saveImage($imagePath);

        // Return the image file for viewing
        return response()->file($imagePath);
    }
}
