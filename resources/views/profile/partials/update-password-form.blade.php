<section>
    <header class="mb-4">
        <h5 class="fw-bold mb-0 text-dark">
            <i class="fas fa-lock text-warning mr-2"></i> {{ __('Perbarui Kata Sandi') }}
        </h5>
        <p class="text-muted small mb-0 mt-1">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-4">
            <label for="update_password_current_password" class="form-label fw-bold small text-muted text-uppercase">
                {{ __('Kata Sandi Saat Ini') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password" 
                   class="form-control input-style @error('current_password', 'updatePassword') is-invalid @enderror" 
                   autocomplete="current-password">
            {{-- Laravel memisahkan error ganti sandi di error bag 'updatePassword' --}}
            @error('current_password', 'updatePassword')
                <span class="invalid-feedback d-block small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="update_password_password" class="form-label fw-bold small text-muted text-uppercase">
                {{ __('Kata Sandi Baru') }}
            </label>
            <input id="update_password_password" name="password" type="password" 
                   class="form-control input-style @error('password', 'updatePassword') is-invalid @enderror" 
                   autocomplete="new-password">
            @error('password', 'updatePassword')
                <span class="invalid-feedback d-block small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label fw-bold small text-muted text-uppercase">
                {{ __('Konfirmasi Kata Sandi Baru') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                   class="form-control input-style @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                   autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <span class="invalid-feedback d-block small">{{ $message }}</span>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-dark btn-rounded shadow-sm px-4">
                <i class="fas fa-key mr-1"></i> {{ __('Simpan Sandi') }}
            </button>

            {{-- Notifikasi "Sandi Diperbarui" dengan Vanilla JS --}}
            @if (session('status') === 'password-updated')
                <span class="text-success fw-bold small" id="pwd-saved-msg">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Sandi berhasil diperbarui.') }}
                </span>
                <script>
                    setTimeout(() => {
                        let el = document.getElementById('pwd-saved-msg');
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