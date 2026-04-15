<div class="main-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Logo -->
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-primary rounded-card flex items-center justify-center">
                    <i class="fas fa-file-alt text-white text-lg"></i>
                </div>
                <span class="text-lg font-semibold text-text-primary">AI ReportHub</span>
            </a>
        </div>

        <!-- Welcome Message -->
        <div class="mb-2 px-3 py-2.5 glass-effect rounded-card">
            <p class="text-xs text-text-secondary mb-0.5">Bienvenue,</p>
            <p class="text-sm font-semibold text-text-primary">{{ Auth::user()->name }}</p>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 space-y-1.5">
            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-card transition-smooth {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-accent/20' }}">
                <i class="fas fa-home w-5 text-sm"></i>
                <span class="font-medium text-sm">Tableau de bord</span>
            </a>

            <a href="{{ route('reports.index') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-card transition-smooth {{ request()->routeIs('reports.*') && !request()->routeIs('reports.merge.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-accent/20' }}">
                <i class="fas fa-file-alt w-5 text-sm"></i>
                <span class="font-medium text-sm">Rapports</span>
                @php
                    $reportCount = Auth::user()->reports()->count();
                @endphp
                @if($reportCount > 0)
                    <span
                        class="ml-auto text-xs px-2 py-0.5 rounded-full bg-primary/20 text-primary font-semibold">{{ $reportCount }}</span>
                @endif
            </a>

            <a href="{{ route('reports.merge.form') }}"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-card transition-smooth {{ request()->routeIs('reports.merge.*') ? 'bg-primary/10 text-primary' : 'text-text-secondary hover:bg-accent/20' }}">
                <i class="fas fa-object-group w-5 text-sm"></i>
                <span class="font-medium text-sm">Fusionner</span>
            </a>

            <a href="{{ route('reports.create') }}"
                class="flex items-center px-3 py-2.5 rounded-card bg-primary text-white hover:bg-primary-hover transition-smooth shadow-sm mt-2">
                <i class="fas fa-plus-circle w-5 text-sm"></i>
                <span class="font-medium text-sm">Nouveau Rapport</span>
            </a>
        </nav>

        <!-- User Profile -->
        <div class="pt-4 border-t border-accent">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="flex items-center space-x-3 w-full px-3 py-2 rounded-card hover:bg-accent/20 transition-smooth">
                    <div
                        class="w-9 h-9 bg-gradient-to-br from-primary to-primary-hover rounded-full flex items-center justify-center text-white font-semibold shadow-sm text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 text-left">
                        <p class="text-sm font-semibold text-text-primary leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-text-secondary leading-tight truncate max-w-[140px]">
                            {{ Auth::user()->email }}</p>
                    </div>
                    <i class="fas fa-chevron-down text-text-secondary text-xs transition-transform duration-200"
                        :class="{ 'rotate-180': open }"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute bottom-full left-0 mb-2 w-56 glass-effect rounded-card shadow-lg overflow-hidden z-50">
                    <div class="py-2">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center px-4 py-2 text-sm text-text-primary hover:bg-accent/20 transition-smooth">
                            <i class="fas fa-user mr-3 w-4 text-text-secondary"></i>
                            <span>Profil</span>
                        </a>
                        <hr class="my-1 border-accent">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-smooth">
                                <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Bar -->
        <header class="sticky top-0 z-40 glass-effect border-b border-accent backdrop-blur-md">
            <div class="px-6 py-3 flex items-center justify-between">
                <div>
                    @hasSection('header')
                        @yield('header')
                    @else
                        <div>
                            <h1 class="text-2xl font-semibold text-text-primary">Tableau de bord</h1>
                            <p class="text-sm text-text-secondary mt-1">Bienvenue, {{ Auth::user()->name }} 👋</p>
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Notifications -->
                    <button class="relative p-2 text-text-secondary hover:text-text-primary transition-smooth">
                        <i class="fas fa-bell text-base"></i>
                        @php
                            $unreadCount = Auth::user()->reports()->where('created_at', '>', now()->subDay())->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </button>

                    <!-- Quick Actions -->
                    <a href="{{ route('reports.create') }}"
                        class="hidden md:inline-flex items-center px-3 py-1.5 bg-primary text-white rounded-card text-sm font-medium btn-primary-hover transition-smooth">
                        <i class="fas fa-plus mr-1.5"></i>
                        Nouveau
                    </a>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 glass-effect rounded-card border-l-4 border-green-500 animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-text-primary font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 glass-effect rounded-card border-l-4 border-red-500 animate-fade-in">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <p class="text-red-600 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 glass-effect rounded-card border-l-4 border-red-500 animate-fade-in">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        <p class="text-red-600 font-semibold">Erreurs de validation</p>
                    </div>
                    <ul class="ml-8 text-sm text-red-600 list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>
</div>