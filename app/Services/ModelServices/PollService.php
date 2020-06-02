<?php

namespace App\Services\ModelServices;

use Users;
use Groups;
use Uploader;
use PollVotes;
use PollStatuses;
use PollQuestions;
use PollQuestionAnswers;
use App\Models\Poll;
use App\Models\PollStatus;
use App\Models\PollQuestion;
use App\Models\PollQuestionAnswer;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Community\Polls\VotePollRequest;
use App\Http\Requests\Community\Polls\CreatePollRequest;
use App\Http\Requests\Community\Polls\UpdatePollRequest;
use App\Http\Requests\Community\Polls\DeletePollRequest;

class PollService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;
    
    private $validParentTypes;
    
    public function __construct()
    {
        $this->model = "App\Models\Poll";
        $this->validParentTypes = ["group"];
    }
    
    public function preload($instance)
    {
        $instance->view_href = route('poll', $instance->slug);

        $instance->parent = $this->findParent($instance);
        $instance->status = PollStatuses::find($instance->poll_status_id);
        $instance->votes = PollVotes::getAllForPoll($instance);
        $instance->user = Users::findPreloaded($instance->user_id);
        $instance->questions = PollQuestions::getAllForPoll($instance);

        $instance->is_owner = $this->userOwnsPoll($instance);
        $instance->has_voted = PollVotes::userHasVoted($instance);
        $instance->num_votes = PollVotes::getNumberOfVotesFor($instance);

        $instance->results = $this->preloadResults($instance);

        $instance->header_image_url = asset($instance->header_image_url);

        return $instance;
    }

    public function preloadResults(Poll $poll)
    {
        $out = [];

        foreach ($poll->results as $question_id => $result)
        {
            $question = PollQuestions::find($question_id);
            if ($question)
            {
                if ($question->open_question)
                {
                    $out[] = [
                        "question" => $question,
                        "answers" => $result->answers,
                    ];
                }
                else
                {
                    $answers = [];   
                    foreach ((array) $result->answers as $answer_id => $num_votes)
                    {
                        $answer = PollQuestionAnswers::find($answer_id);
                        $answers[] = [
                            "answer" => $answer,
                            "num_votes" => $num_votes,
                            "num_votes_percentage" => floor($poll->num_votes / 100 * $num_votes),
                        ];
                    }
    
                    $out[] = [
                        "question" => $question,
                        "answers" => $answers,
                    ];
                }
            }
        }

        return $out;
    }

    public function getAllPreloadedForOverview()
    {
        $out = [];

        $open = PollStatus::where("name->en", "Open")->first();

        foreach ($this->getAllPreloaded() as $poll)
        {
            if ($poll->poll_status_id == $open->id && $poll->public)
            {
                $out[] = $poll;
            }
        }

        return collect($out);
    }

    public function getMyPolls()
    {
        $out = [];

        $user = auth()->user();

        foreach ($this->getAllPreloaded() as $poll)
        {
            if ($poll->user_id == $user->id)
            {
                $out[] = $poll;
            }
        }

        return collect($out);
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $poll)
        {
            if ($poll->slug == $slug)
            {
                return $poll;
            }
        }

        return false;
    }

    public function findPreloadedBySlug($slug)
    {
        foreach ($this->getAllPreloaded() as $poll)
        {
            if ($poll->slug == $slug)
            {
                return $poll;
            }
        }

        return false;
    }

    public function findParent(Poll $poll)
    {
        if ($poll->pollable_type != "" && $poll->pollable_id > 0)
        {
            switch ($poll->pollable_type)
            {
                case "App\\Models\\Group":
                    return Groups::findPreloaded($poll->pollable_id);
                break;
            }
        }
        
        return false;
    }

    public function createFromRequest(CreatePollRequest $request)
    {
        // Determine the poll's status
        $status = PollStatus::where("name->en", "Open")->first();
        if (!$status) PollStatus::all()->first();

        // Compose data we'll use to create the Poll object
        $data = [
            "user_id" => auth()->user()->id,
            "poll_status_id" => $status->id,
            "title" => $request->title,
            "description" => [
                "nl" => $request->description_nl,
                "en" => $request->description_en,
            ],
            "published" => $request->draft == "true" ? false : true,
        ];

        // Upload & add the header image if one was uploaded
        if ($request->hasFile("header_image"))
        {
            $data["header_image_url"] = Uploader::upload($request->header_image, "images/polls");
        }

        // Add the parent if a valid one was provided
        if (in_array($request->parent_type, $this->validParentTypes) and $request->parent_id > 0)
        {
            switch ($request->parent_type)
            {
                case "group":
                    $group = Groups::find($request->parent_id);
                    if ($group)
                    {
                        $data["pollable_type"] = "App\Models\Group";
                        $data["pollable_id"] = $group->id;
                        $data["public"] = false;
                    }
                break;
            }
        }

        // Create the poll
        $poll = Poll::create($data);

        // Process the poll's questions
        $this->processPollQuestions($poll, $request->questions);

        // Return the created poll
        return $poll;
    }

    public function updateFromRequest(Poll $poll, UpdatePollRequest $request)
    {
        // Update the poll
        $poll->title = $request->title;
        $poll->description = [
            "nl" => $request->description_nl,
            "en" => $request->description_en,
        ];
        $poll->public = $request->private == "true" ? false : true;
        $poll->published = $request->draft == "true" ? false : true;

        // Update parent
        if (in_array($request->parent_type, $this->validParentTypes) && $request->parent_id > 0)
        {
            switch ($request->parent_type)
            {
                case "group":
                    $group = Groups::find($request->parent_id);
                    if ($group)
                    {
                        $poll->pollable_type = "App\Models\Group";
                        $poll->pollable_id = $group->id;
                        $poll->public = false;
                    }
                break;
            }
        }
        else
        {
            $poll->pollable_type = null;
            $poll->pollable_id = 0;
            $poll->public = true;
        }

        // Update header image if one was uploaded
        if ($request->hasFile("header_image"))
        {
            $poll->header_image_url = Uploader::upload($request->header_image, "images/polls");
        }

        // Save changes
        $poll->save();

        // Update the poll's questions
        $this->processPollQuestions($poll, $request->questions);

        // Return the updated polll
        return $poll;
    }

    private function processPollQuestions(Poll $poll, $encodedQuestions)
    {
        // Decode the question data & make sure we received an array with at least one entry
        $questionData = json_decode($encodedQuestions);
        if (is_array($questionData) && count($questionData))
        {
            // Track ID's of the updated & created questions
            $questionIds = [];

            // Loop through all of the question data entries
            foreach ($questionData as $questionEntry)
            {
                // Track whether we have updated the (existing) question
                $updatedQuestion = false;
                
                // If this entry has an id
                if (!is_null($questionEntry->id))
                {
                    // Attempt to grab the question by it's id
                    $question = PollQuestion::find($questionEntry->id);
                    if ($question)
                    {
                        // Update the question
                        $question->open_question = $questionEntry->type == "closed" ? false : true;
                        $question->allow_multiple_answers = $questionEntry->multiple_answers_allowed;
                        $question->question = [
                            "en" => $questionEntry->question->en,
                            "nl" => $questionEntry->question->nl,
                        ];

                        // Flag that we've done so
                        $updatedQuestion = true;
                    }
                }

                // If we have not updated an existing question, let's create a new one
                if (!$updatedQuestion)
                {
                    $question = PollQuestion::create([
                        "poll_id" => $poll->id,
                        "open_question" => $questionEntry->type == "closed" ? false : true,
                        "question" => [
                            "nl" => $questionEntry->question->nl,
                            "en" => $questionEntry->question->en,
                        ],
                        "allow_multiple_answers" => $questionEntry->multiple_answers_allowed,
                    ]);
                }

                // Add question id to our tracker array
                $questionIds[] = $question->id;

                // Track created/updates answer ids
                $answerIds = [];

                // Process all of the question's answers
                foreach ($questionEntry->answers as $answerEntry)
                {
                    // Track whether or not we've updated the answer
                    $updatedAnswer = false;
                    
                    // If this entry has an id
                    if (!is_null($answerEntry->id))
                    {
                        // Attempt to grab the answer by it's id
                        $answer = PollQuestionAnswer::find($answerEntry->id);
                        if ($answer)
                        {
                            // Update the answer
                            $answer->value = [
                                "nl" => $answerEntry->value->nl,
                                "en" => $answerEntry->value->en,
                            ];
                            $answer->save();

                            // Flag that we've done so
                            $updatedAnswer = true;
                        }
                    }

                    // If we've not updated an existing answer; let's create a new one
                    if (!$updatedAnswer)
                    {
                        $answer = PollQuestionAnswer::create([
                            "poll_question_id" => $question->id,
                            "value" => [
                                "nl" => $answerEntry->value->nl,
                                "en" => $answerEntry->value->en,
                            ]
                        ]);
                    }

                    // Add updated/created answer's id to the list of answer id's
                    $answerIds[] = $answer->id;
                }

                // Check for deleted answers & make sure they are actually deleted
                $answersToDelete = [];
                foreach ($question->answers as $questionAnswer)
                {
                    if (!in_array($questionAnswer->id, $answerIds))
                    {
                        $answersToDelete[] = $questionAnswer->id;
                    }
                }

                // DO the deleting if necessary
                if (count($answersToDelete))
                {
                    foreach ($answersToDelete as $id)
                    {
                        $answer = PollQuestionAnswer::find($id);
                        if ($answer) $answer->delete();
                    }
                }
            }

            // Check for deleted questions & make sure they are actually deleted
            $questionsToDelete = [];
            foreach ($poll->questions as $pollQuestion)
            {
                if (!in_array($pollQuestion->id, $questionIds))
                {
                    $questionsToDelete[] = $pollQuestion->id;
                }
            }

            // Do the deleting if necessary
            if (count($questionsToDelete))
            {
                foreach ($questionsToDelete as $id)
                {
                    $question = PollQuestion::find($id);
                    if ($question) $question->delete();
                }
            }
        }
    }

    public function delete(Poll $poll)
    {
        $poll->delete();
    }

    public function voteFromRequest(Poll $poll, VotePollRequest $request)
    {   
        $answers = json_decode($request->answers);

        $vote = PollVotes::createVoteForPoll($poll, $answers);

        $poll->num_votes += 1;
        $poll->save();

        $this->updateResults($poll);

        return $vote;
    }

    public function close(Poll $poll)
    {
        $status = PollStatuses::getByTranslatedName("Closed", "en");
        $poll->poll_status_id = $status->id;
        $poll->save();
        return $poll;
    }

    public function reopen(Poll $poll)
    {
        $status = PollStatuses::getByTranslatedName("Open", "en");
        $poll->poll_status_id = $status->id;
        $poll->save();
        return $poll;
    }

    public function userOwnsPoll(Poll $poll, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();
        return $poll->user_id == $user->id;
    }

    public function updateResults(Poll $poll)
    {
        $results = [];
        
        // Process all votes
        foreach (PollVotes::getAllForPoll($poll) as $vote)
        {
            // Process all this vote's answers
            foreach ($vote->answers as $answer)
            {
                // Grab the question we're currently processing
                $question = PollQuestions::findPreloaded($answer->question_id);

                // Question has an entry results entry
                if (array_key_exists($question->id, $results))
                {
                    if ($question->open_question)
                    {
                        $results[$question->id]["answers"][] = $answer->answer;
                    }
                    else
                    {
                        if (is_array($answer->answer))
                        {
                            foreach ($answer->answer as $answer_id)
                            {
                                $results[$question->id]["answers"][$answer_id] = $results[$question->id]["answers"][$answer_id] + 1;
                            }
                        }
                        else
                        {
                            $results[$question->id]["answers"][$answer->answer] = $results[$question->id]["answers"][$answer->answer] + 1;
                        }
                    }
                }
                // Question does not have an results array entry yet
                else
                {
                    // Open question
                    if ($question->open_question)
                    {
                        // Collect individual answers
                        $results[$question->id] = [
                            "answers" => [
                                $answer->answer
                            ]
                        ];
                    }
                    // Closed question
                    else
                    {
                        // Generate array with all possible answers and their vote count
                        $answers = [];
                        foreach ($question->answers as $questionAnswer)
                        {
                            $answers[$questionAnswer->id] = 0;
                        }

                        // Populate the array with the vote we're currently processing
                        if (is_array($answer->answer))
                        {
                            foreach ($answer->answer as $answer_id)
                            {
                                $answers[$answer_id] += 1;
                            }
                        }
                        else
                        {
                            $answers[$answer->answer] += 1;
                        }

                        $results[$question->id] = [
                            "answers" => $answers
                        ];
                    }
                }
            }
        }

        $poll->results = $results;
        $poll->save();

        return $poll;
    }
}