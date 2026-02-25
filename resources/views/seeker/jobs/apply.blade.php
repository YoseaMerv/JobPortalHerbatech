@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between mb-5 position-relative">
                <div class="step-item text-center">
                    <div class="step-circle active" id="dot-1">1</div>
                    <div class="small fw-bold mt-2">Pilih Dokumen</div>
                </div>
                <div class="step-item text-center">
                    <div class="step-circle" id="dot-2">2</div>
                    <div class="small fw-bold mt-2">Pertanyaan</div>
                </div>
                <div class="step-item text-center">
                    <div class="step-circle" id="dot-3">3</div>
                    <div class="small fw-bold mt-2">Review & Kirim</div>
                </div>
            </div>

            <form action="{{ route('seeker.jobs.apply.submit', $job) }}" method="POST" enctype="multipart/form-data" id="multiStepForm">
                @csrf

                <div class="step-content" id="step-1">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Lengkapi Dokumen</h5>
                                <a href="{{ route('seeker.profile.edit') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    <i class="fas fa-user-edit me-1"></i> Perbarui Profil Utama
                                </a>
                            </div>

                            <div class="alert alert-light border-0 small mb-4 py-3 px-4 rounded-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Data identitas diambil dari profil Anda. Pastikan data sudah benar sebelum melanjutkan.
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label-custom">NAMA LENGKAP</label>
                                    <input type="text" class="form-control input-custom bg-light" value="{{ $user->name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">ALAMAT EMAIL</label>
                                    <input type="text" class="form-control input-custom bg-light" value="{{ $user->email }}" readonly>
                                </div>

                                <div class="col-12">
                                    <label class="form-label-custom">RESUMÉ / CV (PDF, DOCX, RTF)</label>

                                    @if($profile?->resume_path)
                                    <div class="card border-0 bg-light mb-3 rounded-4">
                                        <div class="card-body d-flex align-items-center p-3">
                                            <div class="icon-box bg-white text-primary me-3 shadow-sm">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="small fw-bold mb-0 text-dark">Gunakan resume dari profil Anda</p>
                                                <span class="extra-small text-muted">{{ $profile->resume_filename ?? 'CV_Terunggah.pdf' }}</span>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="use_existing_resume" value="1" id="useExisting" checked>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <div id="new-upload-area" class="{{ $profile?->resume_path ? 'd-none' : '' }}">
                                        <div class="upload-area p-4 text-center rounded-4 border-dashed" id="dropzone-resume">
                                            <input type="file" name="resume" class="form-control d-none" id="resumeFile" {{ $profile?->resume_path ? '' : 'required' }}>
                                            <label for="resumeFile" class="mb-0 cursor-pointer">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-primary mb-2"></i>
                                                <p class="small mb-0 fw-bold">Klik untuk unggah file baru</p>
                                                <span class="text-muted extra-small">Maksimal 5MB</span>
                                            </label>
                                        </div>
                                        <div id="file-name-display" class="mt-2 small text-primary fw-bold d-none text-center"></div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label-custom">SURAT LAMARAN (COVER LETTER)</label>
                                    <input type="file" name="cover_letter_file" class="form-control input-custom" required>
                                </div>
                            </div>

                            <div class="mt-5 text-end">
                                <button type="button" class="btn btn-primary px-5 py-2 rounded-pill fw-bold" onclick="nextStep(2)">
                                    Lanjut <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="step-content d-none" id="step-2">
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5">
                        <h5 class="fw-bold mb-4">Pertanyaan Perusahaan</h5>
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="form-label-custom">1. PERNYATAAN KEJUJURAN DATA</label>
                                <p class="text-secondary small mb-2">Apakah seluruh informasi yang Anda berikan benar dan dapat dipertanggungjawabkan?</p>
                                <select name="q1" class="form-select input-custom" required>
                                    <option value="Ya">Ya, saya menyatakan benar</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">2. KOMITMEN FULL-TIME</label>
                                <p class="text-secondary small mb-2">Bersedia bekerja penuh waktu di lokasi kantor?</p>
                                <select name="q2" class="form-select input-custom" required>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">3. DOMISILI & RELOKASI</label>
                                <p class="text-secondary small mb-2">Bersedia relokasi mandiri jika diterima?</p>
                                <select name="q3" class="form-select input-custom" required>
                                    <option value="Ya">Ya/Sudah Domisili Sama</option>
                                    <option value="Tidak">Tidak Bersedia</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">4. KENDARAAN PRIBADI</label>
                                <p class="text-secondary small mb-2">Memiliki kendaraan pribadi untuk mobilitas?</p>
                                <select name="q4" class="form-select input-custom" required>
                                    <option value="Ya">Ya</option>
                                    <option value="Tidak">Tidak</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">5. EKSPEKTASI GAJI</label>
                                <p class="text-secondary small mb-2">Berapa gaji bulanan yang diharapkan?</p>
                                <input type="number" name="q5" class="form-control input-custom" placeholder="Contoh: 5000000" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">6. SKALA KEAHLIAN (1-10)</label>
                                <input type="range" name="q6" class="form-range" min="1" max="10" step="1" id="q6Range" required>
                                <div class="text-center fw-bold text-primary" id="q6Val">5</div>
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">7. PENCAPAIAN TERBAIK</label>
                                <textarea name="q7" class="form-control input-custom" rows="2" required placeholder="Ceritakan satu pencapaian membanggakan..."></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">8. KEAHLIAN UTAMA</label>
                                <input type="text" name="q8" class="form-control input-custom" required placeholder="Sebutkan satu keahlian paling dikuasai...">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">9. GAYA KERJA</label>
                                <select name="q9" class="form-select input-custom" required>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="Kolaborasi Tim">Kolaborasi Tim</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">10. LINGKUNGAN KERJA PRODUKTIF</label>
                                <input type="text" name="q10" class="form-control input-custom" required placeholder="Lingkungan yang memotivasi Anda...">
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">11. MENYIKAPI KRITIK</label>
                                <textarea name="q11" class="form-control input-custom" rows="2" required></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">12. PENGALAMAN KONFLIK TIM</label>
                                <textarea name="q12" class="form-control input-custom" rows="2" required></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">13. MOTIVASI MELAMAR</label>
                                <textarea name="q13" class="form-control input-custom" rows="2" required></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label-custom">14. VISI 1 TAHUN KE DEPAN</label>
                                <input type="text" name="q14" class="form-control input-custom" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom">15. TANGGAL MULAI KERJA</label>
                                <input type="date" name="q15" class="form-control input-custom" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 border-top pt-4">
                            <button type="button" class="btn btn-light px-4 rounded-3 fw-bold" onclick="nextStep(1)">Kembali</button>
                            <button type="button" class="btn btn-primary px-5 rounded-3 fw-bold" onclick="nextStep(3)">Lanjut ke Review</button>
                        </div>
                    </div>
                </div>

                <div class="step-content d-none" id="step-3">
                    <div class="card border-0 shadow-sm rounded-4 p-4 text-center">
                        <i class="fas fa-file-signature fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold">Review Lamaran</h5>
                        <p class="text-muted small">Silakan periksa kembali semua dokumen dan jawaban Anda. Data yang sudah dikirim tidak dapat diubah kembali.</p>
                        <div class="alert alert-info py-2 small mb-4">
                            Semua pertanyaan q1 s/d q15 telah terisi.
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <button type="button" class="btn btn-light px-4 fw-bold" onclick="nextStep(2)">Cek Jawaban</button>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow-sm">Kirim Lamaran Sekarang</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .step-circle {
        width: 45px;
        height: 45px;
        line-height: 45px;
        background: #e9ecef;
        border-radius: 50%;
        display: inline-block;
        color: #adb5bd;
        font-weight: bold;
        border: 3px solid #fff;
        box-shadow: 0 0 0 1px #e9ecef;
    }

    .step-circle.active {
        background: #0d6efd;
        color: white;
        box-shadow: 0 0 0 1px #0d6efd;
    }

    .input-custom {
        border-radius: 12px;
        border: 1px solid #f1f3f5;
        padding: 12px;
        font-size: 0.9rem;
        background-color: #fcfcfc;
    }

    .form-label-custom {
        font-size: 0.7rem;
        font-weight: 800;
        color: #adb5bd;
        letter-spacing: 0.05rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
    }
</style>

<script>
    /**
     * Fungsi navigasi antar step dengan validasi otomatis
     */
    function nextStep(step) {
        // Ambil container step yang sedang aktif saat ini
        const currentStepDiv = document.querySelector('.step-content:not(.d-none)');

        // Cari semua input, select, dan textarea yang memiliki atribut 'required' di step ini
        const inputs = currentStepDiv.querySelectorAll('input[required], select[required], textarea[required]');

        // Cek validasi HTML5 sebelum diperbolehkan lanjut
        let isValid = true;
        inputs.forEach(input => {
            // Jika input kosong atau tidak sesuai format, tampilkan pesan error bawaan browser
            if (!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
            }
        });

        // Jika ada yang belum valid, hentikan proses perpindahan step
        if (!isValid) return;

        // Jika valid, sembunyikan semua step dan tampilkan step tujuan
        document.querySelectorAll('.step-content').forEach(el => el.classList.add('d-none'));
        document.getElementById('step-' + step).classList.remove('d-none');

        // Update indikator lingkaran (progress bar)
        document.querySelectorAll('.step-circle').forEach((el, idx) => {
            el.classList.toggle('active', (idx + 1) <= step);
        });

        // Scroll kembali ke atas halaman agar user melihat dari awal step baru
        window.scrollTo(0, 0);
    }

    /**
     * Logika Input Range untuk Pertanyaan Skala Keahlian (q6)
     */
    const q6Range = document.getElementById('q6Range');
    const q6Val = document.getElementById('q6Val');
    if (q6Range && q6Val) {
        q6Range.addEventListener('input', () => {
            q6Val.textContent = q6Range.value;
        });
    }

    /**
     * Logika Toggle Resume: Antara pakai file profil atau upload baru
     */
    document.getElementById('useExisting')?.addEventListener('change', function() {
        const uploadArea = document.getElementById('new-upload-area');
        const resumeInput = document.getElementById('resumeFile');

        if (this.checked) {
            // Sembunyikan area upload dan matikan kewajiban mengisi file
            uploadArea.classList.add('d-none');
            resumeInput.required = false;
        } else {
            // Tampilkan area upload dan wajibkan user memilih file
            uploadArea.classList.remove('d-none');
            resumeInput.required = true;
        }
    });

    /**
     * Menampilkan nama file yang dipilih pada area upload
     */
    const resumeFileInput = document.getElementById('resumeFile');
    if (resumeFileInput) {
        resumeFileInput.addEventListener('change', function() {
            const display = document.getElementById('file-name-display');
            if (this.files.length > 0) {
                display.textContent = "File terpilih: " + this.files[0].name;
                display.classList.remove('d-none');
            }
        });
    }

    /**
     * Menampilkan nama file untuk Surat Lamaran (Cover Letter)
     */
    const clFileInput = document.querySelector('input[name="cover_letter_file"]');
    if (clFileInput) {
        clFileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // Menambahkan feedback visual sederhana bahwa file sudah masuk
                this.classList.add('is-valid');
            }
        });
    }
</script>
@endsection