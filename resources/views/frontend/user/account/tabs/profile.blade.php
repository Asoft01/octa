@if(session()->get('flash_success'))
<div class="alert alert-success" role="alert" style="display: table; width: 100%; height:100px;">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="position: absolute; right: 6px; top: 0px;">
        <span aria-hidden="true">&times;</span>
    </button>
    @if(is_array(json_decode(session()->get('flash_success'), true)))
        <p style="display: table-cell; vertical-align: middle;">
        {!! implode('', session()->get('flash_success')->all(':message<br/>')) !!}
        </p>
    @else
        <p style="display: table-cell; vertical-align: middle;">
        {!! session()->get('flash_success') !!}
        </p>
    @endif
</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="table-responsive bg-dark text-white" style="margin-top: 16px;">

    {{ html()->modelForm($logged_in_user, 'PATCH', route('frontend.user.profile.update'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
        <table class="table table-striped table-bordered bg-dark text-white" style="border: 1px solid #494949 !important;">
            @if(isset($sub))
                <tr>
                    <th>Email&nbsp;preferences</th>
                    <td>
                        @foreach($sublist as $s)
                            
                            @if($s == "Announcement")
                                <label style="margin-bottom: 0px; margin-top: 8px;"><input type="checkbox" name="sub[]" value="{{ $s }}" @if(in_array($s, $sub)) checked @endif/>
                                    &nbsp;&nbsp;<strong>Special announcement</strong>
                                </label>
                                <div style="margin-top: 0px; font-style: italic; line-height: 20px;">Email sent in rare occasions for important communications.</div>
                            @elseif($s == "Animation_Newsletter")
                                <label style="margin-bottom: 0px; margin-top: 0px;"><input type="checkbox" name="sub[]" value="{{ $s }}" @if(in_array($s, $sub)) checked @endif/>
                                    &nbsp;&nbsp;<strong>Newsletter</strong>
                                </label>
                                <div style="margin-top: 0px; font-style: italic; line-height: 20px;">Email sent monthly with various updates; weekly announcements, latest library additions, new assets, new services, special events coming up, etc.</div>
                            @endif

                        @endforeach                    
                    </td>
                </tr>
            @endif
            <tr>
                <th>@lang('labels.frontend.user.profile.avatar')</th>
                <td>
                    <img src="{{ $logged_in_user->picture }}" class="user-profile-image" />
                    <div style="margin-bottom: 0px; margin-top: 8px;">
                        <div>
                            @foreach($logged_in_user->providers as $provider)
                                @if(strlen($provider->avatar))
                                    <input type="radio" name="avatar_type" value="{{ $provider->provider }}" {{ $logged_in_user->avatar_type == $provider->provider ? 'checked' : '' }} /> {{ ucfirst($provider->provider) }}&nbsp;&nbsp;
                                @endif
                            @endforeach
                            <input type="radio" style="margin-left: 0px;" name="avatar_type" value="gravatar" {{ $logged_in_user->avatar_type == 'gravatar' ? 'checked' : '' }} /> Gravatar&nbsp;&nbsp;
                            <input type="radio" name="avatar_type" value="storage" {{ $logged_in_user->avatar_type == 'storage' ? 'checked' : '' }} /> Upload                       
                        </div>
                    </div><!--form-group-->
        
                    <div class="form-group hidden" id="avatar_location" style="margin: 0px;">
                        {{ html()->file('avatar_location')->class('form-control-file') }}
                    </div><!--form-group-->
                </td>
            </tr>


            <tr>
                <th>Firstname</th>
                <td>{{ html()->text('first_name')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.first_name'))
                    ->attribute('maxlength', 191)
                    ->required()
                    ->value($logged_in_user->first_name)
                    }}
                </td>
            </tr>

            <tr>
                <th>Lastname</th>
                <td>{{ html()->text('last_name')
                    ->class('form-control')
                    ->placeholder(__('validation.attributes.frontend.last_name'))
                    ->attribute('maxlength', 191)
                    ->value($logged_in_user->last_name)
                    ->required() }}
                </td>
            </tr>


            <tr>
                <th>@lang('labels.frontend.user.profile.email')</th>
                <td>{{ $logged_in_user->email }}</td>
            </tr>
        </table>

        <div style="text-align: center;">{{ form_submit('Update my profile') }}</div>

    {{ html()->closeModelForm() }}

</div>


<div style="background-image: url(https://cdn.agora.community/as_bg.jpg); background-size: 100% auto; background-repeat: no-repeat; border-radius: 25px; background-color: black; clear: right; padding: 26px; border: 1px dashed rgb(114, 114, 114); margin-top: 44px; margin-bottom: 48px;">
    
    {{-- AGORA LOGO --}}
    <div class="Header__logo-text" style="margin-bottom: 14px;">
        <a href="https://agora.studio" target="_blank" style="color: white;">
            <svg style="max-width: 41px; float: left; margin-right: 8px; margin-top: 14px;" viewBox="0 0 40 37"><path fill="#FFF" d="M19.884 28.15c2.573 0 10.294 8.779 12.265 8.779h6.479c1.565 0 1.037-.929 1.037-.929S22.162 3.247 20.567.54c-.416-.705-.902-.735-1.34 0C17.59 3.284.085 36 .085 36s-.529.929 1.037.929h6.48c1.97 0 9.69-8.778 12.264-8.778"></path><path class="Header__logo-shadow" fill="#7B7B7B" d="M20.018 28.203c.584 0 1.476.536 2.457 1.223.003 0 .003 0 .005.003-.002-.003-.002-.003-.005-.003L9.268 18.916l5.338 12.657c2.18-1.869 4.27-3.37 5.394-3.37"></path></svg>
            <div class="SplitText__lines" style="display: block; text-align: start; position: relative; font-size: 36px;">
            <strong><div style="position:relative;display:inline-block;"><div style="position:relative;display:inline-block;" class="Header__chars">a</div><div style="position:relative;display:inline-block;" class="Header__chars">g</div><div style="position:relative;display:inline-block;" class="Header__chars">o</div><div style="position:relative;display:inline-block;" class="Header__chars">r</div><div style="position:relative;display:inline-block;" class="Header__chars">a</div></div></strong> <div style="position:relative;display:inline-block;"> <div style="position:relative;display:inline-block;" class="Header__chars">s</div><div style="position:relative;display:inline-block;" class="Header__chars">t</div><div style="position:relative;display:inline-block;" class="Header__chars">u</div><div style="position:relative;display:inline-block;" class="Header__chars">d</div><div style="position:relative;display:inline-block;" class="Header__chars">i</div><div style="position:relative;display:inline-block;" class="Header__chars">o</div></div> </div>
        </a>
    </div>
    <div style="background: rgba(1, 3, 42, 0.7);">
        <p style="font-size: 1.1em;">

            @if(!isset($applicationStatus->status))
                Problem fetching your application status. We are investigating this problem. Retry in a few days.
            @else
                @switch($applicationStatus->status)
                    @case(-1)
                        Problem fetching your application status. We are investigating this problem. Retry in a few days.
                    @break
                    @case(0)
                    <p style="margin-bottom: 12px;">Agora.studio is a global network of professional artists providing high-quality freelancing services to studios across the world. Our mission is to close the gap between freelancers and studios, empowering artists to work remotely on inspiring projects.</p>
                    <p style="margin-bottom: 32px;">If you are <strong>interested in remote freelancing work</strong>, either now or sometime in the future, please take a minute to fill out our application form. We will review all the applications received and will follow-up with those meeting our criteria of selection to join Agora’s talent pool. Those criteria are mainly based on artistic/technical skills demonstrated in your showreel and past professional experiences.</p>
                    <div style="text-align: center; margin-top: 32px;">
                        <a style="border: dashed 1px #22b2ee; padding: 16px;" href="{{ config('app.env') == "production" ? "https://hub.agora.studio" : "http://www.agora.studio.loc" }}/apply/ac">
                            <i class="fab fa-wpforms"></i>&nbsp;&nbsp;Application form</span>
                        </a>
                    </div>
                    @break
                    @case(1)
                        You have successfully submitted your application and your profile will be reviewed shortly by our team. Thank you!
                    @break
                    @case(3)
                        We reviewed your application and are pleased to share that let you know we would look forward to work with you in the future. If you didn't filled the registration form yet, please check your spam folder or click on this link:
                        <div style="text-align: center;">
                            <a href="{{ config('app.env') == "production" ? "https://hub.agora.studio" : "http://www.agora.studio.loc" }}/join/{{ $applicationStatus->encryptedID }}">
                                <button name="bs" class="btn" style="background-color: #25a8df !important; color: white;" />Fill the registration form</button>
                            </a>
                        </div>
                    @break
                    @case(5)
                        We have your information in our talent pool. No need to fill this application again. If you want to update your profile, please <a href="mailto:info@agora.studio?subject=Registration form | update my profile">contact us</a>.
                    @break
                    @case(4)
                        Looks like we have your profile in our database. If you have a new showreel for us to review, <a href="mailto:info@agora.studio?subject=Application form  | new showreel">contact us</a>.
                    @break
                @endswitch

            @endif
        </p>
    </div>
</div>



<div style="text-align: right; font-size: 11px;">Account created: {{ $logged_in_user->created_at->diffForHumans() }}</div>