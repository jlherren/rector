<?php

declare (strict_types=1);
namespace RectorPrefix20210510\Doctrine\Inflector\Rules\Portuguese;

use RectorPrefix20210510\Doctrine\Inflector\GenericLanguageInflectorFactory;
use RectorPrefix20210510\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : Ruleset
    {
        return Rules::getSingularRuleset();
    }
    protected function getPluralRuleset() : Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
