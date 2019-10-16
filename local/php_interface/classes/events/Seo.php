<?php

namespace ig\Events;

use ig\Seo\Meta;

class Seo
{
    public function setFinalMeta(): void
    {
        Meta::getInstance()->setFinalMeta();
    }

    public function setErrorPage():void
    {

    }
}