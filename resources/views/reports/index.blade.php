@extends('layouts.app')

@section('title', 'Mes Rapports - AI ReportHub')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Mes Rapports</h1>
            <p class="text-gray-600 mt-1">Gérez et organisez vos rapports périodiques</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('reports.merge.form') }}" class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-6 py-2.5 rounded-lg hover:shadow-xl transition-smooth font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                </svg>
                <span>Fusionner</span>
            </a>
            <a href="{{ route('reports.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2.5 rounded-lg hover:shadow-xl transition-smooth font-medium flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Nouveau Rapport</span>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass rounded-xl p-4 shadow-sm">
        <form method="GET" action="{{ route('reports.index') }}" class="flex items-center space-x-4">
            <div class="flex-1">
                <label for="frequency" class="block text-sm font-medium text-gray-700 mb-1">Filtrer par fréquence</label>
                <select name="frequency" id="frequency" onchange="this.form.submit()" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Tous les rapports</option>
                    <option value="daily" {{ request('frequency') == 'daily' ? 'selected' : '' }}>Quotidien</option>
                    <option value="weekly" {{ request('frequency') == 'weekly' ? 'selected' : '' }}>Hebdomadaire</option>
                    <option value="monthly" {{ request('frequency') == 'monthly' ? 'selected' : '' }}>Mensuel</option>
                </select>
            </div>
            @if(request('frequency'))
                <div class="pt-6">
                    <a href="{{ route('reports.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Réinitialiser le filtre
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Reports Table -->
    @if($reports->count() > 0)
        <div class="glass rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Titre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fréquence
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date de création
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($reports as $report)
                        <tr class="hover:bg-gray-50 transition-smooth">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($report->isMaster())
                                        <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    @endif
                                    <div class="text-sm font-medium text-gray-900">{{ $report->title }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $report->frequency === 'daily' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $report->frequency === 'weekly' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $report->frequency === 'monthly' ? 'bg-purple-100 text-purple-800' : '' }}">
                                    {{ $report->frequency === 'daily' ? 'Quotidien' : '' }}
                                    {{ $report->frequency === 'weekly' ? 'Hebdomadaire' : '' }}
                                    {{ $report->frequency === 'monthly' ? 'Mensuel' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($report->isMaster())
                                    <span class="text-purple-600 font-medium">Rapport Maître</span>
                                    <span class="text-xs text-gray-400">({{ $report->children->count() }} sources)</span>
                                @else
                                    <span class="text-gray-500">Rapport Source</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $report->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a href="{{ route('reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900 transition-smooth">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('reports.destroy', $report) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-smooth">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="glass rounded-xl p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun rapport</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par créer votre premier rapport.</p>
            <div class="mt-6">
                <a href="{{ route('reports.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:shadow-lg transition-smooth">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nouveau Rapport
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
