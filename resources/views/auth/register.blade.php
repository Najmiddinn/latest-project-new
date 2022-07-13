<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration </title>

    <link rel="stylesheet" href="/assets-backend/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/assets-backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="/assets-backend/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">

    <div class="card">
        <div class="card-body register-card-body">
            <div class="register-logo">
                <p>{{ __('Register') }}</p>
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
            <form action="{{ route('register') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{ __('Name') }}">

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>

                    @error('name')
                    <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                    </span>
                    @enderror

                </div>
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail') }}">
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
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                  </div>
                
                  
                <div class="form-group">
                    <div class="captcha">
                        <span>{!! captcha_img('flat') !!}</span>
                        <button type="button" class="btn btn-info" class="reload" id="reload">
                            &#x21bb;
                        </button>
                    </div>
                  </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
                    </div>
            </form>

            <a href="{{route('login')}}" class="text-center">I already have a membership</a>
        </div>
    </div>
</div>

<script src="/assets-backend/js/adminlte.js"></script>
<script src="/assets-backend/plugins/jquery/jquery.min.js"></script>
<script src="/assets-backend/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    $('#reload').click(function () {
      // alert('sadasd');
        $.ajax({
          url: "/reload-captcha-r", 
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
