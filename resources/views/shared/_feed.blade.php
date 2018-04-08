@if (count($feed_items))
<ol class="statuses">
  @foreach ($feed_items as $talk)
    @include('talks._talk', ['user' => $talk->user])
  @endforeach
  {!! $feed_items->render() !!}
</ol>
@endif