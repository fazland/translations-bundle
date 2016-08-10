<?php

namespace Fazland\TranslationsBundle;

use Fazland\TranslationsBundle\Utils\ClassUtils;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Translation\Loader\LoaderInterface;
use Symfony\Component\Translation\MessageCatalogue;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovannialbero.solinf@gmail.com>
 */
class Loader implements LoaderInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    private $bundleName;

    /**
     * Create a Loader for a given bundle (translations prefix)
     *
     * @param $bundleName
     */
    public function __construct($bundleName)
    {
        $this->bundleName = strtoupper($bundleName);
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $locale, $domain = 'messages')
    {
        $className = ClassUtils::getClassName(file_get_contents($resource));

        /** @var TranslationsInterface $translation */
        $translation = new $className();
        if ($translation instanceof ContainerAwareInterface) {
            $translation->setContainer($this->container);
        }

        $messageCatalogue = new MessageCatalogue($locale);

        if ($domain !== $translation->getDomain()) {
            return $messageCatalogue;
        }

        $messageCatalogue->addResource(new FileResource($resource));

        $translations = $translation->getTranslations();
        $messages = $this->bundleName ? iterator_to_array($this->prefixTranslationKeys($translations)) : $translations;
        $messageCatalogue->add($messages, $domain);

        return $messageCatalogue;
    }

    private function prefixTranslationKeys($messages)
    {
        foreach ($messages as $key => $value) {
            yield $this->bundleName.'---'.$key => $value;
        }
    }
}
