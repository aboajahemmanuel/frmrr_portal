<?php

namespace App\Http\Controllers;

use App\Models\SaveDoc;
use App\Models\Download;
use App\Models\Regulation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveDocController extends Controller
{



    public function saveDocument($id)
    {
        $user = Auth::user();
        $document = Regulation::findOrFail($id);

        if ($user->savedDocuments->contains($document)) {
            return back()->with('error', 'Document is already saved!');
        }

        $save_document = new SaveDoc();
        $save_document->user_id = $user->id;
        $save_document->regulation_id = $document->id;
        $save_document->save();


        return back()->with('success', 'Document saved successfully!');
    }



    public function removeSavedDocument($id)
    {
        $user = Auth::user();
        $document = Regulation::findOrFail($id);

        if (!$user->savedDocuments->contains($document)) {
            return back()->with('error', 'Document is not saved!');
        }

        $user->savedDocuments()->detach($document);

        return back()->with('success', 'Document removed from saved documents!');
    }

    public function showSavedDocuments()
    {
        $user = Auth::user();
        $savedDocuments = $user->savedDocuments;

        return view('saved-documents', compact('savedDocuments'));
    }




    public function download($id)
    {
        $user = Auth::user();
        $regulation = Regulation::findOrFail($id);

        // Log the download information
        Download::create([
            'user_id' => $user->id,
            'regulation_id' => $regulation->id,
        ]);

        // Proceed with the download
        $filePath = public_path('pdf_documents/' . $regulation->regulation_doc);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }



    public function readDoc($file)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Find the regulation using the provided slug
        $regulation = Regulation::where('slug', $file)->first();

        // Check if the regulation exists
        if (!$regulation) {
            return redirect()->back()->with('error', 'Regulation not found.');
        }

        // Construct the file path
        $filePath = public_path('pdf_documents/' . $regulation->regulation_doc);

        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $regulation->regulation_doc . '"'
            ]);
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }
}
