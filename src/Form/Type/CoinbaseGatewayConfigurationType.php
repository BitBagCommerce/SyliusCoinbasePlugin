<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusCoinbasePlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CoinbaseGatewayConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('apiKey', TextType::class, [
                'label' => 'bitbag_sylius_coinbase_plugin.ui.api_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_coinbase_plugin.api_key.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('webhookSecretKey', TextType::class, [
                'label' => 'bitbag_sylius_coinbase_plugin.ui.webhook_secret_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_coinbase_plugin.webhook_secret_key.not_blank',
                        'groups' => 'sylius',
                    ]),
                ],
            ])
            ->add('factoryName', HiddenType::class, [
                'empty_data' => 'coinbase',
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $data = $event->getData();

                $data['payum.http_client'] = '@bitbag_sylius_coinbase_plugin.api_client.coinbase';

                $event->setData($data);
            })
        ;
    }
}
