@extends('layouts.app')

@section('title', 'Modifier le Rapport - AI ReportHub')

@push('scripts')
    @vite(['resources/js/editor.js'])
    <script>
        window.reportId = '{{ $report->id }}';
    </script>
    <script>
        // Agent execution functionality
        document.addEventListener('DOMContentLoaded', function() {
            const executeAgentBtn = document.getElementById('execute-agent-btn');
            const agentSelect = document.getElementById('agent-select');
            const statusDiv = document.getElementById('agent-status');
            
            if (executeAgentBtn && agentSelect) {
                executeAgentBtn.addEventListener('click', async function() {
                    const agentId = agentSelect.value;
                    if (!agentId) {
                        alert('Veuillez sélectionner un agent');
                        return;
                    }
                    
                    // Show loading state
                    executeAgentBtn.disabled = true;
                    executeAgentBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Traitement...';
                    statusDiv.classList.remove('hidden');
                    statusDiv.innerHTML = '<p class="text-sm text-blue-600">Traitement en cours...</p>';
                    
                    try {
                        const response = await fetch(`/agents/${agentId}/execute`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ report_id: {{ $report->id }} })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            statusDiv.innerHTML = '<p class="text-sm text-green-600">✓ Traitement terminé! Rechargez la page pour voir le nouveau rapport.</p>';
                            
                            // Reload after 3 seconds
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            statusDiv.innerHTML = '<p class="text-sm text-red-600">Erreur: ' + data.message + '</p>';
                        }
                    } catch (error) {
                        statusDiv.innerHTML = '<p class="text-sm text-red-600">Erreur de connexion</p>';
                    } finally {
                        executeAgentBtn.disabled = false;
                        executeAgentBtn.innerHTML = 'Exécuter l\'Agent';
                    }
                });
            }
        });
    </script>
@endpush

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center space-x-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Retour aux rapports</span>
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Modifier le Rapport</h1>
        <p class="text-gray-600 mt-1">Dernière modification: {{ $report->updated_at->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Form -->
    <form action="{{ route('reports.update', $report) }}" method="POST" class="glass rounded-xl p-6 shadow-sm space-y-6">
        @csrf
        @method('PUT')

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre du rapport</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   required
                   value="{{ old('title', $report->title) }}"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                   placeholder="Ex: Rapport quotidien - 15 Avril 2026">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Frequency -->
        <div>
            <label for="frequency" class="block text-sm font-medium text-gray-700 mb-2">Fréquence</label>
            <select name="frequency" 
                    id="frequency" 
                    required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('frequency') border-red-500 @enderror">
                <option value="">Sélectionnez une fréquence</option>
                <option value="daily" {{ old('frequency', $report->frequency) == 'daily' ? 'selected' : '' }}>Quotidien</option>
                <option value="weekly" {{ old('frequency', $report->frequency) == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                <option value="monthly" {{ old('frequency', $report->frequency) == 'monthly' ? 'selected' : '' }}>Mensuel</option>
            </select>
            @error('frequency')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- AI Agent Section -->
        <div class="border-t border-gray-200 pt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Agents IA</h2>
            <p class="text-sm text-gray-600 mb-4">Utilisez un agent IA pour transformer votre rapport</p>
            
            <div class="flex items-end space-x-3">
                <div class="flex-1">
                    <label for="agent-select" class="block text-sm font-medium text-gray-700 mb-2">Sélectionner un agent</label>
                    <select id="agent-select" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Choisissez un agent...</option>
                        @foreach(\App\Models\Agent::getDefaultAgents() as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" 
                        id="execute-agent-btn"
                        class="px-6 py-2.5 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-smooth font-medium whitespace-nowrap">
                    Exécuter l'Agent
                </button>
            </div>
            
            <div id="agent-status" class="mt-3 hidden"></div>
        </div>

        <!-- Content (Markdown Editor) -->
        <div class="border-t border-gray-200 pt-6">
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
            <textarea name="content" 
                      id="content" 
                      required
                      rows="20"
                      class="w-full @error('content') border-red-500 @enderror">{{ old('content', $report->content) }}</textarea>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('reports.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-smooth font-medium">
                Annuler
            </a>
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:shadow-lg transition-smooth font-medium">
                <svg class="inline-block w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Mettre à Jour
            </button>
        </div>
    </form>

    @if($report->isMaster())
    <!-- Master Report Info -->
    <div class="mt-6 glass rounded-xl p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Rapports Sources</h2>
        <p class="text-sm text-gray-600 mb-4">Ce rapport maître a été généré à partir de {{ $report->children->count() }} rapport(s) source(s).</p>
        
        <div class="space-y-2">
            @foreach($report->getChildren() as $child)
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $child->title }}</p>
                        <p class="text-xs text-gray-500">{{ $child->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $child->frequency === 'daily' ? 'bg-blue-100 text-blue-800' : '' }}
                        {{ $child->frequency === 'weekly' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $child->frequency === 'monthly' ? 'bg-purple-100 text-purple-800' : '' }}">
                        {{ $child->frequency === 'daily' ? 'Quotidien' : '' }}
                        {{ $child->frequency === 'weekly' ? 'Hebdomadaire' : '' }}
                        {{ $child->frequency === 'monthly' ? 'Mensuel' : '' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
