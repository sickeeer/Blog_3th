<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talk extends Model
{
	protected $fillable = ['content'];
	// 单对单配置
	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
