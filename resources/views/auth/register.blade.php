<x-layout>
    <div class="container vw-100 vh-100">
        <div class="row h-100">
            <div class="col-12 h-100 d-flex justify-content-center align-items-center flex-column">
                <h1 class="fw-semibold fst-italic display-3"><span class="text-highlight">GT</span>imer</h1>
                <h3 class="fw-semibold fs-5">Crea il tuo account GTimer per registrare i tuoi tempi</h3>
                <form class="w-50 d-flex flex-column justify-content-center align-items-center p-3" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-floating mb-3 w-100">
                        <input type="name" class="form-control" id="floatingInputName" placeholder="name@example.com" name="name">
                        <label for="floatingInputName">Username</label>
                        @error('name')
                        <span class="alert text-danger fw-semibold p-0 m-0 small">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-floating mb-3 w-100">
                        <input type="email" class="form-control" id="floatingInputEmail" placeholder="name@example.com" name="email">
                        <label for="floatingInputEmail">Email</label>
                        @error('email')
                        <span class="alert text-danger fw-semibold p-0 m-0 small">{{$message}}</span>
                        @enderror
                    </div>
                    <div class="form-floating mb-1 w-100">
                        <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
                        <label for="floatingPassword">Password</label>
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                @error('password')
                                <span class="alert text-danger fw-semibold p-0 m-0 small">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <div class="small fw-semibold" id="showPassword">Mostra password</div>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3 w-100">
                        <input type="password" class="form-control" id="floatingConfirmPassword" placeholder="Password" name="password_confirmation">
                        <label for="floatingConfirmPassword">Conferma password</label>
                        <div class="row">
                            <div class="col-6 d-flex align-items-center">
                                @error('password_confirmation')
                                <span class="alert text-danger fw-semibold p-0 m-0 small">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center">
                                <div class="small fw-semibold" id="showConfirmPassword">Mostra password</div>
                            </div>
                        </div>
                        
                    </div>
                    <button type="submit" class="button-highlight w-100">Crea account</button>
                </form>
                <p class="m-0 small">Hai già un account? <a href="{{route('register')}}" class="redirect fw-semibold">Accedi ora</a></p>
                <a href="{{route('timer')}}" class="w-25 d-flex justify-content-center text-decoration-none"><button class="button-main mt-3 w-75">Ritorna al timer</button></a>
            </div>
        </div>
    </div>
</x-layout>