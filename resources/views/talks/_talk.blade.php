<li id="talk-{{ $talk->id }}">
	<a href="{{ route('users.show', $user->id )}}">
		<img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar"/>
	</a>
	<span class="user">
		<a href="{{ route('users.show', $user->id )}}">{{ $user->name }}</a>
	</span>
	<span class="timestamp">
		{{ $talk->created_at->diffForHumans() }}
	</span>
	<span class="content">{{ $talk->content }}</span>
	@can('destroy', $talk)
		<form action="{{ route('talks.destroy', $talk->id) }}" method="POST">
			{{ csrf_field() }}
			{{ method_field('DELETE') }}
			<button type="submit" class="btn btn-sm btn-danger talk-delete-btn">删除</button>
		</form>
	@endcan
</li>