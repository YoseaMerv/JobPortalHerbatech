@extends('layouts.seeker')

@section('title', 'Pengerjaan Tes PAPI Kostick')

{{-- Di dalam file resources/views/seeker/papi/test.blade.php --}}

@section('content')
<style>
    :root {
        --papi-primary: #0ea5e9;
        --papi-bg: #f0f9ff;
    }

    .test-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 1020;
        margin-top: -1.5rem;
    }

    .papi-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        background: white;
    }

    .papi-card:hover {
        border-color: var(--papi-primary);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.1);
    }

    .q-badge {
        width: 32px;
        height: 32px;
        background: var(--papi-primary);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.85rem;
    }

    .option-box {
        cursor: pointer;
        padding: 15px;
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
    }

    .option-box:hover {
        background-color: var(--papi-bg);
        border-color: #bae6fd;
    }

    .papi-input:checked+.option-box {
        border-color: var(--papi-primary);
        background-color: var(--papi-bg);
    }

    .progress-bar {
        background: var(--papi-primary);
    }
</style>

{{-- Header Progress --}}
<div class="test-header shadow-sm mb-4 py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">Inventori Kepribadian (PAPI Kostick)</h6>
            <span id="save-indicator" class="badge bg-success bg-opacity-10 text-success" style="opacity: 0; transition: opacity 0.5s;">
                <i class="fas fa-check-circle me-1"></i> Tersimpan
            </span>
            {{-- Tambahkan elemen Timer di sini --}}
            <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold">
                <i class="fas fa-clock me-1"></i> <span id="timer-countdown">30:00</span>
            </div>
        </div>
        <div class="progress mb-1" style="height: 10px;">
            <div id="progBar" class="progress-bar" style="width: 0%"></div>
        </div>
        <div class="d-flex justify-content-between">
            <small class="text-muted" id="progText">0 dari 90 pernyataan selesai</small>
            <small class="text-muted extra-small">Waktu Terbatas: 30 Menit</small>
        </div>
    </div>
</div>

<div class="container pb-5">
    <form action="{{ route('seeker.papi.submit', $testResult->id) }}" method="POST" id="papiForm">
        @csrf
        <div class="row justify-content-center">
            @foreach($questions as $q)
            <div class="col-md-8 mb-4">
                <div class="card papi-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="q-badge me-3">{{ $q->question_number }}</div>
                            <span class="small fw-bold text-muted text-uppercase">Pilih salah satu:</span>
                        </div>

                        {{-- Opsi A --}}
                        <div class="mb-3 position-relative">
                            <input type="radio" name="answers[{{ $q->question_number }}]"
                                value="a" id="q{{ $q->id }}a"
                                class="papi-input d-none" required>
                            <label for="q{{ $q->id }}a" class="option-box w-100 mb-0">
                                <i class="fas fa-arrow-right me-3 text-primary opacity-25"></i>
                                <span>{{ $q->option_a }}</span>
                            </label>
                        </div>

                        {{-- Opsi B --}}
                        <div class="position-relative">
                            <input type="radio" name="answers[{{ $q->question_number }}]"
                                value="b" id="q{{ $q->id }}b"
                                class="papi-input d-none" required>
                            <label for="q{{ $q->id }}b" class="option-box w-100 mb-0">
                                <i class="fas fa-arrow-up-right-from-square me-3 text-primary opacity-25" style="transform: rotate(45deg);"></i>
                                <span>{{ $q->option_b }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-5">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-lg" style="background-color: var(--papi-primary); border: none;">
                Kirim Hasil Inventori <i class="fas fa-check-double ms-2"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    const storageKey = 'papi_answers_{{ $testResult->id }}';
    const papiForm = document.getElementById('papiForm');

    // --- 1. AUTO-SAVE & LOAD ---
    function restoreAnswers() {
        const savedData = localStorage.getItem(storageKey);
        if (savedData) {
            const answers = JSON.parse(savedData);
            for (const key in answers) {
                const radio = document.querySelector(`input[name="${key}"][value="${answers[key]}"]`);
                if (radio) radio.checked = true;
            }
            updateProgress();
        }
    }
    document.addEventListener('DOMContentLoaded', restoreAnswers);

    function updateProgress() {
        const total = 90;
        const checked = document.querySelectorAll('.papi-input:checked').length;
        const percent = (checked / total) * 100;
        const progBar = document.getElementById('progBar');
        const progText = document.getElementById('progText');
        if (progBar) progBar.style.width = percent + '%';
        if (progText) progText.innerText = `${checked} dari 90 pernyataan selesai`;
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('papi-input')) {
            let savedData = JSON.parse(localStorage.getItem(storageKey) || '{}');
            savedData[e.target.name] = e.target.value;
            localStorage.setItem(storageKey, JSON.stringify(savedData));

            updateProgress();
        }
    });

    // --- 2. LOGIKA TIMER SERVER ---
    let timeInSeconds = parseInt('{{ $remainingSeconds > 0 ? $remainingSeconds : 0 }}');
    if (isNaN(timeInSeconds)) timeInSeconds = 0;
    const timerDisplay = document.getElementById('timer-countdown');

    function forceSubmitPapi() {
        if (navigator.onLine) {
            localStorage.removeItem(storageKey);
            alert("Waktu habis! Tes PAPI Anda akan otomatis terkirim.");
            papiForm.onsubmit = null;
            papiForm.submit();
        } else {
            alert("⚠️ KONEKSI TERPUTUS! Waktu sudah habis. Tolong JANGAN tutup halaman ini. Nyalakan kembali internet Anda, sistem akan otomatis mengirim jawaban saat pulih.");
            const retry = setInterval(function() {
                if (navigator.onLine) {
                    clearInterval(retry);
                    localStorage.removeItem(storageKey);
                    alert("✅ Koneksi pulih! Mengirim jawaban PAPI sekarang...");
                    papiForm.onsubmit = null;
                    papiForm.submit();
                }
            }, 3000);
        }
    }

    if (timeInSeconds <= 0) {
        forceSubmitPapi();
    } else {
        const interval = setInterval(function() {
            let mins = Math.floor(timeInSeconds / 60);
            let secs = timeInSeconds % 60;
            secs = secs < 10 ? '0' + secs : secs;
            mins = mins < 10 ? '0' + mins : mins;

            if (timerDisplay) timerDisplay.innerText = `${mins}:${secs}`;

            if (timeInSeconds <= 0) {
                clearInterval(interval);
                forceSubmitPapi();
            }

            if (timeInSeconds === 300) {
                timerDisplay.parentElement.classList.replace('bg-opacity-10', 'bg-opacity-100');
                timerDisplay.parentElement.classList.replace('text-danger', 'text-white');
            }
            timeInSeconds--;
        }, 1000);
    }

    // --- 3. VALIDASI SUBMIT & ANTI DOUBLE SUBMIT ---
    papiForm.onsubmit = function(e) {
        if (!navigator.onLine) {
            alert("Koneksi internet Anda sedang terputus! Nyalakan internet terlebih dahulu untuk mengirim jawaban.");
            return false;
        }

        const checked = document.querySelectorAll('.papi-input:checked').length;
        if (checked < 90) {
            alert('Mohon lengkapi semua (90) pasangan pernyataan sebelum mengirim.');
            const firstUnanswered = document.querySelector('.papi-card:not(:has(.papi-input:checked))');
            if (firstUnanswered) {
                firstUnanswered.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                firstUnanswered.style.borderColor = 'red';
            }
            return false;
        }
        if (confirm('Kirim hasil pengerjaan PAPI Kostick Anda?')) {
            localStorage.removeItem(storageKey);

            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = 'Menyimpan... <i class="fas fa-spinner fa-spin ms-2"></i>';
                btn.style.opacity = '0.7';
            }
            return true;
        }
        return false;
    };
</script>
@endpush
@endsection