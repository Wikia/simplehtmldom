<?php
// example of how to customize the parsing behavior
include('../html_dom_parser.php');

// HTML text extractor
function html_extract_contents($str) {
    // 1. create DOM object
    $parser = new html_dom_parser;

    // 2. prepare HTML data and init everything
    $parser->prepare($str);

    // 3. some contents such as 'comments', 'styles' or 'script' will be treated as 'text',
    // so we need to remove it before parsing...

    // strip out DOCTYPE
    $parser->remove_noise("'<!doctype(.*?)>'is");
    // strip out comments
    $parser->remove_noise("'<!--(.*?)-->'is");
    // strip out <style> tags
    $parser->remove_noise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is", false);
    $parser->remove_noise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is", false);
    // strip out <script> tags
    $parser->remove_noise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is", false);
    $parser->remove_noise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is", false);
    // strip out <pre> tags
    $parser->remove_noise("'<\s*pre[^>]*>(.*?)<\s*/\s*pre\s*>'is", false, false);
    // strip out <code> tags
    $parser->remove_noise("'<\s*code[^>]*>(.*?)<\s*/\s*code\s*>'is", false, false);

    // 4. parsing each node
    $ret = '';
    while ($node=$parser->parse()) {
        // dump node's contents which tag is 'text'
        if ($node->tag=='text')
            $ret .= htmlspecialchars_decode($node->text());
    }
    
    // clean up memory
    $parser->clear();
    unset($parser);

    return $ret;
}

// test it!
$str = file_get_contents('http://www.google.com/');
echo html_extract_contents($str);
?>