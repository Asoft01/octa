@extends('frontend.layouts.app')

@section('content')
    <div class="row justify-content-center align-items-center mb-3" style="margin-top: 24px;">
        <div class="col col-sm-6 align-self-center">
            <div class="card bg-dark text-white">
                <div class="card-header">
                    <strong>
                        @lang('navs.frontend.user.account')
                    </strong>
                    @if(!count(auth()->user()->deliveries) && !isset(auth()->user()->account))
                        <a href="#" id="deleteaccount" style="font-size: 1.2em; float: right; color: red; font-weight: bold;">[Delete my account]</a>
                    @else
                        <a href="mailto: community@agora.studio" style="font-size: 1.2em; float: right; color: red; font-weight: bold;">[Delete my account]</a>
                    @endif
                </div>

                <div class="card-body">
                    <div role="tabpanel">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a href="#profile" class="nav-link active" aria-controls="profile" role="tab" data-toggle="tab">@lang('navs.frontend.user.profile')</a>
                            </li>

                            @if($logged_in_user->canChangePassword())
                                <li class="nav-item">
                                    <a href="#password" class="nav-link" aria-controls="password" role="tab" data-toggle="tab">@lang('navs.frontend.user.change_password')</a>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content bg-dark text-white">
                            <div role="tabpanel" class="tab-pane fade show active pt-3" id="profile" aria-labelledby="profile-tab">
                                @include('frontend.user.account.tabs.profile')
                            </div><!--tab panel profile-->

                            <div role="tabpanel" class="tab-pane fade show pt-3" id="edit" aria-labelledby="edit-tab">
                                @include('frontend.user.account.tabs.edit')
                            </div><!--tab panel profile-->

                            @if($logged_in_user->canChangePassword())
                                <div role="tabpanel" class="tab-pane fade show pt-3" id="password" aria-labelledby="password-tab">
                                    @include('frontend.user.account.tabs.change-password')
                                </div><!--tab panel change password-->
                            @endif
                        </div><!--tab content-->
                    </div><!--tab panel-->
                </div><!--card body-->
            </div><!-- card -->
        </div><!-- col-xs-12 -->
    </div><!-- row -->
@endsection

@push('after-scripts')
<style>
.table-bordered, .table-bordered td, .table-bordered th {
    border: 1px solid #494949;
}
.nav-tabs {
    border-bottom: 1px solid #8f8f8f;
}
.form-control:focus {
    color: black;
}
.input {
    color: black;
}
</style>
<script>
    $(document).ready(function() {
        $("#deleteaccount").click(function() {
            Swal.fire({
                title: "Attention",
                text: "This cannot be undone. If you posted a comment on a video, it will also get deleted after a week (time to process the request). Thank you for your understanding.",
                type: 'warning',
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('frontend.auth.delete.permanently') }}";
                }
            })
        });




         // SUBMIT
         $('#bs').on('click', function(e) {
            
            // CUSTOM VALIDATION
            if($("#showreel").val() == '' || !isUrlValid($("#showreel").val())) {
                        
                    e.preventDefault();
                    Swal.fire({
                        title: "Showreel link is not valid",
                        text: "Please verify the showreel link (ie: https://vimeo.com/ID or https://www.youtube.com/watch?v=ID...). Add a single link.",
                        type: 'warning',
                        onAfterClose: () => {
                            $("#showreel").focus();
                        }
                    });

            } else if($("#linkedin").val() == '' || !isUrlValid($("#linkedin").val())) {
                        
                        e.preventDefault();
                        Swal.fire({
                            title: "LinkedIn link is not valid",
                            text: "Please verify the link for your LinkedIn profile (ie: https://www.linkedin.com/in/YOU).",
                            type: 'warning',
                            onAfterClose: () => {
                                $("#linkedin").focus();
                            }
                        });

            {{--
            @if(isset($as))
            } else if($("#showreel").val() == $("#showreel_old").val()) {
                        
                        e.preventDefault();
                        Swal.fire({
                            title: "Showreel not updated",
                            text: "Please verify the link for your showreel is not the same as the old link.",
                            type: 'warning',
                            onAfterClose: () => {
                                $("#showreel").focus();
                            }
                        });
            @endif
            --}}
            } else {
                $(this).text('Please wait...');
                $(this).prop('disabled', true);
                $(this).parents('form').submit();
            }
            
        });

        function isUrlValid(url) {
            return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
        }


    });
</script>
@endpush