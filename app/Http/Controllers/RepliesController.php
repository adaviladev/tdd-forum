<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param             $channelId
     * @param \App\Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);

        $reply = $thread->addReply(
            [
                'body'    => request('body'),
                'user_id' => auth()->id(),
            ]
        );

        if(request()->expectsJson()) {
            return $reply->load('owner');
        }

        return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Reply $reply
     *
     * @return \Illuminate\Http\Response
     * @internal param Thread $thread
     *
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(['body' => request('body')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reply $reply
     *
     * @return \Illuminate\Http\Response
     * @internal param \App\Channel $channel
     * @internal param Thread $thread
     *
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if(request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }
}
