@extends('layouts.main')

@section('content')
  <style media="screen">
  .help-block{
    color: red !important;
  }
  </style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center text-white bg-info"><strong>Sign In</strong></div>

                <div class="card-body">
                    <form id="login_form">
                      @csrf
                        <div class="form-group">
                            <label for="email" class="col-form-label text-md-right">E-Mail Address</label>
                            <input id="email" type="email" class="form-control" name="email" value="" autocomplete="email" autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-form-label text-md-right">Password</label>
                            <input id="password" type="password" class="form-control" name="password" autocomplete="current-password">
                        </div>

                        <div class="form-group">
                          <div class="form-check">
                              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                              <label class="form-check-label" for="remember">
                                  {{ __('Remember Me') }}
                              </label>
                          </div>
                        </div>
                        <div class="form-group mb-0">
                          <button type="submit" class="btn btn-info btn-block">
                              {{ __('Login') }}
                          </button>
                            <div class="offset-md-1">
                              Don't have an account?
                              <a class="btn btn-link" href="{{ route('register') }}">Sign up</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('/global/validator/bootstrapValidator.min.js')}}"></script>
<script>
   $( document ).ready(function() {
     var base_url = {!! json_encode(url('/')) !!};
     $('body').tooltip({
         selector: '[data-toggle="tooltip"]'
     });

     $('#login_form').bootstrapValidator({
           message: 'This value is not valid',
           excluded: [':disabled', ':hidden', ':not(:visible)'],
              fields: {
               email: {
                     validators: {
                       notEmpty: {
                           message: 'Email is required'
                       },
                   }
               },
               password: {
                 validators: {
                     notEmpty: {
                         message: 'Password  is required'
                     }
                   }
                }
             }
       }).on('success.field.bv', function(e, data) {
               var $parent = data.element.parents('.form-group');
               $parent.removeClass('has-success');
       }).on('success.form.bv', function(e, data) {
         e.preventDefault();
         var $form = $(e.target);
         var bv = $form.data('bootstrapValidator');
           $.post(base_url + '/signin',$form.serialize(),function(result){
             bv.disableSubmitButtons(false);
            if(result.status == true){
              swal({
                title: "Success",
                text: "You login successfully!",
                icon: "success",
              });
              window.location.href = "{{ route('home')}}";
            } else {
              swal({
                title: "Failure",
                text: result.message,
                icon: "warning",
              });
            }
           });
       });

   });
</script>
@endsection
