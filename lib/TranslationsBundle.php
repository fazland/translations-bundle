<?php

namespace Fazland\TranslationsBundle;

use Fazland\TranslationsBundle\DependencyInjection\CompilerPass\AddTranslationResourcesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\Kernel;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovannialbero.solinf@gmail.com>
 */
class TranslationsBundle extends Bundle
{
    /**
     * @var Kernel
     */
    private $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AddTranslationResourcesPass($this->kernel->getBundles()));
    }
}
