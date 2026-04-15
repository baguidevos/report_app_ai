@extends('layouts.app')

@section('title', 'Nouveau Rapport - AI ReportHub')

@push('scripts')
    @stack('resources/js/editor.js')
    <script>
        window.reportId = 'new';
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
        <h1 class="text-3xl font-bold text-gray-900">Créer un Nouveau Rapport</h1>
        <p class="text-gray-600 mt-1">Rédigez votre rapport en Markdown</p>
    </div>

    <!-- Form -->
    <form action="{{ route('reports.store') }}" method="POST" class="glass rounded-xl p-6 shadow-sm space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre du rapport</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   required
                   value="{{ old('title') }}"
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
                <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
            </select>
            @error('frequency')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Content (Markdown Editor) -->
        <div>
            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Contenu</label>
            <textarea name="content" 
                      id="content" 
                      required
                      rows="20"
                      class="w-full @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
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
                Créer le Rapport
            </button>
        </div>
    </form>
</div>
@endsection
