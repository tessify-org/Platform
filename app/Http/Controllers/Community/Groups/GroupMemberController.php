<?php

namespace App\Http\Controllers\Community\Groups;

use Groups;
use GroupMembers;
use App\Http\Controllers\Controller;
use App\Http\Requests\Groups\Members\KickGroupMemberRequest;

class GroupMemberController extends Controller
{
    public function getOverview($slug)
    {

    }

    public function postKick(KickGroupMemberRequest $request, $slug)
    {

    }
}