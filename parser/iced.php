<?php

if (count($argv) < 2) { die("Usage: prefill.php [args]\n"); }

include "bootstrap.php";
include "visitors/superglobals.php";

$pretty_printer = new PHPParser_PrettyPrinter_Default;
$parser = new PHPParser_Parser(new PHPParser_Lexer);
$traverser = new PHPParser_NodeTraverser;
$node_dumper = new PHPParser_NodeDumper;

$GLOBALS['super'] = array();

$traverser->addVisitor(new Superglobals_Visitor);

if (count($argv) > 1 && $argv[1] == "-e") {
    $stdin = fopen('php://stdin', 'r');
    $buffer = "";
    while (!feof($stdin)) {
        $buffer .= fgets($stdin);
    }
    fclose($stdin);
    $code = $buffer;
} else {
    $code = file_get_contents($argv[1]);
}

try {
    $stmts = $parser->parse($code);
    $stmts = $traverser->traverse($stmts);
    $code = $pretty_printer->prettyPrint($stmts);
    //echo($code);

} catch (PHPParser_Error $e) {
    echo 'Parse Error: ', $e->getMessage();
}

$cmd = "php";
$arr = $GLOBALS['super'];

foreach ($arr as $k => $v) {
    $cmd .= " -dprefill.".$k."=".implode(',', $v);
}
$sandbox_path = realpath('sandbox.php');
$target_path = realpath($argv[1]);

set_include_path($target_path);
$cmd .= " -dauto_prepend_file='$sandbox_path' -dprefill.preload=World";

system("$cmd ".$argv[1]);

exit();

?>
