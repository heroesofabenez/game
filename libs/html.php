<?php
if(MASTER_ID !== "HEROES_OF_ABENEZ") exit;
class Menu extends Object {
  static protected $pages = array();
  static protected $page;
  static function Init($pages, $page = "") {
    if(is_array($pages)) { self::$pages = $pages; }
    if(is_string($page)) { self::$page = $page; }
  }

  static function Build() {
    global $page;
    $content = "<div id=\"menu\">\n<ul>\n";
    foreach(self::$pages as $item) {
      $text = $item["text"];
      $link = $item["link"];
      $content .= "<li><a href=\"$link\">$text</a></li>\n";
    }
    $content .= "</ul>\n</div>\n";
    $page->addContent($content);
  }

  static function Destroy() {
    self::$pages = array();
    self::$page = "";
  }
}

class Page extends Object{
  private $title;
  private $styles;
  private $scripts;
  private $metaTags;
  private $channels;
  private $body;
  function __construct() {
    $this->styles = array();
    $this->scripts = array();
    $this->metaTags = array();
  } 
  
  function setTitle($s) {
    $this->title = $s;
  }
  
  function attachStyle($style) {
    $this->styles[] = $style;
  }
  
  function attachScript($script) {
    $this->scripts[] = $script;
  }  
  
  function addMeta($name, $value) {
    $this->metaTags["$name"] = $value;
  }
  
  function addChannel($url, $title, $type = "rss") {
    $this->channels["$title"] = $url;
  }
  
  function addContent($content) {
    $this->body .=  $content;
  }
  
  function addText($text) {
    $text = htmlspecialchars($text);
    $this->body .= $text;
  }
  
  function addImage($url, $desc) {
    $this->addContent("<img src=\"$url\" alt=\"$desc\">");
  }
  
  function addImageLink($imageUrl, $desc, $url) {
    $this->addContent("<a href=\"$url\" target=\"_blank\">");
    $this->addImage($imageUrl, $desc);
    $this->addContent("</a>");
  }
  
  function addForm($form) {
    $content = $form->render();
    $this->addContent($content);
  }
  
  function render() {
    $page = "<!DOCTYPE HTML>\n"
           ."<html>\n"
           ."<head>\n";   
    if(isset($this->metaTags)) {
      foreach($this->metaTags as $name=>$value){
        $name = strtolower($name);
        switch ($name) {
        case "content-type":
        case "content-language":
        case "refresh":
        case "pragma":
        case "cache-control":
          $page .= "  <meta http-equiv=\"$name\" content=\"$value\">\n";
          break;
      
        default:
        	$page .= "  <meta name=\"$name\" content=\"$value\">\n";
        	break;
        }
      }
    }
    $page .= "  <title>"."$this->title"."</title>\n";
    if(isset($this->styles)) {
      foreach($this->styles as $style) {
       	$page .= "  <link rel=\"stylesheet\" type=\"text/css\" href=\""."$style"."\">\n";
      }
    }
    if(isset($this->scripts)) {
       foreach($this->scripts as $script) {
      	$page .= "  <script src=\"$script\"></script>\n";
      }
    }
    if(isset($this->channels)) {
       foreach($this->channels as $title=>$url) {
      	$page .= "  <link rel=\"alternate\" type=\"application/rss+xml\" title=\"$title\" href=\"$url\">\n";
      }
    }
    $page .= "</head>\n"
           ."<body>\n";
    $page .= $this->body;             
    $page .= "</body>\n"
           ."</html>\n";
    return $page;
  }
}
?>