<?php

namespace RectorPrefix20210714;

if (\class_exists('Tx_Extbase_Persistence_Query')) {
    return;
}
class Tx_Extbase_Persistence_Query
{
}
\class_alias('Tx_Extbase_Persistence_Query', 'Tx_Extbase_Persistence_Query', \false);
