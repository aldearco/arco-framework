<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Controllers\Controller;

class NoteController extends Controller {
    //

    public function index() {
        $notes = auth()->notes();

        $authors = Note::find(1)->users();

        return view('notes/index', [
            'notes' => $notes,
            'authors' => $authors
        ]);
    }
}