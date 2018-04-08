<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class FollowersController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth');
	}

	// 关注用户@post
	public function store(User $user){
		if (Auth::user()->id === $user->id) {
			session()->flash("请不要瞎比操作自己的首页!");
			return redirect('/');
		}
		if (!Auth::user()->isFollowing($user->id)) {
			Auth::user()->follow($user->id);
		}

		return redirect()->route('users.show', $user->id);
	}
	// 取消关注用户@delete
	public function destroy(User $user){
		if (Auth::user()->id === $user->id) {
			session()->flash("请不要瞎比操作自己的首页!");
			return redirect('/');
		}
		if (Auth::user()->isFollowing($user->id)) {
			Auth::user()->unfollow($user->id);
		}

		return redirect()->route('users.show', $user->id);
	}
}
