<?php

namespace App\Http\Controllers\Reviews;

use Reviews;
use ReviewRequests;
use App\Http\Requests\Reviews\CreateReviewRequest;
use App\Http\Requests\Reviews\UpdateReviewRequest;
use App\Http\Requests\Reviews\DeleteReviewRequest;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function getOverview()
    {
        // Render the my reviews page
        return view("pages.reviews.overview", [
            "reviews" => Reviews::getMyReviews(),
            "requests" => ReviewRequests::getMyRequests(),
            "outstandingStrings" => collect([
                "title" => __("reviews.overview_requests_title"),
                "no_records" => __("reviews.overview_requests_no_records"),
                "accept" => __("reviews.overview_requests_accept"),
                "reject" => __("reviews.overview_requests_reject"),
            ]),
            "overviewStrings" => collect([
                "title" => __("reviews.overview_list_title"),
                "no_records" => __("reviews.overview_no_records"),
            ])
        ]);
    }

    public function getView($uuid)
    {
        // Grab the review we want to view
        $review = Reviews::findByUuid($uuid);
        if (!$review)
        {
            flash(__("reviews.not_found"))->error();
            return redirect()->back();
        }

        // Grab the target data we want from whatever was reviewed
        switch ($review->reviewable_type)
        {
            default:
                $target = null;
            break;
            
            case "App\\Models\\User":
                $target = [
                    "type" => __("reviews.type_user"),
                    "name" => $review->reviewable->formatted_name,
                    "image_url" => asset($review->reviewable->avatar_url),
                ];
            break;
            
            case "App\\Models\\Task":
                $target = [
                    "type" => __("reviews.type_task"),
                    "name" => $review->reviewable->title,
                    "image_url" => asset($review->reviewable->header_image_url),
                ];
            break;

            case "App\\Models\\Project":
                $target = [
                    "type" => __("reviews.type_project"),
                    "name" => $review->reviewable->title,
                    "image_url" => asset($review->reviewable->header_image_url),
                ];
            break;
        }

        // Render the create review form
        return view("pages.reviews.view", [
            "review" => $review,
            "target" => $target,
        ]);
    }

    public function getCreate($type, $slug)
    {
        // Grab the target we're writing a review for
        $target = Reviews::findTarget($type, $slug);
        if (!$target)
        {
            flash(__("reviews.target_not_found"))->error();
            return redirect()->back();
        }

        // Render the create review page
        return view("pages.reviews.create", [
            "type" => $type,
            "slug" => $slug,
            "target" => $target,
            "oldInput" => collect([
                "rating" => old("rating"),
                "message" => old("message"),
                "public" => old("public"),
            ]),
            "strings" => collect([
                "rating" => __("reviews.form_rating"),
                "message" => __("reviews.form_message"),
                "message_hint" => __("reviews.form_message_hint"),
                "public" => __("reviews.form_public"),
                "cancel" => __("reviews.create_cancel"),
                "submit" => __("reviews.create_submit"),
            ]),
        ]);
    }

    public function postCreate(CreateReviewRequest $request, $type, $slug)
    {
        // Create the review
        $review = Reviews::createFromRequest($request, $type, $slug);

        // Render the review has been created page
        return view("pages.reviews.created", [
            "review" => $review,
            "type" => $type,
            "slug" => $slug,
        ]);
    }

    public function getUpdate($uuid)
    {
        // Grab the review we want to update
        $review = Reviews::findByUuid($uuid);
        if (!$review)
        {
            flash(__("reviews.not_found"))->error();
            return redirect()->back();
        }

        // Render the update review page
        return view("pages.reviews.update", [
            "review" => $review,
            "oldInput" => collect([
                "rating" => old("rating"),
                "message" => old("message"),
                "public" => old("public"),
            ]),
            "strings" => collect([
                "rating" => __("reviews.form_rating"),
                "message" => __("reviews.form_message"),
                "message_hint" => __("reviews.form_message_hint"),
                "public" => __("reviews.form_public"),
                "cancel" => __("reviews.update_cancel"),
                "submit" => __("reviews.update_submit"),
            ]),
        ]);
    }

    public function postUpdate(UpdateReviewRequest $request, $uuid)
    {
        // Grab the review we want to update
        $review = Reviews::findByUuid($uuid);
        if (!$review)
        {
            flash(__("reviews.not_found"))->error();
            return redirect()->back();
        }

        // Update the review
        $review = Reviews::updateFromRequest($request, $review);

        // Flash message & redirect the user to the view review page
        flash(__("reviews.updated"))->success();
        return redirect()->route("reviews.view", $review->uuid);
    }

    public function getDelete($uuid)
    {
        // Grab the review we want to delete
        $review = Reviews::findByUuid($uuid);
        if (!$review)
        {
            flash(__("reviews.not_found"))->error();
            return redirect()->back();
        }

        // Render the delete review page
        return view("pages.reviews.delete", [
            "review" => $review,
        ]);
    }

    public function postDelete(DeleteReviewRequest $request, $uuid)
    {
        // Grab the review we want to delete
        $review = Reviews::findByUuid($uuid);
        if (!$review)
        {
            flash(__("reviews.not_found"))->error();
            return redirect()->back();
        }

        // Delete the review
        $review->delete();

        // Flash message & redirect user back to my reviews overview
        flash(__("reviews.deleted"))->success();
        return redirect()->route("reviews");
    }
}