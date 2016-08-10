<?php

namespace Fazland\TranslationsBundle\Twig\NodeVisitor;

use Fazland\TranslationsBundle\Twig\Node\TranslationFunctionNode;
use Symfony\Bridge\Twig\Node\TransDefaultDomainNode;
use Symfony\Bridge\Twig\NodeVisitor\Scope;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovanni.albero@fazland.com>
 */
class TranslationDefaultDomainNodeVisitor extends \Twig_BaseNodeVisitor
{
    /**
     * @var Scope
     */
    private $scope;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->scope = new Scope();
    }

    /**
     * {@inheritdoc}
     */
    protected function doEnterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Block || $node instanceof \Twig_Node_Module) {
            $this->scope = $this->scope->enter();
        }

        if ($node instanceof TransDefaultDomainNode) {
            if ($node->getNode('expr') instanceof \Twig_Node_Expression_Constant) {
                $this->scope->set('domain', $node->getNode('expr'));

                return $node;
            } else {
                $var = $env->getParser()->getVarName();
                $name = new \Twig_Node_Expression_AssignName($var, $node->getLine());
                $this->scope->set('domain', new \Twig_Node_Expression_Name($var, $node->getLine()));

                return new \Twig_Node_Set(false, new \Twig_Node([$name]), new \Twig_Node([$node->getNode('expr')]), $node->getLine());
            }
        }

        if (!$this->scope->has('domain')) {
            return $node;
        }

        if ($node instanceof TranslationFunctionNode && null === ($domain = $node->getAttribute('domain'))) {
            $node->setAttribute('domain', $this->scope->get('domain'));
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    protected function doLeaveNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof \Twig_Node_Block || $node instanceof \Twig_Node_Module) {
            $this->scope = $this->scope->leave();
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return -20;
    }
}
