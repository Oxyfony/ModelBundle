Installation
============

Step 1: Télécharger O2ModelBundle via composer
----------------------------------------------

Ajouter O2ModelBundle dans votre composer.json:

.. code-block:: javascript

   {
       "require": {
           "oxyfony/model-bundle": "dev-master"
       }
   }

Maintenant lancer la commande composer pour télécharger le bundle :

.. code-block:: bash

   php composer.phar update oxyfony/user-bundle

Composer installera le bundle dans votre dosser `vendor/oxyfony` .

Step 2: Activer le bundle
-------------------------

Activer le bundle dans le Kernel:

.. code-block:: php

   <?php
   // app/AppKernel.php
   
   public function registerBundles()
   {
       $bundles = array(
           // ...
           new O2\Bundle\ModelBundle\O2ModelBundle(),
       );
   }
