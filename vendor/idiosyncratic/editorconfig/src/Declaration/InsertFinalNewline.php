<?php

declare (strict_types=1);
namespace RectorPrefix20220204\Idiosyncratic\EditorConfig\Declaration;

final class InsertFinalNewline extends \RectorPrefix20220204\Idiosyncratic\EditorConfig\Declaration\BooleanDeclaration
{
    public function getName() : string
    {
        return 'insert_final_newline';
    }
}
