<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documentation;

class DocumentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function previewDokumentasi($id)
    {
        $dokum = Documentation::where('antrian_id', $id)->get();

        return view('page.antrian-workshop.preview', compact('dokum'));
    }
}
