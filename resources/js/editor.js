import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';

// Initialize EasyMDE editor
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('content');
    
    if (textarea) {
        const easyMDE = new EasyMDE({
            element: textarea,
            spellChecker: false,
            autosave: {
                enabled: true,
                uniqueId: 'report-editor-' + (window.reportId || 'new'),
                delay: 1000,
            },
            toolbar: [
                'bold', 'italic', 'heading', '|',
                'quote', 'unordered-list', 'ordered-list', '|',
                'link', 'image', '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                'guide'
            ],
            status: ['lines', 'words', 'cursor'],
            placeholder: 'Rédigez votre rapport ici en Markdown...',
            renderingConfig: {
                singleLineBreaks: false,
                codeSyntaxHighlighting: true,
            },
        });
        
        // Store instance for later use
        window.easyMDE = easyMDE;
        
        // Sync content before form submission
        const form = textarea.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                textarea.value = easyMDE.value();
            });
        }
    }
});
