---
description: Ce document décrit le processus exact que l'agent IA suit lorsque l'humain lui demande d'effectuer une session de testing sur le projet Laravel.
---

# 🧪 Workflow de Testing — Agent IA Laravel

> Ce document décrit le processus exact que l'agent IA suit lorsque l'humain lui demande d'effectuer une session de testing sur le projet Laravel.

---

## 🎯 Déclenchement

Le workflow démarre **uniquement sur demande explicite** de l'humain, via des phrases comme :

- *"Lance les tests"*
- *"Teste ce code"*
- *"Vérifie que tout fonctionne"*
- *"Écris les tests pour ce module"*
- *"Débogue cette erreur"*
- *"Revois ce code"*

---

## 📋 Vue d'ensemble du workflow

```
DEMANDE HUMAINE
      │
      ▼
┌─────────────────┐
│  1. ANALYSE     │  ← Comprendre le périmètre demandé
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  2. GÉNÉRATION  │  ← Écrire / compléter le code si besoin
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  3. ÉCRITURE    │  ← Créer les tests (Unit + Feature)
│   DES TESTS     │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  4. EXÉCUTION   │  ← Lancer les tests et lire les résultats
└────────┬────────┘
         │
      ┌──┴──┐
      │     │
   ✅ OK  ❌ FAIL
      │     │
      │     ▼
      │  ┌─────────────────┐
      │  │  5. DÉBOGAGE    │  ← Analyser, corriger, relancer
      │  └────────┬────────┘
      │           │
      └─────┬─────┘
            │
            ▼
┌─────────────────┐
│  6. REVUE       │  ← Audit qualité du code et des tests
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  7. RAPPORT     │  ← Résumé clair pour l'humain
└─────────────────┘
```

---

## 🔍 Étape 1 — Analyse du périmètre

**Objectif :** Comprendre exactement ce qui doit être testé avant de toucher au code.

L'agent doit :

1. Identifier le(s) fichier(s) concerné(s) — Model, Controller, Service, Migration, etc.
2. Lister les responsabilités du code (ce qu'il est censé faire).
3. Repérer les dépendances externes (BDD, API, services tiers).
4. Définir la stratégie de test : **Unit** (logique isolée) ou **Feature** (comportement HTTP/BDD).
5. Signaler à l'humain si le périmètre est flou avant de continuer.

> ⚠️ **Règle :** L'agent ne commence jamais à écrire des tests sans avoir compris ce qu'il teste.

---

## 🏗️ Étape 2 — Génération de code (si applicable)

**Objectif :** S'assurer que le code à tester existe et est testable.

L'agent doit :

1. Vérifier que le code source existe (Model, Controller, Migration, etc.).
2. Générer le code manquant si l'humain l'a demandé, en suivant les conventions Laravel.
3. S'assurer que le code respecte le principe de **responsabilité unique** — un code trop couplé sera refactorisé avant d'être testé.
4. Confirmer à l'humain ce qui a été généré avant de passer aux tests.

**Conventions à respecter :**

- Models dans `app/Models/`
- Controllers dans `app/Http/Controllers/`
- Services dans `app/Services/`
- Form Requests dans `app/Http/Requests/`

---

## ✍️ Étape 3 — Écriture des tests

**Objectif :** Produire des tests exhaustifs, lisibles et maintenables.

### 3a. Tests Unitaires (`tests/Unit/`)

Couvrir la **logique métier isolée** :

- Méthodes de Models (accessors, mutators, scopes, relations)
- Classes de Services
- Helpers et utilitaires
- Validation de règles métier

```php
// Exemple de structure attendue
class UserTest extends TestCase
{
    /** @test */
    public function it_returns_full_name(): void
    {
        $user = new User(['first_name' => 'John', 'last_name' => 'Doe']);
        $this->assertEquals('John Doe', $user->full_name);
    }
}
```

### 3b. Tests de Fonctionnalité (`tests/Feature/`)

Couvrir les **comportements HTTP et BDD** :

- Endpoints API (statuts HTTP, structure JSON)
- Authentification et autorisations
- Flux CRUD complets avec base SQLite in-memory
- Validation des Form Requests
- Events et Jobs dispatchés

```php
// Exemple de structure attendue
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->putJson("/api/users/{$user->id}", [
                'first_name' => 'Jane',
            ]);

        $response->assertOk()
                 ->assertJsonPath('data.first_name', 'Jane');

        $this->assertDatabaseHas('users', ['first_name' => 'Jane']);
    }
}
```

### Règles d'écriture obligatoires

- Chaque test a **un seul objectif** — pas de tests qui vérifient plusieurs choses à la fois.
- Les noms de méthodes décrivent le **comportement attendu** en anglais (`it_does_something`).
- Utiliser `RefreshDatabase` pour les Feature tests avec BDD.
- Utiliser les **Factories** Laravel — jamais de données en dur codées manuellement.
- Toujours tester le **cas heureux** ET les **cas d'erreur** (validation, 404, 403, etc.).

---

## ▶️ Étape 4 — Exécution des tests

**Objectif :** Lancer les tests et interpréter les résultats.

L'agent exécute dans cet ordre :

```bash
# 1. Tests unitaires uniquement
php artisan test --testsuite=Unit --colors=always

# 2. Tests de fonctionnalité uniquement
php artisan test --testsuite=Feature --colors=always

# 3. Suite complète avec coverage (si demandé)
php artisan test --coverage --min=80
```

**Interprétation des résultats :**

| Symbole | Signification |
|---------|--------------|
| `.`     | Test passé ✅ |
| `F`     | Assertion échouée ❌ |
| `E`     | Erreur PHP (exception) 💥 |
| `W`     | Warning ⚠️ |
| `S`     | Test ignoré (skipped) ⏭️ |

---

## 🐛 Étape 5 — Débogage et correction

**Objectif :** Corriger chaque test en échec de manière méthodique.

Pour chaque test en échec, l'agent suit cette séquence :

1. **Lire le message d'erreur complet** — ne jamais deviner sans lire le stack trace.
2. **Identifier la cause racine** — bug dans le code source ou dans le test lui-même ?
3. **Corriger au bon endroit** :
   - Si le code source est faux → corriger le code source.
   - Si le test est mal écrit → corriger le test.
   - Ne jamais modifier un test pour le faire passer artificiellement.
4. **Relancer uniquement le test corrigé** pour valider :
   ```bash
   php artisan test --filter=NomDuTest
   ```
5. **Relancer la suite complète** une fois tous les correctifs appliqués.

> ⚠️ **Règle absolue :** L'agent ne passe jamais à l'étape suivante tant qu'il reste un test en échec.

---

## 🔎 Étape 6 — Revue de code

**Objectif :** Auditer la qualité du code et des tests produits.

L'agent vérifie les points suivants :

### Qualité du code source
- [ ] Pas de logique métier dans les Controllers (déléguer aux Services).
- [ ] Les queries Eloquent utilisent des scopes et évitent le N+1.
- [ ] Les validations sont dans des Form Requests dédiés.
- [ ] Les méthodes font moins de 20 lignes.
- [ ] Pas de `dd()`, `dump()`, `var_dump()` oubliés.

### Qualité des tests
- [ ] Chaque feature publique a au moins un test.
- [ ] Les cas d'erreur sont couverts (422, 403, 404).
- [ ] Aucun test ne dépend d'un autre test.
- [ ] Les Factories couvrent tous les états nécessaires.
- [ ] Le coverage atteint le seuil minimum de **80%**.

### Signalement
Si un problème est détecté, l'agent le signale clairement à l'humain avec :
- La localisation exacte du problème (fichier + ligne).
- La raison pour laquelle c'est problématique.
- Une suggestion de correction concrète.

---

## 📄 Étape 7 — Rapport final

**Objectif :** Fournir un résumé clair et actionnable à l'humain.

L'agent produit systématiquement ce rapport :

```
## ✅ Résumé de la session de testing

### Tests exécutés
- Unit Tests    : X passés / Y total
- Feature Tests : X passés / Y total
- Code Coverage : XX%

### Fichiers créés / modifiés
- tests/Unit/...
- tests/Feature/...
- app/...

### Problèmes corrigés
- [BUG] Description du bug → correction appliquée
- [TEST] Description du test corrigé

### Points d'attention (à valider par l'humain)
- ...

### Prochaines étapes suggérées
- ...
```

---

## 🚫 Ce que l'agent ne fait jamais

- Ne modifie pas un test pour le faire passer sans corriger la vraie cause.
- Ne saute pas une étape même si l'humain est pressé.
- Ne génère pas de tests vides ou avec des assertions triviales (`assertTrue(true)`).
- Ne supprime pas un test en échec pour "nettoyer" les résultats.
- N'ignore pas un warning sans l'avoir signalé à l'humain.

---

## 📌 Référence rapide des commandes

```bash
# Tous les tests
php artisan test

# Un testsuite spécifique
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Un test spécifique
php artisan test --filter=NomDuTest

# Avec code coverage
php artisan test --coverage --min=80

# Mode verbose
php artisan test --verbose

# Stopper au premier échec
php artisan test --stop-on-failure
```