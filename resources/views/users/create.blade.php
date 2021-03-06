@extends('layouts.app')

@section('page-title')
    @lang('users.title')
@stop

@section('section-title')
    @lang('users.section_header')
@stop

@section('errors-message')
    @include('common.errors')
@stop

@section('susscess-message')
    @include('common.success')
@stop

@section('page-content')
    <div class="x_content">
        <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('user.store') }}">
          {{ csrf_field() }}
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">@lang('users.label_name') <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="name" class="form-control col-md-7 col-xs-12" data-validate-length-range="2" data-validate-words="2" name="name" placeholder="@lang('users.eg_name')" required="required" type="text" value="{{ old('name') }}">
            </div>
          </div>
          <div class="item form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">@lang('users.label_email') <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="email" type="email" class="form-control col-md-7 col-xs-12" name="email" value="{{ old('email') }}" required="required">
            </div>
          </div>
          <div class="item form-group">
            <label for="password" class="control-label col-md-3 col-sm-3 col-xs-12">@lang('users.label_password') <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="password" type="password" name="password" data-validate-length-range="6,32" class="form-control col-md-7 col-xs-12" required="required">
            </div>
          </div>
          <div class="item form-group">
            <label for="password_confirmation" class="control-label col-md-3 col-sm-3 col-xs-12">@lang('users.label_password_confirm') <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input id="password_confirmation" type="password" name="password_confirmation" data-validate-linked="password" class="form-control col-md-7 col-xs-12" required="required">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12">@lang('users.label_account_permission') <span class="required">*</span>
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <select class="form-control" id="role_id" name="role_id">
                <option value="2">@lang('users.option_manager')</option>
                <option value="3">@lang('users.option_staff')</option>
              </select>
            </div>
          </div>
          <div class="ln_solid"></div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4 col-sm-offset-4 col-xs-offset-3">
              <a class="btn btn-primary" href="{{ route('user.create') }}">@lang('users.btn_cancel')</a>
              <button id="send" type="submit" class="btn btn-success">@lang('users.btn_submit')</button>
            </div>
          </div>
        </form>
    </div>
@stop
@push('end-page-scripts')
    <script>
        var errorMessages = {!! json_encode(trans('errors')) !!};
    </script>

    <!-- validator -->
    <script src="/bower_resources/gentelella/vendors/validator/validator.min.js"></script>


    <!-- Validator submit -->
    <script>
      // Override validate message
      validator.message.min = errorMessages.min_6;
      validator.message.max = errorMessages.max_32;
      validator.message.date = errorMessages.invalid_date;
      validator.message.email = errorMessages.invalid_email;
      validator.message.empty = errorMessages.field_required;
      validator.message.select = errorMessages.select_option;
      validator.message.complete = errorMessages.at_least_2_words;
      validator.message.password_repeat = errorMessages.passwords_not_match;

      // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
      $('form')
        .on('blur', 'input[required], input.optional, select.required', validator.checkField)
        .on('change', 'select.required', validator.checkField)
        .on('keypress', 'input[required][pattern]', validator.keypress);

      $('.multi.required').on('keyup blur', 'input', function() {
        validator.checkField.apply($(this).siblings().last()[0]);
      });

      $('form').submit(function(e) {
        e.preventDefault();
        var submit = true;

        // evaluate the form using generic validaing
        if (!validator.checkAll($(this))) {
          submit = false;
        }

        if (submit)
          this.submit();

        return false;
      });
    </script>
    <!-- /validator -->
@endpush
