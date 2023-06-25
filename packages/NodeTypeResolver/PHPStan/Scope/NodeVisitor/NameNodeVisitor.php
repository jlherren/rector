<?php

declare (strict_types=1);
namespace Rector\NodeTypeResolver\PHPStan\Scope\NodeVisitor;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeVisitorAbstract;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
final class NameNodeVisitor extends NodeVisitorAbstract implements ScopeResolverNodeVisitorInterface
{
    public function enterNode(Node $node) : ?Node
    {
        if ($node instanceof Namespace_ && $node->name instanceof Name) {
            $node->name->setAttribute(AttributeKey::IS_NAMESPACE_NAME, \true);
            return null;
        }
        if ($node instanceof UseUse && ($node->type === Use_::TYPE_NORMAL || $node->type === Use_::TYPE_UNKNOWN)) {
            $node->name->setAttribute(AttributeKey::IS_USEUSE_NAME, \true);
            return null;
        }
        if (!$node instanceof StaticCall) {
            return null;
        }
        if (!$node->class instanceof Name) {
            return null;
        }
        $node->class->setAttribute(AttributeKey::IS_STATICCALL_CLASS_NAME, \true);
        return null;
    }
}
