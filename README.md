# Sama Billet

Application complète de billetterie en PHP/MySQL.

## Fonctionnalités

- Inscription et connexion avec mots de passe sécurisés
- Catalogue d’événements et recherche
- Détail des événements et billetterie
- Réservation et paiement simulé
- Tableau de bord client avec billets
- Génération de billets avec référence unique
- Interface administrateur pour créer les événements
- API PHP JSON pour le panier et les événements

## Installation locale

1. Importer `database/schema.sql` dans MySQL.
2. Copier `.env.example` vers `.env` ou configurer les variables dans `config/database.php`.
3. Démarrer un serveur PHP :

```bash
php -S localhost:8000 -t public
```

4. Ouvrir `http://localhost:8000`.

## Comptes de démonstration

Après importation du schéma :

- Admin : `admin@sama-billet.sn` / `Admin123!`
- Client : `client@sama-billet.sn` / `Client123!`

## Structure

- `public/` : pages web et ressources publiques
- `config/` : configuration MySQL
- `includes/` : fonctions communes
- `database/` : schéma SQL
- `api/` : endpoints JSON
