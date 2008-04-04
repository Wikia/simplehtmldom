<?php
error_reporting(E_ALL);
include('../html_dom_parser.php');

function dump_all_nodes($dom) {
    foreach ($dom->nodes as $n) {
        echo $n->nodetype.'-'.htmlspecialchars($n->text()).'<br>';
    }
}
//echo htmlspecialchars($dom->save()).'!!';
//dump_all_nodes($dom);

// -----------------------------------------------------------------------------
// test BAD HTML
$str = <<<HTML
<strong class="see <a href="http://www.oeb.harvard.edu/faculty/girguis/">http://www.oeb.harvard.edu/faculty/girguis/</a>">.</strong></p> 
HTML;
$dom = str_get_dom($str);
//die;

// -----------------------------------------------------------------------------
// test self closing tags add attr
$str = <<<HTML
<hr>
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
assert($e->outertext=='<hr id="foo">');
// -----------------------------------------------
$str = <<<HTML
<hr/>
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
assert($e->outertext=='<hr id="foo"/>');
// -----------------------------------------------
$str = <<<HTML
<hr />
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
assert($e->outertext=='<hr id="foo" />');
// -----------------------------------------------
$str = <<<HTML
<hr>
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" class="bar">');
// -----------------------------------------------
$str = <<<HTML
<hr/>
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" class="bar"/>');
// -----------------------------------------------
$str = <<<HTML
<hr />
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" class="bar" />');
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk=ll>
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" kk=ll class="bar">');
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk="ll"/>
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" kk="ll" class="bar"/>');
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk=ll />
HTML;
$dom = str_get_dom($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" kk=ll class="bar" />');

// -----------------------------------------------------------------------------
echo 'All pass!<br>';
?>