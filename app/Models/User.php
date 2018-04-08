<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword;
use Auth;

class User extends Authenticatable
{
	use Notifiable;
	protected $dates = ['last_login_time'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password','introduction','avatar'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];
// boot 方法会在用户模型类完成初始化之后进行加载
	public static function boot()
	{
		parent::boot();

		static::creating(function ($user) {
			$user->activation_token = str_random(30);
		});
	}
// gravatar 网络图片 用以建立头像图片
	public function gravatar($size = '100')
	{
		$hash = md5(strtolower(trim($this->attributes['email'])));
		return "http://www.gravatar.com/avatar/$hash?s=$size";
	}
// 发送密码重置通知
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPassword($token));
	}
// 单对多配置 微博
	public function talks()
	{
		return $this->hasMany(Talk::class);
	}
// 单对多配置 粉丝
	public function followers()
	{
		return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
	}
// 单对多配置 关注
	public function followings()
	{
		return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
	}
// 关注逻辑 （利用单对多默认方法）
	public function follow($user_ids)
	{
		if (!is_array($user_ids)) {
			$user_ids = compact('user_ids');
		}
		$this->followings()->sync($user_ids, false);
	}
// 取消关注逻辑 （利用单对多默认方法）
	public function unfollow($user_ids)
	{
		if (!is_array($user_ids)) {
			$user_ids = compact('user_ids');
		}
		$this->followings()->detach($user_ids);
	}
// 判断是否关注 （利用单对多默认方法）
	public function isFollowing($user_id)
	{
		return $this->followings->contains($user_id);
	}
// 关注者微博列表
	public function feed()
	{
		$user_ids = Auth::user()->followings->pluck('id')->toArray();
		// var_dump($user_ids);
		array_push($user_ids, Auth::user()->id);
		// var_dump($user_ids);
		return Talk::whereIn('user_id', $user_ids)
							  ->with('user')
							  ->orderBy('created_at', 'desc');
	}
}
