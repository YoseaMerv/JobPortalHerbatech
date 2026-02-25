@extends('layouts.seeker')

@section('content')
<style>
    .kraepelin-container { overflow-x: auto; white-space: nowrap; padding: 40px 20px; background: #f8fafc; height: calc(100vh - 160px); }
    .test-column { display: inline-block; width: 70px; vertical-align: top; margin-right: 20px; transition: all 0.3s ease; opacity: 0.2; padding: 15px 5px; border-radius: 12px; border: 2px solid transparent; }
    .test-column.active { opacity: 1; transform: scale(1.02); background: #ffffff; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); border: 2px solid #4338ca; }
    .number-box { text-align: center; font-size: 1.5rem; font-weight: 800; padding: 4px 0; color: #1e293b; line-height: 1; }
    .input-box { width: 45px; margin: 5px auto; border: 2px solid #e2e8f0; text-align: center; border-radius: 8px; font-weight: 900; font-size: 1.2rem; padding: 4px 0; background: #f1f5f9; display: block; }
    .test-column.active .input-box { background: #ffffff; border-color: #cbd5e1; }
    .input-box:focus { outline: none; border-color: #4338ca; box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.15); }
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .timer-header { position: sticky; top: 0; background: white; z-index: 100; padding: 20px 40px; border-bottom: 2px solid #e2e8f0; }
</style>

@php
    $questionsArray = is_string($questions) ? json_decode($questions, true) : $questions;
@endphp

<div class="container-fluid p-0">
    <div class="timer-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold text-dark">TES KRAEPELIN - HERBATECH</h5>
            <p class="text-muted small mb-0">Jumlahkan dari bawah ke atas. Masukkan angka terakhir.</p>
        </div>
        <div class="d-flex align-items-center gap-5">
            <div class="text-center">
                <div class="text-muted small fw-bold">PROGRES</div>
                <div id="progress-text" class="h5 fw-bold mb-0">1 / {{ count($questionsArray) }}</div>
            </div>
            <div class="text-end" style="min-width: 140px;">
                <div class="text-muted small fw-bold">SISA WAKTU</div>
                <div id="column-timer" class="display-6 fw-bold text-primary mb-0">15s</div>
            </div>
        </div>
    </div>

    <div class="kraepelin-container" id="main-test-area">
        @foreach($questionsArray as $colIndex => $column)
            <div class="test-column" id="col-{{ $colIndex }}">
                <div class="text-center small fw-extrabold text-primary mb-3">#{{ $colIndex + 1 }}</div>
                @php 
                    $reversedCol = array_reverse($column, true);
                    $totalItems = count($column);
                @endphp
                @foreach($reversedCol as $visualRowIndex => $num)
                    <div class="number-box">{{ $num }}</div>
                    @if(!$loop->last)
                        @php $originalIndex = ($totalItems - 1) - $visualRowIndex; @endphp
                        <input type="number" class="input-box" maxlength="1"
                               oninput="if (this.value.length > 1) this.value = this.value.slice(-1);"
                               data-col="{{ $colIndex }}" data-row="{{ $originalIndex }}"
                               id="input-{{ $colIndex }}-{{ $originalIndex }}" disabled>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Inisialisasi variabel dari PHP ke JS
    const totalColumns = {{ count($questionsArray) }};
    const testId = "{{ $test->id }}";
    const submitUrl = "{{ route('seeker.kraepelin.submit', ':testId') }}".replace(':testId', testId);
    
    let currentCol = 0;
    let columnTimeLimit = 1; // detik
    let timerInterval;
    let isSubmitting = false;
    let allAnswers = {};

    // CSRF Token Setup
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    }

    window.addEventListener('beforeunload', function (e) {
        if (!isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Peringatan: Progres akan hilang!';
        }
    });

    function startColumnTimer() {
        // Hentikan timer sebelumnya jika ada
        if (timerInterval) clearInterval(timerInterval);

        let timeLeft = columnTimeLimit;
        const timerDisplay = document.getElementById('column-timer');
        const progressDisplay = document.getElementById('progress-text');

        timerDisplay.innerText = timeLeft + 's';
        progressDisplay.innerText = `${currentCol + 1} / ${totalColumns}`;
        
        // Reset kolom lama
        document.querySelectorAll('.test-column').forEach(el => el.classList.remove('active'));
        
        // Aktifkan kolom baru
        const activeEl = document.getElementById(`col-${currentCol}`);
        if (!activeEl) return;
        
        activeEl.classList.add('active');
        activeEl.querySelectorAll('.input-box').forEach(input => input.disabled = false);
        
        // Fokus ke input paling bawah
        const firstInput = document.getElementById(`input-${currentCol}-0`);
        if(firstInput) firstInput.focus();
        
        activeEl.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });

        timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.innerText = timeLeft + 's';

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                saveCurrentColumnData();
                moveToNextColumn();
            }
        }, 1000);
    }

    function saveCurrentColumnData() {
        const activeEl = document.getElementById(`col-${currentCol}`);
        if (!activeEl) return;
        activeEl.querySelectorAll('.input-box').forEach(input => {
            if(input.value !== "") {
                allAnswers[`${input.dataset.col}-${input.dataset.row}`] = input.value;
            }
        });
    }

    function moveToNextColumn() {
        const oldCol = document.getElementById(`col-${currentCol}`);
        if(oldCol) {
            oldCol.classList.remove('active');
            oldCol.querySelectorAll('.input-box').forEach(input => input.disabled = true);
        }
        
        currentCol++;
        if (currentCol < totalColumns) {
            startColumnTimer();
        } else {
            finishTest();
        }
    }

async function finishTest() {
    isSubmitting = true;
    document.body.style.opacity = '0.5';

    try {
        const response = await axios.post(submitUrl, {
            answers: allAnswers,
            total_answered: Object.keys(allAnswers).length
        }, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        if (response.data.status === 'success') {
            window.location.href = response.data.redirect;
        }
    } catch (error) {
        console.error("Submission Error:", error.response?.data);
        alert('Gagal mengirim data. Silakan cek tab Network di F12 untuk detail.');
        isSubmitting = false;
        document.body.style.opacity = '1';
    }
}

function resetUI() {
    isSubmitting = false;
    document.body.style.opacity = '1';
    document.body.style.pointerEvents = 'auto';
    document.getElementById('column-timer').innerText = "Error";
}

    // Jalankan pertama kali
    document.addEventListener('DOMContentLoaded', () => {
        startColumnTimer();
    });

    // Pindah Input Otomatis
    document.addEventListener('keyup', function(e) {
        if (e.target.classList.contains('input-box')) {
            if (e.target.value.length >= 1) {
                const col = parseInt(e.target.dataset.col);
                const row = parseInt(e.target.dataset.row);
                const nextInput = document.getElementById(`input-${col}-${row + 1}`);
                if (nextInput) nextInput.focus();
            }
        }
    });
</script>
@endsection