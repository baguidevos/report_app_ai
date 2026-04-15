@extends('layouts.app')

@section('title', 'Fusionner des Rapports - AI ReportHub')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.report-checkbox');
    const selectedCount = document.getElementById('selected-count');
    const previewContent = document.getElementById('preview-content');
    const mergeForm = document.getElementById('merge-form');
    const frequencySelect = document.getElementById('master-frequency');

    // Update selection count and preview
    function updateSelection() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked);
        const count = selected.length;

        selectedCount.textContent = count;

        // Enable/disable merge button
        const mergeBtn = document.getElementById('merge-btn');
        if (count >= 2) {
            mergeBtn.disabled = false;
            mergeBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            mergeBtn.disabled = true;
            mergeBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        // Update preview
        updatePreview(selected);
    }

    // Update preview content
    function updatePreview(selectedReports) {
        if (selectedReports.length === 0) {
            previewContent.innerHTML = '<p class="text-text-secondary text-center py-8">Sélectionnez au moins 2 rapports pour voir l\'aperçu</p>';
            return;
        }

        let preview = '';
        selectedReports.forEach((report, index) => {
            const title = report.dataset.title;
            const date = report.dataset.date;
            const frequency = report.dataset.frequency;
            const content = report.dataset.content;

            preview += `## ${title}\\n\\n`;
            preview += `**Date:** ${date}\\n\\n`;
            preview += `**Fréquence:** ${frequency}\\n\\n`;
            preview += `${content}\\n\\n`;

            if (index < selectedReports.length - 1) {
                preview += `---\\n\\n`;
            }
        });

        // Simple markdown to HTML conversion for preview
        preview = preview
            .replace(/^## (.+)$/gm, '<h2 class="text-xl font-semibold mt-6 mb-3 text-text-primary">$1</h2>')
            .replace(/^\\*\\*(.+?)\\*\\*$/gm, '<strong>$1</strong>')
            .replace(/^---$/gm, '<hr class="my-4 border-accent">')
            .replace(/\\n/g, '<br>');

        previewContent.innerHTML = preview;
    }

    // Add event listeners to checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    // Select all functionality
    const selectAll = document.getElementById('select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
            updateSelection();
        });
    }

    // Initial update
    updateSelection();
});
</script>
@endpush

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('reports.index') }}" 
           class="inline-flex items-center px-3 py-2 glass-effect border border-accent text-text-primary rounded-card hover:bg-accent/20 transition-smooth">
            <i class="fas fa-chevron-left"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-text-primary">Fusionner & Analyser</h1>
            <p class="text-sm text-text-secondary mt-1">Sélectionnez les rapports à jumeler pour une analyse globalisée.</p>
        </div>
    </div>

    @if($reports->count() < 2)
        <div class="glass-effect rounded-card p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-accent/20 rounded-full flex items-center justify-center">
                <i class="fas fa-object-group text-text-secondary text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-text-primary mb-2">Pas assez de rapports</h3>
            <p class="text-sm text-text-secondary mb-6">Vous avez besoin d'au moins 2 rapports pour effectuer une fusion.</p>
            <a href="{{ route('reports.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-card font-medium btn-primary-hover transition-smooth">
                <i class="fas fa-plus mr-2"></i>
                Créer un rapport
            </a>
        </div>
    @else
        <form action="{{ route('reports.merge') }}" method="POST" id="merge-form" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Report Selection -->
                <div class="glass-effect rounded-card p-6 border border-accent">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-text-primary">Sélection des Rapports</h2>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="select-all" class="rounded border-accent text-primary focus:ring-primary">
                            <span class="text-sm text-text-secondary">Tout sélectionner</span>
                        </label>
                    </div>

                    <!-- Filters -->
                    <div class="mb-4">
                        <select name="frequency_filter" 
                                onchange="window.location.href='?frequency=' + this.value" 
                                class="w-full px-4 py-3 glass-effect border border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white text-sm">
                            <option value="">Tous les rapports</option>
                            <option value="daily" {{ request('frequency') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                            <option value="weekly" {{ request('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                            <option value="monthly" {{ request('frequency') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                        </select>
                    </div>

                    <!-- Reports List -->
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($reports as $report)
                            <label class="report-card flex items-start space-x-3 p-4 glass-effect rounded-card border border-accent cursor-pointer card-hover">
                                <input type="checkbox"
                                       name="report_ids[]"
                                       value="{{ $report->id }}"
                                       class="report-checkbox mt-1 rounded border-accent text-primary focus:ring-primary">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-text-primary">{{ $report->title }}</p>
                                    <p class="text-xs text-text-secondary">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                                    <span class="inline-block mt-2 text-xs px-3 py-1 rounded-full bg-accent text-text-primary">
                                        @if($report->frequency === 'daily')
                                            Quotidien
                                        @elseif($report->frequency === 'weekly')
                                            Hebdomadaire
                                        @elseif($report->frequency === 'monthly')
                                            Mensuel
                                        @endif
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column: Preview & Settings -->
                <div class="space-y-6">
                    <!-- Master Report Settings -->
                    <div class="glass-effect rounded-card p-6 border border-accent">
                        <h2 class="text-lg font-semibold text-text-primary mb-4">Paramètres du Rapport Maître</h2>

                        <div>
                            <label for="master-frequency" class="block text-sm font-medium text-text-secondary mb-2">Fréquence du rapport maître</label>
                            <select name="frequency"
                                    id="master-frequency"
                                    required
                                    class="w-full px-4 py-3 glass-effect border border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white">
                                <option value="">Sélectionnez une fréquence</option>
                                <option value="weekly">Hebdomadaire</option>
                                <option value="monthly">Mensuel</option>
                            </select>
                        </div>

                        <div class="mt-4 p-4 bg-primary/5 rounded-card border border-primary/20">
                            <p class="text-sm text-text-primary">
                                <strong>Rapports sélectionnés:</strong> <span id="selected-count" class="font-semibold">0</span>
                            </p>
                            <p class="text-xs text-text-secondary mt-1">
                                Minimum 2 rapports requis pour la fusion
                            </p>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="glass-effect rounded-card p-6 border border-accent">
                        <h2 class="text-lg font-semibold text-text-primary mb-4">Aperçu du Contenu</h2>
                        <div id="preview-content" class="prose prose-sm max-w-none glass-effect p-4 rounded-card border border-accent max-h-96 overflow-y-auto">
                            <p class="text-text-secondary text-center py-8">Sélectionnez au moins 2 rapports pour voir l'aperçu</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button type="submit"
                            id="merge-btn"
                            disabled
                            class="w-full px-6 py-3 bg-primary text-white rounded-card font-medium opacity-50 cursor-not-allowed transition-smooth disabled:opacity-50 disabled:cursor-not-allowed btn-primary-hover">
                        <i class="fas fa-wand-magic-sparkles mr-2"></i>
                        Fusionner avec Agent IA
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
