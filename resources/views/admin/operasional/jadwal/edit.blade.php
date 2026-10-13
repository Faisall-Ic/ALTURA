<x-layouts.app>
    <x-slot:title>Edit Jadwal Pelajaran</x-slot:title>

    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <a href="{{ route('admin.jadwal.index') }}" class="inline-flex items-center gap-1.5 text-xs text-brand-primary hover:text-brand-navy font-semibold mb-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke Daftar Jadwal
            </a>
            <h1 class="text-2xl font-bold text-neutral-text">Edit Jadwal Pelajaran</h1>
            <p class="text-xs text-neutral-muted mt-1">Perbarui alokasi waktu dan ruang belajar mengajar.</p>
        </div>

        <div class="bg-white border border-neutral-border rounded-2xl p-6 sm:p-8 shadow-xs">
            <form action="{{ route('admin.jadwal.update', $schedule) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="class_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Pilih Kelas <span class="text-red-accent">*</span>
                        </label>
                        <select name="class_id" id="class_id" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            @foreach ($classes as $c)
                                <option value="{{ $c->id }}" {{ old('class_id', $schedule->class_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->major?->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subject_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Mata Pelajaran <span class="text-red-accent">*</span>
                        </label>
                        <select name="subject_id" id="subject_id" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            @foreach ($subjects as $s)
                                <option value="{{ $s->id }}" {{ old('subject_id', $schedule->subject_id) == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }} ({{ $s->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="teacher_id" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Guru Pengajar <span class="text-red-accent">*</span>
                        </label>
                        <select name="teacher_id" id="teacher_id" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            @foreach ($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('teacher_id', $schedule->teacher_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->user?->name }} ({{ $t->specialization_subject }})
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="room" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Ruang Kelas / Lab <span class="text-red-accent">*</span>
                        </label>
                        <input type="text" name="room" id="room" value="{{ old('room', $schedule->room) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                        @error('room')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="day_of_week" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Hari <span class="text-red-accent">*</span>
                        </label>
                        <select name="day_of_week" id="day_of_week" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                            @foreach ($days as $d)
                                <option value="{{ $d }}" {{ old('day_of_week', $schedule->day_of_week) == $d ? 'selected' : '' }}>
                                    {{ $d }}
                                </option>
                            @endforeach
                        </select>
                        @error('day_of_week')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time_start" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Jam Mulai <span class="text-red-accent">*</span>
                        </label>
                        <input type="time" name="time_start" id="time_start" value="{{ old('time_start', \Illuminate\Support\Str::substr($schedule->time_start, 0, 5)) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        @error('time_start')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="time_end" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                            Jam Selesai <span class="text-red-accent">*</span>
                        </label>
                        <input type="time" name="time_end" id="time_end" value="{{ old('time_end', \Illuminate\Support\Str::substr($schedule->time_end, 0, 5)) }}" required
                            class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text text-xs focus:outline-none focus:border-brand-primary">
                        @error('time_end')
                            <p class="text-xs text-red-accent mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="note" class="block text-xs font-semibold uppercase text-neutral-muted mb-2">
                        Catatan Tambahan
                    </label>
                    <input type="text" name="note" id="note" value="{{ old('note', $schedule->note) }}"
                        class="w-full px-4 py-2.5 rounded-xl bg-surface border border-neutral-border text-neutral-text placeholder-neutral-muted text-xs focus:outline-none focus:border-brand-primary">
                </div>

                <div class="pt-4 flex items-center justify-end gap-3 border-t border-neutral-border">
                    <a href="{{ route('admin.jadwal.index') }}" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-neutral-muted hover:text-neutral-text hover:bg-neutral-subtle transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-primary hover:bg-brand-navy shadow-xs transition-all">
                        Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
