@extends('frontend.layouts.app')

@section('title', app_name() . ' | Contact')

@section('content')

    <div class="row justify-content-center" style="padding-top: 40px; padding-bottom: 40px; margin-left: 0px; margin-right: 0px;">
        <div class="col col-sm-8 align-self-center">

        <div style="float: right;"><a href="{{ route('frontend.user.manage') }}"><i class="fas fa-cogs"></i>Manage my reviews</a></div>
        <p style="font-size: 24px; line-height: 22px;">
                @if(auth()->user()->hasRole('mentor'))
                    <span style="font-size: 16px">Requested by:</span><br />
                    {{ $delivery->user->first_name }} {{ $delivery->user->last_name }}</p>
                @else
                    @if($delivery->reviewer)
                        <span style="font-size: 16px">Reviewer:</span><br />
                        {{ $delivery->reviewer->user->first_name }} {{ $delivery->reviewer->user->last_name }}</p>
                    @endif
                @endif
            <p style="margin-top: 2px;">


                @if($delivery->order_item->stream_id!=null)
                    Due date: {{ timezone()->convertToLocal(new \Carbon\Carbon($delivery->order_item->stream_start_time)) }}<br />
                @else
                    @if($delivery->status_id == 4)
                        @if(isset($delivery->completed_date))
                            Completed date: {{ $delivery->completed_date->diffForHumans() }}</br>
                        @endif
                    @else
                        @if(auth()->user()->hasRole('mentor'))
                            Due date: {{ $delivery->due_date?$delivery->due_date->subDays(3)->format('Y-m-d'):'' }}</br>
                        @else
                            Due date: Within {{ $delivery->due_date?$delivery->due_date->diffForHumans():'' }}</br>
                        @endif
                    @endif

                @endif

                @if($delivery->order_item->zoom_data!=null)
                    @php

                        $zoom_data=json_decode($delivery->order_item->zoom_data)

                    @endphp

                        @if($zoom_data && $zoom_data->status=='success')
                            Stream Link : <a href="{{$zoom_data->meeting_url}}">Meeting Link</a><br />
                        @endif

                    @endif

                <?php echo $delivery->order_item->product->isLive == true ? "Live" : "Pre-recorded"; ?> review<br />
                {{ $delivery->product->description }} <i>~{{ substr($delivery->order_item->quantity, 0, -3) }} minutes</i><br />
                Visibility: <?php echo $delivery->order_item->product->isPublic == true ? "Public" : "Private"; ?><br />
                @if(auth()->user()->hasRole('mentor'))
                    Skill level: {{ $delivery->level }}
                @endif
            </p>

            @if($delivery->status_id == 2 && $delivery->note)
                <h1>Note</h1>
                <p>{{ $delivery->note }}</p>
            @endif

            @if($delivery->status_id == 4)
                <h1>Review video</h1>
            @else
                <h1>Video</h1>
            @endif



            <div style="">
                @if($delivery->status_id == 4)
                    <a href="{{ config('ac.CDN_MEDIA') }}{{ $delivery->content->contentable->video }}" download="{{ config('ac.CDN_MEDIA') }}{{ $delivery->content->contentable->video }}">
                @else
                    <a href="{{ config('ac.CDN_MEDIA') }}{{ 'uploadReviews/'.$delivery->videoToReview }}" download="{{ config('ac.CDN_MEDIA') }}{{ 'uploadReviews/'.$delivery->videoToReview }}">
                @endif

                <i class="fas fa-download"></i> Download video</a>
                <span style="font-size: 10px;">Right-click -> Save link as...</span>
            </div>

            <div id="video-embedded-container" style="max-width: 1200px; padding-top: 20px;">
                @if($delivery->status_id == 4)
                    <div style="margin-bottom: 24px;">
                    <a href="{{ $delivery->content->contentable->syncsketch }}" target="_blank" style="color: white;">
                    <div id="vsyncsketch" class="btn btn-slider-pro" style="float: none;">
                        <img src="{{ config('ac.CDN_MEDIA') }}{{ 'img/frontend/syncsketch.png' }}" style="max-width: 24px; vertical-align: middle; padding-top: 0px; margin-right: 8px;"> SyncSketch
                    </div>
                    </a>
                    </div>

                    <video id="Video-Vayvo-Single" style="height:auto; width: 100%" poster="{{ config('ac.CDN_MEDIA') }}{{ $delivery->content->contentable->poster }}" preload="true" data-autoresize="fit" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true, "playbackRates": [0.5, 1, 1.5, 2]}'>
                        <source src="{{ config('ac.CDN_MEDIA') }}{{ $delivery->content->contentable->video }}" type="video/mp4">
                    </video>
                @else
                    <video id="Video-Vayvo-Single" style="height:auto; width: 100%" preload="auto" data-autoresize="fit" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true, "playbackRates": [0.5, 1, 1.5, 2]}'>
                        <source src="{{ config('ac.CDN_MEDIA') }}{{ 'uploadReviews/'.$delivery->videoToReview }}" type="video/mp4">
                    </video>
                @endif

			</div><!-- clolse #video-embedded-container -->








        </div><!--col-->
    </div><!--row-->

    {{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
@endsection

@push('after-scripts')

@endpush
