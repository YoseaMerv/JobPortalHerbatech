<div class="modal fade" id="modalSkill" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('seeker.profile.skill.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Keahlian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nama Keahlian</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Laravel, Project Management, PHP" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tingkat Kemahiran</label>
                    <select name="level" class="form-select">
                        <option value="Beginner">Pemula (Beginner)</option>
                        <option value="Intermediate">Menengah (Intermediate)</option>
                        <option value="Advanced">Ahli (Advanced)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Keahlian</button>
            </div>
        </form>
    </div>
</div>