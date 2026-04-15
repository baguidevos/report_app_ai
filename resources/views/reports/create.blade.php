@extends('layouts.app')

@section('title', 'Nouveau Rapport - AI ReportHub')

@push('scripts')
    @vite(['resources/js/editor.js'])
    <script>
        window.reportId = 'new';
    </script>
@endpush
@section('header')
    <div>
        <h1 class="text-2xl font-semibold text-text-primary">Nouveau Rapport</h1>
        <p class="text-sm text-text-secondary mt-1">Rédiger un nouveau rapport</p>
    </div>
@endsection
@section('content')
@if ($errors->any())
   @foreach ($errors->all() as $error)
       <p class="text-sm text-red-600">{{ $error }}</p>
   @endforeach
@endif
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('reports.index') }}"
            class="inline-flex items-center px-3 py-2 glass-effect border border-accent text-text-primary rounded-card hover:bg-accent/20 transition-smooth">
            <i class="fas fa-chevron-left"></i>
        </a>        
        <div>
            <h1 class="text-xl font-semibold text-text-primary">Nouveau Rapport</h1>
            <p class="text-sm text-text-secondary mt-1">Rédigez votre rapport en Markdown</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('reports.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Title -->
        <div>
            <label for="title" class="block text-sm font-medium text-text-secondary mb-2">Titre du rapport</label>
            <input type="text" name="title" id="title" required value="{{ old('title') }}"
                class="w-full px-4 py-3 glass-effect border border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white @error('title') border-red-500 @enderror"
                placeholder="Ex: Rapport quotidien - 15 Avril 2026">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Frequency -->
        <div>
            <label for="frequency" class="block text-sm font-medium text-text-secondary mb-2">Fréquence</label>
            <select name="frequency" id="frequency" required
                class="w-full px-4 py-3 glass-effect border border-accent rounded-card shadow-sm focus:border-primary focus:ring-primary bg-white @error('frequency') border-red-500 @enderror">
                <option value="">Sélectionnez une fréquence</option>
                <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                <option value="weekly" {{ old('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                <option value="monthly" {{ old('frequency') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
            </select>
            @error('frequency')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Editor Toolbar -->
        <div>
            <label for="content" class="block text-sm font-medium text-text-secondary mb-2">Contenu</label>
            <div class="glass-effect rounded-card border border-accent overflow-hidden">
                <!-- Toolbar -->
                <div class="px-4 py-3 border-b border-accent flex gap-4">
                    <i
                        class="fas fa-bold text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i
                        class="fas fa-italic text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i
                        class="fas fa-list-ul text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i
                        class="fas fa-heading text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i
                        class="fas fa-link text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                    <i
                        class="fas fa-image text-text-secondary cursor-pointer hover:text-text-primary transition-smooth"></i>
                </div>

                <!-- Textarea -->
                <textarea name="content" id="content" rows="20"
                    class="w-full px-6 py-4 bg-transparent border-none outline-none resize-none text-text-primary @error('content') border-red-500 @enderror"
                    placeholder="Commencez à écrire ici...">{{ old('content') }}</textarea>
            </div>
            @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center pt-6 border-t border-accent">
            <a href="{{ route('reports.index') }}"
                class="inline-flex items-center px-6 py-3 glass-effect border border-accent text-text-primary rounded-card font-medium hover:bg-accent/20 transition-smooth">
                Annuler
            </a>
            <button type="submit"
                class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-card font-medium btn-primary-hover transition-smooth">
                <i class="fas fa-check mr-2"></i>
                Créer le Rapport
            </button>
        </div>
    </form>
</div>
@endsection
