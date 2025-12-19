# 🎮 TP-1 — Sélection d’un jeu aléatoire (Random Game)

![Youpi](https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExaTI1Zm1kZW1yaDVkbjN6NGV3eXQ2NTZ3a3pvMmJibzVod3dvcHVrZSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/i456dLugvvZPunX8ZH/giphy.gif)

## 🎯 Objectif
Depuis la **page Home**, permettre à l’utilisateur de cliquer sur un bouton **🎲 Random game** afin de :
1. Sélectionner **un jeu aléatoire depuis la base de données**
2. **Rediriger automatiquement** vers la page **détail** de ce jeu

Ce TP permet de travailler :
- le routing simple (`page=random`)
- l’accès aux données via **PDO / Repository**
- la **redirection HTTP**
- une première règle métier

---

## 🧭 Contexte
L’application dispose déjà de :
- une page **Home**
- une page **Games**
- une page **Games/id**

Les jeux sont stockés en base de données et accessibles via un Repository.

👉 Tu dois ajouter une fonctionnalité **transversale**, déclenchée depuis la Home.

---

## 🧩 Travail demandé

### 1️⃣ Ajouter une nouvelle route `random`
- La route doit être accessible via l’URL :  
  `/?page=random`
- Elle ne doit **pas afficher de vue**
- Son rôle est uniquement de :
    - sélectionner un jeu aléatoire
    - rediriger vers sa page détail

💡 **Hint** :  
Cette route est parfaite pour une **redirection HTTP côté serveur**.

---

### 2️⃣ Sélectionner un jeu aléatoire en base de données
- La récupération du jeu doit se faire :
    - via le **Repository**
    - avec une requête SQL
- Un seul jeu doit être retourné

💡 **Hint** :  
Le Controller ne doit pas contenir de SQL.

---

### 3️⃣ Rediriger vers la page détail
- Une fois le jeu trouvé :
    - récupérer son identifiant
    - rediriger vers la route de détail existante

Exemple de destination :
`/?page=detail&id=4`

💡 **Hint important** :  
La redirection doit être faite avec la fonction PHP **`header()`**, suivie de l’arrêt du script.

---

### 4️⃣ Ajouter le bouton sur la Home
- Ajouter un bouton ou lien **🎲 Random game**
- Visible sur la page Home
- Il déclenche la route `random`

💡 **Hint** :  
Aucun formulaire n’est nécessaire, un simple lien suffit.

---

## ✨ Bonus — Random sans répétition (optionnel)
Objectif : éviter d’afficher deux fois de suite **le même jeu**.

### Règle métier
- Lors de plusieurs clics consécutifs :
    - le jeu tiré ne doit pas être identique au précédent
- Le dernier jeu affiché est mémorisé en **session**

💡 **Hints** :
- stocker l’`id` du dernier jeu en session
- comparer avant de rediriger
- prévoir un nombre maximum de tentatives

---

## ✅ Résultat attendu
- Le bouton **Random game** est fonctionnel
- Chaque clic redirige vers un jeu valide
- L’utilisateur arrive toujours sur une **page détail**
- Le code respecte une séparation claire :
    - Controller → orchestration
    - Repository → accès DB

---

## 🧠 Compétences travaillées
- Routing simple en PHP
- Redirection HTTP avec `header()`
- Accès DB via Repository
- Gestion de session
- Implémentation d’une règle métier

---

## 🚀 Aller plus loin
- Ajouter une animation de “roulette” avant la redirection
- Afficher un message “🎉 Surprise game!”
- Ajouter un compteur de jeux découverts

Bon TP 👌