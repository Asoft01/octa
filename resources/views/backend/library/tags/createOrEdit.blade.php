@extends('backend.layouts.app')

@section('title', 'Tags | ' . isset($tag) ? "Edit" : "Create")

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.library.tags.store'))->class('form-horizontal')->open() }}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <h4 class="card-title mb-0">
                            Tags Management
                            <small class="text-muted">{{ isset($tag) ? "Edit" : "Create" }}</small>
                        </h4>
                    </div>

                    {{-- DELETE --}}
                    @if(isset($tag))
                        <div class="col-sm-7" style="text-align: right;">
                                <a href="{{ route('admin.library.tags.delete', $tag->id) }}" name="confirm_item" id="delete" class="btn btn-primary btn-danger">Delete</a>
                        </div>
                    @endif
                </div>

                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        
                        
                        {{-- TITLE --}}
                        <div class="form-group row">
                            {{ html()->label("Title *")->class('col-md-2 form-control-label')->for('title') }}
                            <div class="col-md-10">
                                {{ html()->text('title')
                                    ->class('form-control')
                                    ->placeholder("Title")
                                    ->value(isset($tag) ?  $tag->title : null)
                                    ->attribute('maxlength', 191)
                                    ->required()
                                    ->autofocus() }}
                            </div>
                        </div>
                        
                    

                    </div>
                </div>
            </div>

            <div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{ form_cancel(route('admin.library.tags'), __('buttons.general.cancel')) }}
                    </div>

                    <div class="col text-right">
                        <button type="submit" id="submitform" class="btn btn-primary">{{ isset($tag) ? "Edit" : "Create" }}</button>
                    </div>
                </div>
            </div>
        </div>

     
        @if(isset($tag))
            {{ html()->hidden('tagID', $tag->id) }}
        @endif

    {{ html()->form()->close() }}
@endsection

@push('after-styles')
@endpush


@push('after-scripts')
<script>
    $(document).ready(function() {
        

        // VALIDATION
        $('#submitform').on('click', function(e) {
           return true;
        });
    });
</script>
@endpush
