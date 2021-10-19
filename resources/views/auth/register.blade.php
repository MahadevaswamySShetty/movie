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
                <div class="card-header text-center text-white bg-info"><strong>Sign Up</strong></div>

                <div class="card-body">
                    <form id="login_form">
                      @csrf
                        <div class="form-group">
                            <label for="name" >Name</label>
                            <input id="name" type="text" class="form-control" name="name" autocomplete="name" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="email" >E-Mail Address</label>
                            <input id="email" type="email" class="form-control" name="email" autocomplete="email" autofocus>
                        </div>
                        <div class="form-group">
                            <label for="password" >Password</label>
                            <input id="password" type="password" class="form-control" name="password" autocomplete="current-password">
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="current-password">
                        </div>
                        <div class="form-group mb-0">
                          <button type="submit" class="btn btn-info btn-block">
                              Register
                          </button>
                            <div class="offset-md-1">
                              Have an account?
                              <a class="btn btn-link" href="{{ route('login') }}">Sign in</a>
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
               name: {
                     validators: {
                       notEmpty: {
                           message: 'Name is required'
                       },
                   }
               },
               email: {
                     validators: {
                       notEmpty: {
                           message: 'Email is required'
                       },
                   }
               },
               password: {
                validators: {
                    identical: {
                        field: 'password_confirmations',
                        message: 'The password and its confirm are not the same'
                    },
                    notEmpty: {
                        message: 'Password is required'
                    },
                }
              },
              password_confirmation: {
                  validators: {
                      identical: {
                          field: 'password',
                          message: 'The password and its confirm are not the same'
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
           $.post(base_url + '/signup',$form.serialize(),function(result){
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
