<?php

namespace Fazland\TranslationsBundle\Utils;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovannialbero.solinf@gmail.com>
 */
class ClassUtils
{
    /**
     * Returns the FQN of the first class in a PHP file
     *
     * @param string $file_contents
     *
     * @return null|string
     */
    public static function getClassName($file_contents)
    {
        $tokens = array_values(array_filter(token_get_all($file_contents), function ($token) {
            return $token[0] !== T_WHITESPACE;
        }));
        $count = count($tokens);

        $namespace = '';
        for ($i = 0; $i < $count; ++$i) {
            $token = $tokens[$i];
            if (! is_array($token)) {
                continue;
            }

            if (T_NAMESPACE === $token[0]) {
                $token = $tokens[++$i];

                while ($i < $count && is_array($token) && in_array($token[0], [T_NS_SEPARATOR, T_STRING])) {
                    $namespace .= $token[1];
                    $token = $tokens[++$i];
                }
            }

            if (T_CLASS === $token[0] && is_array($token = $tokens[++$i]) && T_STRING === $token[0]) {
                return $namespace.'\\'.$token[1];
            }
        }

        return null;
    }
}
