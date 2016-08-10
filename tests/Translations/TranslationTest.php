<?php

namespace Fazland\TranslationsBundle\tests\Translations;

use Fazland\TranslationsBundle\Tests\Fixtures\Translations\AppKernel;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

class TranslationTest extends WebTestCase
{
    public function provideRoutesAndExpectedResults()
    {
        return [
            ['/test-base-translation', 'homepage.html'],
            ['/test-translation-from-twig-variable', 'translate_include_variable_result.html'],
            ['/test-translation-from-twig-expression', 'expression_result.html'],
        ];
    }

    /**
     * @dataProvider provideRoutesAndExpectedResults
     */
    public function testTranslationKeysAreGeneratedCorrectly($route, $resultFile)
    {
        $client = static::createClient();
        $client->request('GET', $route);

        $response = $client->getResponse();
        $this->assertEquals(file_get_contents(__DIR__.'/../Fixtures/Translations/expected/'.$resultFile), $response->getContent());
    }

    public function testCatalogueGeneratedCorrectly()
    {
        $client = static::createClient();
        $client->getKernel()->boot();

        $result = [
            'messages' => [
                'TESTBUNDLE---TEST_TRANS--FOO' => 'BAR',
                'TESTBUNDLE---TEST_TRANS--BAZ' => 'FOO',
            ]
        ];

        $this->assertEquals($result, $client->getContainer()->get('translator.default')->getCatalogue('it_IT')->all());
    }

    protected static function createKernel(array $options = [])
    {
        return new AppKernel('test', true);
    }

    public function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove(__DIR__.'/../Fixtures/Translations/cache');
        $fs->remove(__DIR__.'/../Fixtures/Translations/logs');
    }
}
