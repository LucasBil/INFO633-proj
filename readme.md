# Sujet
## Contexte
Chaque année, dans les différentes filières de l'école, les étudiants réalisent des projets en lien avec leur formation. Par exemple, les PEIP ont un TPE en 1e année, puis un projet CPAS en deuxième année. Les élèves ingénieurs IDU ou SNI, conçoivent et développe sur un temps long (plusieurs semestres) un projet conséquent, intégrateur de multiples compétences (scientifiques, techniques comme organisationnelles).

L'école budgète chaque année un financement permettant d’acquérir le matériel nécessaire à la réalisation à la réalisation des projets. Le matériel conséquent (onéreux et nécessitant un suivi) est géré par les services techniques et informatiques de l'école. Par contre le petit matériel ne fait pas l'objet d'un suivi particulier actuellement.

Chaque nouvelle année, il est difficile d'avoir un état des lieux fiables de ce petit matériel, de son état, de son lieu de stockage ou encore de son utilisation.

L'objectif est de bénéficier d'une application web permettant son suivi, de son achat à sa fin de vie, en passant par ses utilisations successives dans différents projets.

Ce besoin implique de suivre aussi dans le temps les projets réalisés. A la fois pour en garder une trace (sujet, année, étudiants impliqués) mais aussi pour stocker les ressources réalisées (rapports, présentation, photos, vidéos, démonstration, etc).

## Besoin
Concevoir et développer une application web permettant le suivi du « petit » matériel en lien avec les projets réalisés par les étudiants. Ce matériel est utilisé dans des projets qui mènent à des réalisations. Certaines de ces réalisations ont vocation à perdurer dans le temps, éventuellement d'évoluer dans le cadre de nouveaux projets tandis que d'autres peuvent être démontées permettant de réutiliser le matériel.

Ces projets dans le domaine du numérique impliquent aussi des ressources de type développement informatique qu'il est intéressant de conserver au-delà de la fin du projet (même si la réalisation physique est démontée). Ce sont autant de ressources qui peuvent servir d'exemple aux projets à venir. L'idée est de mémoriser tout ce qui peut caractériser un projet : des photos/vidéos des réalisations, des fichiers de code, une url d'un dépôt git, la liste des étudiants impliqués, des rapports, etc. Ce sont autant de ressources pour les projets à venir.

Un projet aura toujours un début et une fin (seules l'année et la durée sont in fine signifiantes) . En fin de projet, les ressources produites lui sont associées et stockées. Il est toujours possible de lui affecter des ressources « en l'état » à la fin du projet. Cela permet d'avoir une vitrine des projets réalisés. On essayera dans la mesure du possible en fin de projet d'extraire le maximum de ressources afin de garantir leur disponibilité dans le temps (même si des url , moins pérennes, peuvent être stockées également).

Le matériel connaît lui des évolutions au fil du temps, car il peut être réutilisé dans différents contextes, devenir non fonctionnel, être perdu, etc. Il sera important de conserver ces utilisations successives jusqu'à éventuellement sa mise en rebut pour différentes raisons (dysfonctionnement, fonctionnalité dépassée, etc). On visera également à mémoriser dans l'application des informations importantes qui peuvent être présentes sur les sites des constructeurs, mais qui risquent de ne plus être disponibles dans le temps.

L'application devra évoluer dans le temps et permettre petit à petit de réintroduire des projets passés par exemple au moment où du matériel est récupéré afin d'être réutilisé. Rechercher une conception la plus simple possible. Identifier pour cela les informations importantes pour son utilisation et devant être stockées de façon indépendante et unitaire dans la BD de celles relevant d'une liste. Par exemple, pour les étudiants qui ont travaillé sur un projet, il n'est pas nécessaire d'avoir des champs spécifiques pour chaque étudiant. Une liste d'étudiant stockée sous la forme d'une unique chaîne de caractères est amplement suffisante.

Au travers de cette application et selon la conception choisie, il sera intéressant de travailler certaines fonctionnalités de façon assez génériques, par exemple :
* Développer une API pour le stockage et la distribution d'images à la demande à l'aide de Fetch (avec un attribut de taille par exemple) ;
* Lister le contenu d'un répertoire et permettre le dépôt de nouveaux fichiers ;
* Développer des zones de saisies avec mise en forme (Markdown par exemple) et gérer le stockage en dehors de la BD si le volume est important ;
* Etc

# Structure
```
├───api
│   ├───controllers
│   ├───models
│   │   └───enum
│   ├───services
│   ├───uploads
│   └───utils
├───db
│   ├───info633
│   ├───mysql
│   ├───performance_schema
│   └───sys
├───docker
│   └───php
└───webapp
```
# Techno & Commande
```
- Docker
- PHP / JS / HMTL / CSS
```
```
> cd ./docker
> docker compose up
```

# Contributeur
<div style="display:flex; flex-direction:column; gap:5px;">
    <div style="display:flex; gap:5px;">
        <img src="https://avatars.githubusercontent.com/u/189119597?s=64&v=4" alt="Corentin-ccl" width="24" height="24">
        <a href="https://github.com/Corentin-ccl">Corentin-ccl</a>
    </div>
    <div style="display:flex; gap:5px;">
        <img src="https://avatars.githubusercontent.com/u/92669821?s=64&v=4" alt="Corentin-ccl" width="24" height="24">
        <a href="https://github.com/Corentin-ccl">Tadf0in</a>
    </div>
    <div style="display:flex; gap:5px;">
        <img src="https://avatars.githubusercontent.com/u/189831908?s=64&v=4" alt="Corentin-ccl" width="24" height="24">
        <a href="https://github.com/Corentin-ccl">therealmaxence</a>
    </div>
    <div style="display:flex; gap:5px;">
        <img src="https://avatars.githubusercontent.com/u/97104433?s=60&v=4" alt="Corentin-ccl" width="24" height="24">
        <a href="https://github.com/LucasBil">LucasBil</a>
    </div>
</div>