<!-- {   { d  d($user) }  } -->
<p>{{ $user->name }} - {{ $user->email }}</p>
<p>{{ $user->introduction }}</p>
<p>since {{$user->created_at->diffForHumans()}} | last Login {{isset($user->last_login_time)?$user->last_login_time->diffForHumans():"记录异常"}}</p>
<img src="{{ $user->gravatar('140') }}" alt="{{ $user->name }}" class="gravatar"/>
<img class="thumbnail img-responsive" src="{{ $user->avatar }}" width="300px" height="300px">
