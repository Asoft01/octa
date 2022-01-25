@extends('frontend.layouts.app')

@section('title', app_name() . ' | Contact')

@section('content')

    <div class="row justify-content-center" style="padding-top: 40px; padding-bottom: 40px; margin-left: 0px; margin-right: 0px;">
        <div class="col col-sm-8 align-self-center">

            <h1>Manage My Reviews</h1>

            <h3>Pending reviews</h3>
            @if(count($deliveries->where('status_id', 2)))
            <div>


                <div class="row" style="margin-bottom: 42px;">


                    @foreach($deliveries->where('status_id', 2) as $d)
                    <div class="col col-12 col-md-4 col-lg-4" style="border-radius: 25px; margin-bottom: 12px;">
                        <div class="ac-video-index-container" style="padding: 20px;">
                            <a href="{{ route('frontend.user.review', ['review' => $d->order->uuid]) }}">

                                <div class="progression-video-index-content">
                                    <div class="progression-video-index-table">
                                        <div class="progression-video-index-vertical-align">

                                            <div style="color: white;">
                                                <p style="font-size: 24px; line-height: 22px;">
                                                    @if(auth()->user()->hasRole('mentor'))
                                                        <span style="font-size: 16px">Requested by:</span><br />
                                                        {{ $d->user->first_name }} {{ $d->user->last_name }}</p>
                                                    @else
                                                        @if($d->reviewer)
                                                        <span style="font-size: 16px">Reviewer:</span><br />
                                                        {{ $d->reviewer->user->first_name }} {{ $d->reviewer->user->last_name }}</p>
                                                        @endif
                                                    @endif
                                                <p style="margin-top: 2px;">
                                                    @if(auth()->user()->hasRole('mentor'))
                                                        @if($d->due_date)
                                                            Due date: {{ $d->due_date->subDays(3)->format('Y-m-d') }}</br>
                                                        @endif
                                                    @else
                                                        @if($d->due_date)
                                                            Due date: Within {{ $d->due_date->diffForHumans() }}</br>
                                                        @endif
                                                    @endif
                                                    <?php echo $d->order_item->product->isLive == true ? "Live" : "Pre-recorded"; ?> review<br />
                                                    {{ $d->product->description }} <i>~{{ substr($d->order_item->quantity, 0, -3) }} minutes</i><br />
                                                    Visibility: <?php echo $d->order_item->product->isPublic == true ? "Public" : "Private"; ?><br />
                                                    @if(auth()->user()->hasRole('mentor'))
                                                        Skill level: {{ $d->level }}
                                                    @endif
                                                </p>


                                            </div>


                                            <div class="clearfix"></div>

                                        </div><!-- close .progression-video-index-vertical-align -->
                                    </div><!-- close .progression-video-index-table -->
                                </div><!-- close .progression-video-index-content -->
                                <div class="video-index-border-hover" style="pointer-events: none; z-index:1001; border: solid 2px #383838; border-radius: 25px;"></div>
                            </a>

                        </div><!-- close .ac-video-index-container  -->
                    </div><!-- close .item -->
                    @endforeach


                </div>


            </div>
            @else
                <p>You don't have any pending reviews, <a href="{{ route('frontend.user.order') }}">request one here</a></p>
            @endif





            <h3>Your reviews</h3>

            @if(count($deliveries->where('status_id', 4)))
            <div>
                <div class="row" style="margin-bottom: 42px;">


                    @foreach($deliveries->where('status_id', 4) as $d)

                    <div class="col col-12 col-md-4 col-lg-4">
                        <div class="ac-video-index-container">
                            <a href="{{ route('frontend.user.review', ['review' => $d->order->uuid]) }}">
                                <div class="ac-video-feaured-image">
                                    @if(isset($d->content->contentable->thumb))
                                        <img src="{{ config('ac.SIH') }}{{ config('ac.THUMB_RES') }}{{ $d->content->contentable->thumb }}" style="postion: absolute;">
                                    @else
                                        <img src="{{ config('ac.SIH') }}{{ config('ac.THUMB_RES') }}{{ $d->content->contentable->poster }}" style="postion: absolute;">
                                    @endif
                                </div>

                                <div class="progression-video-index-content">
                                    <div class="progression-video-index-table">
                                        <div class="progression-video-index-vertical-align">

                                            <div style="color: white; text-shadow: 0 0 4px #000;">
                                                <p style="font-size: 24px; line-height: 22px;">
                                                    @if(auth()->user()->hasRole('mentor'))
                                                        <span style="font-size: 16px">Requested by:</span><br />
                                                        {{ $d->user->first_name }} {{ $d->user->last_name }}</p>
                                                    @else
                                                        <span style="font-size: 16px">Reviewer:</span><br />
                                                        {{ $d->reviewer->user->first_name }} {{ $d->reviewer->user->last_name }}</p>
                                                    @endif
                                                <p style="margin-top: 2px;">
                                                    @if(isset($d->completed_date))
                                                        Completed date: {{ $d->completed_date->diffForHumans() }}<br />
                                                    @endif
                                                    <?php echo $d->order_item->product->isLive == true ? "Live" : "Pre-recorded"; ?> review<br />
                                                    Length: {{ substr($d->order_item->quantity, 0, -3) }} minutes<br />
                                                    Visibility: <?php echo $d->order_item->product->isPublic == true ? "Public" : "Private"; ?>
                                                </p>
                                            </div>


                                            <div class="clearfix"></div>

                                        </div><!-- close .progression-video-index-vertical-align -->
                                    </div><!-- close .progression-video-index-table -->
                                </div><!-- close .progression-video-index-content -->
                                <div class="video-index-border-hover"></div>
                            </a>

                        </div><!-- close .ac-video-index-container  -->
                    </div><!-- close .item -->
                    @endforeach


                </div>
            </div>
            @else
                <p>You don't have any review.</p>
            @endif




        </div><!--col-->
    </div><!--row-->

    {{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
@endsection

@push('after-scripts')
    @if(config('access.captcha.contact'))
        @captchaScripts
    @endif
@endpush
