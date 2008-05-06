<?php
// -----------------------------------------------------------------------------
// setup
error_reporting(E_ALL);
require_once('../html_dom_parser.php');
$dom = new html_dom_parser;

// -----------------------------------------------------------------------------
// selector test 1
$str = <<<HTML
<img class="class0" id="id0" src="src0">
<img class="class1" id="id1" src="src1">
<img class="class2" id="id2" src="src2">
HTML;
$dom->load($str);

// -----------------------------------------------
// all
$e = $dom->find('*');
assert(count($e)==3);

// -----------------------------------------------
// tag
assert(count($dom->find('img'))==3);
assert(count($dom->find('!img'))==2);
assert(count($dom->find('text'))==2);
assert(count($dom->find('!text'))==3);

// -----------------------------------------------
// class
$es = $dom->find('img.class0');
assert(count($es)==1);
assert($es[0]->src=='src0');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class0" id="id0" src="src0">');
assert(count($dom->find('!img.class0'))==4);

$es = $dom->find('.class0');
assert(count($es)==1);
assert($es[0]->src=='src0');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class0" id="id0" src="src0">');
assert(count($dom->find('!.class0'))==4);

// -----------------------------------------------
// id
$es = $dom->find('img#id1');
assert(count($es)==1);
assert($es[0]->src=='src1');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class1" id="id1" src="src1">');

$es = $dom->find('#id2');
assert(count($es)==1);
assert($es[0]->src=='src2');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class2" id="id2" src="src2">');

// -----------------------------------------------
// attr
$es = $dom->find('img[src="src0"]');
assert(count($es)==1);
assert($es[0]->src=='src0');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class0" id="id0" src="src0">');

$es = $dom->find('img[src=src0]');
assert(count($es)==1);
assert($es[0]->src=='src0');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class0" id="id0" src="src0">');

$es = $dom->find('[src=src0]');
assert(count($es)==1);
assert($es[0]->src=='src0');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class0" id="id0" src="src0">');

$es = $dom->find('[src="src0"]');
assert(count($es)==1);
assert($es[0]->src=='src0');
assert($es[0]->innertext=='');
assert($es[0]->outertext=='<img class="class0" id="id0" src="src0">');

// -----------------------------------------------
// xml namespace test
$str = <<<HTML
<bw:bizy id="date">text</bw:bizy>
HTML;
$dom->load($str);
$es = $dom->find('bw:bizy');
assert(count($es)==1);
assert($es[0]->id=='date');

// -----------------------------------------------
// user defined tag name test
$str = <<<HTML
<div_test id="1">text</div_test>
HTML;
$dom->load($str);
$es = $dom->find('div_test');
assert(count($es)==1);
assert($es[0]->id=='1');
// -----------------------------------------------
$str = <<<HTML
<div-test id="1">text</div-test>
HTML;
$dom->load($str);
$es = $dom->find('div-test');
assert(count($es)==1);
assert($es[0]->id=='1');
// -----------------------------------------------
$str = <<<HTML
<div::test id="1">text</div::test>
HTML;
$dom->load($str);
$es = $dom->find('div::test');
assert(count($es)==1);
assert($es[0]->id=='1');

// -----------------------------------------------
// find all occurrences of id="1" regardless of the tag
$str = <<<HTML
<img class="class0" id="1" src="src0">
<img class="class1" id="2" src="src1">
<div class="class2" id="1">ok</div>
HTML;
$dom->load($str);
$es = $dom->find('[id=1]');
assert(count($es)==2);
assert($es[0]->tag=='img');
assert($es[1]->tag=='div');

// -----------------------------------------------------------------------------
// multiple selector test
$str = <<<HTML
<div class="class0" id="id0" ><div class="class1" id="id1"><div class="class2" id="id2">ok</div></div></div>
HTML;
$dom->load($str);

$es = $dom->find('div');
assert(count($es)==3);
assert($es[0]->id=='id0');
assert($es[1]->id=='id1');
assert($es[2]->id=='id2');

$es = $dom->find('div div');
assert(count($es)==2);
assert($es[0]->id=='id1');
assert($es[1]->id=='id2');

$es = $dom->find('div div div');
assert(count($es)==1);
assert($es[0]->id=='id2');

// -----------------------------------------------------------------------------
// multiple selector test 2
$str = <<<HTML
<table>
    <tr>
        <td>0</td>
        <td>1</td>
    </tr>
</table>
<table>
    <tr>
        <td>2</td>
        <td>3</td>
    </tr>
</table>
HTML;
$dom->load($str);
$es = $dom->find('table td');
assert(count($es)==4);
assert($es[0]->innertext=='0');
assert($es[1]->innertext=='1');
assert($es[2]->innertext=='2');
assert($es[3]->innertext=='3');

// -----------------------------------------------------------------------------
// multiple selector test 3
$str = <<<HTML
<table>
    <tr>
        <td>
            <table class="hello">
                <tr>
                    <td>0</td>
                    <td>1</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="hello">
    <tr>
        <td>2</td>
        <td>3</td>
    </tr>
</table>
HTML;
$dom = str_get_dom($str);
$es = $dom->find('table.hello td');
assert(count($es)==4);
assert($es[0]->innertext=='0');
assert($es[1]->innertext=='1');
assert($es[2]->innertext=='2');
assert($es[3]->innertext=='3');

// -----------------------------------------------------------------------------
// nested selector test
$str = <<<HTML
<ul>
    <li>0</li>
    <li>1</li>
</ul>
<ul>
    <li>2</li>
    <li>3</li>
</ul>
HTML;
$dom = str_get_dom($str);
$es= $dom->find('ul');
assert(count($es)==2);
foreach($es as $n) {
    $li = $n->find('li');
    assert(count($li)==2);
}

$es= $dom->find('li');
assert(count($es)==4);
assert($es[0]->innertext=='0');
assert($es[1]->innertext=='1');
assert($es[2]->innertext=='2');
assert($es[3]->innertext=='3');
assert($es[0]->outertext=='<li>0</li>');
assert($es[1]->outertext=='<li>1</li>');
assert($es[2]->outertext=='<li>2</li>');
assert($es[3]->outertext=='<li>3</li>');

$counter = 0;
foreach($dom->find('ul') as $ul) {
    foreach($ul->find('li') as $li) {
        assert($li->innertext=="$counter");
            assert($li->outertext=="<li>$counter</li>");
        ++$counter;
    }
}

// -----------------------------------------------------------------------------
//  [attribute=value] selector
$str = <<<HTML
<input type="radio" name="newsletter" value="Hot Fuzz" />
<input type="radio" name="newsletters" value="Cold Fusion" />
<input type="radio" name="accept" value="Evil Plans" />
HTML;
$dom->load($str);

$es = $dom->find('[name=newsletter]');
assert(count($es)==1);
assert($es[0]->name=='newsletter');
assert($es[0]->value=='Hot Fuzz');
assert($es[0]->outertext=='<input type="radio" name="newsletter" value="Hot Fuzz" />');

$es = $dom->find('[name="newsletter"]');
assert(count($es)==1);
assert($es[0]->name=='newsletter');
assert($es[0]->value=='Hot Fuzz');
assert($es[0]->outertext=='<input type="radio" name="newsletter" value="Hot Fuzz" />');

// -----------------------------------------------------------------------------
//  [attribute!=value] selector
$str = <<<HTML
<input type="radio" name="newsletter" value="Hot Fuzz" />
<input type="radio" name="newsletter" value="Cold Fusion" />
<input type="radio" name="accept" value="Evil Plans" />
HTML;
$dom->load($str);

$es = $dom->find('[name!=newsletter]');
assert(count($es)==1);
assert($es[0]->name=='accept');
assert($es[0]->value=='Evil Plans');
assert($es[0]->outertext=='<input type="radio" name="accept" value="Evil Plans" />');

$es = $dom->find('[name!="newsletter"]');
assert(count($es)==1);
assert($es[0]->name=='accept');
assert($es[0]->value=='Evil Plans');
assert($es[0]->outertext=='<input type="radio" name="accept" value="Evil Plans" />');

$es = $dom->find("[name!='newsletter']");
assert(count($es)==1);
assert($es[0]->name=='accept');
assert($es[0]->value=='Evil Plans');
assert($es[0]->outertext=='<input type="radio" name="accept" value="Evil Plans" />');

// -----------------------------------------------------------------------------
//  [attribute^=value] selector
$str = <<<HTML
<input name="newsletter" />
<input name="milkman" />
<input name="newsboy" />
HTML;
$dom->load($str);

$es = $dom->find('[name^=news]');
assert(count($es)==2);
assert($es[0]->name=='newsletter');
assert($es[0]->outertext=='<input name="newsletter" />');
assert($es[1]->name=='newsboy');
assert($es[1]->outertext=='<input name="newsboy" />');

$es = $dom->find('[name^="news"]');
assert(count($es)==2);
assert($es[0]->name=='newsletter');
assert($es[0]->outertext=='<input name="newsletter" />');
assert($es[1]->name=='newsboy');
assert($es[1]->outertext=='<input name="newsboy" />');

// -----------------------------------------------------------------------------
//  [attribute$=value] selector
$str = <<<HTML
<input name="newsletter" />
<input name="milkman" />
<input name="jobletter" />
HTML;
$dom->load($str);

$es = $dom->find('[name$=letter]');
assert(count($es)==2);
assert($es[0]->name=='newsletter');
assert($es[0]->outertext=='<input name="newsletter" />');
assert($es[1]->name=='jobletter');
assert($es[1]->outertext=='<input name="jobletter" />');

$es = $dom->find('[name$="letter"]');
assert(count($es)==2);
assert($es[0]->name=='newsletter');
assert($es[0]->outertext=='<input name="newsletter" />');
assert($es[1]->name=='jobletter');
assert($es[1]->outertext=='<input name="jobletter" />');

// -----------------------------------------------------------------------------
//  [attribute*=value] selector
$str = <<<HTML
<input name="man-news" />
<input name="milkman" />
<input name="letterman2" />
<input name="newmilk" />
HTML;
$dom->load($str);

$es = $dom->find('[name*=man]');
assert(count($es)==3);
assert($es[0]->name=='man-news');
assert($es[0]->outertext=='<input name="man-news" />');
assert($es[1]->name=='milkman');
assert($es[1]->outertext=='<input name="milkman" />');
assert($es[2]->name=='letterman2');
assert($es[2]->outertext=='<input name="letterman2" />');

$es = $dom->find('[name*="man"]');
assert(count($es)==3);
assert($es[0]->name=='man-news');
assert($es[0]->outertext=='<input name="man-news" />');
assert($es[1]->name=='milkman');
assert($es[1]->outertext=='<input name="milkman" />');
assert($es[2]->name=='letterman2');
assert($es[2]->outertext=='<input name="letterman2" />');

// -----------------------------------------------------------------------------
// Testcase for '[]' names element
// -----------------------------------------------------------------------------
//  normal checkbox
$str = <<<HTML
<input type="checkbox" name="news" value="foo" />
<input type="checkbox" name="news" value="bar">
<input type="checkbox" name="news" value="baz" />
HTML;
$dom->load($str);
$es = $dom->find('[name=news]');
assert(count($es)==3);
assert($es[0]->name=='news');
assert($es[0]->value=='foo');
assert($es[1]->name=='news');
assert($es[1]->value=='bar');
assert($es[2]->name=='news');
assert($es[2]->value=='baz');

// -----------------------------------------------------------------------------
//  with '[]' names checkbox
$str = <<<HTML
<input type="checkbox" name="news[]" value="foo" />
<input type="checkbox" name="news[]" value="bar">
<input type="checkbox" name="news[]" value="baz" />
HTML;
$dom->load($str);
$es = $dom->find('[name=news[]]');
assert(count($es)==3);
assert($es[0]->name=='news[]');
assert($es[0]->value=='foo');
assert($es[1]->name=='news[]');
assert($es[1]->value=='bar');
assert($es[2]->name=='news[]');
assert($es[2]->value=='baz');

// -----------------------------------------------------------------------------
// regular expression syntax escaping
$str = <<<HTML
<div>
<a href="image/one.png">one</a>
<a href="image/two.jpg">two</a>
<a href="/favorites/aaa">three (text)</a>
</div>
HTML;
$dom->load($str);
assert(count($dom->find('a[href^="image/"]'))==2);
assert(count($dom->find('a[href*="/favorites/"]'))==1);

// -----------------------------------------------------------------------------
// tear down
$dom->clear();
unset($dom);
?>