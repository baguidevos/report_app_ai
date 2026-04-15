# AI ReportHub

Une application web moderne pour la création, la gestion et la fusion de rapports périodiques avec intégration d'agents IA spécialisés.

## Fonctionnalités

### MVP (Phase Actuelle)
- ✅ **Éditeur Markdown** - Éditeur riche avec EasyMDE, preview en temps réel et sauvegarde automatique
- ✅ **Gestion de Rapports** - CRUD complet avec catégorisation par fréquence (quotidien, hebdomadaire, mensuel)
- ✅ **Fusion Intelligente** - Sélection multiple de rapports et concaténation automatique en rapports maîtres
- ✅ **Agents IA** - Trois agents spécialisés (Synthèse Client, Analyse de Tendances, Formatage Professionnel)
- ✅ **Design Moderne** - Interface glassmorphism avec Tailwind CSS, responsive et élégante

## Stack Technique

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Vite 7 + Tailwind CSS 4 + Blade Templates
- **Base de données**: SQLite (configurable pour MySQL/PostgreSQL)
- **Éditeur Markdown**: EasyMDE
- **IA**: OpenAI API (GPT-3.5-turbo / GPT-4)
- **Queue**: Laravel Queue (database driver)

## Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Node.js 18+ et npm
- SQLite ou autre SGBD

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone <repository-url>
cd ecommerce_learn
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Installer les dépendances JavaScript**
```bash
npm install
```

4. **Configurer l'environnement**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurer la base de données**
Le projet utilise SQLite par défaut. Le fichier `database/database.sqlite` sera créé automatiquement.

6. **Exécuter les migrations et seeders**
```bash
php artisan migrate --seed
```

7. **Configurer l'API OpenAI** (optionnel mais recommandé)

Obtenez une clé API sur [OpenAI Platform](https://platform.openai.com/api-keys) et ajoutez-la dans `.env`:
```env
OPENAI_API_KEY=sk-votre-clé-api-ici
OPENAI_MODEL=gpt-3.5-turbo
```

8. **Compiler les assets**
```bash
npm run build
```

9. **Lancer l'application**
```bash
php artisan serve
```

L'application sera disponible à l'adresse: http://localhost:8000

## Utilisation

### Créer un Rapport
1. Cliquez sur "Nouveau Rapport"
2. Remplissez le titre, sélectionnez la fréquence
3. Rédigez votre contenu en Markdown avec l'éditeur intégré
4. Sauvegardez

### Fusionner des Rapports
1. Allez sur la page "Fusionner"
2. Sélectionnez au moins 2 rapports à fusionner
3. Choisissez la fréquence du rapport maître
4. Visualisez l'aperçu en temps réel
5. Cliquez sur "Fusionner les Rapports"

### Utiliser les Agents IA
1. Ouvrez un rapport existant
2. Dans la section "Agents IA", sélectionnez un agent
3. Cliquez sur "Exécuter l'Agent"
4. Un nouveau rapport sera créé avec le contenu transformé par l'IA

## Architecture

### Structure des Fichiers
```
app/
├── Models/
│   ├── Report.php          # Modèle de rapport
│   ├── Agent.php           # Modèle d'agent IA
│   └── User.php            # Modèle utilisateur
├── Http/Controllers/
│   ├── ReportController.php    # CRUD rapports
│   ├── MergeController.php     # Fusion de rapports
│   └── AgentController.php     # Exécution agents IA
├── Services/
│   ├── ReportMergeService.php  # Logique de fusion
│   └── AIService.php           # Intégration OpenAI
├── Jobs/
│   └── ProcessAIAgentJob.php   # Job asynchrone IA
└── Http/Requests/
    ├── StoreReportRequest.php
    ├── UpdateReportRequest.php
    └── MergeReportsRequest.php

resources/
├── views/
│   ├── layouts/app.blade.php       # Layout principal
│   └── reports/
│       ├── index.blade.php         # Liste des rapports
│       ├── create.blade.php        # Création
│       ├── edit.blade.php          # Édition
│       └── merge.blade.php         # Interface de fusion
└── js/
    ├── app.js                      # Entry point
    └── editor.js                   # Configuration EasyMDE
```

### Base de Données

**Table `reports`**
- `id`: Identifiant unique
- `user_id`: Propriétaire du rapport
- `title`: Titre du rapport
- `content`: Contenu Markdown
- `frequency`: Fréquence (daily/weekly/monthly)
- `parent_report_id`: ID du rapport maître (nullable)
- `timestamps`: created_at, updated_at

**Table `agents`**
- `id`: Identifiant unique
- `name`: Nom de l'agent
- `system_prompt`: Prompt système pour l'IA
- `is_default`: Agent par défaut (boolean)
- `timestamps`: created_at, updated_at

## Configuration Avancée

### Queue Worker

Pour le traitement asynchrone des agents IA, lancez un worker:
```bash
php artisan queue:work --queue=ai-processing
```

En production, utilisez Supervisor pour maintenir le worker actif.

### Cache

Le statut des jobs IA est stocké dans le cache. Pour nettoyer:
```bash
php artisan cache:clear
```

## Développement

### Mode développement
```bash
npm run dev
```

Ceci lance Vite en mode watch avec hot-reload.

### Tests
```bash
php artisan test
```

## Déploiement

1. **Préparer l'environnement de production**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

2. **Configurer la queue en production**
Utilisez Laravel Forge, Vapor, ou configurez Supervisor manuellement.

3. **Variables d'environnement critiques**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `OPENAI_API_KEY=votre-clé-production`
- `QUEUE_CONNECTION=database`

## Roadmap Post-MVP

### P1 (Priorité Haute)
- [ ] Export PDF/HTML des rapports
- [ ] Authentification complète (Laravel Breeze)
- [ ] Historique des versions de rapports
- [ ] Dashboard analytics

### P2 (Priorité Moyenne)
- [ ] Recherche sémantique IA
- [ ] Templates de rapports prédéfinis
- [ ] Collaboration multi-utilisateurs
- [ ] Notifications email

## Métriques de Succès (du PRD)

- **Taux de fusion**: > 5 fusions/utilisateur/mois
- **Gain de temps**: Génération rapport mensuel < 5 min
- **Satisfaction IA**: Note pertinence > 4/5

## Licence

Ce projet est sous licence MIT.

## Support

Pour toute question ou problème, ouvrez une issue sur le repository.

---

**Développé avec ❤️ utilisant Laravel & Tailwind CSS**
