<?php

namespace Fazland\TranslationsBundle\Tests\Fixtures\Translations\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends Controller
{
    /**
     * @Route(path="/test-base-translation")
     *
     * @return Response
     */
    public function firstAction()
    {
        return $this->render('TestBundle:Page:homepage.html.twig');
    }

    /**
     * @Route(path="/test-translation-from-twig-variable")
     *
     * @return Response
     */
    public function secondAction()
    {
        return $this->render('TestBundle:Page:pass_variable.html.twig');
    }

    /**
     * @Route(path="/test-translation-from-twig-expression")
     *
     * @return Response
     */
    public function thirdAction()
    {
        return $this->render('TestBundle:Page:translate_expression.html.twig');
    }
}
