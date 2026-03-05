@extends('layouts.seeker')

@section('title', 'Pengerjaan Tes Kraepelin')

@section('content')
<style>
    /* Layout Utama */
    .kraepelin-container { 
        overflow-x: auto; 
        white-space: nowrap; 
        padding: 60px 20px; 
        background: #f8fafc; 
        height: calc(100vh - 160px); 
        scroll-behavior: smooth;
        display: flex;
        align-items: flex-start;
    }

    /* Kolom Tes */
    .test-column { 
        display: inline-block; 
        min-width: 85px; 
        vertical-align: top; 
        margin-right: 30px; 
        transition: all 0.3s ease; 
        opacity: 0.15; 
        padding: 20px 10px; 
        border-radius: 16px; 
        border: 2px solid transparent; 
        background: #f1f5f9;
        pointer-events: none; /* Kunci kolom yang tidak aktif */
    }

    .test-column.active { 
        opacity: 1; 
        transform: scale(1.05); 
        background: #ffffff; 
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
        border: 2px solid #4338ca; 
        pointer-events: auto; /* Buka kunci untuk kolom aktif */
    }

    /* Angka Kraepelin */
    .number-box { 
        text-align: center; 
        font-size: 1.75rem; 
        font-weight: 800; 
        padding: 5px 0; 
        color: #1e293b; 
        line-height: 1;
        user-select: none;
    }

    /* Input Jawaban */
    .input-box { 
        width: 50px; 
        margin: 8px auto; 
        border: 2px solid #cbd5e1; 
        text-align: center; 
        border-radius: 10px; 
        font-weight: 900; 
        font-size: 1.4rem; 
        padding: 6px 0; 
        background: #f8fafc; 
        display: block;
        color: #4338ca;
        transition: all 0.2s;
    }

    .test-column.active .input-box { 
        background: #fff;
        border-color: #94a3b8;
    }

    .input-box:focus { 
        outline: none; 
        border-color: #4338ca; 
        background: #eef2ff !important;
        box-shadow: 0 0 0 4px rgba(67, 56, 202, 0.2); 
    }

    /* Sembunyikan Arrow Number */
    input::-webkit-outer-spin-button, 
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }

    /* Sticky Header */
    .timer-header { 
        position: sticky; 
        top: 0; 
        background: white; 
        z-index: 1000; 
        padding: 20px 40px; 
        border-bottom: 2px solid #e2e8f0; 
    }

    .text-danger-custom { color: #ef4444 !important; animation: blinker 1s linear infinite; }
    @keyframes blinker { 50% { opacity: 0; } }
    
    /* Overlay Peringatan */
    #warning-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.85); z-index: 9999; display: none;
        align-items: center; justify-content: center; color: white; text-align: center;
    }
</style>

@php
    $questionsData = $test->questions;
    $questionsArray = is_string($questionsData) ? json_decode($questionsData, true) : $questionsData;
@endphp

<div class="container-fluid p-0">
    <div id="offline-alert" class="alert alert-danger mb-0 rounded-0 text-center fw-bold d-none" style="position: sticky; top:0; z-index: 1001;">
        <i class="fas fa-wifi-slash mr-2"></i> Koneksi Internet Terputus! Jangan merefresh halaman.
    </div>

    <div id="warning-overlay">
        <div>
            <i class="fas fa-exclamation-triangle text-warning fa-4x mb-3"></i>
            <h2 class="fw-bold">Peringatan Kecurangan!</h2>
            <p>Anda terdeteksi keluar dari layar tes. Ini adalah peringatan ke-<span id="cheat-count">0</span> dari 3.</p>
            <button class="btn btn-warning fw-bold mt-3 px-4 rounded-pill" onclick="resumeTest()">Kembali ke Tes</button>
        </div>
    </div>

    <div class="timer-header d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <h5 class="mb-0 fw-bold text-dark text-uppercase letter-spacing-1">Tes Kraepelin</h5>
            <p class="text-muted small mb-0">Lakukan penjumlahan dari <b>Bawah ke Atas</b>. Ketik digit terakhir saja.</p>
        </div>
        <div class="d-flex align-items-center gap-5">
            <div class="text-center border-end pe-5">
                <div class="text-muted small fw-bold text-uppercase">Kolom</div>
                <div id="progress-text" class="h4 fw-bold mb-0 text-dark">1 / {{ count($questionsArray) }}</div>
            </div>
            <div class="text-end" style="min-width: 160px;">
                <div class="text-muted small fw-bold text-uppercase">Sisa Waktu</div>
                <div id="column-timer" class="display-6 fw-bold text-primary mb-0">30s</div>
            </div>
        </div>
    </div>

    <div class="kraepelin-container" id="main-test-area">
        @foreach($questionsArray as $colIndex => $column)
            <div class="test-column" id="col-{{ $colIndex }}">
                <div class="text-center small fw-bold text-primary mb-4">KOLOM {{ $colIndex + 1 }}</div>
                
                @php 
                    $totalItems = count($column);
                @endphp

                {{-- Render dari Atas ke Bawah (Logic Tetap Sama) --}}
                @for ($i = $totalItems - 1; $i >= 0; $i--)
                    <div class="number-box">{{ $column[$i] }}</div>
                    
                    @if ($i > 0)
                        <input type="number" 
                               class="input-box" 
                               maxlength="1"
                               data-col="{{ $colIndex }}" 
                               data-row="{{ $i - 1 }}"
                               id="input-{{ $colIndex }}-{{ $i - 1 }}" 
                               oninput="handleInput(this)"
                               disabled>
                    @endif
                @endfor
            </div>
        @endforeach
    </div>
</div>

{{-- Modal Loading Simpan --}}
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content bg-transparent border-0 text-center">
            <div class="spinner-border text-primary mb-3" style="width: 4rem; height: 4rem;" role="status"></div>
            <h4 class="text-white fw-bold">Sedang Mengolah Hasil...</h4>
            <p class="text-white-50">Mohon jangan tutup halaman ini.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const totalColumns = {{ count($questionsArray) }};
    const testId = "{{ $test->id }}";
    const submitUrl = "{{ route('seeker.kraepelin.submit', ':testId') }}".replace(':testId', testId);
    
    // Konfigurasi Tes
    const columnTimeLimit =30; // 30 detik per kolom
    const storageKey = `kraepelin_progress_${testId}`;
    
    let currentCol = 0;
    let timerInterval;
    let isSubmitting = false;
    let allAnswers = {};
    let cheatWarnings = 0;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

    // --- 1. LOCAL STORAGE (ANTI-RESET) ---
    function loadSavedProgress() {
        const savedData = localStorage.getItem(storageKey);
        if (savedData) {
            const parsedData = JSON.parse(savedData);
            allAnswers = parsedData.answers || {};
            
            // Jangan load currentCol dari storage, paksa lanjut agar waktu real-time
            // Kita hanya me-restore jawaban yang sudah terketik di memori
            Object.keys(allAnswers).forEach(key => {
                const parts = key.split('-');
                const col = parts[0];
                const row = parts[1];
                const inputEl = document.getElementById(`input-${col}-${row}`);
                if (inputEl) {
                    inputEl.value = allAnswers[key];
                }
            });
        }
    }

    function saveToLocal() {
        localStorage.setItem(storageKey, JSON.stringify({
            answers: allAnswers,
            last_saved: new Date().getTime()
        }));
    }

    // --- 2. DETEKSI INTERNET (OFFLINE/ONLINE) ---
    window.addEventListener('offline', () => {
        document.getElementById('offline-alert').classList.remove('d-none');
        document.getElementById('main-test-area').style.pointerEvents = 'none'; // Kunci layar
    });

    window.addEventListener('online', () => {
        document.getElementById('offline-alert').classList.add('d-none');
        document.getElementById('main-test-area').style.pointerEvents = 'auto'; // Buka kunci
    });

    // --- 3. DETEKSI CHEAT (PINDAH TAB) ---
    document.addEventListener("visibilitychange", () => {
        if (document.hidden && !isSubmitting && currentCol < totalColumns) {
            cheatWarnings++;
            document.getElementById('cheat-count').innerText = cheatWarnings;
            document.getElementById('warning-overlay').style.display = 'flex';
            
            if (cheatWarnings >= 3) {
                // Auto-Submit Jika Melanggar 3 Kali
                Swal.fire({
                    icon: 'error',
                    title: 'Pelanggaran Maksimal!',
                    text: 'Anda telah keluar dari layar tes 3 kali. Tes dihentikan otomatis.',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });
                setTimeout(finishTest, 2500);
            }
        }
    });

function resumeTest() {
        // 1. Sembunyikan overlay peringatan
        document.getElementById('warning-overlay').style.display = 'none';
        
        // 2. Cari baris terakhir yang harus dikerjakan (dari bawah ke atas)
        let r = 0;
        let targetInput = null;
        
        while (true) {
            const el = document.getElementById(`input-${currentCol}-${r}`);
            if (!el) break; // Berhenti jika sudah mencapai baris paling atas (tidak ada elemen lagi)
            
            // Cari input pertama yang tidak didisable dan isinya masih kosong
            if (!el.disabled && el.value === "") {
                targetInput = el;
                break; 
            }
            r++;
        }

        // 3. Kembalikan fokus kursor
        if (targetInput) {
            targetInput.focus();
            // Opsional: gulir layar agar field tersebut berada di tengah
            targetInput.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
        } else {
            // Fallback: jika semua baris sudah penuh, kembali ke kolom terbawah
            const firstInput = document.getElementById(`input-${currentCol}-0`);
            if(firstInput) firstInput.focus();
        }
    }

    // --- 4. PROTEKSI KELUAR HALAMAN ---
    window.addEventListener('beforeunload', function (e) {
        if (!isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Progres tes mungkin tidak tersimpan sepenuhnya ke server jika Anda keluar.';
        }
    });

    // --- FUNGSI UTAMA KRAEPELIN ---
    
    // Handle Input (Agar tercatat langsung ke Local Storage)
    window.handleInput = function(element) {
        if (element.value.length > 1) {
            element.value = element.value.slice(-1);
        }
        
        // Simpan ke Object memory
        if (element.value !== "") {
            allAnswers[`${element.dataset.col}-${element.dataset.row}`] = element.value;
            saveToLocal(); // Auto-save ke LocalStorage
            
            // Pindah ke Atas (Sesuai Logika Original Anda)
            const col = element.dataset.col;
            const currentRow = parseInt(element.dataset.row);
            const nextInput = document.getElementById(`input-${col}-${currentRow + 1}`);
            if (nextInput) nextInput.focus();
        }
    };

    function startColumnTimer() {
        if (timerInterval) clearInterval(timerInterval);

        let timeLeft = columnTimeLimit;
        const timerDisplay = document.getElementById('column-timer');
        const progressDisplay = document.getElementById('progress-text');

        timerDisplay.innerText = timeLeft + 's';
        timerDisplay.classList.remove('text-danger-custom');
        progressDisplay.innerText = `${currentCol + 1} / ${totalColumns}`;
        
        // Kunci Semua Kolom
        document.querySelectorAll('.test-column').forEach(el => {
            el.classList.remove('active');
            el.querySelectorAll('.input-box').forEach(input => input.disabled = true);
        });
        
        // Buka Kolom Saat Ini
        const activeEl = document.getElementById(`col-${currentCol}`);
        if (!activeEl) return;
        
        activeEl.classList.add('active');
        activeEl.querySelectorAll('.input-box').forEach(input => input.disabled = false);
        
        // Auto Fokus ke Input Terbawah (Index 0)
        setTimeout(() => {
            const firstInput = document.getElementById(`input-${currentCol}-0`);
            if(firstInput) {
                firstInput.focus();
                firstInput.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
            }
        }, 100); 

        // Auto Scroll Horizontal
        activeEl.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });

        timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.innerText = timeLeft + 's';

            if (timeLeft <= 5) timerDisplay.classList.add('text-danger-custom');

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                moveToNextColumn(); // Pindah Kolom Otomatis
            }
        }, 1000);
    }

    function moveToNextColumn() {
        // Ambil Data Terakhir sebelum pindah (jaga-jaga ada yang telat ngetik)
        const activeEl = document.getElementById(`col-${currentCol}`);
        if (activeEl) {
            activeEl.querySelectorAll('.input-box').forEach(input => {
                if(input.value !== "") {
                    allAnswers[`${input.dataset.col}-${input.dataset.row}`] = input.value;
                }
                input.disabled = true; // Kunci permanen
            });
            saveToLocal();
        }

        currentCol++;
        if (currentCol < totalColumns) {
            startColumnTimer();
        } else {
            finishTest();
        }
    }

    // Event BACKSPACE KE BAWAH (Row - 1) (Tetap dipertahankan)
    document.addEventListener('keydown', function(e) {
        if (e.target.classList.contains('input-box')) {
            const col = e.target.dataset.col;
            const currentRow = parseInt(e.target.dataset.row);

            if (e.key === "Backspace" && e.target.value === "") {
                const prevInput = document.getElementById(`input-${col}-${currentRow - 1}`);
                if (prevInput) prevInput.focus();
            }
        }
    });

    // --- 5. SUBMIT VIA AJAX ---
    async function finishTest() {
        if (isSubmitting) return; // Anti Double-Submit
        isSubmitting = true;
        clearInterval(timerInterval);

        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        
        try {
            const response = await axios.post(submitUrl, {
                answers: allAnswers,
                total_answered: Object.keys(allAnswers).length,
                cheat_count: cheatWarnings // Opsional: kirim data cheat ke server
            });
            
            if (response.data.status === 'success') {
                localStorage.removeItem(storageKey); // Bersihkan LocalStorage setelah berhasil
                window.location.href = response.data.redirect;
            } else {
                throw new Error("Gagal memproses data di server");
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat menyimpan jawaban. Data Anda aman di sistem kami. Halaman akan dimuat ulang.',
                confirmButtonText: 'Muat Ulang'
            }).then(() => {
                isSubmitting = false;
                loadingModal.hide();
                window.location.reload(); // Reload agar bisa submit ulang
            });
        }
    }

    // Mulai Tes saat halaman siap
    document.addEventListener('DOMContentLoaded', () => {
        loadSavedProgress();
        startColumnTimer();
    });
</script>
@endsection