<?php

/*
 * Copyright (c) 2013 Defense I/O LLC
 * All rights reserved.
 *
 * Identify obsolote files
 * Daniel Zulla (zulla@defense.io)
 */

class Vab_Vab_Visitor extends PHPParser_NodeVisitorAbstract
{
    private $name = 'vab_vab';

	public function enterNode(PHPParser_Node $node) {
        if ($node instanceof PHPParser_Node_Expr_Concat) {
            return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_concat'),
                                                    [$node->left, $node->right]);
        } elseif ($node instanceof PHPParser_Node_Expr_AssignConcat) {
            return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('next_concat'),
                                                    [$node->var, $node->expr]);
        }
    }

    public function afterTraverse(array $nodes) {
    }

}

?>
