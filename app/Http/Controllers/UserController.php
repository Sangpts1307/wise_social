<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\User;
use App\Models\Friend;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    private $apiResponse;

    public function __construct() {
        $this->apiResponse = new ApiResponse();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * 
     * @return string JSON
     */
    public function show(Request $request)
    {
        $userId = Auth::id();
        if (!$userId) {
            return $this->apiResponse->UnAuthoriration();
        }
        $user = User::with('follower', 'follows')
            ->select(
                'id', 'name', 'email',
                'avatar', 'overview'
            )->where('id', $userId)->first();
        $user->followers = count($user->follower);
        $user->following = count($user->follows);
        unset($user->follower, $user->follows);
        $folderAvatar = null;
        if (!is_null($user->avatar)) {
            $folderAvatar = explode('@', $user->email);
            $user->avatar = url(
                'avatars/' . $folderAvatar[0] . '/' . $user->avatar
            );
        }
        return $this->apiResponse->success($user);
    }

    /**
     * Controller list suggest friend
     * @param 
     * 
     */
    public function suggestFriend(Request $request)
    {
        $userId = Auth::id();
        // List friend id 
        $listFriendId = DB::table('friends')
            ->where('user_id', $userId)
            ->select('friend_id')
            ->pluck('friend_id')->toArray();
        $listFriendId[] = $userId; // list include userId self 
        // List suggest friend
        $suggests = User::with([
            'experiences' => function ($experienceQuery) {
                return $experienceQuery->select('id', 'user_id', 'title');
            }
        ])->whereNotIn('id', $listFriendId)
            ->where('status', User::STATUS_ACTIVE)
            ->select(
                'id', 'name', 'avatar', 
                'created_at'
            )->orderBy('created_at', 'ASC')
            ->limit(config('constant.limit'))
            ->get();
        if (count($suggests) > 0) {    // Check null before list
            foreach ($suggests as $user) {
                $folderAvatar = null;
                if (!is_null($user->avatar)) {
                    $folderAvatar = explode('@', $user->email);
                    $user->avatar = url(
                        'avatars/' . $folderAvatar[0] . '/' . $user->avatar
                    );
                }
                $txtExperience = '';
                $i = 1;
                foreach ($user->experiences as $experience) {
                    if ($i < count($user->experiences)) {
                        $txtExperience .= $experience->title . ', ';
                    } else {
                        $txtExperience .= $experience->title;
                    }
                    $i++;
                }
                $user->experience= $this->truncateString($txtExperience, 20);
                unset($user->experiences);
            }
        }
        return $this->apiResponse->success($suggests);
    }

    private function truncateString($string, $length, $append = '...')
    {
        if (mb_strlen($string) > $length) {
            return mb_substr($string, 0, $length) . $append;
        }
        return $string;
    }

    /**
     * Controller method add friend
     * 
     * @param 
     * 
     */
    public function addFriend(Request $request)
    {
        $param = $request->all();
        try {
            DB::beginTransaction();
            $friend = new Friend();
            $friend->user_id = Auth::id();
            $friend->friend_id = $param['friend_id'];
            $friend->approved = Friend::UNUPPROVED;
            $friend->created_at = Carbon::now();
            $friend->save();
            DB::commit();
            return $this->apiResponse->success();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return $this->apiResponse->InternalServerError();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
