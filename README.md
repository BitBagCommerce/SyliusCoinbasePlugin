<h1 align="center">
    <a href="https://packagist.org/packages/bitbag/coinbase-plugin" title="License" target="_blank">
        <img src="https://img.shields.io/packagist/l/bitbag/coinbase-plugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/coinbase-plugin" title="Version" target="_blank">
        <img src="https://img.shields.io/packagist/v/bitbag/coinbase-plugin.svg" />
    </a>
    <a href="http://travis-ci.org/BitBagCommerce/SyliusCoinbasePlugin" title="Build status" target="_blank">
            <img src="https://img.shields.io/travis/BitBagCommerce/SyliusCoinbasePlugin/master.svg" />
        </a>
    <a href="https://scrutinizer-ci.com/g/BitBagCommerce/SyliusCoinbasePlugin/" title="Scrutinizer" target="_blank">
        <img src="https://img.shields.io/scrutinizer/g/BitBagCommerce/SyliusCoinbasePlugin.svg" />
    </a>
    <a href="https://packagist.org/packages/bitbag/coinbase-plugin" title="Total Downloads" target="_blank">
        <img src="https://poser.pugx.org/bitbag/coinbase-plugin/downloads" />
    </a>
    <p>
        <img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="85">
    </p>
</h1>

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
    
## Webhook subscriptions

For proper operation of the plugin, it is necessary to add a URL to notifications about the status of the payment. The URL can be set in the [settings](https://commerce.coinbase.com/dashboard/settings) and should be in a similar format as `https://{your_domain}/payment/coinbase/notify`
   
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
