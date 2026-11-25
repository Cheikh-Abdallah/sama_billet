# Sama Billet

Application complète de billetterie en PHP/MySQL.

## Fonctionnalités

- Inscription et connexion avec mots de passe sécurisés
- Catalogue d’événements et recherche
- Détail des événements et billetterie
- Réservation et paiement de démonstration
- Options de paiement prévues pour Wave, Orange Money, Free Money et carte bancaire
- Tableau de bord client avec billets
- Génération de billets avec référence unique
- Interface administrateur pour créer les événements
- Application web progressive (PWA) installable sur Android et iOS, avec fenêtre d’installation
- Cache hors ligne des ressources statiques et page de secours (les pages PHP privées ne sont pas mises en cache)
- API PHP JSON pour le panier et les événements

## Paiements mobiles

Le formulaire propose Wave, Orange Money et Free Money. Le paiement du projet reste actuellement **simulé** : aucune transaction réelle n’est débitée et les commandes de démonstration sont marquées comme payées. Pour encaisser réellement, il faut choisir un prestataire/agrégateur disponible au Sénégal, ajouter ses identifiants secrets dans la configuration serveur, créer la transaction côté serveur et ne confirmer la commande qu’après validation sécurisée de sa notification (webhook). Ne déployez pas le flux simulé comme un système d’encaissement réel.

## Installation locale

1. Importer `database/schema.sql` dans MySQL.
2. Copier `.env.example` vers `.env` ou configurer les variables dans `config/database.php`.
3. Démarrer le serveur PHP avec le dossier `public/` comme racine :

```bash
php -S localhost:8000 -t public
```

4. Ouvrir `http://localhost:8000`.

## Installer l’application

La PWA et son service worker doivent être servis depuis la racine du site en **HTTPS** (ou `localhost` en développement). Sur Android, le navigateur compatible propose l’installation et la fenêtre Sama Billet affiche le bouton natif lorsqu’il est disponible. Sur iPhone/iPad, ouvrez le menu **Partager**, puis choisissez **Sur l’écran d’accueil** ; iOS ne permet pas de déclencher directement l’invite d’installation native depuis une page web.

## Comptes de démonstration

Après importation du schéma :

- Admin : `admin@sama-billet.sn` / `Admin123!`
- Client : `client@sama-billet.sn` / `Client123!`

## Structure

- `public/` : pages web et ressources publiques
- `public/assets/` : styles, scripts et icônes
- `public/sw.js` : service worker PWA
- `public/manifest.webmanifest` : manifeste installable
- `config/` : configuration MySQL
- `includes/` : fonctions communes
- `database/` : schéma SQL
- `api/` : endpoints JSON
