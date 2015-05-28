<?php

/*
 * Copyright (c) 2013 Defense I/O LLC
 * All rights reserved.
 *
 * Identify obsolote files
 * Daniel Zulla (zulla@defense.io)
 */

class Include_Visitor extends PHPParser_NodeVisitorAbstract
{
    private $name = 'include';

	public function enterNode(PHPParser_Node $node) {
        if ($node instanceof PHPParser_Node_Expr_Include) {
            return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_include'),
                                                    [$node->expr]);
        }
    }

    public function afterTraverse(array $nodes) {
    }

}

?>
