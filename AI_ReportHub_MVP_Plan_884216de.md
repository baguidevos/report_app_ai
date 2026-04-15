# Plan de Développement - AI ReportHub MVP

## 1. Architecture Technique

### Stack Technologique
- **Backend**: Laravel 12 (déjà configuré)
  - PHP 8.2+
  - SQLite (déjà présent dans `database/database.sqlite`)
  - Eloquent ORM pour la gestion des données
  - Laravel Queue pour les traitements IA asynchrones
  
- **Frontend**: 
  - Vite 7.x + TailwindCSS 4.x (déjà configurés)
  - Blade templates pour le rendu serveur
  - JavaScript vanilla ou Alpine.js pour l'interactivité légère
  - Éditeur Markdown: SimpleMDE ou EasyMDE (bibliothèque JS légère)

- **Intégration IA**:
  - Utiliser Laravel ai sdk laravel/ai
  - Laravel HTTP Client pour les appels API
  - Queue workers pour les traitements longs

### Structure des Composants

```
app/
├── Models/
│   ├── User.php (existant)
│   ├── Report.php (nouveau)
│   └── Agent.php (nouveau)
├── Http/
│   ├── Controllers/
│   │   ├── ReportController.php
│   │   ├── MergeController.php
│   │   └── AgentController.php
│   └── Requests/
│       ├── StoreReportRequest.php
│       └── MergeReportsRequest.php
├── Services/
│   ├── ReportMergeService.php
│   └── AIService.php
├── Jobs/
│   └── ProcessAIAgentJob.php
└── Policies/
    └── ReportPolicy.php

resources/
├── views/
│   ├── reports/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   ├── edit.blade.php
│   │   └── merge.blade.php
│   └── agents/
│       └── show.blade.php
└── js/
    ├── app.js
    └── editor.js (configuration éditeur MD)

database/
└── migrations/
    ├── create_reports_table.php
    └── create_agents_table.php
```

---

## 2. Décomposition des Tâches (Features P0)

### Phase 1: Infrastructure & Modèles de Données (Semaine 1)

#### Tâche 1.1: Configuration Base de Données
- [ ] Créer migration `create_reports_table.php`
  - Champs: id, user_id, title, content (TEXT), frequency (daily/weekly/monthly), parent_report_id (nullable), created_at, updated_at
  - Index sur user_id et frequency
- [ ] Créer migration `create_agents_table.php`
  - Champs: id, name, system_prompt (TEXT), is_default (boolean), created_at, updated_at
- [ ] Exécuter les migrations et vérifier l'intégrité
- **Estimation**: 2 heures

#### Tâche 1.2: Création des Modèles Eloquent
- [ ] `app/Models/Report.php`
  - Relations: belongsTo(User), belongsTo(Report) pour parent, hasMany(Report) pour children
  - Scopes: byFrequency(), recent()
  - Méthodes: isMaster(), getChildren()
- [ ] `app/Models/Agent.php`
  - Méthodes: execute($content), getDefaultAgents() (seeders)
- [ ] Mettre à jour `app/Models/User.php` avec relation hasMany(Report)
- **Estimation**: 3 heures

#### Tâche 1.3: Seeders pour Agents par Défaut
- [ ] Créer `DatabaseSeeder` avec agents prédéfinis:
  - "Synthèse Client": prompt pour résumer rapports journaliers
  - "Analyse de Tendances": prompt pour identifier patterns récurrents
  - "Formatage Professionnel": prompt pour structurer le contenu
- **Estimation**: 2 heures

---

### Phase 2: Gestion des Rapports - CRUD (Semaines 2-3)

#### Tâche 2.1: Routes & Contrôleurs de Base
- [ ] Définir routes RESTful dans `routes/web.php`:
  ```php
  Route::resource('reports', ReportController::class);
  Route::post('reports/{report}/merge', [MergeController::class, 'store']);
  Route::post('agents/{agent}/execute', [AgentController::class, 'execute']);
  ```
- [ ] Créer `ReportController` avec méthodes: index, create, store, edit, update, destroy
- [ ] Créer Form Requests pour validation (`StoreReportRequest`, `UpdateReportRequest`)
- **Estimation**: 4 heures

#### Tâche 2.2: Interface Liste des Rapports
- [ ] Vue `resources/views/reports/index.blade.php`
  - Tableau avec filtres par fréquence (daily/weekly/monthly)
  - Boutons: Nouveau rapport, Fusionner, Supprimer
  - Indicateur visuel pour rapports maîtres
- [ ] Styling TailwindCSS avec design minimaliste/glassmorphism
- [ ] Pagination simple
- **Estimation**: 6 heures

#### Tâche 2.3: Éditeur Markdown
- [ ] Intégrer EasyMDE/SimpleMDE via CDN ou npm
- [ ] Vue `resources/views/reports/create.blade.php` et `edit.blade.php`
  - Champ titre
  - Sélecteur de fréquence (dropdown)
  - Zone d'édition Markdown avec preview en temps réel
  - Boutons: Sauvegarder, Annuler, Mode Zen (plein écran)
- [ ] Fichier `resources/js/editor.js` pour initialisation
- [ ] Support syntaxe Markdown complète (headers, lists, tables, code blocks)
- **Estimation**: 8 heures

#### Tâche 2.4: Logique Backend CRUD
- [ ] Implémenter `ReportController@store` avec validation
- [ ] Implémenter `ReportController@update` avec autorisations
- [ ] Implémenter `ReportController@destroy` avec soft deletes (optionnel)
- [ ] Ajouter middleware d'authentification (Laravel Breeze ou simple auth)
- **Estimation**: 4 heures

---

### Phase 3: Fonctionnalité de Fusion (Semaines 4-5) ⚠️ RISQUE ÉLEVÉ

#### Tâche 3.1: Interface de Sélection Multiple
- [ ] Vue `resources/views/reports/merge.blade.php`
  - Liste checkbox des rapports filtrables par période/fréquence
  - Prévisualisation dynamique du texte concaténé
  - Ordre de fusion draggable (drag & drop simple)
  - Sélecteur de fréquence pour le rapport maître résultant
- [ ] JavaScript pour gestion état sélection et prévisualisation
- **Estimation**: 8 heures

#### Tâche 3.2: Service de Fusion
- [ ] Créer `app/Services/ReportMergeService.php`
  - Méthode `merge(array $reportIds, string $masterFrequency)`
  - Concaténation intelligente: ajout séparateurs, préservation structure Markdown
  - Détection et suppression doublons de headers
  - Génération automatique titre ("Rapport [période] - [date]")
- [ ] Tests unitaires pour scénarios de fusion edge cases
- **Estimation**: 10 heures

#### Tâche 3.3: Contrôleur de Fusion
- [ ] Créer `MergeController@store`
  - Validation: minimum 2 rapports sélectionnés
  - Appel à ReportMergeService
  - Création nouveau rapport maître avec parent_report_id = null
  - Mise à jour children reports avec parent_report_id
  - Redirection vers éditeur du rapport fusionné
- **Estimation**: 4 heures

#### ⚠️ Risques Techniques - Fusion:
1. **Conflits Markdown**: Headers dupliqués, listes imbriquées incorrectes
   - *Mitigation*: Parser Markdown simple pour détecter structures, ajouter sauts de ligne stratégiques
2. **Performance**: Fusion de 30+ rapports volumineux
   - *Mitigation*: Limiter sélection à 50 rapports max, optimiser requêtes DB
3. **Perte de contexte**: Concaténation brute sans logique sémantique
   - *Mitigation MVP*: Accepter limitation, améliorer avec IA en Phase 4

---

### Phase 4: Agents IA Spécialisés (Semaines 5-6) ⚠️ RISQUE MOYEN

#### Tâche 4.1: Configuration API IA
- [ ] Ajouter clés API dans `.env`:
  ```env
  OPENAI_API_KEY=sk-...
  OPENAI_MODEL=gpt-4-turbo
  ```
- [ ] Installer dépendance: `composer require openai-php/client` (ou utiliser HTTP client natif)
- [ ] Créer `app/Services/AIService.php`
  - Méthode `executePrompt(string $prompt, string $content, array $options)`
  - Gestion erreurs API (rate limits, timeouts)
  - Logging des appels pour debugging
- **Estimation**: 4 heures

#### Tâche 4.2: Job Asynchrone pour Traitement IA
- [ ] Créer `app/Jobs/ProcessAIAgentJob.php`
  - Implémente ShouldQueue
  - Reçoit: agent_id, report_id, user_id
  - Appelle AIService avec system_prompt de l'agent + contenu rapport
  - Met à jour rapport avec résultat ou crée nouveau rapport
  - Gestion retry en cas d'échec API
- [ ] Configurer queue worker dans `config/queue.php` (database driver)
- **Estimation**: 6 heures

#### Tâche 4.3: Interface Exécution Agent
- [ ] Vue `resources/views/agents/show.blade.php` ou modal
  - Dropdown sélection agent (liste depuis DB)
  - Preview du rapport cible
  - Bouton "Exécuter l'Agent"
  - Indicateur de progression (spinner + message "Traitement en cours...")
- [ ] JavaScript pour appel AJAX vers `AgentController@execute`
- [ ] Affichage résultat: soit inline, soit nouveau rapport créé
- **Estimation**: 6 heures

#### Tâche 4.4: Contrôleur Agent
- [ ] Créer `AgentController@execute`
  - Validation: agent existe, rapport accessible
  - Dispatch ProcessAIAgentJob
  - Retour immédiat avec job_id pour polling status
  - Endpoint supplémentaire `GET /agents/job/{jobId}/status` pour vérification
- **Estimation**: 4 heures

#### ⚠️ Risques Techniques - IA:
1. **Coûts API**: Appels fréquents GPT-4 peuvent devenir coûteux
   - *Mitigation*: Utiliser gpt-3.5-turbo pour MVP, cache résultats, limiter longueur input
2. **Latence**: Temps de réponse 5-15 secondes
   - *Mitigation*: Queue asynchrone obligatoire, UX avec loading states clairs
3. **Qualité Variables**: Prompts mal formulés = résultats médiocres
   - *Mitigation*: Tester prompts extensively, permettre édition system_prompt par utilisateur (P2)

---

### Phase 5: Intégration & Polish (Semaine 7)

#### Tâche 5.1: Authentification Utilisateur
- [ ] Installer Laravel Breeze ou créer auth simple
  - `composer require laravel/breeze --dev`
  - `php artisan breeze:install blade`
- [ ] Middleware `auth` sur toutes routes reports/agents
- [ ] Page login/register basique
- **Estimation**: 4 heures

#### Tâche 5.2: Design System & UX
- [ ] Créer layout principal `resources/views/layouts/app.blade.php`
  - Navigation header avec logo "AI ReportHub"
  - Flash messages pour feedback utilisateur
  - Footer minimal
- [ ] Composants Tailwind réutilisables:
  - Boutons primary/secondary/danger
  - Cards pour rapports
  - Badges pour fréquences
- [ ] Mode Zen pour éditeur (fullscreen, distractions minimales)
- [ ] Transitions CSS fluides entre pages
- **Estimation**: 8 heures

#### Tâche 5.3: Tests & Validation
- [ ] Tests Feature pour flux critiques:
  - Création rapport → sauvegarde → affichage
  - Fusion 3 rapports → vérification contenu concaténé
  - Exécution agent → vérification résultat IA
- [ ] Tests manuels cross-browser (Chrome, Firefox, Safari)
- [ ] Correction bugs identifiés
- **Estimation**: 6 heures

---

### Phase 6: Déploiement & Documentation (Semaine 8)

#### Tâche 6.1: Préparation Production
- [ ] Optimiser assets: `npm run build`
- [ ] Configurer `.env.production` avec vraies clés API
- [ ] Setup queue worker pour production (Supervisor ou Laravel Forge)
- [ ] Vérifier migrations fonctionnent sur DB vide
- **Estimation**: 4 heures

#### Tâche 6.2: Documentation Minimale
- [ ] README.md avec:
  - Instructions installation locale
  - Configuration variables d'environnement
  - Commandes deployment
- [ ] Commentaires code critiques (ReportMergeService, AIService)
- **Estimation**: 3 heures

---

## 3. Évaluation des Risques

### Risques Techniques Majeurs

| Risque | Probabilité | Impact | Mitigation |
|--------|-------------|--------|------------|
| Complexité fusion Markdown | Haute | Moyen | Parser simple MVP, accepter imperfections, itérer post-MVP |
| Coûts API IA imprévus | Moyenne | Élevé | Budget monitoring, fallback gpt-3.5, caching agressif |
| Performance avec 50+ rapports | Moyenne | Moyen | Pagination stricte, lazy loading, indexes DB optimisés |
| Qualité prompts IA variable | Haute | Moyen | Testing extensif prompts, permettre ajustement utilisateur (P2) |
| Timeline sous-estimée | Moyenne | Élevé | Buffer 20% temps, prioriser features core, reporter P1/P2 |

### Dépendances Critiques
1. **Clés API IA**: Bloquant pour Phase 4 - obtenir dès Semaine 1
2. **Design Assets**: Minimal besoin (Tailwind suffit), mais icônes SVG nécessaires
3. **Testing Environment**: SQLite local OK, prévoir staging si possible

---

## 4. Timeline & Ressources

### Estimation Globale
- **Durée Totale**: 6-8 semaines (30-40 jours ouvrés)
- **Effort Total**: ~120 heures de développement
- **Buffer Risque**: +20% = 144 heures totales

### Répartition par Semaine

| Semaine | Focus | Heures | Livrables |
|---------|-------|--------|-----------|
| 1 | Infrastructure & Modèles | 7h | Schéma DB, Modèles Eloquent, Seeders |
| 2-3 | CRUD Rapports + Éditeur MD | 22h | Interface complète création/édition rapports |
| 4-5 | Fusion Rapports | 26h | Sélection multiple, service fusion, contrôleur |
| 5-6 | Agents IA | 20h | Intégration API, jobs async, interface exécution |
| 7 | Intégration & Polish | 18h | Auth, design system, tests, bug fixes |
| 8 | Déploiement | 7h | Build production, docs, launch |

### Ressources Nécessaires
- **Développeur Full-Stack**: 1 personne (Laravel + Blade + Tailwind + JS)
- **Designer**: Optionnel (Tailwind components suffisent pour MVP)
- **Budget API IA**: ~$50-100/mois (GPT-3.5/4, dépend usage)
- **Hébergement**: Laravel Forge/Vapor ou VPS simple ($10-20/mois)

### Milestones Clés
1. **Fin Semaine 1**: Schéma DB validé, modèles fonctionnels
2. **Fin Semaine 3**: CRUD complet opérationnel, éditeur MD intégré
3. **Fin Semaine 5**: Fusion de rapports fonctionnelle (feature critique)
4. **Fin Semaine 6**: Agents IA opérationnels avec queue workers
5. **Fin Semaine 8**: MVP déployé en production, prêt pour users beta

---

## 5. Recommandations Post-MVP (P1/P2)

### Immédiatement après MVP:
- Export PDF/HTML (P1) - Bibliothèque DomPDF ou Snappy
- Historique versions rapports (P2) - Package laravel-versionable
- Recherche sémantique IA (P2) - Vector embeddings + Pinecone/Meilisearch

### Améliorations UX:
- Templates rapports prédéfinis
- Collaboration multi-utilisateurs
- Notifications email rapports générés
- Dashboard analytics (métriques succès PRD)

---

## Checklist Pré-Démarrage

- [ ] Obtenir clés API OpenAI/Anthropic
- [ ] Confirmer stack technique avec stakeholder
- [ ] Setup repository Git avec branche develop
- [ ] Configurer environment local (PHP 8.2+, Node 18+, Composer)
- [ ] Valider estimations timeline avec équipe
- [ ] Définir critères acceptance pour chaque feature P0

---

**Note**: Ce plan suppose un développeur expérimenté Laravel. Ajuster estimations +30-50% si développeur junior ou unfamiliar avec stack.