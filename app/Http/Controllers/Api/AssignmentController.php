<?php

namespace App\Http\Controllers\Api;

use Exception;
use Assignments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Assignments\CreateAssignmentRequest;
use App\Http\Requests\Api\Assignments\UpdateAssignmentRequest;
use App\Http\Requests\Api\Assignments\DeleteAssignmentRequest;

class AssignmentController extends Controller
{
    public function postCreateAssignment(CreateAssignmentRequest $request)
    {
        $assignment = Assignments::createFromApiRequest($request);
        
        return response()->json([
            "status" => "success", 
            "assignment" => $assignment
        ]);
    }

    public function postUpdateAssignment(UpdateAssignmentRequest $request)
    {
        $assignment = Assignments::updateFromApiRequest($request);

        return response()->json([
            "status" => "success", 
            "assignment" => $assignment
        ]);
    }

    public function postDeleteAssignment(DeleteAssignmentRequest $request)
    {
        $assignment = Assignments::find($request->assignment_id);
        $assignment->delete();

        return response()->json([
            "status" => "success"
        ]);
    }
}