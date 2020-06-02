<?php

namespace App\Http\Controllers\Community;

use Polls;
use Groups;
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
            "polls" => Polls::getAllPreloadedForOverview(),
            "myPolls" => Polls::getMyPolls(),
            "strings" => collect([
                "no_records" => __("polls.overview_no_records"),
            ]),
            "myPollsStrings" => collect([
                "title" => __("polls.my_polls_title"),
                "status" => __("polls.my_polls_status"),
                "num_votes" => __("polls.my_polls_num_votes"),
                "privacy" => __("polls.my_polls_privacy"),
                "public" => __("polls.my_polls_public"),
                "private" => __("polls.my_polls_private"),
                "vote" => __("polls.results_vote"),
                "votes" => __("polls.results_votes"),
            ]),
        ]);
    }

    public function getView($slug)
    {
        $poll = Polls::findPreloadedBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        return view("pages.community.polls.view", [
            "poll" => $poll,
            "strings" => collect([
                "questions" => __("polls.vote_questions"),
                "no_questions" => __("polls.vote_no_questions"),
                "question" => __("polls.vote_question"),
                "answer" => __("polls.vote_answer"),
                "cancel" => __("polls.vote_cancel"),
                "submit" => __("polls.vote_submit"),
            ]),
            "resultStrings" => collect([
                "question" => __("polls.vote_question"),
                "vote" => __("polls.results_vote"),
                "votes" => __("polls.results_votes"),
                "no_results" => __("polls.results_no_results"),
            ]),
            "oldInput" => collect([
                "answers" => old("answers"),
            ]),
        ]);
    }

    public function getCreate()
    {
        return view("pages.community.polls.create", [
            "myGroups" => Groups::getMyGroups(),
            "strings" => collect([
                "general" => __("polls.form_general"),
                "questions" => __("polls.form_questions"),
                "no_questions" => __("polls.form_no_questions"),
                "add_question" => __("polls.form_add_question"),
                "parent" => __("polls.form_parent"),
                "no_parent" => __("polls.form_no_parent"),
                "group" => __("polls.form_group"),
                "parent_group" => __("polls.form_parent_group"),
                "no_parent_groups" => __("polls.form_no_parent_groups"),
                "select_parent_group" => __("polls.form_select_parent_group"),
                "title" => __("polls.form_title"),
                "description" => __("polls.form_description"),
                "question" => __("polls.form_question"),
                "question_type" => __("polls.form_question_type"),
                "question_multiple" => __("polls.form_question_multiple"),
                "question_type_open" => __("polls.form_question_type_open"),
                "question_type_closed" => __("polls.form_question_type_closed"),
                "question_add_answer" => __("polls.form_question_add_answer"),
                "question_answers" => __("polls.form_question_answers"),
                "answer" => __("polls.form_answer"),
                "private" => __("polls.form_private"),
                "draft" => __("polls.form_draft"),
                "draft_help" => __("polls.form_draft_help"),
                "header_image" => __("polls.form_header_image"),
                "cancel" => __("polls.create_cancel"),
                "submit" => __("polls.create_submit"),
                "en" => __("general.en"),
                "nl" => __("general.nl"),
            ]),
            "oldInput" => collect([
                "title" => old("title"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "questions" => old("questions"),
            ]),
        ]);
    }

    public function postCreate(CreatePollRequest $request)
    {
        $poll = Polls::createFromRequest($request);

        flash(__("polls.created"))->success();
        return redirect()->route("poll", $poll->slug);
    }
    
    public function getEdit($slug)
    {
        $poll = Polls::findPreloadedBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        return view("pages.community.polls.edit", [
            "poll" => $poll,
            "myGroups" => Groups::getMyGroups(),
            "strings" => collect([
                "general" => __("polls.form_general"),
                "questions" => __("polls.form_questions"),
                "no_questions" => __("polls.form_no_questions"),
                "add_question" => __("polls.form_add_question"),
                "publishing" => __("polls.form_publishing"),
                "parent" => __("polls.form_parent"),
                "no_parent" => __("polls.form_no_parent"),
                "group" => __("polls.form_group"),
                "parent_group" => __("polls.form_parent_group"),
                "no_parent_groups" => __("polls.form_no_parent_groups"),
                "select_parent_group" => __("polls.form_select_parent_group"),
                "title" => __("polls.form_title"),
                "description" => __("polls.form_description"),
                "question" => __("polls.form_question"),
                "question_type" => __("polls.form_question_type"),
                "question_multiple" => __("polls.form_question_multiple"),
                "question_type_open" => __("polls.form_question_type_open"),
                "question_type_closed" => __("polls.form_question_type_closed"),
                "question_add_answer" => __("polls.form_question_add_answer"),
                "question_answers" => __("polls.form_question_answers"),
                "answer" => __("polls.form_answer"),
                "private" => __("polls.form_private"),
                "draft" => __("polls.form_draft"),
                "draft_help" => __("polls.form_draft_help"),
                "header_image" => __("polls.form_header_image"),
                "cancel" => __("polls.edit_cancel"),
                "submit" => __("polls.edit_submit"),
                "en" => __("general.en"),
                "nl" => __("general.nl"),
            ]),
            "oldInput" => collect([
                "title" => old("title"),
                "description_nl" => old("description_nl"),
                "description_en" => old("description_en"),
                "questions" => old("questions"),
            ]),
        ]);
    }

    public function postEdit(UpdatePollRequest $request, $slug)
    {
        $poll = Polls::findBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        $poll = Polls::updateFromRequest($poll, $request);

        flash(__("polls.updated"))->success();
        return redirect()->route("poll", $poll->slug);
    }

    public function getDelete($slug)
    {
        $poll = Polls::findBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        return view("pages.community.polls.delete", [
            "poll" => $poll,
        ]);
    }

    public function postDelete(DeletePollRequest $request, $slug)
    {
        $poll = Polls::findBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        Polls::delete($poll);

        flash(__("polls.deleted"))->success();
        return redirect()->route("polls");
    }

    // public function getVote($slug)
    // {
    //     $poll = Polls::findBySlug($slug);
    //     if (!$poll)
    //     {
    //         flash(__("polls.not_found"))->error();
    //         return redirect()->route("polls");
    //     }
    // 
    //     return view("pages.community.polls.vote", [
    //         "poll" => $poll,
    //     ]);
    // }

    public function postVote(VotePollRequest $request, $slug)
    {
        $poll = Polls::findBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        Polls::voteFromRequest($poll, $request);

        flash(__("polls.voted"))->success();
        return redirect()->route("poll", $poll->slug);
    }

    public function getClose($slug)
    {
        $poll = Polls::findBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        Polls::close($poll);

        flash(__("polls.closed"))->success();
        return redirect()->route("poll", $poll->slug);
    }

    public function getReopen($slug)
    {
        $poll = Polls::findBySlug($slug);
        if (!$poll)
        {
            flash(__("polls.not_found"))->error();
            return redirect()->route("polls");
        }

        Polls::reopen($poll);
        
        flash(__("polls.reopened"))->success();
        return redirect()->route("poll", $poll->slug);
    }
}