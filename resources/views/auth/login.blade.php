@extends('layouts.app')

@section('content')
<div class="container">
<div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card-group">
        <div class="card p-4">
          

          <div class="card-body">
              <form method="POST" action="{{ route('login') }}">
                  @csrf
            <h1>Login</h1>
            <p class="text-muted">Ingrese su Cuenta</p>
            <div class="input-group mb-3{{ $errors->has('email' ? 'is-invalid' :'') }}">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-user"></i>
                </span>
              </div>
              <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="example@email.com">
             
              @error('email')
              <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
            </div>
            <div class="input-group mb-4 {{ $errors->has('password' ? 'is-invalid' :'') }}">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="icon-lock"></i>
                </span>
              </div>
              <input class="form-control" type="password" lass="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
              @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
            </div>
            <div class="row">
              <div class="col-6">
                <button class="btn btn-primary px-4" type="submit">Login</button>
              </div>
              <div class="col-6 text-right">
                <button class="btn btn-link px-0" type="button">Forgot password?</button>
              </div>
            </div>
          
          </form>
        </div>
        </div>
        <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
          <div class="card-body text-center">
            <div>
              <h2>Sotfware I</h2>
              <p>Libreria</p>
              <button class="btn btn-primary active mt-3" type="button">Sistema</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
  @endsection
