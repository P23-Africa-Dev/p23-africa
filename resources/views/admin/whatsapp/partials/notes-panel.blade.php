@php
    $conversation = $conversation ?? null;
    $notes = $notes ?? collect();
    $noteStoreUrl = $noteStoreUrl ?? null;
@endphp

<div class="wa-notes-panel">
    <div class="wa-notes-list" data-wa-notes-list>
        @forelse ($notes as $note)
            <div class="mb-2 pb-2 border-bottom">
                <div class="small fw-semibold">{{ $note->admin?->name ?: 'Admin' }}</div>
                <div class="small">{{ $note->note }}</div>
            </div>
        @empty
            <div class="text-muted small">No internal notes yet.</div>
        @endforelse
    </div>

    @if ($noteStoreUrl && $conversation)
        <form method="POST" action="{{ $noteStoreUrl }}" class="mt-3" data-wa-note-form>
            @csrf
            <textarea name="note" class="form-control form-control-sm" rows="2" placeholder="Add internal note..."></textarea>
            <div class="mt-2 text-end">
                <button class="btn btn-sm btn-outline-success" type="submit">Save Note</button>
            </div>
        </form>
    @endif
</div>
