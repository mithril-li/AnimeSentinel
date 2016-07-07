@extends('layouts.app')
@section('title', 'Recently Uploaded')

@section('content-center')
  <div class="content-header">Recently Uploaded</div>
  @foreach($shows as $show)
    <div class="synopsis-panel">
      <div class="row">
        <div class="col-sm-2">
          <a href="{{ $show->details_url }}">
            <img class="img-thumbnail synopsis-thumbnail" src="{{ url('/media/thumbnails/'.$show->id) }}" alt="{{ $show->title }} - Thumbnail">
          </a>
        </div>
        <div class="col-sm-10">
          <div class="synopsis-title"><a href="{{ $show->details_url }}">{{ $show->title }}</a></div>
          <div class="synopsis-details">
            {!! $show->description !!}
          </div>
          <div class="synopsis-episodes">
            <div class="row">
              <div class="col-sm-4">
                <a href="{{ url("/anime/$show->id/$show->this_translation/episode-$show->this_episode") }}">
                  Episode {{ $show->this_episode }} Has Aired
                </a>
              </div>
              <div class="col-sm-4">
                Uploaded Episode Type: {{ $show->this_translation === 'sub' ? 'Subbed' : '' }} {{ $show->this_translation === 'dub' ? 'Dubbed' : ''}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
@endsection
