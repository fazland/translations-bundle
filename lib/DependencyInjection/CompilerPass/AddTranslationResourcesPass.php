<?php

namespace DependencyInjection\CompilerPass;

use Fazland\TranslationsBundle\TranslationsInterface;
use Fazland\TranslationsBundle\Utils\ClassUtils;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovannialbero.solinf@gmail.com>
 */
class AddTranslationResourcesPass implements CompilerPassInterface
{
    /**
     * @var Bundle[]
     */
    private $bundles;

    /**
     * AddTranslationResourcesPass constructor.
     *
     * @param Bundle[] $bundles
     */
    public function __construct(array $bundles)
    {
        $this->bundles = $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $translatorDefinition = $container->getDefinition('translator.default');

        foreach ($this->bundles as $bundle) {
            $alias = $this->createLoaderForBundle($container, $bundle);

            $finder = Finder::create()
                ->files()
                ->in($bundle->getPath().'/Translations')
                ->name('*.php');

            $this->loadFiles($finder, $translatorDefinition, $alias);
        }
    }

    private function createLoaderForBundle(ContainerBuilder $container, Bundle $bundle)
    {
        $bundleName = $bundle->getName();
        $alias = 'fazland.translation.'.$bundleName;

        $definition = clone $container->getDefinition('fazland.translations.loader.prototype');

        $definition
            ->setAbstract(false)
            ->replaceArgument(0, $bundleName)
            ->addTag('translation.loader', ['alias' => $alias])
            ;

        return $alias;
    }

    /**
     * @param Finder $finder
     * @param Definition $translatorDefinition
     * @param string $alias
     */
    private function loadFiles(Finder $finder, Definition $translatorDefinition, $alias)
    {
        /** @var SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            $class = ClassUtils::getClassName($fileInfo->getContents());
            if (null === $class) {
                continue;
            }

            $reflection = new \ReflectionClass($class);
            if ($reflection->implementsInterface(TranslationsInterface::class) && !$reflection->isAbstract()) {
                $localeMethod = $reflection->getMethod('getLocale');
                $domainMethod = $reflection->getMethod('getDomain');

                $translatorDefinition->addMethodCall('addResource', [
                    $alias,
                    $fileInfo->getPathname(),
                    $localeMethod->invoke(null),
                    $domainMethod->invoke(null),
                ]);
            }
        }
    }
}
