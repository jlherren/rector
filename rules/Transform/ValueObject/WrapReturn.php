<?php

declare (strict_types=1);
namespace Rector\Transform\ValueObject;

use PHPStan\Type\ObjectType;
final class WrapReturn
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $method;
    /**
     * @var bool
     */
    private $isArrayWrap = \false;
    public function __construct(string $type, string $method, bool $isArrayWrap)
    {
        $this->type = $type;
        $this->method = $method;
        $this->isArrayWrap = $isArrayWrap;
    }
    public function getObjectType() : ObjectType
    {
        return new ObjectType($this->type);
    }
    public function getMethod() : string
    {
        return $this->method;
    }
    public function isArrayWrap() : bool
    {
        return $this->isArrayWrap;
    }
}
