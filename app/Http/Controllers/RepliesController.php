<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Inspections\Spam;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index(Channel $channel, Thread $thread)
    {
        return $thread->replies()->paginate(5);
    }

    /**
     * @param             $channelId
     * @param \App\Thread $thread
     *
     * @param Spam $spam
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store($channelId, Thread $thread)
    {
        try {
            $this->validateReply();

            $reply = $thread->addReply(
                [
                    'body'    => request('body'),
                    'user_id' => auth()->id(),
                ]
            );

            return $reply->load('owner');
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.',
                422
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Reply $reply
     *
     * @param Spam $spam
     *
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @internal param Thread $thread
     * @throws \Exception
     */
    public function update(Request $request, Reply $reply)
    {
        try {
            $this->authorize('update', $reply);

            $this->validateReply();

            $reply->update(['body' => request('body')]);
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.',
                422
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reply $reply
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @internal param \App\Channel $channel
     * @internal param Thread $thread
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    /**
     * @throws \Exception
     */
    protected function validateReply()
    {
        $this->validate(request(), ['body' => 'required']);

        resolve(Spam::class)->detect(request('body'));
    }
}
