<?php
namespace Fazland\TranslationsBundle\Tests\Fixtures\Translations\TestBundle\Translations\it_IT;

use Fazland\TranslationsBundle\AbstractTranslation;

class TestTrans extends AbstractTranslation
{
    /**
     * @inheritDoc
     */
    protected function getMessages()
    {
        return [
            'FOO' => 'BAR',
            'BAZ' => 'FOO'
        ];
    }
}
