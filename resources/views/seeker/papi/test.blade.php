@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Psikotes: PAPI Kostick</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>Penting:</strong> Kerjakan dengan jujur dan cepat. Pilih pernyataan yang paling menggambarkan diri Anda.
            </div>

            <form action="{{ route('seeker.papi.submit', $testResult->id) }}" method="POST">
                @csrf
                <div class="row">
                    @foreach($questions as $question)
                    <div class="col-md-6 mb-4">
                        <div class="p-3 border rounded bg-light h-100">
                            <p class="fw-bold">No. {{ $question->question_number }}</p>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="answers[{{ $question->question_number }}]"
                                    value="a" id="papi_{{ $question->id }}_a" required>
                                <label class="form-check-label" for="papi_{{ $question->id }}_a">
                                    {{ $question->option_a }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answers[{{ $question->question_number }}]"
                                    value="b" id="papi_{{ $question->id }}_b" required>
                                <label class="form-check-label" for="papi_{{ $question->id }}_b">
                                    {{ $question->option_b }}
                                </label>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-success btn-lg">Selesaikan Tes PAPI</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.onbeforeunload = function() {
        return "Data pengerjaan tes Anda mungkin akan hilang. Apakah Anda yakin ingin keluar?";
    };

    // Menghilangkan pesan saat form disubmit
    document.querySelector('form').onsubmit = function() {
        window.onbeforeunload = null;
    };
</script>

@endsection