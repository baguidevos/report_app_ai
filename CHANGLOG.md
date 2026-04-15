# Changelog

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

## [1.1.0] - 2026-04-15

### Fixes
- **Rapports** : Correction du blocage de la soumission du formulaire de création lié à l'attribut `required` sur le textarea (conflit avec EasyMDE).
- **Rapports** : Correction de l'erreur 419 lors de la suppression d'un rapport (balise form mal fermée).
- **Rapports** : Ajout de `event.stopPropagation()` et d'une confirmation de suppression dans la liste des rapports pour éviter les redirections accidentelles.
- **Routage** : Correction de l'erreur 404 sur le lien de fusion des rapports (conflit de priorité entre la ressource et la route personnalisée).
- **Services** : Simplification de la logique de tri dans `ReportMergeService`.

### UI/UX
- Ajout de l'affichage dynamique des erreurs de validation sur la page de création de rapport.
- Amélioration de la structure HTML des formulaires.
