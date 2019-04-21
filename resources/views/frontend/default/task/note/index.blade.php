<link rel="stylesheet" href="{{ mix('/css/front/task/note/index.css') }}">
<script src="https://cdn.ckeditor.com/ckeditor5/11.2.0/classic/ckeditor.js"></script>


<div class="xh_note" id="xh_note">
    <div class="note_title">笔记</div>
    <h1>Classic editor</h1>
    <textarea name="content" id="editor">
        &lt;p&gt;This is some sample content.&lt;/p&gt;
    </textarea>
    <script>
        ClassicEditor
            .create( document.querySelector( '#editor' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
</div>

<script src="{{ mix('/js/front/task/note/index.js') }}"></script>