<section>
    <header class="mb-4">
        <h5 class="fw-bold mb-0 text-danger">
            <i class="fas fa-exclamation-triangle mr-2"></i> {{ __('Hapus Akun') }}
        </h5>
        <p class="text-muted small mb-0 mt-2">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    {{-- Tombol Pemicu Modal --}}
    <button type="button" class="btn btn-outline-danger btn-rounded fw-bold px-4 mt-2" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Hapus Akun Secara Permanen') }}
    </button>

    {{-- Modal Bootstrap 5 untuk Konfirmasi --}}
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-header border-0 pb-0 mt-3 px-4">
                        <h5 class="modal-title fw-bold text-dark" id="confirmUserDeletionModalLabel">
                            {{ __('Apakah Anda yakin ingin menghapus akun ini?') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body px-4 pt-3">
                        <p class="text-muted small mb-4">
                            {{ __('Setelah akun dihapus, semua data akan hilang permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi tindakan ini.') }}
                        </p>
                        
                        <div class="mb-3">
                            <label for="password" class="sr-only">{{ __('Kata Sandi') }}</label>
                            <input type="password" 
                                   class="form-control input-style @error('password', 'userDeletion') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="{{ __('Masukkan kata sandi Anda') }}">
                            
                            {{-- Menampilkan Error Jika Password Salah --}}
                            @error('password', 'userDeletion')
                                <span class="invalid-feedback d-block small mt-2">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-rounded px-4 fw-bold" data-bs-dismiss="modal">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn btn-danger btn-rounded px-4 fw-bold shadow-sm">
                            {{ __('Hapus Akun') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk membuka modal otomatis jika ada error validasi password --}}
    @if($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Membuka modal kembali jika salah masukin password
                var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                myModal.show();
            });
        </script>
    @endif
</section>