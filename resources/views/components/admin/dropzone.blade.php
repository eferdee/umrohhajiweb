@props(['name', 'accept' => 'image/*', 'existing' => null, 'hint' => 'PNG, JPG, hingga 2MB'])

<div
    x-data="{
        fileName: null,
        previewUrl: @js($existing),
        dragover: false,
        handleFiles(files) {
            if (! files || ! files.length) return;
            const file = files[0];
            this.fileName = file.name;
            if (file.type.startsWith('image/')) {
                this.previewUrl = URL.createObjectURL(file);
            }
        },
        onDrop(e) {
            this.dragover = false;
            const input = this.$refs.fileInput;
            input.files = e.dataTransfer.files;
            this.handleFiles(input.files);
        },
    }"
>
    <div
        class="dropzone"
        :class="{ 'is-dragover': dragover }"
        @click="$refs.fileInput.click()"
        @dragover.prevent="dragover = true"
        @dragleave.prevent="dragover = false"
        @drop.prevent="onDrop($event)"
    >
        <input
            x-ref="fileInput"
            type="file"
            name="{{ $name }}"
            accept="{{ $accept }}"
            class="sr-only"
            @change="handleFiles($event.target.files)"
            {{ $attributes }}
        >

        <template x-if="previewUrl">
            <div class="flex flex-col items-center gap-2">
                <img :src="previewUrl" class="w-28 h-20 object-cover rounded-lg border border-[var(--color-admin-border)] shadow-sm" alt="Pratinjau">
                <p class="text-xs text-[var(--color-ink-soft)]" x-text="fileName ?? 'Berkas saat ini'"></p>
                <span class="text-xs font-medium text-[var(--color-primary)]">Klik atau seret untuk mengganti</span>
            </div>
        </template>

        <template x-if="!previewUrl">
            <div class="flex flex-col items-center gap-2 pointer-events-none">
                <span class="w-11 h-11 rounded-full bg-[var(--color-surface)] border border-[var(--color-admin-border)] flex items-center justify-center text-[var(--color-primary)]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 7.5m0 0L7.5 12m4.5-4.5v13.5" /></svg>
                </span>
                <p class="text-sm font-medium text-[var(--color-ink)]">Klik untuk unggah atau seret berkas ke sini</p>
                <p class="text-xs text-[var(--color-ink-soft)]">{{ $hint }}</p>
            </div>
        </template>
    </div>
</div>
