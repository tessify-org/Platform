<?php

namespace App\Services\ModelServices;

use Auth;
use Users;
use App\Models\User;
use App\Models\Task;
use App\Models\Group;
use App\Models\Project;
use App\Models\Message;
use App\Models\ViewEmailRequest;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Http\Requests\Messages\SendMessageRequest;
use App\Http\Requests\Admin\Users\SendMessageRequest as AdminSendMessageRequest;

class MessageService implements ModelServiceContract
{
    use ModelServiceGetters;
    
    private $model;
    private $records;
    private $preloadedRecords;
    
    public function __construct()
    {
        $this->model = "App\Models\Message";
    }
    
    public function preload($instance)
    {
        // Relationships
        $instance->sender = Users::findPreloaded($instance->sender_id);
        $instance->receiver = Users::findPreloaded($instance->receiver_id);
        $instance->reply_to = $this->find($instance->reply_to_id);

        // View href
        $instance->view_href = route('messages.read', $instance->uuid);

        return $instance;
    }
    
    public function findByUuid($uuid)
    {
        foreach ($this->getAll() as $message)
        {
            if ($message->uuid == $uuid)
            {
                return $message;
            }
        }

        return false;
    }

    public function findPreloadedByUuid($uuid)
    {
        foreach ($this->getAllPreloaded() as $message)
        {
            if ($message->uuid == $uuid)
            {
                return $message;
            }
        }

        return false;
    }

    public function numUnread(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $out = 0;

        if ($user)
        {
            foreach ($this->getAll() as $message)
            {
                if ($message->receiver_id == $user->id and !$message->read)
                {
                    $out += 1;
                }
            }
        }

        return $out;
    }

    public function receivedMessages(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $out = [];
        
        foreach ($this->getAllPreloaded() as $message)
        {
            if ($message->receiver_id == $user->id)
            {
                $out[] = $message;
            }
        }

        $out = collect($out);
        $out = $out->sortByDesc('id')->values();

        return $out;
    }

    public function sentMessages(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $out = [];

        foreach ($this->getAllPreloaded() as $message)
        {
            if ($message->sender_id == $user->id)
            {
                $out[] = $message;
            }
        }

        $out = collect($out);
        $out = $out->sortByDesc('id')->values();

        return $out;
    }

    public function sendFromRequest(SendMessageRequest $request, Message $replyTo = null)
    {
        $user = Auth::user();

        $receiver = Users::findUserByFormattedName($request->user);

        return Message::create([
            "sender_id" => $user->id,
            "receiver_id" => $receiver->id,
            "reply_to_id" => is_null($replyTo) ? 0 : $replyTo->id,
            "subject" => $request->subject,
            "message" => $request->message,
        ]);
    }

    public function sendMessageFromAdminRequest(User $user, AdminSendMessageRequest $request)
    {
        return Message::create([
            "sender_id" => auth()->user()->id,
            "receiver_id" => $user->id,
            "subject" => $request->subject,
            "message" => $request->message,
        ]);
    }
    
    public function markAsRead(Message $message)
    {
        $message->read = true;
        $message->save();

        return $message;
    }

    public function sendViewEmailRequestMessage(User $targetUser, User $user = null, ViewEmailRequest $request)
    {
        if (is_null($user)) $user = Auth::user();

        return Message::create([
            "type" => "request_access_to_email",
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => __("profiles.profile_email_request_message_subject"),
            "message" => __("profiles.profile_email_request_message_text", ["name" => $user->formattedName]),
            "data" => [
                "uuid" => $request->uuid,
                "request_processed" => false,
                "request_accepted" => null,
            ]
        ]);
    }

    public function sendInviteToProjectMessage(User $targetUser, Project $targetProject, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        return Message::create([
            "type" => "invite_to_project",
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => __("messages.project_invite_subject"),
            "message" => __("messages.project_invite_message", [
                "user" => $user->formatted_name,
                "project" => $targetProject->title,
            ]),
            "data" => [
                "project_slug" => $targetProject->slug,
            ]
        ]);
    }

    public function sendInviteToTaskMessage(User $targetUser, Task $targetTask, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        return Message::create([
            "type" => "invite_to_task",
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => __("messages.task_invite_subject"),
            "message" => __("messages.task_invite_message", [
                "user" => $user->formatted_name,
                "task" => $targetTask->title,
            ]),
            "data" => [
                "task_slug" => $targetTask->slug,
            ],
        ]);
    }

    public function sendInviteToGroupMessage(User $targetUser, Group $targetGroup, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();

        return Message::create([
            "type" => "invite_to_group",
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => __("messages.group_invite_subject"),
            "message" => __("messages.group_invite_message", [
                "user" => $user->formatted_name,
                "group" => $targetGroup->name,
            ]),
            "data" => [
                "group_slug" => $targetGroup->slug,
                "request_processed" => false,
                "request_accepted" => null,
            ],
        ]);
    }

    public function sendAskProjectQuestionMessage(User $targetUser, Project $targetProject, string $question, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();
        
        return Message::create([
            "type" => "project_question",
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => __("messages.ask_project_question_subject"),
            "message" => __("messages.ask_project_question_message", [
                "user" => $user->formatted_name,
                "project" => $targetProject->title,
                "question" => $question,
            ]),
            "data" => [
                "project_id" => $targetProject->id,
            ]
        ]);
    }

    public function sendAskTaskQuestionMessage(User $targetUser, Task $targetTask, string $question, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();
        
        return Message::create([
            "type" => "task_question",
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => __("messages.ask_task_question_subject"),
            "message" => __("messages.ask_task_question_message", [
                "user" => $user->formatted_name,
                "task" => $targetTask->title,
                "question" => $question
            ]),
            "data" => [
                "task_id" => $targetTask->id,
            ]
        ]);
    }

    public function sendKickedFromProjectTeamMessage(User $targetUser, Project $targetProject, string $reason = null, User $user = null)
    {
        if (is_null($user)) $user = auth()->user();
        
        return Message::create([
            "type" => "task_question",
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => __("messages.kicked_from_project_team_subject"),
            "message" => is_null($reason) ? __("messages.kicked_from_project_team_message", ["project" => $targetProject->title]) : __("messages.kicked_from_project_team_message_reason", [
                "project" => $targetProject->title,
                "reason" => $reason,
            ]),
            "data" => [
                "project_id" => $targetProject->id,
            ]
        ]);
    }

    public function sendMessage(User $targetUser, string $subject, string $message, User $user = null)
    {
        // Grab logged in user if no user was provided
        if (is_null($user)) $user = auth()->user();

        // Create (and thereby send) the message & return it
        return Message::create([
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => $subject,
            "message" => $message,
        ]);
    }   

    public function sendCustomMessage(User $targetUser, string $type, string $subject, string $message, array $data = null, User $user = null) 
    {
        // Grab logged in user if no user was provided
        if (is_null($user)) $user = auth()->user();

        // Create (and thereby send) the message & return it
        return Message::create([
            "type" => $type,
            "sender_id" => $user->id,
            "receiver_id" => $targetUser->id,
            "subject" => $subject,
            "message" => $message,
            "data" => $data,
        ]);
    }

    public function acceptInvitation(Message $message)
    {
        $data = $message->data;
        $data["request_processed"] = true;
        $data["request_accepted"] = true;
        $message->data = $data;
        $message->save();
    } 

    public function rejectInvitation(Message $message)
    {
        $data = $message->data;
        $data["request_processed"] = true;
        $data["request_accepted"] = false;
        $message->data = $data;
        $message->save();
    }
}