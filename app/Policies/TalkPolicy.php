<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Talk;

class TalkPolicy
{
	use HandlesAuthorization;

	/**
	 * Create a new policy instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}
	public function destroy(User $user, Talk $talk)
	{
		return $user->id === $talk->user_id;
	}
}