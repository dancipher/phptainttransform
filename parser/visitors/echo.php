<?php

/*
 * Copyright (c) 2013 Defense I/O LLC
 * All rights reserved.
 *
 * Identify obsolote files
 * Daniel Zulla (zulla@defense.io)
 */

class Echo_Visitor extends PHPParser_NodeVisitorAbstract
{
    private $name = 'echo';

	public function enterNode(PHPParser_Node $node) {
        if ($node instanceof PHPParser_Node_Stmt_Echo) {
            return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_echo'),
                                                    $node->exprs);
        } elseif ($node instanceof PHPParser_Node_Stmt_FuncCall) {
            switch($node->name) {
                case "mysql_query":
                    return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_mysql_query'),
                                                            $node->exprs);
                    break;
                
                case "mysql_select_db":
                    return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_mysql_query'),
                                                            $node->exprs);
                    break;

            }
        }
    }


    public function afterTraverse(array $nodes) {
    }

}

?>
