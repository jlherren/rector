<?php

declare (strict_types=1);
namespace RectorPrefix20210510\Symplify\SetConfigResolver\Tests\Bootstrap;

use RectorPrefix20210510\PHPUnit\Framework\TestCase;
use RectorPrefix20210510\Symplify\SetConfigResolver\Bootstrap\InvalidSetReporter;
use RectorPrefix20210510\Symplify\SetConfigResolver\Exception\SetNotFoundException;
final class InvalidSetReporterTest extends TestCase
{
    /**
     * @var InvalidSetReporter
     */
    private $invalidSetReporter;
    protected function setUp() : void
    {
        $this->invalidSetReporter = new InvalidSetReporter();
    }
    /**
     * @doesNotPerformAssertions
     */
    public function test() : void
    {
        $setNotFoundException = new SetNotFoundException('not found', 'one', ['two', 'three']);
        $this->invalidSetReporter->report($setNotFoundException);
    }
}
