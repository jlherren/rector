<?php

declare (strict_types=1);
namespace RectorPrefix20211024\Doctrine\Inflector\Rules\NorwegianBokmal;

use RectorPrefix20211024\Doctrine\Inflector\Rules\Patterns;
use RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset;
use RectorPrefix20211024\Doctrine\Inflector\Rules\Substitutions;
use RectorPrefix20211024\Doctrine\Inflector\Rules\Transformations;
final class Rules
{
    public static function getSingularRuleset() : \RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset
    {
        return new \RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset(new \RectorPrefix20211024\Doctrine\Inflector\Rules\Transformations(...\RectorPrefix20211024\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getSingular()), new \RectorPrefix20211024\Doctrine\Inflector\Rules\Patterns(...\RectorPrefix20211024\Doctrine\Inflector\Rules\NorwegianBokmal\Uninflected::getSingular()), (new \RectorPrefix20211024\Doctrine\Inflector\Rules\Substitutions(...\RectorPrefix20211024\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset() : \RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset
    {
        return new \RectorPrefix20211024\Doctrine\Inflector\Rules\Ruleset(new \RectorPrefix20211024\Doctrine\Inflector\Rules\Transformations(...\RectorPrefix20211024\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getPlural()), new \RectorPrefix20211024\Doctrine\Inflector\Rules\Patterns(...\RectorPrefix20211024\Doctrine\Inflector\Rules\NorwegianBokmal\Uninflected::getPlural()), new \RectorPrefix20211024\Doctrine\Inflector\Rules\Substitutions(...\RectorPrefix20211024\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getIrregular()));
    }
}
