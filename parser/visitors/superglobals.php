<?php

/*
 * Copyright (c) 2013 Defense I/O LLC
 * All rights reserved.
 *
 * Identify obsolote files
 * Daniel Zulla (zulla@defense.io)
 */

class Superglobals_Visitor extends PHPParser_NodeVisitorAbstract
{
	public function enterNode(PHPParser_Node $node) {
        if ($node instanceof PHPParser_Node_Expr_ArrayDimFetch) {
            $name = strtolower($node->var->name);
            $name = str_replace('_', '', $name);
            if (!array_key_exists($name, $GLOBALS['super'])) {
                $GLOBALS['super'][$name] = array();
            }
            if (!in_array($node->dim->value, $GLOBALS['super'][$name]))
                array_push($GLOBALS['super'][$name], $node->dim->value);
        }
    }

    public function afterTraverse(array $nodes) {
        $a = $GLOBALS['super'];
        foreach ($a as $k => &$arr) {
            $arr = implode(",", $arr);
        }
    }

}

?>
