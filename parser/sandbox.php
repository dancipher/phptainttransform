<?php

include "nexfiltrate.php";
include "bootstrap.php";

include "visitors/str_concat.php";
include "visitors/include.php";
include "visitors/mysql_query.php";
include "visitors/echo.php";

$pretty_printer = new PHPParser_PrettyPrinter_Default;
$parser = new PHPParser_Parser(new PHPParser_Lexer);
$traverser = new PHPParser_NodeTraverser;
$node_dumper = new PHPParser_NodeDumper;

$traverser->addVisitor(new Str_Concat_Visitor);
$traverser->addVisitor(new Include_Visitor);
$traverser->addVisitor(new Echo_Visitor);
$traverser->addVisitor(new MySQL_Visitor);

if (count($argv) > 1 && $argv[1] == "-e") {
    $stdin = fopen('php://stdin', 'r');
    $buffer = "";
    while (!feof($stdin)) {
        $buffer .= fgets($stdin);
    }
    fclose($stdin);
    $code = $buffer;
} else {
    $code = file_get_contents($argv[0]);
}

try {
    $stmts = $parser->parse($code);
    $stmts = $traverser->traverse($stmts);
    $code = $pretty_printer->prettyPrint($stmts);
    echo($code);

} catch (PHPParser_Error $e) {
    echo 'Parse Error: ', $e->getMessage();
}

eval($code);
exit();

?>
