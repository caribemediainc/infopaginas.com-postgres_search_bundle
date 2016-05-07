<?php

namespace Alsatian\PostgresSearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class AlsatianPostgresSearchExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = [
            'dbal' => [
                'types' => [
                    'tsvector' => 'Alsatian\PostgresSearchBundle\DBAL\TsvectorType'
                ],
                'mapping_types' => [
                    'tsvector' => 'tsvector'
                ],
            ],
            'orm' => [
                'dql' => [
                    'string_functions' => [
                        'tsquery' => 'Alsatian\PostgresSearchBundle\DQL\TsqueryFunction',
                        'plainto_tsquery' => 'Alsatian\PostgresSearchBundle\DQL\PlainToTsqueryFunction',
                        'tsrank' => 'Alsatian\PostgresSearchBundle\DQL\TsrankFunction',
                        'tsheadline' => 'Alsatian\PostgresSearchBundle\DQL\TsheadlineFunction'
                    ]
                ]
            ]
        ];

        $container->prependExtensionConfig('doctrine', $config);
    }
}
