<header class="am-topbar am-topbar-inverse">
	<div class="blog-g-fixed">
		<h1 class="am-topbar-brand">
			<a href="/">Sickeeer's Blog!</a>
		</h1>
		<button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span	class="am-icon-bars"></span></button>
		<div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
			<ul class="am-nav am-nav-pills am-topbar-nav">
				<li class="am-active"><a href="/">首页</a></li>
				<li><a href="/article">文章</a></li>
				<!-- <li><a href="tags.html">标签</a></li> -->
				<li class="am-dropdown" data-am-dropdown>
					<a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">更多</a>
					<ul class="am-dropdown-content">
						<li><a href="{{ route('help') }}">我</a></li>
						<li><a href="/post.html">投稿</a></li>
					</ul>
				</li>
			</ul>
			<!-- <form style="margin-bottom:0" class="am-topbar-form am-topbar-left am-form-inline am-topbar-right" role="search">
				<div class="am-form-group">
					<input type="text" class="am-form-field am-input-sm" placeholder="搜索文章">
				</div>
				<button type="submit" class="am-btn am-btn-default am-btn-sm">搜索</button>
			</form> -->

			<div class="am-topbar-right">
			  <div class="am-dropdown" data-am-dropdown="{boundary: '.am-topbar'}">
				<button class="am-btn am-btn-secondary am-topbar-btn am-btn-sm am-dropdown-toggle" data-am-dropdown-toggle>其他 <span class="am-icon-caret-down"></span></button>
				<ul class="am-dropdown-content">
				  <li><a href="{{ route('help') }}">帮助</a></li>
				  <li><a href="#">随便看看</a></li>
				</ul>
			  </div>
			</div>
		<!-- @ - if (!Auth::check()) 等同-->
		@guest
		<!-- @ - guest -->
			<div class="am-topbar-right">
			  <button onclick="location.href='{{ route('login') }}'" class="am-btn am-btn-primary am-topbar-btn am-btn-sm">登录</button>
			</div>
			
		@else
			<div class="am-topbar-right">
				<a href=""> {{ Auth::user()->name }}</a>
			</div>
			<div class="am-topbar-right">
				<form action="{{ route('logout') }}" method="POST">
							  {{ csrf_field() }}
							  {{ method_field('DELETE') }}
					<button class="btn btn-block btn-danger" type="submit" name="button">退出</button>
				</form>
			</div>
		<!-- @ - endif -->
		@endguest
		<!-- @ - endguest -->
		  </div>
		</div>
	</div>
</header>