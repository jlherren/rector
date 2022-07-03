<?php

declare (strict_types=1);
namespace Rector\Core\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\Goto_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Label;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Throw_;
use PhpParser\Node\Stmt\TryCatch;
final class TerminatedNodeAnalyzer
{
    /**
     * @var array<class-string<Node>>
     */
    private const TERMINATED_NODES = [Return_::class, Throw_::class];
    /**
     * @var array<class-string<Node>>
     */
    private const TERMINABLE_NODES = [Throw_::class, Return_::class, Break_::class, Continue_::class];
    /**
     * @var array<class-string<Node>>
     */
    private const TERMINABLE_NODES_BY_ITS_STMTS = [TryCatch::class, If_::class, Switch_::class];
    /**
     * @var array<class-string<Node>>
     */
    private const ALLOWED_CONTINUE_CURRENT_STMTS = [InlineHTML::class, Nop::class];
    /**
     * @param \PhpParser\Node\Stmt\TryCatch|\PhpParser\Node\Stmt\If_|\PhpParser\Node\Stmt\Switch_|\PhpParser\Node $node
     */
    public function isAlwaysTerminated($node, Node $currentStmt) : bool
    {
        if (\in_array(\get_class($currentStmt), self::ALLOWED_CONTINUE_CURRENT_STMTS, \true)) {
            return \false;
        }
        if (!\in_array(\get_class($node), self::TERMINABLE_NODES_BY_ITS_STMTS, \true)) {
            return $this->isTerminatedNode($node, $currentStmt);
        }
        if ($node instanceof TryCatch) {
            return $this->isTerminatedInLastStmtsTryCatch($node, $currentStmt);
        }
        if ($node instanceof If_) {
            return $this->isTerminatedInLastStmtsIf($node, $currentStmt);
        }
        /** @var Switch_ $node */
        return $this->isTerminatedInLastStmtsSwitch($node, $currentStmt);
    }
    private function isTerminatedNode(Node $previousNode, Node $currentStmt) : bool
    {
        if (\in_array(\get_class($previousNode), self::TERMINABLE_NODES, \true)) {
            return \true;
        }
        if ($previousNode instanceof Expression && $previousNode->expr instanceof Exit_) {
            return \true;
        }
        if ($previousNode instanceof Goto_) {
            return !$currentStmt instanceof Label;
        }
        return \false;
    }
    private function isTerminatedInLastStmtsSwitch(Switch_ $switch, Node $node) : bool
    {
        if ($switch->cases === []) {
            return \false;
        }
        $hasDefault = \false;
        foreach ($switch->cases as $key => $case) {
            if (!$case->cond instanceof Expr) {
                $hasDefault = \true;
            }
            if ($case->stmts === [] && isset($switch->cases[$key + 1])) {
                continue;
            }
            if (!$this->isTerminatedInLastStmts($case->stmts, $node)) {
                return \false;
            }
        }
        return $hasDefault;
    }
    private function isTerminatedInLastStmtsTryCatch(TryCatch $tryCatch, Node $node) : bool
    {
        if ($tryCatch->finally instanceof Finally_ && $this->isTerminatedInLastStmts($tryCatch->finally->stmts, $node)) {
            return \true;
        }
        foreach ($tryCatch->catches as $catch) {
            if (!$this->isTerminatedInLastStmts($catch->stmts, $node)) {
                return \false;
            }
        }
        return $this->isTerminatedInLastStmts($tryCatch->stmts, $node);
    }
    private function isTerminatedInLastStmtsIf(If_ $if, Node $node) : bool
    {
        // Without ElseIf_[] and Else_, after If_ is possibly executable
        if ($if->elseifs === [] && !$if->else instanceof Else_) {
            return \false;
        }
        foreach ($if->elseifs as $elseIf) {
            if (!$this->isTerminatedInLastStmts($elseIf->stmts, $node)) {
                return \false;
            }
        }
        if (!$this->isTerminatedInLastStmts($if->stmts, $node)) {
            return \false;
        }
        if (!$if->else instanceof Else_) {
            return \false;
        }
        return $this->isTerminatedInLastStmts($if->else->stmts, $node);
    }
    /**
     * @param Stmt[] $stmts
     */
    private function isTerminatedInLastStmts(array $stmts, Node $node) : bool
    {
        if ($stmts === []) {
            return \false;
        }
        \end($stmts);
        $lastKey = \key($stmts);
        $lastNode = $stmts[$lastKey];
        if (isset($stmts[$lastKey - 1]) && $this->isTerminatedNode($stmts[$lastKey - 1], $node)) {
            return \false;
        }
        if ($lastNode instanceof Expression) {
            return $lastNode->expr instanceof Exit_;
        }
        return \in_array(\get_class($lastNode), self::TERMINATED_NODES, \true);
    }
}