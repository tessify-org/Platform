<?php

namespace App\Http\Controllers\Api;

use TeamMemberApplications;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Projects\TeamMemberApplications\DenyTeamMemberApplicationRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\AcceptTeamMemberApplicationRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\CreateTeamMemberApplicationRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\UpdateTeamMemberApplicationRequest;
use App\Http\Requests\Api\Projects\TeamMemberApplications\DeleteTeamMemberApplicationRequest;

class TeamMemberApplicationController extends Controller
{
    public function postCreateApplication(CreateTeamMemberApplicationRequest $request)
    {
        $application = TeamMemberApplications::createFromRequest($request);

        return response()->json([
            "status" => "success",
            "application" => $application    
        ]);
    }

    public function postUpdateApplication(UpdateTeamMemberApplicationRequest $request)
    {
        $application = TeamMemberApplications::updateFromRequest($request);

        return response()->json([
            "status" => "success",
            "application" => $application    
        ]);
    }

    public function postDeleteApplication(DeleteTeamMemberApplicationRequest $request)
    {
        return response()->json(["status" => "success"]);
    }

    public function postAcceptApplication(AcceptTeamMemberApplicationRequest $request)
    {
        TeamMemberApplications::apiAccept($request);

        return response()->json(["status" => "success"]);
    }

    public function postDenyApplication(DenyTeamMemberApplicationRequest $request)
    {
        TeamMemberApplications::apiDeny($request);

        return response()->json(["status" => "success"]);
    }
}