Fondamentaux de Model Bundle
============================

L'objectif d'Oxygen Model Bundle est de proposer des pratiques de codage pour manipuler le modelèle de données
de son bundle extensible par d'autres bundles.

C'est aussi un ensemble d'outils et de pratiques de codage pour normaliser cette manipulation au sein d'une équipe
de développement.

Le manager d'un modèle
----------------------

Chaque modèle de données doit être manipuler au travers de son propre Manager. Une classe abstraite de base
est fournie : Manager/ModelManager

Ce manager manipule un et un seul modèle de données. Par exemple dans le cas d'entités, il va manipuler une entité
en particulier.

.. note::

    Aujourd'hui le bundle n'offre des outils que pour des entités utilisant Doctrine ORM.
    C'est la classe Entity/Manager/EntityManager, implémentant ModelManager qui fournit cette capacité.
    
A chaque modèle correspond un service pour le manipuler.

Un alias pour chaque modèle
---------------------------

A chaque modèle (par exemple chaque entité), un alias est associé. L'alias est de la forme suivante :
[bundle_alias].[class_name_alias]

Par exemple pour l'entité O2UserBundle:User, nous aurons l'alias : o2_user.user

Pourquoi ceci rend votre modèle extensible ?
--------------------------------------------

L'ensemble de votre code utilisera le manager ou l'alias pour manipuler le modèle de données.

Etant donné que chaque modèle de données a son propre service, grâce aux mécanismes :

* Dependency Injection de Symfony2
* Surcharge d'entité de Doctrine

vous êtes en mesure d'étendre une entité sans avoir à surcharger son code de manipulation (formulaire, controller, manager, ...)