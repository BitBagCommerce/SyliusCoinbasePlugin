## Overview

This plugin allows you to integrate Coinbase payment with Sylius platform app.

## Installation

1. Require plugin with composer:

    ```bash
    composer require bitbag/coinbase-plugin
    ```

2. Import routing on top of your `config/routes.yaml` file:

    ```yaml
    bitbag_sylius_coinbase_plugin:
        resource: "@BitBagSyliusCoinbasePlugin/Resources/config/routing.yml"
    ```

3. Add plugin class to your `config/bundles.php` file:

    ```php
    $bundles = [
        BitBag\SyliusCoinbasePlugin\BitBagSyliusCoinbasePlugin::class => ['all' => true],
    ];
    ```

4. Clear cache:

    ```bash
    bin/console cache:clear
    ```
   
## Customization

### Available services you can [decorate](https://symfony.com/doc/current/service_container/service_decoration.html) and forms you can [extend](http://symfony.com/doc/current/form/create_form_type_extension.html)

Run the below command to see what Symfony services are shared with this plugin:
 
    ```bash
    $ bin/console debug:container bitbag_sylius_coinbase_plugin
    ```

## Testing

    ```bash
    $ composer install
    $ cd tests/Application
    $ yarn install
    $ yarn run gulp
    $ bin/console assets:install web -e test
    $ bin/console doctrine:database:create -e test
    $ bin/console doctrine:schema:create -e test
    $ bin/console server:run 127.0.0.1:8080 -d web -e test
    $ open http://localhost:8080
    $ bin/behat
    $ bin/phpspec run
    ```

## Contribution

Learn more about our contribution workflow on http://docs.sylius.org/en/latest/contributing/.
