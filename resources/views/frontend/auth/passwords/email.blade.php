@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.passwords.reset_password_box_title'))

@section('content')
    <div class="row justify-content-center" style="padding-top: 50px; padding-bottom: 40px;">
        <div class="col col-sm-6 align-self-center">

            @include('includes.partials.messages')

            <div class="card" style="background: #161424; border: 2px solid rgba(255,255,255, 0.04); box-shadow: 0px 26px 30px rgba(0,0,0, 0.09);">
                <div class="card-header modal-header-pro" style="background: #161424; margin-bottom: -8px;">
                    <h2>
                        @lang('labels.frontend.passwords.reset_password_box_title')
                    </h2>
                </div><!--card-header-->

                <div class="card-body">

                    @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ html()->form('POST', route('frontend.auth.password.email.post'))->open() }}
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    {{ html()->label(__('validation.attributes.frontend.email'))->for('email') }}

                                    {{ html()->email('email')
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.email'))
                                        ->attribute('maxlength', 191)
                                        ->required()
                                        ->autofocus() }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->

                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-0 clearfix">
                                    {{ form_submit(__('labels.frontend.passwords.send_password_reset_link_button')) }}
                                </div><!--form-group-->
                            </div><!--col-->
                        </div><!--row-->
                    {{ html()->form()->close() }}
                </div><!-- card-body -->
            </div><!-- card -->
        </div><!-- col-6 -->
    </div><!-- row -->

    {{-- LOGIN --}}
    @include('frontend.includes.login')
    
@endsection
