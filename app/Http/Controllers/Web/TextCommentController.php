<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TextComment\StoreTextCommentRequest;
use App\Models\Text;
use App\Models\TextComment;
use Illuminate\Http\RedirectResponse;

class TextCommentController extends Controller
{
    public function store(StoreTextCommentRequest $request, Text $text): RedirectResponse
    {
        $text->comments()->create([
            'user_id' => (int) $request->user()->id,
            'content' => (string) $request->validated()['content'],
        ]);

        return redirect()
            ->route('texts.content.show', $text)
            ->withFragment('comments')
            ->with('status', 'Đã gửi bình luận.');
    }

    public function destroy(Text $text, TextComment $comment): RedirectResponse
    {
        abort_unless((string) $comment->text_id === (string) $text->id, 404);

        $user = auth()->user();
        abort_unless($user !== null, 403);

        $canDelete = $user->role === 'teacher'
            || ($user->role === 'user' && (string) $comment->user_id === (string) $user->id);

        abort_unless($canDelete, 403);

        $comment->delete();

        return back()
            ->withFragment('comments')
            ->with('status', 'Đã xóa bình luận.');
    }
}
