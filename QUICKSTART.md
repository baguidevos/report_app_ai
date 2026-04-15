# Guide de Démarrage Rapide - AI ReportHub

## Installation en 5 Minutes

### 1. Installer les dépendances
```bash
composer install
npm install
```

### 2. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Initialiser la base de données
```bash
php artisan migrate --seed
```

Cela va créer:
- Tables `reports` et `agents`
- 3 agents IA par défaut (Synthèse Client, Analyse de Tendances, Formatage Professionnel)
- 1 utilisateur test

### 4. Compiler les assets
```bash
npm run build
```

### 5. Lancer l'application
```bash
php artisan serve
```

Ouvrez http://localhost:8000 dans votre navigateur.

## (Optionnel) Configurer OpenAI

Pour utiliser les agents IA, ajoutez dans `.env`:
```env
OPENAI_API_KEY=sk-votre-clé-api
OPENAI_MODEL=gpt-3.5-turbo
```

Obtenez une clé sur: https://platform.openai.com/api-keys

## Premier Usage

### Créer un rapport
1. Cliquez "Nouveau Rapport"
2. Remplissez le formulaire
3. Utilisez l'éditeur Markdown pour rédiger
4. Sauvegardez

### Fusionner des rapports
1. Créez au moins 2 rapports
2. Allez sur "Fusionner"
3. Sélectionnez les rapports
4. Choisissez la fréquence du rapport maître
5. Fusionnez!

### Utiliser un agent IA
1. Ouvrez un rapport existant
2. Section "Agents IA" → choisissez un agent
3. Cliquez "Exécuter l'Agent"
4. Attendez le traitement (quelques secondes)
5. Un nouveau rapport est créé avec le résultat

## Commandes Utiles

**Mode développement (hot-reload):**
```bash
npm run dev
```

**Lancer queue worker (pour IA):**
```bash
php artisan queue:work --queue=ai-processing
```

**Reset complet:**
```bash
php artisan migrate:fresh --seed
```

## Problèmes Courants

### Erreur "Clé API non configurée"
→ Ajoutez `OPENAI_API_KEY` dans `.env`

### Assets ne chargent pas
→ Lancez `npm run build` ou `npm run dev`

### Queue ne fonctionne pas
→ Lancez `php artisan queue:work`

### Base de données vide
→ Lancez `php artisan migrate --seed`

## Support

Consultez le README.md pour plus de détails.
