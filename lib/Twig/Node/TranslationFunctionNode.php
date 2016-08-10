<?php

namespace Fazland\TranslationsBundle\Twig\Node;

use Fazland\TranslationsBundle\Twig\NodeVisitor\TranslationDefaultDomainNodeVisitor;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovanni.albero@fazland.com>
 */
class TranslationFunctionNode extends \Twig_Node_Expression
{
    public function __construct($name, \Twig_Node $arguments, $lineno)
    {
        if (count($arguments) < 1) {
            throw new \Twig_Error_Syntax("Argument 1 required for function '_'");
        }

        $nodes = array_values($arguments->nodes);

        $keyAttribute = array_shift($nodes);
        $vars = array_shift($nodes);
        $domain = array_shift($nodes);

        $attributes = [
            'vars' => $vars,
            'key' => $keyAttribute,
            'domain' => $domain,
            'name' => $name
        ];

        parent::__construct([], $attributes, $lineno);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        $parts = explode(':', $compiler->getFilename());

        if (count($parts) !== 3) {
            throw new \LogicException("This function can be used with short name templates ONLY. Use trans instead");
        }

        $filename = preg_replace('/.html.twig$/i', '', $parts[2]);
        $prefix = strtoupper(
            $parts[0].'---'.
            trim(str_replace('/', '_', $parts[1]), '_') .
            '-' .
            trim(str_replace('/', '_', $filename), '_')
        );

        $compiler
            ->raw('$this->env->getExtension(\'translator\')->getTranslator()->trans(\''.$prefix.'--\'.strtoupper((string)')
            ->subcompile($this->getAttribute('key'))
            ->raw(')')
            ->raw(', ');

        $vars = $this->getAttribute('vars');
        if (empty($vars)) {
            $compiler->raw('array()');
        } else {
            $compiler->subcompile($vars);
        }

        $domain = $this->getAttribute('domain');
        if (! empty($domain)) {
            $compiler
                ->raw(', ')
                ->subcompile($domain);
        }

        $compiler->raw(')');
    }
}
