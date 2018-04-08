<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;
use App\Handlers\ImageUploadHandler;

class UserController extends Controller
{

	public function __construct()
	{
		// 登录权限判断 except 排除
		$this->middleware('auth', [
			'except' => ['show', 'signup','store','index','login','logValidate','logout','confirmEmail']
		]);
		// 游客权限 非游客无法访问 signup login路由
		$this->middleware('guest', [
			'only' => ['signup','login']
		]);
	}

// 注册页面
	public function signup(){
		return view('user.signup');
	}
// 用户信息页（个人中心）
	public function show(User $user){
		$talks = $user->talks()
						->orderBy('created_at', 'desc')
						->paginate(30);
		return view('user.show', compact('user','talks'));
	}
// 注册逻辑处理（post）
	public function store(Request $request)
	{
		$this->validate($request, [
			'name' => 'required|max:50',
			'email' => 'required|email|unique:users|max:255',
			'password' => 'required|confirmed|min:6',
			'captcha' => 'required|captcha',
		], [
			'captcha.required' => '验证码不能为空',
			'captcha.captcha' => '请输入正确的验证码',
		]);
		/**
		$data = $request->all();
		var_dump($data);
			array (size=5)
			  '_token' => string 'jcab7GK99CBvZXs6VSw0wx5sBdJVNHY1gfMl8p59' (length=40)
			  'name' => string 'sickeeer' (length=8)
			  'email' => string '111123@qq.com' (length=13)
			  'password' => string '123456' (length=6)
			  'password_confirmation' => string '123456' (length=6)
		**/
		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);
		// 登录
		$this->sendEmailConfirmationTo($user);
		session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
		return redirect('/');
	}
// 登录页面
	public function login(){
		return view('user.login');
	}
// 登录逻辑处理（post）
	public function logValidate(Request $request){
		$credentials = $this->validate($request, [
			'email' => 'required|email|max:255',
			'password' => 'required'
		]);
		if (Auth::attempt($credentials, $request->has('remember'))) {
			if(Auth::user()->activated) {
				session()->flash('success', '欢迎回来！');
				return redirect()->intended(route('users.show', [Auth::user()]));
			} else {
				Auth::logout();
				session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
				return redirect('/');
			}
		} else {
			session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
			return redirect()->back();
		}
	}
// 登出逻辑处理
	public function logout(){
		Auth::logout();
		session()->flash('success', '您已成功退出！');
		return redirect('login');
	}
// 用户修改资料页面
	public function edit(User $user)
	{
		// 权限判断 \App\Policies\UserPolicy::update
		// \app\Providers\AuthServiceProvider::$policies 添加提供
		$this->authorize('update', $user);
		return view('user.edit', compact('user'));
	}
// 用户修改资料逻辑处理（patch）
	// public function update(UserRequest $request, User $user) 权限与表单验证采用 UserRequest 策略
	public function update(User $user,ImageUploadHandler $uploader, Request $request)
	{
		$this->validate($request, [
			'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
			// 'email' => 'required|email',
			'password' => 'nullable|confirmed|min:6',
			'introduction' => 'max:80',
			'avatar' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200',

		]);
		//  权限判断 \app\policies\UserPolicy::update
		// \app\Providers\AuthServiceProvider::$policies 添加提供
		$this->authorize('update', $user);
		$data = [];
		$data['name'] = $request->name;
		if ($request->password) {
			$data['password'] = bcrypt($request->password);
		}
		$data['introduction'] = $request->introduction;
		if ($request->avatar) {
			$result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
			if ($result) {
				$data['avatar'] = $result['path'];
			}
		}
		$user->update($data);
		
		// session()->flash('success', '个人资料更新成功！');
		// return redirect()->route('users.show', $user->id);
		// 效果与下等同


		return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
	}
// 用户列表页面
	public function index()
	{
		// $users = User::all();
		$users = User::paginate(3);
		return view('user.index', compact('users'));
	}
// 管理员删除用户逻辑处理
	public function destroy(User $user)
	{
		$this->authorize('destroy', $user);
		$user->delete();
		session()->flash('success', '成功删除用户！');
		return back();
	}
// 验证邮箱逻辑处理
	public function confirmEmail($token)
	{
		$user = User::where('activation_token', $token)->firstOrFail();

		$user->activated = true;
		$user->activation_token = null;
		$user->save();

		Auth::login($user);
		session()->flash('success', '恭喜你，激活成功！');
		return redirect()->route('users.show', [$user]);
	}
// 邮件发送逻辑处理
	protected function sendEmailConfirmationTo($user)
	{
		$view = 'mail.confirm';
		$data = compact('user');
		$to = $user->email;
		$subject = "感谢注册 Sample 应用！请确认你的邮箱。";
		Mail::send($view, $data, function ($message) use ($to, $subject) {
			$message->to($to)->subject($subject);
		});
	}
// 关注页面
	public function followings(User $user)
	{
		$users = $user->followings()->paginate(30);
		$title = '关注的人';
		return view('user.show_follow', compact('users', 'title'));
	}
// 粉丝页面
	public function followers(User $user)
	{
		$users = $user->followers()->paginate(30);
		$title = '粉丝';
		return view('user.show_follow', compact('users', 'title'));
	}
}
