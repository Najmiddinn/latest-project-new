
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/assets-backend/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/assets-backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/assets-backend/css/adminlte.min.css">
    <title>Login</title>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="card">
        <div class="card-body login-card-body">
            <div class="login-logo">
                Reset Password
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
          @endif
          <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{__('E-Mail')}}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Send Password Reset Link') }}</button>
                    </div>
                </div>
                @if (Route::has('password.request'))
                <p class="mb-0">
                    <a class="btn btn-link" href="{{ route('login') }}" style="padding-left:0">
                        {{ __('Login') }}
                    </a>
                </p>
                @endif
            </form>
            <p class="mb-0">
                <a href="{{route('register')}}" class="text-center">Register a new membership</a>
            </p>
        </div>
    </div>
</div>


<script src="/assets-backend/js/adminlte.js"></script>
<script src="/assets-backend/plugins/jquery/jquery.min.js"></script>
 
<script type="text/javascript">
    $('#reload').click(function () {
    //   alert('sadasd');
        $.ajax({
          url: "/reload-captcha-b", 
          // data: {id:id},
          type: "GET", 
          // dataType: "json", 
                 
            success: function (data) {
                $(".captcha span").html(data.captcha);
            }
        });
    });
  
  </script>


</body>
</html>