<?php

namespace App\Services\ModelServices;

use Auth;
use Messages;
use App\Models\User;
use App\Models\Message;
use App\Models\ViewEmailRequest;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Projects\WorkMethods\CreateWorkMethodRequest;
use App\Http\Requests\Projects\WorkMethods\UpdateWorkMethodRequest;

class ViewEmailRequestService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;

    public function __construct()
    {
        $this->model = "App\Models\ViewEmailRequest";
    }
    
    public function preload($instance)
    {
        return $instance;
    }

    public function findByUuid($uuid)
    {
        foreach ($this->getAll() as $request)
        {
            if ($request->uuid == $uuid)
            {
                return $request;
            }
        }

        return false;
    }

    public function sendRequest(User $targetUser, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $request = ViewEmailRequest::create([
            "user_id" => $user->id,
            "target_user_id" => $targetUser->id,
        ]);
        
        Messages::sendViewEmailRequestMessage($targetUser, $user, $request);

        return $request;
    }

    public function accept(Message $message, ViewEmailRequest $request)
    {
        $data = $message->data;
        $data["request_processed"] = true;
        $data["request_accepted"] = true;

        $message->data = $data;
        $message->save();

        $request->status = "accepted";
        $request->save();

        return $request;
    }

    public function reject(Message $message, ViewEmailRequest $request)
    {
        $data = $message->data;
        $data["request_processed"] = true;
        $data["request_accepted"] = false;

        $message->data = $data;
        $message->save();

        $request->status = "rejected";
        $request->save();

        return $request;
    }

    public function canViewEmail(User $targetUser, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $request = ViewEmailRequest::where("user_id", $user->id)->where("target_user_id", $targetUser->id)->first();
        if ($request and $request->status == "accepted")
        {
            return true;
        }

        return false;
    }

    public function hasSentRequest(User $targetUser, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $request = ViewEmailRequest::where("user_id", $user->id)->where("target_user_id", $targetUser->id)->first();
        
        return $request ? true : false;
    }

    public function openRequests(User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        $out = [];  

        foreach ($this->getAll() as $request)
        {
            if ($request->user_id == $user->id && $request->status == "open")
            {
                $out[] = $request;
            }
        }
            
        return collect($out);
    }

    public function numRequests(User $user = null)
    {
        return $this->openRequests($user)->count();
    }
}