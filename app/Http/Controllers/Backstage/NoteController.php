<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Transformers\NoteTransformer;
use App\Models\Note;

class NoteController extends Controller
{

    public function index()
    {
        $notes = Note::filtered()->sorted()->with('user', 'course')->paginate(self::perPage());

        return view('admin.notes.index', compact('notes'));
    }

    public function destroy(Note $note)
    {
        $note->delete();

        return $this->response->noContent();
    }
}
