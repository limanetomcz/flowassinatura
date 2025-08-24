<form wire:submit.prevent="login" class="animated-card hover-card p-4 shadow-sm rounded bg-white">

    {{-- Email --}}
    <div class="input-group mb-3">
        <input type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               wire:model="email"
               placeholder="Email" autofocus>
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
        </div>
        @error('email')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Password --}}
    <div class="input-group mb-3">
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               wire:model="password"
               placeholder="Senha">
        <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
        </div>
        @error('password')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Remember & Submit --}}
    <div class="row">
        <div class="col-7">
            <div class="icheck-primary">
                <input type="checkbox" wire:model="remember" id="remember">
                <label for="remember">Lembrar-me</label>
            </div>
        </div>
        <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block shadow-sm" wire:loading.attr="disabled">
                {{-- Spinner while loading --}}
                <span wire:loading class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>
                <i class="fas fa-sign-in-alt mr-1" wire:loading.class="d-none"></i>
                Entrar
            </button>
        </div>
    </div>

    {{-- Error Message --}}
    @if($error)
        <div class="alert alert-danger mt-3">{{ $error }}</div>
    @endif

    {{-- Success Message (optional) --}}
    @if (session()->has('message'))
        <div class="alert alert-success mt-3">{{ session('message') }}</div>
    @endif
</form>

@section('css')
<style>
.hover-card { transition: all 0.3s ease; opacity: 0; transform: translateY(20px); }
.hover-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.15); }
.form-control { border-radius: 0.5rem; transition: all 0.2s; }
.form-control:focus { box-shadow: 0 0 0 0.2rem rgba(78, 84, 200, 0.25); border-color: #4e54c8; }
.btn { border-radius: 0.5rem; transition: all 0.2s; }
.btn:hover { transform: translateY(-2px); }
.alert { font-weight: 500; border-radius: 0.5rem; box-shadow: 0 0.15rem 0.5rem rgba(0,0,0,0.1); }
.fade-slide-in { animation: fadeSlideIn 0.5s ease forwards; }
@keyframes fadeSlideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.animated-card');
    cards.forEach((card, index) => setTimeout(() => card.classList.add('fade-slide-in'), index*100));
});
</script>
@endsection
