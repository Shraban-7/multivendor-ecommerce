<div>
    <div id="{{ $name }}-editor"></div>
    <textarea name="{{ $name }}" id="{{ $name }}" hidden>{!! old($name, $value) !!}</textarea>
</div>

<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('{{ $name }}-editor')) {
            var editor = new Quill('#{{ $name }}-editor', {
                theme: 'snow',
            });

            var quillEditor = document.getElementById('{{ $name }}');
            editor.root.innerHTML = {!! json_encode(old($name, $value)) !!};

            editor.on('text-change', function() {
                quillEditor.value = editor.root.innerHTML;
            });

            quillEditor.addEventListener('input', function() {
                editor.root.innerHTML = quillEditor.value;
            });
        }
    });
</script>
