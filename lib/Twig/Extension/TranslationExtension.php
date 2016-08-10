<?php

namespace Fazland\TranslationsBundle\Twig\Extension;
use Fazland\TranslationsBundle\Twig\Node\TranslationFunctionNode;
use Fazland\TranslationsBundle\Twig\NodeVisitor\TranslationDefaultDomainNodeVisitor;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Alessandro Chitolina <alekitto@gmail.com>
 * @author Giovanni Albero <giovanni.albero@fazland.com>
 */
class TranslationExtension extends \Twig_Extension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * TranslationExtension constructor
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('_', null, ['node_class' => TranslationFunctionNode::class])
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getNodeVisitors()
    {
        return [
            new TranslationDefaultDomainNodeVisitor()
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'fazland_translation';
    }
}
