<?php

namespace App\Http\Controllers\Community;

use Polls;
use App\Http\Controllers\Controller;
use App\Http\Requests\Community\Polls\VotePollRequest;
use App\Http\Requests\Community\Polls\CreatePollRequest;
use App\Http\Requests\Community\Polls\UpdatePollRequest;
use App\Http\Requests\Community\Polls\DeletePollRequest;

class PollController extends Controller
{
    public function getOverview()
    {
        return view("pages.community.polls.overview", [
            "polls" => Polls::getAllPreloaded(),
        ]);
    }

    public function getView($slug)
    {
        

        return view("pages.community.polls.view", [

        ]);
    }

    public function getCreate()
    {
        return view("pages.community.polls.create", [

        ]);
    }

    public function postCreate(CreatePollRequest $request)
    {

    }

    public function getEdit($slug)
    {
        return view("pages.community.polls.edit", [

        ]);
    }

    public function postEdit(UpdatePollRequest $request, $slug)
    {

    }

    public function getDelete($slug)
    {
        return view("pages.community.polls.delete", [

        ]);
    }

    public function postDelete(DeletePollRequest $request, $slug)
    {

    }

    public function getVote($slug)
    {
        return view("pages.community.polls.vote", [

        ]);
    }

    public function postVote(VotePollRequest $request, $slug)
    {

    }
}