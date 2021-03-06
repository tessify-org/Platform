<?php

namespace App\Services\ModelServices;

use Auth;
use Uuid;
use Tags;
use Skills;
use Avatar;
use Projects;
use Uploader;
use Assignments;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Project;
use App\Traits\ModelServiceGetters;
use App\Contracts\ModelServiceContract;
use App\Jobs\Auth\SendAccountRecoveryEmail;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Admin\Users\BanUserRequest;
use App\Http\Requests\Profiles\UpdateProfileRequest;
use App\Http\Requests\Admin\Users\CreateUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Http\Requests\Admin\Users\DeleteUserRequest;
use App\Http\Requests\Settings\ChangePasswordRequest;

use Illuminate\Validation\ValidationException;

class UserService implements ModelServiceContract
{
    use ModelServiceGetters;

    private $model;
    private $records;
    private $preloadedRecords;

    public function __construct()
    {
        $this->model = "App\Models\User";
    }

    public function current()
    {
        $user = Auth::user();
        if ($user)
        {
            return $this->findPreloaded($user->id);
        }

        return false;
    }

    public function preload($instance)
    {
        // Add link to the user's profile page
        $instance->profile_href = route("profile", $instance->slug);
        
        // Manually load the dynamic attributes
        $instance->formatted_name = $instance->formattedName;
        $instance->formatted_has_been_checked = $instance->has_been_checked 
            ? __("general.yes")
            : __("general.no");
        // $instance->formatted_

        // TODO: load relationships.. not necessary yet
        $instance->skills = Skills::getAllForUser($instance);
        $instance->assignments = Assignments::findAllPreloadedForUser($instance);
        $instance->interests = Tags::getAllForUser($instance);

        // Return the upgraded user
        return $instance;
    }

    public function getAllNotInProjectTeam(Project $project)
    {
        $out = [];

        foreach ($this->getAllPreloaded() as $user)
        {
            if (!Projects::isTeamMember($user, $project))
            {
                $out[] = $user;
            }
        }

        return collect($out);
    }

    public function findAuthorForProject(Project $project)
    {
        foreach ($this->getAllPreloaded() as $user)
        {
            if ($user->id == $project->author_id)
            {
                return $user;
            }
        }

        return false;
    }

    public function findUserByEmail($email)
    {
        foreach ($this->getAll() as $user)
        {
            if ($user->email == $email)
            {
                return $user;
            }
        }

        return false;
    }

    public function findBySlug($slug)
    {
        foreach ($this->getAll() as $user)
        {
            if ($user->slug == $slug)
            {
                return $user;
            }
        }

        return false;
    }

    public function findPreloadedBySlug($slug)
    {
        foreach ($this->getAllPreloaded() as $user)
        {
            if ($user->slug == $slug)
            {
                return $user;
            }
        }

        return false;
    }

    public function createFromRegisterRequest(RegisterRequest $request)
    {
        $user = User::create([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "password" => $request->password,
        ]);

        $user = $this->generateAvatar($user);

        return $user;
    }

    public function generateAvatar(User $user)
    {
        $filename = Uploader::generateFileName("jpg");
        $filepath = "storage/images/avatars/".$filename;
        
        $avatar = Avatar::create($user->formattedName)->save(public_path($filepath), 100);
        
        $user->avatar_url = $filepath;
        $user->save();

        return $user;
    }

    public function sendRecoverAccountEmail($email)
    {
        $user = $this->findUserByEmail($email);
        
        $user->recovery_code = Uuid::generate();
        $user->save();

        SendAccountRecoveryEmail::dispatch($user)->onQueue("emails");
    }

    public function emailExists($email)
    {
        return User::where("email", $email)->first() ? true : false;
    }

    public function recoveryCodeIsValid($email, $code)
    {
        $user = $this->findUserByEmail($email);
        if ($user and $user->recovery_code == $code)
        {
            return true;
        }
        return false;
    }

    public function resetPassword(User $user, ResetPasswordRequest $request)
    {
        $user->password = bcrypt($request->password);
        $user->save();
        return $user;
    }

    public function updateProfileFromRequest(UpdateProfileRequest $request, User $user = null)
    {
        // Grab the user
        if (is_null($user)) $user = Auth::user();

        // Update the user's direct attributes
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->headline = $request->headline;
        $user->about_me = $request->about_me;
        $user->email = $request->email;
        $user->publicly_display_email = $request->publicly_display_email == "true" ? true : false;
        $user->phone = $request->phone;
        $user->save();

        // Process interests (tag ids)
        $tag_ids = [];
        $tag_names = json_decode($request->interests);
        if (is_array($tag_names) && count($tag_names))
        {
            foreach ($tag_names as $tag_name)
            {
                $tag = Tags::findOrCreateByName($tag_name);
                if ($tag)
                {
                    $tag_ids[] = $tag->id;
                }
            }
        }
        $user->interests()->sync($tag_ids);

        // Process skills
        if ($request->skills !== "[]")
        {
            $skills = json_decode($request->skills);
            if (is_array($skills) and count($skills))
            {
                $user->skills()->detach();

                foreach ($skills as $skillData)
                {
                    $skill = Skills::findOrCreateByName($skillData->skill);

                    $user->skills()->attach($skill->id, [
                        "mastery" => $skillData->mastery,
                        "description" => $skillData->description,
                    ]);
                }
            }
        }
        
        // Return the updated user
        return $user;
    }

    public function getFollowers(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $out = [];

        foreach ($user->followers()->get() as $u)
        {
            $out[] = $this->preload($u);
        }

        return collect($out);
    }

    public function getFollowing(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $out = [];

        foreach ($user->followings(User::class)->get() as $u)
        {
            $out[] = $this->preload($u);
        }

        return collect($out);
    }
    
    public function banPermanently(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $user->permabanned = true;
        $user->save();

        return $user;
    }

    public function banTemporarily($numDays = 1, User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $user->banned_until = now()->addDays($numDays)->format("Y-m-d H:i:s");
        $user->save();

        return $user;
    }

    public function unban(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();

        $user->permabanned = false;
        $user->banned_until = null;
        $user->save();

        return $user;
    }

    public function flagAsChecked(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();
        
        if (!$user->has_been_checked)
        {
            $user->has_been_checked = true;
            $user->save();
        }

        return $user;
    }

    public function flagAsUnchecked(User $user = null)
    {
        if (is_null($user)) $user = Auth::user();
        
        if ($user->has_been_checked)
        {
            $user->has_been_checked = false;
            $user->save();
        }

        return $user;
    }

    public function createFromAdminRequest(CreateUserRequest $request)
    {
        return User::create([
            "is_admin" => $request->is_admin == "true" ? true : false,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "headline" => $request->headline,
            "interests" => $request->interests,
        ]);
    }

    public function updateFromAdminRequest(User $user, UpdateUserRequest $request)
    {
        $user->is_admin = $request->is_admin == "true" ? true : false;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->headline = $request->headline;
        $user->interests = $request->interests;
        
        if ($request->has("password") and $request->password != "") $user->password = bcrypt($request->password);

        $user->save();

        return $user;
    }

    public function userWithFormattedNameExists($formattedName)
    {
        foreach ($this->getAll() as $user)
        {
            if ($user->formattedName == $formattedName)
            {
                return true;
            }
        }

        return false;
    }

    public function findUserByFormattedName($formattedName)
    {
        foreach ($this->getAll() as $user)
        {
            if ($user->formattedName == $formattedName)
            {
                return $user;
            }
        }

        return false;
    }

    public function changePasswordFromRequest(ChangePasswordRequest $request)
    {
        // Grab the logged in user
        $user = auth()->user();

        // Validate the supplied current password
        if (!app("hash")->check($request->password, $user->password))
        {
            // Throw the exception
            throw ValidationException::withMessages([
                "password" => __("settings.change_password_incorrect_password"),
            ]);
        }

        // Change the password
        $user->password = bcrypt($request->new_password);
        $user->save();
    }
}