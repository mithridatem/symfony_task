# symfony_task
## Description du projet :

Ce projet est un exercice de cours, pour la création et l'utilisation d'une API avec le framework symfony.

## Notice récupération de la correction API :
### Pré-requis :

-php 8.1++,

-Symfony-CLI,

-mysql ou mariaDB,

-terminal (bash, powershell etc..),

-git,

-postman (tester les url de l'API).

### Ci-dessous les étapes pour récupérer la correction du projet api sur votre machine.

1️⃣ se déplacer dans le répertoire qui contient tous vos projet symfony.

2️⃣ ouvrir git bash dans le répertoire (clic droit -> git bash here).

3️⃣ créer un nouveau projet symfony avec la commande suivante dans un terminal :
```bash
symfony console new nom_projet --webapp
```

4️⃣ se déplacer dans le nouveau projet avec la commande suivante dans le terminal :
```bash
cd nom_projet
```
5️⃣ ajouter les modules avec les commandes suivantes dans un terminal:
```bash
composer require --dev orm-fixtures
composer require fakerphp/faker
```
6️⃣ supprimer le dossier ***.git*** dans le dossier.

7️⃣ tout supprimer dans le dossier sauf les dossiers : ***var, vendor*** et les fichiers : ***.env*** et ***.gitignore***

8️⃣ initialiser le projet avec git et le lier avec le repository github :
```bash
git init
git remote add origin https://github.com/mithridatem/symfony_task.git
```
9️⃣ pull le contenu de la correction dans un terminal avec la commande :
```bash
git pull origin master
```
🔟 éditer le fichier ***.env*** (configuration BDD) comme ci-dessous :
```txt
DATABASE_URL="mysql://loginbdd:mdpbdd@127.0.0.1:3306/nombdd?serverVersion=8&charset=utf8mb4"
```
🔟-1️⃣ créer la base de données avec la commande suivante dans un terminal :
```bash
symfony console doctrine:database:create
```
⚠️ le serveur ***mysql*** doit être lancé.

🔟-2️⃣ migrer la structure sur le serveur mysql avec la commande suivante dans un terminal :
```bash
symfony console doctrine:migrations:migrate
```
🔟-3️⃣ ajouter un jeu de données factice avec la commande suivante dans un terminal (pour tester l'API) :
```bash
symfony console doctrine:fixtures:load
```
🔟-4️⃣ démarrer le serveur de test Symfony avec la commande suivante dans un terminal (pour tester l'API) :
```bash
symfony server:start -d
```
