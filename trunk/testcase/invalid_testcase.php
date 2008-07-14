<?php
// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once('../simple_html_dom.php');
$dom = new simple_html_dom;

// -----------------------------------------------------------------------------
//self-closing tags test
$str = <<<HTML
<hr>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
assert($e->outertext=='<hr id="foo">');
// -----------------------------------------------
$str = <<<HTML
<hr/>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
assert($e->outertext=='<hr id="foo"/>');
// -----------------------------------------------
$str = <<<HTML
<hr />
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
assert($e->outertext=='<hr id="foo" />');
// -----------------------------------------------
$str = <<<HTML
<hr>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" class="bar">');
// -----------------------------------------------
$str = <<<HTML
<hr/>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" class="bar"/>');
// -----------------------------------------------
$str = <<<HTML
<hr />
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->id= 'foo';
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" class="bar" />');
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk=ll>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" kk=ll class="bar">');
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk="ll"/>
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" kk="ll" class="bar"/>');
// -----------------------------------------------
$str = <<<HTML
<hr id="foo" kk=ll />
HTML;
$dom->load($str);
$e = $dom->find('hr', 0);
$e->class = 'bar';
assert($e->outertext=='<hr id="foo" kk=ll class="bar" />');

// -----------------------------------------------------------------------------
// optional closing tags test
$str = <<<HTML
<body>
</b><.b></a>
</body>
HTML;
$dom = str_get_dom($str);

assert($dom->find('body', 0)->outertext==$str);

// -----------------------------------------------
// optional closing tags test
$str = <<<HTML
<body>
<div>
</body>
HTML;
$dom = str_get_dom($str);

assert($dom->find('body', 0)->outertext==$str);

// -----------------------------------------------
// optional closing tags test
$str = <<<HTML
<body>
<div> </a> </div>
</body>
HTML;
$dom = str_get_dom($str);

assert($dom->find('body', 0)->outertext==$str);

// -----------------------------------------------
$str = <<<HTML
<table>
<tr><td>1<td>2<td>3
</table>
HTML;
$dom = str_get_dom($str);
assert(count($dom->find('td'))==3);
assert($dom->find('td', 0)->innertext=='1');
assert($dom->find('td', 0)->outertext=='<td>1');
assert($dom->find('td', 1)->innertext=='2');
assert($dom->find('td', 1)->outertext=='<td>2');
assert($dom->find('td', 2)->innertext=="3\r\n");
assert($dom->find('td', 2)->outertext=="<td>3\r\n");

// -----------------------------------------------
$str = <<<HTML
<table>
<tr>
    <td><b>1</b></td>
    <td><b>2</b></td>
    <td><b>3</b></td>
</table>
HTML;
$dom = str_get_dom($str);
assert(count($dom->find('tr td'))==3);

// -----------------------------------------------
$str = <<<HTML
<table>
<tr><td><b>11</b></td><td><b>12</b></td><td><b>13</b></td>
<tr><td><b>21</b></td><td><b>32</b></td><td><b>43</b></td>
</table>
HTML;
$dom = str_get_dom($str);
assert(count($dom->find('tr'))==2);
assert(count($dom->find('tr td'))==6);
assert($dom->find('tr', 1)->outertext=="<tr><td><b>21</b></td><td><b>32</b></td><td><b>43</b></td>\r\n");
assert($dom->find('tr', 1)->innertext=="<td><b>21</b></td><td><b>32</b></td><td><b>43</b></td>\r\n");
assert($dom->find('tr', 1)->plaintext=="213243\r\n");

// -----------------------------------------------
$str = <<<HTML
<p>1
<p>2</p>
<p>3
HTML;
$dom = str_get_dom($str);
assert(count($dom->find('p'))==3);
assert($dom->find('p', 0)->innertext=="1\r\n");
assert($dom->find('p', 0)->outertext=="<p>1\r\n");
assert($dom->find('p', 1)->innertext=="2");
assert($dom->find('p', 1)->outertext=="<p>2</p>");
assert($dom->find('p', 2)->innertext=="3");
assert($dom->find('p', 2)->outertext=="<p>3");
// -----------------------------------------------
$str = <<<HTML
<dl><dt>1<dd>2<dt>3<dd>4</dl>
HTML;
$dom = str_get_dom($str);
assert(count($dom->find('dt'))==2);
assert(count($dom->find('dd'))==2);
assert($dom->find('dt', 0)->innertext=="1");
assert($dom->find('dt', 0)->outertext=="<dt>1");
assert($dom->find('dt', 1)->innertext=="3");
assert($dom->find('dt', 1)->outertext=="<dt>3");
assert($dom->find('dd', 0)->innertext=="2");
assert($dom->find('dd', 0)->outertext=="<dd>2");
assert($dom->find('dd', 1)->innertext=="4");
assert($dom->find('dd', 1)->outertext=="<dd>4");

// -----------------------------------------------
$str = <<<HTML
<dl id="dl1"><dt>11<dd>12<dt>13<dd>14</dl>
<dl id="dl2"><dt>21<dd>22<dt>23<dd>24</dl>
HTML;
$dom = str_get_dom($str);
assert(count($dom->find('#dl1 dt'))==2);
assert(count($dom->find('#dl2  dd'))==2);
assert($dom->find('dl', 0)->innertext=="<dt>11<dd>12<dt>13<dd>14");
assert($dom->find('dl', 1)->innertext=="<dt>21<dd>22<dt>23<dd>24");

// -----------------------------------------------
$str = <<<HTML
<ul id="ul1"><li><b>1</b><li><b>2</b></ul>
<ul id="ul2"><li><b>3</b><li><b>4</b></ul>
HTML;
$dom = str_get_dom($str);
assert(count($dom->find('ul[id=ul1] li'))==2);
// -----------------------------------------------------------------------------
// invalid test 1
$str = <<<HTML
<div>
    <div class="class0" id="id0" >
    <img class="class0" id="id0" src="src0">
    </img>
    <img class="class0" id="id0" src="src0">
    </div>
</div>
HTML;
$dom->load($str);
assert(count($dom->find('img'))==2);
assert(count($dom->find('img'))==2);
assert($dom==$str);

// -----------------------------------------------------------------------------
// nvalid test 2
$str = <<<HTML
<div>
    <div class="class0" id="id0" >
    <span></span>
    </span>
    <span></span>
    </div>
</div>
HTML;
$dom->load($str);
assert(count($dom->find('span'))==2);
assert(count($dom->find('div'))==2);
assert($dom==$str);

// -----------------------------------------------------------------------------
// invalid test 3
$str = <<<HTML
<div>
    <div class="class0" id="id0" >
    <span></span>
    <span>
    <span></span>
    </div>
</div>
HTML;
$dom->load($str);
assert(count($dom->find('span'))==3);
assert(count($dom->find('div'))==2);
assert($dom==$str);

// -----------------------------------------------------------------------------
// BAD HTML test
$str = <<<HTML
<strong class="see <a href="http://www.oeb.harvard.edu/faculty/girguis/">http://www.oeb.harvard.edu/faculty/girguis/</a>">.</strong></p> 
HTML;
$dom->load($str);
// foreach ($dom->nodes as $n) 
// echo $n->tag.'<br>';
// print_r($dom->find('strong', 0)->attr);
// echo '<br>'.htmlspecialchars($str);
// echo '<br>'.htmlspecialchars($dom->save());
// -----------------------------------------------
$str = <<<HTML
<a href="http://www.oeb.harvard.edu/faculty/girguis\">http://www.oeb.harvard.edu/faculty/girguis/</a>">
HTML;
$dom->load($str);
// echo '<br>'.htmlspecialchars($str);
// echo '<br>'.htmlspecialchars($dom->save());
// -----------------------------------------------
$str = <<<HTML
<strong class="''""";;''""";;\"\''''\"""''''""''>""'''"'" '
HTML;
$dom->load($str);
// echo '<br>'.htmlspecialchars($str);
// echo '<br>'.htmlspecialchars($dom->save());
//die;

// -----------------------------------------------------------------------------
// tear down
$dom->clear();
unset($dom);
?>