*******Documentation********
@Note: This bundle has dependency on KNP paginator bundle. If KNP Paginator bundle not installed then please do first install KNP Paginator bundle using following document ref url:

URL: https://github.com/KnpLabs/KnpPaginatorBundle

Run below command to install from composer

composer require kapilpatel20/bvi-cms dev-master

Add bundle in AppKernel.php in registerBundles function

new Bvi\CmsBundle\BviCmsBundle(),

Export route file in your app/config/routing.yml as below

bvi_cms:
    resource: "@BviCmsBundle/Resources/config/routing.yml"
    prefix:   /cms

Install assets using below command

php app/console assets:install

Update your db schema using belwo command

php app/console doctrine:schema:update --force

@Note: This bundle has dependency on KNP paginator bundle. If KNP Paginator bundle not installed then please do first install KNP Paginator bundle using following document ref url:

URL: https://github.com/KnpLabs/KnpPaginatorBundle  