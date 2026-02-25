<div class="modal fade" id="modalExperience" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('seeker.profile.experience.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pengalaman Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Posisi Pekerjaan</label>
                    <input type="text" name="job_title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Berakhir</label>
                        <input type="date" name="end_date" class="form-control">
                        <small class="text-muted">Kosongkan jika masih bekerja.</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi Pekerjaan</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Karier</button>
            </div>
        </form>
    </div>
</div>