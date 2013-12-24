<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Element extends Object {
  protected $name;
  protected $content;
  protected $class;
  protected $id;
  function __construct($name, $content = "") {
    if(!is_string($name)) { exit("Invalid value for parametr name passed to method Element::__construct. Expected string."); }
    if(!is_string($content)) { exit("Invalid value for parametr content passed to method Element::__construct. Expected string."); }
    $this->name = $name;
    $this->content = $content;
  }
  
  function setId($id) {
    $this->id = $id;
  }
  
  function setClass($class) {
    $this->class = $class;
  }
  
  function addText($content) {
    if(!is_string($content)) { exit("Invalid value for parametr content passed to method Element::addText. Expected string."); }
    $this->content .= $content;
  }
  
  function removeText() {
    $this->content = "";
  }
  
  function setText($content) {
    if(!is_string($content)) { exit("Invalid value for parametr content passed to method Element::setText. Expected string."); }
    $this->content = $content;
  }
  
  function render() {
     $return = "<$this->name";
     if($this->class) { $return .= " class='{$this->class}'"; }
     if($this->id) { $return .= " id='$this->id'"; }
     $return .= ">$this->content</$this->name>\n";
  }
}

class Container extends Object {
  protected $name;
  protected $parts = array();
  protected $class;
  protected $id;
  function __construct($name) {
    if(!is_string($name)) { exit("Invalid value for parametr name passed to method Container::__construct. Expected string."); }
    $this->name = $name;
  }
  
  function setId($id) {
    $this->id = $id;
  }
  
  function setClass($class) {
    $this->class = $class;
  }
  
  function append($element) {
    if($element instanceof Element OR $element instanceof Container) {  } else { exit("Invalid value for parametr element passed to method Container::append. Expected Element or Container."); }
    $count = count($this->parts);
    $this->parts[] = $element;
    return $count;
  }
  
  function remove($nodeId) {
    unset($this->parts[$nodeId]);
  }
  
  function addText($node) {
    if(is_string($node)) {
      $this->parts[] = new TextNode($node);
    } elseif($node instanceof TextNode) {
      $this->parts[] = $node;
    } else { exit("Invalid value for parametr node passed to method Container::addText. Expected string or TextNode."); }
  }
  
  function addParagraph($content = "") {
    $element = new Paragraph($content);
    $count = count($this->parts);
    $this->parts[$count] = $element;
    $return = & $this->parts[$count];
    return $return;
  }
  
  function addImage($source = "") {
    $element = new Image($source);
    $count = count($this->parts);
    $this->parts[$count] = $element;
    $return = & $this->parts[$count];
    return $return;
  } 
  
  function render() {
    $return = "<$this->name";
    if($this->class) { $return .= " class='$this->class'"; }
    if($this->id) { $return .= " id='$this->id'"; }
    $return .= ">\n";
    foreach($this->parts as $part) {
      $return .= $part->render();
    }
    $return .= "</$this->name>\n";
    return $return;
  }
}

class Paragraph extends Element{
  function __construct($content = "") {
    $name = "p";
    parent::__construct($name, $content);
  }
}

class RowBreak extends Element{
 function __construct() {
    $name = "br";
    parent::__construct($name);
  }
  
  function render() {
    return "<br>";
  }
}

class Image extends Element {
  protected $source;
  protected $alt;
  protected $title;
  protected $align;
  function __construct($source = "") {
    parent::__construct("img");
    if($source) { $this->source = $source; }
  }
  
  function setSource($source) {
    if(!is_string($source)) { exit("Invalid value for parametr source passed to method Image::setSource. Expected string."); }
    $this->source = $source;
  }
  
  function setTitle($title) {
    if(!is_string($title)) { exit("Invalid value for parametr title passed to method Image::setTitle. Expected string."); }
    $this->title = $title;
  }
  
  function setAlt($alt) {
    if(!is_string($alt)) { exit("Invalid value for parametr alt passed to method Image::setAlt. Expected string."); }
    $this->alt = $alt;
  }
  
  function setAlign($align) {
    if(!is_string($align)) { exit("Invalid value for parametr align passed to method Image::setAlign. Expected string."); }
    $aligns = array("left", "right", "top", "middle", "baseline", "bottom", "absbottom", "absmiddle", "texttop");
    if(in_array($align, $aligns)) { $this->align = $align; }
  }
  
  function render() {
    $return = "<img src='$this->source'";
    if($this->alt) { $return .= " alt='$this->alt'"; }
    if($this->title) { $return .= " title='$this->title'"; }
    if($this->align) { $return .= " align='$this->align'"; }
    $return .= ">\n";
    return $return;
  }
}

class Div extends Container {
  function __construct() {
    parent::__construct("div");
  }
}

class Table extends Container {
  protected $colls;
  protected $collsNames = array();
  protected $rows = array();
  function __construct($colls) {
    parent::__construct("table");
    if(is_int($colls)) { $this->colls = $colls; } else { exit("Invalid value for parametr colls passed to method Table::__construct. Expected integer."); }
  }
  
  function setCollName($coll, $name) {
    if($coll > $this->colls OR $coll <= 0) { exit("Invalid column."); }
    if(!is_string($name)) { exit("Invalid value for parametr name passed to method Table::setCollName. Expected string."); }
    $this->collsNames[$coll] = $name;
    return $this;
  }
  
  function addRow(array $row) {
    if(count($row) > $this->colls) { exit; }
    $count = count($this->rows);
    $this->rows[$count] = $row;
    $row = &$this->rows[$count];
    return $row;
  }
  
  function removeRow($row) {
    if(!is_int($row)) { exit; }
    if(isset($this->rows[$row])) { unset($this->rows[$row]); }
  }
  
  function render() {
    $return = "<table>\n<tr>";
    foreach($this->collsNames as $name) {
      $return .= "<td>$name</td>";
    }
    $return .= "</tr>\n";
    foreach($this->rows as $row) {
      $return .= "<tr>";
      foreach($row as $coll) {
        $return .= "<td>$coll</td>";
      }
      $return .= "</tr>\n";
    }
    $return .= "</table>\n";
    return $return;
  }
}

class Span extends Container{
  function __construct() {
    parent::__construct("span");
  }
}

class ListItem extends Element {
  function __construct($text) {
    parent::__construct("li", $text);
  }
}

class ListElement extends Container {
  function __construct($type = "ul") {
switch($type) {
case "ul":
case "ol":
  parent::__construct($type);
  break;
default:
  parent::__construct("ul");
  break;
}
  }
  
  function addItem($text) {
    if(!is_string($text)) { exit; }
    $count = count($this->parts);
    $this->parts[$count] = new ListItem($text);
  }
  
  function append($item) {
    if(item instanceof ListItem) {  } else { exit; }
    $count = count($this->parts);
    $this->parts[$count] = $item;
  }
  
  function remove($node) {
    unset($this->parts[$node]);
  }
}

class TextNode extends Object {
  protected $content;
  function __construct($content = "") {
    if(!is_string($content)) { exit("Invalid value for parametr content passed to method TextNode::__construct. Expected string."); }
    $this->content = $content;
  }
  
  function addText($content) {
    if(!is_string($content)) { exit("Invalid value for parametr content passed to method TextNode::addText. Expected string."); }
    $this->content .= $content;
  }
  
  function removeText() {
    $this->content = "";
  }
  
  function setText($content) {
    if(!is_string($content)) { exit("Invalid value for parametr content passed to method TextNode::setText. Expected string."); }
    $this->content = $content;
  }
  
  function render() {
    return $this->content . "\n";
  }
}

class Page extends Object {
  public $elements = array();
  protected $title;
  protected $scripts = array();
  protected $metas = array();
  protected $styles = array();  
  private $channels = array();
  function setTitle($title) {
   if(!is_string($title)) { exit("Invalid value for parametr title passed to method Page::__construct. Expected string."); }
   $this->title = $title;
  }
  
  function addMeta($name, $content) {
    if(!is_string($name)) { exit("Invalid value for parametr name passed to method Page::addMeta. Expected string."); }
    if(!is_string($content)) { exit("Invalid value for parametr content passed to method Page::addMeta. Expected string."); }
    $this->metas[] = array("name" => $name, "content" => $content);
  }
  
  function addMetas($metas = array()) {
    if(!is_array($metas)) { exit("Invalid value for parametr metas passed to method Page::addMetas. Expected array."); }
    foreach($metas as $meta) {
      $this->addMeta($meta["name"], $meta["content"]);
    }
  }
  
  function addChannel($url, $title, $type = "rss") {
    $this->channels["$title"] = $url;
  }
  
  function attachStyle($style) {
    if(!is_string($style)) { exit("Invalid value for parametr style passed to method Page::attachStyle. Expected string."); }
    $this->styles[] = $style;
  }
  
  function attachStyles($styles = array()) {
    if(!is_array($styles)) { exit("Invalid value for parametr styles passed to method Page::attachStyles. Expected array."); }
    foreach($styles as $style) {
      $this->attachStyle($style);
    }
  }
  function attachScript($script) {
    if(!is_string($script)) { exit("Invalid value for parametr script passed to method Page::attachScript. Expected string."); }
    $this->scripts[] = $script;
  }
  
  function attachScripts($scripts = array()) {
    if(!is_array($scripts)) { exit("Invalid value for parametr scripts passed to method Page::attachScripts. Expected array."); }
    foreach($scripts as $script) {
      $this->attachScript($script);
    }
  }
  
  function addParagraph($content = "") {
    $element = new Paragraph($content);
    $count = count($this->elements);
    $this->elements[$count] = $element;
    $return = & $this->elements[$count];
    return $return;
  }
  
  function addDiv() {
    $element = new Div();
    $count = count($this->elements);
    $this->elements[$count] = $element;
    $return = & $this->elements[$count];
    return $return;
  }
  
  function addSpan() {
    $element = new Span();
    $count = count($this->elements);
    $this->elements[$count] = $element;
    $return = & $this->elements[$count];
    return $return;
  }
  
  function addTable($colls) {
    $element = new Table($colls);
    $count = count($this->elements);
    $this->elements[$count] = $element;
    $return = & $this->elements[$count];
    return $return;
  }
    
  function addImage($source = "") {
    $element = new Image($source);
    $count = count($this->elements);
    $this->elements[$count] = $element;
    $return = & $this->elements[$count];
    return $return;
  } 
  
  function addList($type = "ul") {
    $element = new ListElement($type);
    $count = count($this->elements);
    $this->elements[$count] = $element;
    $return = & $this->elements[$count];
    return $return;
  }
  
  function append($element) {
    if($element instanceof Element OR $element instanceof Container) {  } else { exit("Invalid value for parametr element passed to method Page::append. Expected Element or Container."); }
    $this->elements[] = $element;
  }
  
  function remove($node) {
    unset($this->elements[$node]);
  }
  
  function render() {
    $page = "<!DOCTYPE HTML>
<html>
<head>
  <title>$this->title</title>";
    foreach($this->styles as $style) {
      $page .= "  <link rel=\"stylesheet\" type=\"text/css\" href=\""."$style"."\">\n";
    }
    foreach($this->scripts as $script) {
      $page .= "  <script src=\"$script\"></script>\n";
    }
    foreach($this->channels as $title=>$url) {
    $page .= "  <link rel=\"alternate\" type=\"application/rss+xml\" title=\"$title\" href=\"$url\">\n";
    }
    $page .= "
</head>
<body>
";
    foreach($this->elements as $element) {
      $page .= $element->render();
    }
    $page .= "</body>
</html>";
    return $page;
  }
}
?>