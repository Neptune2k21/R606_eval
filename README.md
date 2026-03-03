# R606_eval

## Description
Evaluation pour le cours de Maintenance Applicative. Ce projet utilise Docker pour créer un environnement de développement avec MySQL et phpMyAdmin.

## Prérequis
- Docker
- Docker Compose
## Installation
1. Clonez ce dépôt :
```bash
git clone "https://github.com/Neptune2k21/R606_eval.git"
```
2. Accédez au répertoire du projet :
```bash
cd R606_eval
```
3. Créez un fichier `.env` à la racine du projet avec les variables d'environnement nécessaires (copier coller le .env.example à la racine du projet et modifier le si besoin)
4. Démarrez les conteneurs Docker :
```bash
docker-compose up -d
```
5. Accédez à phpMyAdmin via votre navigateur à l'adresse `http://localhost:8080` et connectez-vous avec les informations d'identification définies dans le fichier `.env`.
## Utilisation
- Vous pouvez accéder à l'application via `http://localhost:8000` après avoir démarré les conteneurs Docker.
- Utilisez phpMyAdmin pour gérer votre base de données MySQL.
## Nettoyage
Pour arrêter et supprimer les conteneurs Docker, utilisez la commande suivante :
```bash
docker-compose down
```
## Problèmes ou issue possible lors de l'installation
- Assurez-vous que les ports 3307 et 8080 ne sont pas utilisés par d'autres applications sur votre machine.
- Vérifiez que Docker et Docker Compose sont correctement installés et configurés sur votre système.
- Si vous rencontrez des problèmes de connexion à la base de données, assurez-vous que les variables d'environnement dans le fichier `.env` sont correctement définies et correspondent à celles utilisées dans le fichier `docker-compose.yml`.