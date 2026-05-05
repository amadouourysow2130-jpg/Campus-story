# Campus-story
projet web campus-story

##  Auteurs
- Sow  Amadou Oury 
- Tran Lysa 

---
## Lien du projet GitHub

https://github.com/amadouourysow2130-jpg/Campus-story

## 🎥 Démonstration vidéo

https://www.youtube.com/@AmadouOurySow-u2b 

##  Description du projet

Campus Stories est une plateforme web permettant aux étudiants de partager leurs expériences universitaires (cours, examens, logement, etc.).

Les utilisateurs peuvent publier des stories, consulter celles des autres et interagir via un système de réactions.

---

##  Fonctionnalités principales

###  Gestion des stories
- Création de stories
- Affichage sous forme de grille
- Détail d’une story

###  Interface utilisateur
- Design responsive
- Couleurs par catégorie
- Navigation intuitive

### Gestion utilisateur
- Inscription / Connexion
- Profil utilisateur
- Affichage des stories personnelles

### Réactions
- Plusieurs types de réactions (utile, inspirant, etc.)
- Limitation à une réaction par utilisateur
- Mise à jour dynamique avec AJAX

### 🔍 Filtrage
- Filtrage des stories par catégorie

---

## ⚡ Interaction temps réel

Le projet utilise AJAX pour mettre à jour les réactions sans recharger la page, permettant une interaction fluide entre plusieurs utilisateurs.

---

## Contraintes respectées

Le site empêche plusieurs actions non autorisées :

-  Réagir sans être connecté  
-  Réagir plusieurs fois à la même réaction  
-  Accéder à une story inexistante  
-  Injection de code (sécurité avec `htmlspecialchars`)  

---

## Structure du projet
Campus-story/
│
├── index.php
├── story.php
├── profile.php
├── create_story.php
├── login.php
├── register.php
│
├── api/
│ ├── get_story.php
│ ├── get_stories.php
│
├── data/
│ ├── stories.json
│ ├── users.json
│
├── includes/
│ ├── json_utils.php
│ ├── session.php
│
├── css/
│ └── style.css
│
├── js/
│ ├── ajax.js
│ ├── main.js
│
└── images/


---

##  Choix techniques

- **PHP** → logique backend  
- **JSON** → stockage des données  
- **HTML/CSS** → interface utilisateur  
- **JavaScript (AJAX)** → interaction dynamique  

---

##  Lancer le projet

Dans le terminal :

```bash
php -S localhost:8000

Démonstration

Une vidéo de démonstration est fournie montrant :

les fonctionnalités principales
l’interaction en temps réel
les contraintes respectées
