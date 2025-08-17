<form wire:submit.prevent="login">
    {{-- Email input field --}}
    <div class="input-group mb-3">
        <input type="email" 
               name="email" 
               class="form-control @error('email') is-invalid @enderror"
               wire:model="email" {{-- Binds this input to the Livewire $email property --}}
               placeholder="{{ __('adminlte::adminlte.email') }}" 
               autofocus>

        {{-- Input group icon --}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
        </div>

        {{-- Display validation error for email --}}
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    {{-- Password input field --}}
    <div class="input-group mb-3">
        <input type="password" 
               name="password" 
               class="form-control @error('password') is-invalid @enderror"
               wire:model="password" {{-- Binds this input to the Livewire $password property --}}
               placeholder="{{ __('adminlte::adminlte.password') }}">

        {{-- Input group icon --}}
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
            </div>
        </div>

        {{-- Display validation error for password --}}
        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    {{-- Remember me checkbox and submit button --}}
    <div class="row">
        <div class="col-7">
            {{-- Remember me checkbox --}}
            <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                <input type="checkbox" 
                       wire:model="remember" {{-- Binds this checkbox to the Livewire $remember property --}}
                       id="remember">

                <label for="remember">
                    {{ __('adminlte::adminlte.remember_me') }}
                </label>
            </div>
        </div>

        <div class="col-5">
            {{-- Submit button --}}
            <button type="submit" class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                <span class="fas fa-sign-in-alt"></span>
                {{ __('adminlte::adminlte.sign_in') }}
            </button>
        </div>
    </div>

    {{-- Display general login error from Livewire $error property --}}
    @if($error)
        <div class="alert alert-danger mt-3" role="alert">
            {{ $error }}
        </div>
    @endif
</form>
