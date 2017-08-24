*******Documentation********

Run below command to install from composer

composer require kapilpatel20/bvi-cms dev-master

Add bundle in AppKernel.php in registerBundles function

new BviCmsBundle\BviCmsBundle(),

if KnpPaginatorBundle not added then please do add in AppKernel.php file 

new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),

Export route file in your app/config/routing.yml as below

bvi_cms:
    resource: "@BviCmsBundle/Resources/config/routing.yml"
    prefix:   /cms

Install assets using below command

php app/console assets:install

Update your db schema using below command

php app/console doctrine:schema:update --force

