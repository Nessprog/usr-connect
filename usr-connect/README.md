# ⚽ USR Connect - Gestion du Bénévolat

USR Connect est une application de gestion de planning pour les bénévoles d'un événement sportif caritatif prenant place à Reventin Vaugris.
Elle permet aux administrateurs de créer des missions par pôles et aux bénévoles de s'inscrire en un clic.

## 🚀 Fonctionnalités

- **Tableau de bord** : Vue d'ensemble des missions.
- **Système de Pôles** : Missions triées par catégories (Buvette, Caisse, Sportif, Restauration).
- **Gestion des inscriptions** : Inscription et désistement en temps réel.
- **Interface Admin** : Création, modification et suppression des créneaux.
- **Design Responsive** : Optimisé pour mobile et ordinateur (Tailwind CSS).

## 🛠️ Stack Technique

- **Framework PHP** : Laravel 11
- **Frontend** : Vue.js 3 (Composition API)
- **Liaison** : Inertia.js
- **Style** : Tailwind CSS
- **Base de données** : MySQL

## 💻 Installation en local

1.  **Cloner le projet** :
    git clone [https://github.com/Nessprog/usr-connect.git](https://github.com/Nessprog/usr-connect.git)
    cd usr-connect
2.  **Installer les dépendances PHP**
    Utilisez Composer pour installer toutes les bibliothèques Laravel :
    composer install

3.  **Installer les dépendances JavaScript**
    Installez les modules nécessaires pour Vue.js et Tailwind :
    npm install

4.  **Configurer l'environnement**
    Copiez le fichier d'exemple et générez la clé de sécurité :
    cp .env.example .env
    php artisan key:generate

    Note : Ouvrez le fichier .env et modifiez les lignes DB_DATABASE, DB_USERNAME et DB_PASSWORD selon votre configuration locale.

5.  **Lancer les migrations**
    Créez la structure de la base de données :
    php artisan migrate

6.  **Lancer le projet**
    Vous devez lancer deux terminaux en parallèle :
    Terminal 1 (Serveur PHP) :
    php artisan serve

    Terminal 2 (Compilation Assets) :
            npm run dev

L'application sera alors accessible sur http://127.0.0.1:8000.
