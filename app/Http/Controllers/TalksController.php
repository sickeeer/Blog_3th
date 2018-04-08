<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Talk;
use Auth;

class TalksController extends Controller
{
	// 初始化方法
	public function __construct()
	{
		$this->middleware('auth');
	}
	// 新增逻辑处理（post）
	public function store(Request $request)
	{
		$this->validate($request, [
			'content' => 'required|max:140'
		]);

		Auth::user()->talks()->create([
			'content' => $request['content']
		]);
		session()->flash('success', '发表成功。');
		return redirect()->back();
	}
	// 删除逻辑处理
	public function destroy(Talk $talk)
	{
		$this->authorize('destroy', $talk);
		$talk->delete();
		session()->flash('success', '微博已被成功删除！');
		return redirect()->back();
	}
}
