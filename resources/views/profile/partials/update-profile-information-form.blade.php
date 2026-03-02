<section>
    <header class="mb-4">
        <h5 class="fw-bold mb-0 text-dark">
            {{ __('Informasi Akun Login') }}
        </h5>
        <p class="text-muted small mb-0 mt-1">
            {{ __("Perbarui nama tampilan dan alamat email yang Anda gunakan untuk masuk ke sistem.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-4">
            <label for="name" class="form-label fw-bold small text-muted text-uppercase">{{ __('Nama Lengkap') }}</label>
            <input id="name" name="name" type="text" class="form-control input-style @error('name') is-invalid @enderror" 
                   value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <span class="invalid-feedback d-block small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="form-label fw-bold small text-muted text-uppercase">{{ __('Email Login') }}</label>
            <input id="email" name="email" type="email" class="form-control input-style @error('email') is-invalid @enderror" 
                   value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <span class="invalid-feedback d-block small">{{ $message }}</span>
            @enderror

            {{-- Peringatan Jika Email Belum Diverifikasi --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded-3 small" style="background-color: #fffbeb; border: 1px solid #fde68a;">
                    <span class="text-dark">{{ __('Email Anda belum diverifikasi.') }}</span>

                    <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline fw-bold text-dark text-decoration-none border-0 bg-transparent" style="box-shadow: none;">
                        {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 mb-0 text-success fw-bold small">
                            <i class="fas fa-check-circle mr-1"></i> {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-rounded shadow-sm px-4">
                <i class="fas fa-save mr-1"></i> {{ __('Simpan') }}
            </button>

            {{-- Notifikasi "Tersimpan" dengan Vanilla JS (menggantikan Alpine.js bawaan) --}}
            @if (session('status') === 'profile-updated')
                <span class="text-success fw-bold small" id="saved-msg">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Tersimpan.') }}
                </span>
                <script>
                    // Otomatis menghilangkan tulisan "Tersimpan" setelah 2 detik
                    setTimeout(() => {
                        let el = document.getElementById('saved-msg');
                        if(el) { 
                            el.style.transition = 'opacity 0.5s ease'; 
                            el.style.opacity = '0'; 
                            setTimeout(() => el.remove(), 500);
                        }
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>