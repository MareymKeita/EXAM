<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->getEnvironment()] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        // Importer les routes depuis le dossier config/routes
        $routes->import($this->getProjectDir().'/config/routes/*.yaml');
    }

    protected function configureContainer(LoaderInterface $loader): void
    {
        // Charger la configuration des services pour l'environnement actuel
        $loader->load($this->getProjectDir().'/config/services_'.$this->getEnvironment().'.yaml');
        
        // Charger la configuration des packages
        $loader->load($this->getProjectDir().'/config/packages/*.{yaml,yml}');
    }
}
