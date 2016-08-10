<?php
namespace Fazland\TranslationsBundle;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Intl\Intl;

/**
 * @author Massimiliano Braglia <massimiliano.braglia@fazland.com>
 */
abstract class AbstractTranslation implements TranslationsInterface
{
    /**
     * @inheritdoc
     */
    public function getTranslations()
    {
        $reflClass = new \ReflectionClass($this);

        $base = self::getBase() . '\\' . self::getLocale();
        $shortNsName = ltrim(substr($reflClass->getNamespaceName(), strlen($base)), '\\');

        $ns = str_replace('\\', '_', strtoupper($shortNsName));
        $className = strtoupper(Container::underscore($reflClass->getShortName()));

        $baseKey = ltrim($ns.'-'.$className.'--', '-');

        $add_prefix_func = function() use ($baseKey) { foreach ($this->getMessages() as $key => $value) { yield $baseKey.$key => $value; } };
        return iterator_to_array($add_prefix_func());
    }

    /**
     * @inheritDoc
     */
    public static function getDomain()
    {
        return "messages";
    }

    public static function getLocale()
    {
        $reflClass = new \ReflectionClass(static::class);
        $base = self::getBase();

        $translationNamespace = ltrim(substr($reflClass->getNamespaceName(), strlen($base)), '\\');
        $parts = explode('\\', $translationNamespace);

        $locale = $parts[0];
        $names = Intl::getLocaleBundle()->getLocaleNames();

        if (! isset($names[$locale])) {
            throw new \LogicException(sprintf("Locale '%s' does not exists", $locale));
        }

        return $locale;
    }

    /**
     * @return array
     */
    abstract protected function getMessages();

    private static function getBase()
    {
        $reflClass = new \ReflectionClass(static::class);
        return preg_replace("#([\w\\\\]+Bundle\\\\Translations\\\\).+#", '$1', $reflClass->getNamespaceName());
    }
}
