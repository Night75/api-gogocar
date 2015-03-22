<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()

    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Gomycar\ApiBundle\GomycarApiBundle(),
            new AppBundle\AppBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');

        $localFile = __DIR__.'/config/local_'.$this->getEnvironment().'.yml';

        if (is_file($localFile)) {
            $loader->load($localFile);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCacheDir()
    {
        $cacheDir = getenv('SYMFONY__API_CACHE_DIR');

        if (false !== $cacheDir) {
            return rtrim($cacheDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->environment;
        }

        return parent::getCacheDir();
    }

    /**
     * {@inheritDoc}
     */
    public function getLogDir()
    {
        $logDir = getenv('SYMFONY__API_LOG_DIR');

        if (false !== $logDir) {
            return rtrim($logDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        }

        return parent::getLogDir();
    }
}
