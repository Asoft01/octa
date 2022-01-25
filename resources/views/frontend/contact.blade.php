@extends('frontend.layouts.app')

@section('title', app_name() . ' | Contact')

@section('content')

    <div class="row justify-content-center" style="padding-top: 50px; padding-bottom: 40px; margin-left: 0px; margin-right: 0px;">
        <div class="col col-sm-8 align-self-center">

            @include('includes.partials.messages')

            <div class="card" style="background: #161424; border: 2px solid rgba(255,255,255, 0.04); box-shadow: 0px 26px 30px rgba(0,0,0, 0.09);">
                <div class="card-header modal-header-pro" style="background: #161424;">
                    <h2 style="font-size: 24px;">
                        @lang('labels.frontend.contact.box_title')
                    </h2>
                    <p style="margin-top: 12px; margin-bottom: 0px;">Your feedback matters to us! Let us know what you think about the platform, suggestions for new educational content or any issues you encounter during navigation.</p>
                </div><!--card-header-->

                <div class="card-body">
                    {{ html()->form('POST', route('frontend.contact.send'))->open() }}
                        <div class="row" style="margin-top: -24px;">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.name'))->for('name') }}

                                    {{ html()->text('name', optional(auth()->user())->name)
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.name'))
                                        ->attribute('maxlength', 191)
                                        ->required()
                                        ->autofocus() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}

                                    {{ html()->email('email', optional(auth()->user())->email)
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.email'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        {{--<div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.phone'))->for('phone') }}

                                    {{ html()->text('phone')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.phone'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->--}}

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.message'))->for('message') }}

                                    {{ html()->textarea('message')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.message'))
                                        ->attribute('rows', 10)
                                        ->required() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        @if(config('access.captcha.contact'))
                            <div class="row">
                                <div class="col">
                                    @captcha
                                    {{ html()->hidden('captcha_status', 'true') }}
                                </div><!--col-->
                            </div><!--row-->
                        @endif

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit(__('labels.frontend.contact.button')) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
                </div><!--card-body-->
            </div><!--card-->
        </div><!--col-->
    </div><!--row-->

    {{-- LOGIN --}}
	@include('frontend.includes.login')

    {{-- UP --}}
    <a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
@endsection
