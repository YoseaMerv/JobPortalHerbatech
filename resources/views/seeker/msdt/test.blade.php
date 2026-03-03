@extends('layouts.seeker')

@section('content')
<style>
    :root {
        --msdt-danger: #ef4444;
    }

    .test-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 1020;
        margin-top: -1.5rem;
    }

    .msdt-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        background: white;
    }

    .msdt-card:hover {
        border-color: var(--msdt-danger);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);
    }

    .option-btn {
        cursor: pointer;
        padding: 15px;
        border: 2px solid #f1f5f9;
        border-radius: 12px;
        transition: all 0.2s;
        display: block;
        margin-bottom: 0;
    }

    .msdt-input:checked+.option-btn {
        border-color: var(--msdt-danger);
        background-color: #fef2f2;
        font-weight: bold;
    }

    .timer-box {
        font-family: 'Courier New', monospace;
        font-size: 1.2rem;
    }
</style>

{{-- 1. Header Progress & Timer --}}
<div class="test-header shadow-sm mb-4 py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">Management Style Diagnostic Test (MSDT)</h6>
            <div class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold timer-box">
                <i class="fas fa-clock me-1"></i> <span id="timer-display">30:00</span>
            </div>
        </div>
        <div class="progress mb-1" style="height: 10px;">
            <div id="progBar" class="progress-bar bg-danger" style="width: 0%"></div>
        </div>
        <div class="d-flex justify-content-between">
            <small class="text-muted" id="progText">0 dari 64 soal selesai</small>
            <small class="text-muted extra-small">Waktu: 30 Menit</small>
        </div>
    </div>
</div>

<div class="container pb-5">
    {{-- 2. Form Utama --}}
    <form action="{{ route('seeker.msdt.submit', $testResult->id) }}" method="POST" id="msdtForm">
        @csrf
        <div class="row justify-content-center">
            @foreach($questions as $question)
            <div class="col-md-8 mb-4">
                <div class="card msdt-card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-danger me-2">{{ $question->question_number }}</span>
                            <small class="text-muted fw-bold">PILIH SALAH SATU:</small>
                        </div>

                        {{-- Opsi A --}}
                        <div class="mb-3">
                            <input class="form-check-input d-none msdt-input" type="radio"
                                name="answers[{{ $question->question_number }}]"
                                value="a" id="msdt_{{ $question->id }}_a" required>
                            <label class="option-btn" for="msdt_{{ $question->id }}_a">
                                {{ $question->option_a }}
                            </label>
                        </div>

                        {{-- Opsi B --}}
                        <div class="mb-0">
                            <input class="form-check-input d-none msdt-input" type="radio"
                                name="answers[{{ $question->question_number }}]"
                                value="b" id="msdt_{{ $question->id }}_b" required>
                            <label class="option-btn" for="msdt_{{ $question->id }}_b">
                                {{ $question->option_b }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-danger btn-lg rounded-pill px-5 fw-bold shadow-lg">
                Kirim Jawaban MSDT <i class="fas fa-paper-plane ms-2"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // --- 1. CONFIGURATION ---
    let timeInSeconds = 30 * 60; // 30 Menit
    const timerDisplay = document.getElementById('timer-display');
    const msdtForm = document.getElementById('msdtForm');

    // --- 2. TIMER LOGIC ---
    const interval = setInterval(function() {
        let mins = Math.floor(timeInSeconds / 60);
        let secs = timeInSeconds % 60;

        secs = secs < 10 ? '0' + secs : secs;
        mins = mins < 10 ? '0' + mins : mins;

        if (timerDisplay) {
            timerDisplay.innerText = `${mins}:${secs}`;
        }

        if (timeInSeconds <= 0) {
            clearInterval(interval);
            alert("Waktu habis! Jawaban MSDT Anda akan dikirim otomatis.");
            msdtForm.onsubmit = null;
            msdtForm.submit();
        }

        if (timeInSeconds === 300) {
            timerDisplay.parentElement.classList.replace('bg-opacity-10', 'bg-danger');
            timerDisplay.parentElement.classList.replace('text-danger', 'text-white');
            alert("Peringatan: Waktu sisa 5 menit lagi!");
        }

        timeInSeconds--;
    }, 1000);

    // --- 3. PROGRESS BAR LOGIC ---
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('msdt-input')) {
            const total = 64;
            const checked = document.querySelectorAll('.msdt-input:checked').length;
            const percent = (checked / total) * 100;

            document.getElementById('progBar').style.width = percent + '%';
            document.getElementById('progText').innerText = `${checked} dari 64 soal selesai`;
        }
    });

    // --- 4. SUBMIT VALIDATION ---
    msdtForm.onsubmit = function() {
        const checked = document.querySelectorAll('.msdt-input:checked').length;
        if (checked < 64) {
            alert('Mohon lengkapi semua (64) soal sebelum mengirim.');
            return false;
        }
        return confirm('Kirim hasil tes MSDT sekarang?');
    };
</script>
@endpush
@endsection