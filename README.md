# symfony_task
**Notice récupération de la correction API** :
Ci-dessous les étapes pour récupérer la correction du projet api sur votre machine.
-1 se déplacer dans le répertoire qui contient tous vos projet symfony.
-2 ouvrir git bash dans le répertoire (clic droit -> git bash here).
-3 créer un nouveau projet symfony avec la commande suivante dans un terminal :
```bash
symfony console new nom_projet --webapp
```
-4 se déplacer dans le nouveau projet avec la commande suivante dans le terminal :
```bash
cd nom_projet
```
-5 ajouter les modules avec les commandes suivantes dans un terminal:
```bash
composer require --dev orm-fixtures
composer require fakerphp/faker
```
-6 supprimer le dossier ***.git*** dans le dossier
-7 tout supprimer dans le dossier sauf les dossiers : ***var, vendor*** et les tous fichiers sauf : ***.env*** et ***.gitignore***
-8 initialiser le projet avec git et le lier avec le repository github :
```bash
git init
git remote add origin https://github.com/mithridatem/symfony_task.git
```
-9 pull le contenu de la correction dans un terminal avec la commande :
```bash
git pull origin master
```
-10 éditer le fichier ***.env*** (configuration BDD) comme ci-dessous :
```txt
DATABASE_URL="mysql://loginbdd:mdpbdd@127.0.0.1:3306/nombdd?serverVersion=8&charset=utf8mb4"
```
-11 créer la base de données avec la commande suivante dans un terminal :
```bash
symfony console doctrine:database:create
```
**NB** : le serveur mysql doit être lancé.
-12 migrer la structure sur le serveur mysql avec la commande suivante dans un terminal :
```bash
symfony console doctrine:migrations:migrate
```
