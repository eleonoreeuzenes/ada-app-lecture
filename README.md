# 📚 Booklad

**Booklad** est une application de suivi de lecture gamifiée.
Chaque livre terminé fait progresser l’utilisateur·rice avec des **points**, **niveaux** et **badges** déblocables. L’interface est intuitive, responsive, et motivante.

---

## 🚀 Fonctionnalités principales

* 🔐 Authentification sécurisée (email + mot de passe)
* 📘 Suivi de lecture (livres à lire, en cours, terminés)
* 🏅 Système de badges et progression par niveau
* 📊 Profil avec tableau de bord personnalisé avec barre de progression
* 🔎 Recherche et ajout de livres à sa bibliothèque
* ⚙️ Paramètres utilisateur (modification et suppression de compte)

---

## 🧱 Stack utilisée

* **Backend** : Laravel 11 + Sanctum + PostgreSQL
* **Frontend** : Vue 3 + Pinia + TypeScript + Tailwind CSS
* **Auth** : Laravel Sanctum (via token Bearer)
* **DB viewer** : Adminer
* **Conteneurisation** : Docker + Docker Compose

---

## ⚙️ Installation
```bash
cp .env.example .env
cd backend cp .env.example .env
cd ../frontend cp .env.example .env
```


## 1. 🐋 Lancer l’environnement Docker en local

```bash
docker compose up -d
```

👉 Cela va créer :

* `backend` (Laravel)
* `frontend` (Vue)
* `db` (PostgreSQL)
* `adminer` (interface DB à [http://localhost:8080](http://localhost:8080))

---

### 2. Initialiser Laravel

- Dans un nouveau terminal :
  
`docker compose exec -it backend /bin/bash `

- Installer les dépendances Laravel du composer.json dans le container 

`composer install`

- Générer la clé à l'intérieur du container

`php artisan key:generate`

- API_KEY

  - Entrer dans le container app
  - Jouer la commande ci dessous pour entrer en ligne de commande avec tinker
  - `php artisan tinker`
  - Générer une clé avec la commande ci dessous
  - `\Str::random(64)`
  - Copier la clé générée dans le .env pour la variable API_KEY
  - A chaque appel de l'API, il faudra ajouter cette clé dans les headers pour la varialbe X-API-KEY 

- SeederMigrations et seeders
```bash
php artisan migrate:fresh --seed
```

Cela initialise la base et crée un compte test `booklad@example.com` (mdp : `Password:123`).


---

### 3. 🖥️ Accéder à l’application

| Service     | URL                                                    |
| ----------- | ------------------------------------------------------ |
| Frontend    | [http://localhost:5173](http://localhost:5173)         |
| Backend API | [http://localhost:8000/api](http://localhost:8000/api) |
| Adminer     | [http://localhost:8080](http://localhost:8080)         |

note: en local privilégier un npm run dev hors docker pour vue

---

## 📁 Structure du projet

```
booklad/
├── backend/        # Laravel 11 + API REST
├── frontend/       # Vue 3 + Pinia + Tailwind
├── docker-compose.yml
```
---

## 🧠 Auteur

Projet développé dans le cadre du test d'admission ADA Tech School.
Développé avec ❤️ par Eléonore Euzenes.

