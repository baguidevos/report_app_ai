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
            previewContent.innerHTML = '<p class="text-gray-500 text-center py-8">Sélectionnez au moins 2 rapports pour voir l\'aperçu</p>';
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
            .replace(/^## (.+)$/gm, '<h2 class="text-xl font-bold mt-6 mb-3">$1</h2>')
            .replace(/^\\*\\*(.+?)\\*\\*$/gm, '<strong>$1</strong>')
            .replace(/^---$/gm, '<hr class="my-4 border-gray-300">')
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
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center space-x-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Retour aux rapports</span>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Fusionner des Rapports</h1>
        <p class="text-gray-600 mt-1">Sélectionnez les rapports à fusionner en un rapport maître</p>
    </div>

    @if($reports->count() < 2)
        <div class="glass rounded-xl p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Pas assez de rapports</h3>
            <p class="mt-1 text-sm text-gray-500">Vous avez besoin d'au moins 2 rapports pour effectuer une fusion.</p>
            <div class="mt-6">
                <a href="{{ route('reports.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-smooth">
                    Créer un rapport
                </a>
            </div>
        </div>
    @else
        <form action="{{ route('reports.merge') }}" method="POST" id="merge-form" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Report Selection -->
                <div class="glass rounded-xl p-6 shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Sélection des Rapports</h2>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Tout sélectionner</span>
                        </label>
                    </div>

                    <!-- Filters -->
                    <div class="mb-4">
                        <select name="frequency_filter" onchange="window.location.href='?frequency=' + this.value" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Tous les rapports</option>
                            <option value="daily" {{ request('frequency') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                            <option value="weekly" {{ request('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                            <option value="monthly" {{ request('frequency') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                        </select>
                    </div>

                    <!-- Reports List -->
                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @foreach($reports as $report)
                            <label class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-blue-400 cursor-pointer transition-smooth">
                                <input type="checkbox" 
                                       name="report_ids[]" 
                                       value="{{ $report->id }}"
                                       class="report-checkbox mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                       data-title="{{ $report->title }}"
                                       data-date="{{ $report->created_at->format('d/m/Y H:i') }}"
                                       data-frequency="{{ $report->frequency === 'daily' ? 'Quotidien' : ($report->frequency === 'weekly' ? 'Hebdomadaire' : 'Mensuel') }}"
                                       data-content="{{ addslashes(substr($report->content, 0, 200)) }}">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $report->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded-full 
                                        {{ $report->frequency === 'daily' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $report->frequency === 'weekly' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $report->frequency === 'monthly' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ $report->frequency === 'daily' ? 'Quotidien' : '' }}
                                        {{ $report->frequency === 'weekly' ? 'Hebdomadaire' : '' }}
                                        {{ $report->frequency === 'monthly' ? 'Mensuel' : '' }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column: Preview & Settings -->
                <div class="space-y-6">
                    <!-- Master Report Settings -->
                    <div class="glass rounded-xl p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Paramètres du Rapport Maître</h2>
                        
                        <div>
                            <label for="master-frequency" class="block text-sm font-medium text-gray-700 mb-2">Fréquence du rapport maître</label>
                            <select name="frequency" 
                                    id="master-frequency" 
                                    required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Sélectionnez une fréquence</option>
                                <option value="weekly">Hebdomadaire</option>
                                <option value="monthly">Mensuel</option>
                            </select>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm text-blue-800">
                                <strong>Rapports sélectionnés:</strong> <span id="selected-count">0</span>
                            </p>
                            <p class="text-xs text-blue-600 mt-1">
                                Minimum 2 rapports requis pour la fusion
                            </p>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="glass rounded-xl p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aperçu du Contenu</h2>
                        <div id="preview-content" class="prose prose-sm max-w-none bg-white p-4 rounded-lg border border-gray-200 max-h-96 overflow-y-auto">
                            <p class="text-gray-500 text-center py-8">Sélectionnez au moins 2 rapports pour voir l'aperçu</p>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button type="submit" 
                            id="merge-btn"
                            disabled
                            class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg font-medium opacity-50 cursor-not-allowed transition-smooth disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-lg">
                        <svg class="inline-block w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                        </svg>
                        Fusionner les Rapports
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
