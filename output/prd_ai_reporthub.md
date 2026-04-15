# AI ReportHub - Product Requirements Document

## 1. Contexte et Objectifs du Projet

### 1.1 Contexte
Dans un environnement professionnel ou personnel, la rédaction de rapports périodiques (journaliers, hebdomadaires, mensuels) est souvent perçue comme une tâche répétitive et fragmentée. Le passage d'un rapport de bas niveau (ex: quotidien) à un rapport de haut niveau (ex: mensuel) nécessite un effort de consolidation manuel important.

### 1.2 Objectifs
- **Productivité** : Réduire le temps passé à consolider les informations.
- **Clarté** : Utiliser l'IA pour extraire l'essentiel de volumes de textes importants.
- **Flexibilité** : Offrir un environnement Markdown simple mais puissant pour tout type de rapport.

### 1.3 Métriques de Succès
| Métrique | Définition | Cible |
|--------|------------|--------|
| Taux de fusion | Nombre de rapports fusionnés par utilisateur | > 5 / mois |
| Gain de temps | Temps estimé pour générer un rapport mensuel | < 5 min |
| Satisfaction IA | Note de pertinence des résumés IA | > 4/5 |

## 2. Scénarios d'Utilisation Clés

### Scénario 1 : La Routine Hebdomadaire
- **Utilisateur** : Consultant indépendant.
- **Trigger** : Vendredi soir, fin de semaine.
- **Objectif** : Envoyer un récapitulatif au client.
- **Flux** : L'utilisateur fusionne ses 5 rapports journaliers, demande à l'agent "Synthèse Client" de formater le résultat, et exporte en PDF.

### Scénario 2 : Le Bilan Semestriel
- **Utilisateur** : Manager de projet.
- **Trigger** : Fin de semestre.
- **Objectif** : Analyser les tendances de performance.
- **Flux** : L'utilisateur sélectionne les 6 derniers rapports mensuels, utilise l'agent "Analyse de Tendances" pour identifier les points de blocage récurrents.

## 3. Flux d'Interaction Utilisateur

### 3.1 Flowchart
Voir le diagramme Mermaid dans les notes de conception précédente.

### 3.2 Points d'Expérience Clés
- **Onboarding** : Interface "zéro distraction" (Zen mode) pour l'écriture.
- **Aha Moment** : Instantanéité du jumelage de rapports avec aperçu du texte concaténé.
- **Rétention** : Système d'agents spécialisés que l'utilisateur peut configurer pour ses propres besoins.

## 4. Modules Fonctionnels (MVP)

### 4.1 Fonctionnalités Coeur (P0)
| Fonctionnalité | Description | Priorité |
|---------|-------------|----------|
| Éditeur Markdown | Éditeur de texte riche avec support complet de la syntaxe MD. | P0 |
| Gestion de Rapports | Création, édition, suppression et catégorisation par fréquence. | P0 |
| Fusion (Jumelage) | Sélection multiple et concaténation intelligente des textes. | P0 |
| Agents IA Spécialisés | Possibilité d'invoquer des agents (Synthèse, Analyse) sur le texte. | P0 |

### 4.2 Fonctionnalités Étendues (P1/P2)
| Fonctionnalité | Description | Priorité |
|---------|-------------|----------|
| Export PDF/HTML | Conversion professionnelle des rapports consolidés. | P1 |
| Historique des versions | Suivi des modifications sur les rapports maîtres. | P2 |
| Recherche Globale | Recherche plein texte dans tous les rapports (IA semantic search). | P2 |

## 5. Description de la Forme du Produit

### 5.1 Plateforme
Application Web Progressive (PWA) pour un accès facile sur Desktop et Mobile.

### 5.2 Principes de Design
- **Minimalisme** : Focus sur le contenu texte.
- **Premium** : Utilisation d'ombres douces, de polices typographiques modernes et d'un arrière-plan diffus (Glassmorphism).
- **Réactivité** : Transitions fluides entre les états de liste et d'édition.

## Annexes

### A. Glossaire
- **Rapport Maître (Master)** : Un rapport issu de la fusion de plusieurs rapports sources.
- **Agent IA** : Un prompt système spécialisé agissant sur le contenu du rapport.

### B. Historique des Versions
- v1.0 : Initialisation du PRD (MVP).
