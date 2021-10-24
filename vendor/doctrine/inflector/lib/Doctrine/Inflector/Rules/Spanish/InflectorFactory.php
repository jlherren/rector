<?php

declare (strict_types=1);
namespace RectorPrefix20211024\Doctrine\Inflector\Rules\Spanish;

use RectorPrefix20211024\Doctrine\Inflector\GenericLanguageInflectorFactory;
use RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends \RectorPrefix20211024\Doctrine\Inflector\GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : \RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset
    {
        return \RectorPrefix20211024\Doctrine\Inflector\Rules\Spanish\Rules::getSingularRuleset();
    }
    protected function getPluralRuleset() : \RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset
    {
        return \RectorPrefix20211024\Doctrine\Inflector\Rules\Spanish\Rules::getPluralRuleset();
    }
}
