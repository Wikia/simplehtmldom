<?
error_reporting(E_ALL);
include_once('../html_dom_parser.php');

$dir = './html/';

$files = array(
    array('name'=>'empty.htm',          'url'=>''),
    //array('name'=>'project_index.htm',  'url'=>'http://simplehtmldom.sourceforge.net/index.htm'),
    //array('name'=>'project_manual.htm', 'url'=>'http://simplehtmldom.sourceforge.net/manual.htm'),
    array('name'=>'google.htm',         'url'=>'http://www.google.com.tw/'),
    array('name'=>'mootools.htm',       'url'=>'http://www.mootools.net/'),
    array('name'=>'jquery.htm',         'url'=>'http://jquery.com/'),
    array('name'=>'scriptaculo.htm',    'url'=>'http://script.aculo.us/'),
    array('name'=>'apache.htm',         'url'=>'http://www.apache.org/'),
    array('name'=>'microsoft.htm',      'url'=>'http://www.microsoft.com/'),
    array('name'=>'slashdot.htm',       'url'=>'http://www.slashdot.org/'),
    array('name'=>'ror.htm',            'url'=>'http://www.rubyonrails.org/'),
    array('name'=>'yahoo.htm',          'url'=>'http://tw.yahoo.com/'),
    
    array('name'=>'phpbb.htm',          'url'=>'http://www.phpbb.com/'),
    array('name'=>'python.htm',         'url'=>'http://www.python.org/'),
    //array('name'=>'ruby.htm',           'url'=>'http://www.ruby-lang.org/en/'),
    array('name'=>'lua.htm',            'url'=>'http://www.lua.org/'),
    array('name'=>'php.htm',            'url'=>'http://www.php.net/'),
    array('name'=>'ibm.htm',            'url'=>'http://www.ibm.com/'),
    array('name'=>'mysql.htm',          'url'=>'http://www.mysql.com/'),
    array('name'=>'java.htm',           'url'=>'http://java.sun.com/'),
    array('name'=>'flickr.htm',         'url'=>'http://www.flickr.com/tour/upload/'),
    array('name'=>'answers.htm',        'url'=>'http://www.answers.com/'),
    array('name'=>'amazon.htm',         'url'=>'http://www.amazon.com/'),
    array('name'=>'youtube.htm',        'url'=>'http://www.youtube.com/watch?v=kib05Ip6GSo&feature=bz302'),
);


echo 'memory: '.memory_get_usage().'<br>';
$dom = new html_dom_parser;

foreach($files as $f) {
    // get file from url
    if($f['url']!='')
        file_put_contents($dir.$f['name'], file_get_contents($f['url']));
    else
        file_put_contents($dir.$f['name'], '');

    $start = microtime();
    $dom->load_file($dir.$f['name'], false);
    list($eu, $es) = explode(' ', microtime());
    list($bu, $bs) = explode(' ', $start);
    echo sprintf('(%.1f)', ((float)$eu+(float)$es-(float)$bu-(float)$bs)*1000).'<br>';
    
    if (file_get_contents($dir.$f['name'])!=$dom->save()) {
        echo "[<font color='red'>failed</font>] ".$f['name']."<br>";
        file_put_contents($dir.$f['name'].'.error', $dom->save());
    }
    else
        echo "[success] ".$f['name']."<br>";
    
    //$dom->clear();
    echo 'memory: '.memory_get_usage().'<br>';

    flush();
}

$dom = null;
echo memory_get_usage().'<br>';

?>