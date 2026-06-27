<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Text;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function index(): View
    {
        $texts = Text::query()
            ->with('textTopic:id,name')
            ->latest()
            ->paginate(9, ['id', 'text_topic_id', 'name', 'author', 'read_link'])
            ->withQueryString();

        return view('library.index', compact('texts'));
    }
}
    