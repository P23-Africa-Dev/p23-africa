<div class="template-editor-pane d-none" data-format-pane="rich_text">
    <label class="form-label">Rich Text Editor</label>
    <div class="border rounded p-2 bg-light-subtle mb-2" data-rich-toolbar>
        <div class="btn-group btn-group-sm mb-1 me-1" role="group">
            <button type="button" class="btn btn-outline-secondary" data-rich-command="bold"><i
                    class="bi bi-type-bold"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="italic"><i
                    class="bi bi-type-italic"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="underline"><i
                    class="bi bi-type-underline"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="strikeThrough"><i
                    class="bi bi-type-strikethrough"></i></button>
        </div>
        <div class="btn-group btn-group-sm mb-1 me-1" role="group">
            <button type="button" class="btn btn-outline-secondary" data-rich-command="insertUnorderedList"><i
                    class="bi bi-list-ul"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="insertOrderedList"><i
                    class="bi bi-list-ol"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="formatBlock"
                data-rich-value="blockquote"><i class="bi bi-chat-left-quote"></i></button>
        </div>
        <div class="btn-group btn-group-sm mb-1 me-1" role="group">
            <button type="button" class="btn btn-outline-secondary" data-rich-command="justifyLeft"><i
                    class="bi bi-text-left"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="justifyCenter"><i
                    class="bi bi-text-center"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="justifyRight"><i
                    class="bi bi-text-right"></i></button>
        </div>
        <div class="btn-group btn-group-sm mb-1" role="group">
            <button type="button" class="btn btn-outline-secondary" data-rich-command="undo"><i
                    class="bi bi-arrow-counterclockwise"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-command="redo"><i
                    class="bi bi-arrow-clockwise"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-link><i
                    class="bi bi-link-45deg"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-mention><i class="bi bi-at"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-heading><i
                    class="bi bi-type-h1"></i></button>
            <button type="button" class="btn btn-outline-secondary" data-rich-emoji>😊</button>
        </div>
    </div>
    <div class="form-control" contenteditable="true" data-rich-editor style="min-height:260px;overflow:auto;">
        {!! $value !!}</div>
    <input type="hidden" name="rich_text_content" data-rich-hidden value="{{ $value }}">
    <div class="small mt-2 text-muted">For non-technical users: rich formatting, mentions, links, quotes, and headings.
    </div>
</div>
