@extends('layouts.default')
@section('title', $user->name)
@section('content')
<div class="row">
  <div class="col-md-offset-2 col-md-8">
	<div class="col-md-12">
	  <div class="col-md-offset-2 col-md-8">
		<section class="user_info">
		  @include('shared._user_info', ['user' => $user])
		</section>
		<section class="stats">
          @include('shared._stats', ['user' => $user])
        </section>
	  </div>
	</div>
	<div class="col-md-12">

	  @if (Auth::check())
		@include('user._follow_form')
	  @endif
	  @if (count($talks) > 0)
		<ol class="talks">
		  @foreach ($talks as $talk)
			@include('talks._talk')
		  @endforeach
		</ol>
		{!! $talks->render() !!}
	  @endif
	</div>
  </div>
</div>
@stop