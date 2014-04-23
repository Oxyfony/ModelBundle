Entités Doctrine ORM
====================

Nous allons vous expliquer comment créer votre premier manager d'une entité de votre bundle.

Créer le service
----------------

Pour partager des bonnes pratiques de codage, nous recommandons de créer un fichier entity_manager.xml dans le dossier
Resources/config/services de votre bundle.

Dans ce fichier déclarer votre service

.. code-block:: xml

   <service id="my.entity.page">
      <tag name="o2_model.manager" class="MyBundle:Page" />
   </service>

Vous tagguez le service avec *o2_model.manager* pour indiquer que ce service manage l'entité MyBundle:Page.
Les autres attributs possibles du tag sont :

* type : par défaut la valeur est *entity*
* alias : facultatif si l'attribut class est de la forme MyBundle:Page. L'alias est un identifiant nommant l'entité

Puis vous chargez ce service :

.. code-block:: php
   
   <?php
   // /bundle/path/DependencyInjection/MyExtension.php
   
   public function load(array $configs, ContainerBuilder $container)
   {
        // ...
        
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services/entity_manager.xml');
   }

Utiliser le manager de son entité
---------------------------------

.. code-block:: php

   <?php
   
   // instance of ContainerAware
   $container = ...
   
   // Search all pages
   $pages = $container->get('my.entity.page')->getRepository()->findAll();
   
   // Create instance
   $new_page = $container->get('my.entity.page')->createInstance();
   
   // Set entity columns
   // ...
   
   // Differents ways to save
   $container->get('my.entity.page')->save($new_page, array('flush' => true'));
   // .. or :
   $container->get('my.entity.page')->persist($new_page);
   $container->get('my.entity.page')->flush($new_page);
   
Les méthodes save, persist et flush de votre manager ne traite que les entités qui le concernent ainsi que celles
définies dans les relations dont il est propriétaire.

Vous pouvez donner un tableau d'entité comme premier argument de ces méthodes.
