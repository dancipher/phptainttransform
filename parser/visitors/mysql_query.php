<?php

/*
 * Copyright (c) 2013 Defense I/O LLC
 * All rights reserved.
 *
 * Identify obsolote files
 * Daniel Zulla (zulla@defense.io)
 */

class MySQL_Visitor extends PHPParser_NodeVisitorAbstract
{
    private $name = 'mysql_query';

	public function enterNode(PHPParser_Node $node) {
        if ($node instanceof PHPParser_Node_Expr_FuncCall) {
            if ($node->name == "mysql_query") {
            return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_mysql_query'),
                                                    $node->args);
                
            }
            if ($node->name == "mysql_connect") {
             
                return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_mysql_connect'),
                                                        $node->args);
            }
            if ($node->name == "mysql_select_db") {

                return new PHPParser_Node_Expr_FuncCall(new PHPParser_Node_Name('nex_mysql_select_db'),
                    $node->args);
            }
        }
    }


    public function afterTraverse(array $nodes) {
    }

}

?>
