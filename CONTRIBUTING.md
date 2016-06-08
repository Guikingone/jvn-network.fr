# Règles de suivi du projet :
- Vous devez faire partie de l'Equipe directrice du projet ou avoir obtenu un droit de modification des fichiers par un de ses membres.
- JVN-Network est un site libre de partenariat et voué à la communauté, son fonctionnement lui est propre et expliqué plus bas dans ce fichiers, merci d'en prendre connaissance.
- Tout commit/modification doit être ramenée sur une branche adjacente à master, le nom est libre, les merge seront effectués après acceptation des modifications, avec la terminaison suivante :

# Pour les membres de l'Equipe directrice :

Dans le cadre d'un commit principal sur master qui se déroule dans le cadre d'un déploiement continu :

- git commit -m "[!C] || ------------ "

Dans le cadre d'un commit en attente de validation sur master :

- git commit -m "[!-C] || ------------ "

Dans le cadre d'un commit rapide sur master qui ne nécessite pas de vérification :

- git commit -m "[!C !F] || ------------ "

Dans le cadre d'un commit sur une branche tierce :

- git commit -m "[!TB] || ----------- "

Dans le cadre d'un commit en attente de validation sur une branche tierce :

- git commit -m "[!-TB] || ----------- "

# Pour les contributeurs associé(e)s :

Dans le cadre d'un commit sur master après validation par l'équipe :

- git commit -m "[!!CT][!-C] || -------------- "

Dans le cadre d'un commit en attente de validation sur master :

- git commit -m "[!!CT][!-C] || -------------- "

Dans le cadre d'un commit sur une branche tierce après validation par l'équipe :

- git commit -m "[!!CT][!TB] || --------------- "

Dans le cadre d'un commit sur une branche tierce en attente de validation :

- git commit -m "[!!CT][!-TB] || ---------------- "

# Fonctionnement global de la validation des commits :

- Durant le développement en continu, les commits se font sur une branche tierce qui est jointe à master, les commits sont donc envoyés en ligne sur la branche tierce, une fois que la branche tierce ne doit plus recevoir de commits, la branche est close et les commits sont analysés en vue d'être ajoutés à master.
  Une fois les commits envoyés sur master, la branche est mergés au sein de master (avec vérification qu'aucune manipulation ne peut engendrer de retour en arrière suite à un bug.

- Le développement sur master est officiellement clos quand le branches à merger sont closes, la phase de peaufinage commence une fois les commits analysés, le merge arrivant une fois l'analyse terminée.
  
- Si durant cette phase, un commit pose soucis, la branche entière est rouverte et le commit en question est repoussé sur la branche, les commits analysés avant ce dernier sont conservés sur master, ceux arrivant à la suite sont suspendus.

- Une fois que la branche a été mergé avec master, la branche est supprimée et une suivante fait son apparition, cela évite les doublons.

- Attention ! Une branche ne doit jamais jamais contenir de commits en double, même pour un correctif, si correctif il y a, il doit faire l'objet d'un commit spécifique expliqué plus bas.

# Fonctionnement des correctifs rapides (rustine en interne) :

- Un correctif rapide peut s'appliquer en 2 cas de figure :

- Le commit précédant à engendrer un soucis de compatibilité, dans ce cas, le correctif s'applique suite à ce commit, son nommage est expliqué plus bas.

- Le correctif correspondant à un soucis passager, dans ce cas, un commit rapide est autorisé et son nommage diffère du précédent.

# Nommage des correctifs :

- Un correctif se nomme via son importance, son contenu et l'auteur du correctif :

- git commit -m "[!C, !O][!!CC || ---------]" 

    => !C, !O = Importance du correctif (!C signifiant que le correctif est majeur, !O signifiant que le correctif est optionnel et ne demande pas un travail direct).
 
# Fonctionnement du nommage des branches :

- Une branche se dénomme en fonction de son importance :

- "[!X, !C, !O][TB] || ------- [DD]"  

    => !X, !C, !O = Importance de la branche (!X signifiant que la branche est un correctif, !C signifiant que la branche est lié à l'équipe, !O signifiant que la branche est optionnelle et ne demande pas un travail direct).
    => [TB] = Branche tierce, || ------- = Nom de la branche. 
    => [DD] = Date de démarrage de la branche (à noter en jour/mois/année, ex : 11/06/2016). 

# Fonctionnement des pull request :

Chaque pull request doit faire l'objet d'une étude par l'équipe et ne saurait être validé sans l'accord préalable de l'ensemble des contributeurs de l'équipe.

# Règles relatives à l'utilisation des fichiers du projet :

- Tout modification/ajout/suppression/utilisation est libre dans un repository git personnel, procédure :

- Télécharger le dossier relatif à ce projet, créer un repository git, cloner vos fichiers au sein de ce dernier et créer vos modifications au sein de dernier.

- Toute modification/ajout/suppression/utilisation d'un fichier au sein d'une branche doit être soumis(e) à l'Equipe avant qu'il ne devienne effectif(ve), les commits doivent respecter la règle établie plus haut.

# Fonctionnement interne

JVN ayant été crée par la communauté pour la communauté, cette dernière est prioritaire sur tout changements au sein de ce projet et en sera averti par mail/message interne, l'Equipe de développement tient à ce que toute reproduction lui soit notifié afin d'en prendre note et de pouvoir en modifier les contenus sans avertissements.
