<?php //netteloader=IComponent

/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt, and/or GPL license.
 *
 * For more information please see http://nette.org
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 * @link       http://nette.org
 * @category   Nette
 * @package    Nette
 */

if(!defined('PHP_VERSION_ID')){$tmp=explode('.',PHP_VERSION);define('PHP_VERSION_ID',($tmp[0]*10000+$tmp[1]*100+$tmp[2]));}if(PHP_VERSION_ID<50200){throw
new
Exception('Nette Framework requires PHP 5.2.0 or newer.');}error_reporting(E_ALL|E_STRICT);@set_magic_quotes_runtime(FALSE);iconv_set_encoding('internal_encoding','UTF-8');extension_loaded('mbstring')&&mb_internal_encoding('UTF-8');header('X-Powered-By: Nette Framework');define('NETTE',TRUE);define('NETTE_VERSION_ID',10000);define('NETTE_PACKAGE','PHP 5.2');function
callback($callback,$m=NULL){return($m===NULL&&$callback
instanceof
Callback)?$callback:new
Callback($callback,$m);}function
dump($var){foreach(func_get_args()as$arg)Debug::dump($arg);return$var;}interface
IComponent{const
NAME_SEPARATOR='-';function
getName();function
getParent();function
setParent(IComponentContainer$parent=NULL,$name=NULL);}interface
IComponentContainer
extends
IComponent{function
addComponent(IComponent$component,$name);function
removeComponent(IComponent$component);function
getComponent($name);function
getComponents($deep=FALSE,$filterType=NULL);}interface
INamingContainer
extends
IComponentContainer{}interface
ISignalReceiver{function
signalReceived($signal);}interface
IStatePersistent{function
loadState(array$params);function
saveState(array&$params);}interface
IRenderable{function
invalidateControl();function
isControlInvalid();}interface
IPartiallyRenderable
extends
IRenderable{}interface
IPresenter{function
run(PresenterRequest$request);}interface
IPresenterLoader{function
getPresenterClass(&$name);}interface
IPresenterResponse{function
send();}interface
IRouter{const
ONE_WAY=1;const
SECURED=2;function
match(IHttpRequest$httpRequest);function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest);}interface
IDebugPanel{function
getTab();function
getPanel();function
getId();}interface
ICacheStorage{function
read($key);function
write($key,$data,array$dependencies);function
remove($key);function
clean(array$conds);}interface
IConfigAdapter{static
function
load($file,$section=NULL);static
function
save($config,$file,$section=NULL);}interface
IServiceLocator{function
addService($name,$service,$singleton=TRUE,array$options=NULL);function
getService($name,array$options=NULL);function
removeService($name);function
hasService($name);}interface
IFormControl{function
loadHttpData();function
setValue($value);function
getValue();function
getRules();function
getErrors();function
isDisabled();function
translate($s,$count=NULL);}interface
ISubmitterControl
extends
IFormControl{function
isSubmittedBy();function
getValidationScope();}interface
IFormRenderer{function
render(Form$form);}interface
IMailer{function
send(Mail$mail);}interface
IAnnotation{function
__construct(array$values);}interface
IAuthenticator{const
USERNAME='username';const
PASSWORD='password';const
IDENTITY_NOT_FOUND=1;const
INVALID_CREDENTIAL=2;const
FAILURE=3;const
NOT_APPROVED=4;function
authenticate(array$credentials);}interface
IAuthorizator{const
ALL=NULL;const
ALLOW=TRUE;const
DENY=FALSE;function
isAllowed($role=self::ALL,$resource=self::ALL,$privilege=self::ALL);}interface
IIdentity{function
getId();function
getRoles();}interface
IResource{function
getResourceId();}interface
IRole{function
getRoleId();}interface
ITemplate{function
render();}interface
IFileTemplate
extends
ITemplate{function
setFile($file);function
getFile();}interface
ITranslator{function
translate($message,$count=NULL);}interface
IHttpRequest{const
GET='GET',POST='POST',HEAD='HEAD',PUT='PUT',DELETE='DELETE';function
getUri();function
getQuery($key=NULL,$default=NULL);function
getPost($key=NULL,$default=NULL);function
getPostRaw();function
getFile($key);function
getFiles();function
getCookie($key,$default=NULL);function
getCookies();function
getMethod();function
isMethod($method);function
getHeader($header,$default=NULL);function
getHeaders();function
isSecured();function
isAjax();function
getRemoteAddress();function
getRemoteHost();}interface
IHttpResponse{const
PERMANENT=2116333333;const
BROWSER=0;const
S200_OK=200,S204_NO_CONTENT=204,S300_MULTIPLE_CHOICES=300,S301_MOVED_PERMANENTLY=301,S302_FOUND=302,S303_SEE_OTHER=303,S303_POST_GET=303,S304_NOT_MODIFIED=304,S307_TEMPORARY_REDIRECT=307,S400_BAD_REQUEST=400,S401_UNAUTHORIZED=401,S403_FORBIDDEN=403,S404_NOT_FOUND=404,S405_METHOD_NOT_ALLOWED=405,S410_GONE=410,S500_INTERNAL_SERVER_ERROR=500,S501_NOT_IMPLEMENTED=501,S503_SERVICE_UNAVAILABLE=503;function
setCode($code);function
getCode();function
setHeader($name,$value);function
addHeader($name,$value);function
setContentType($type,$charset=NULL);function
redirect($url,$code=self::S302_FOUND);function
setExpiration($seconds);function
isSent();function
getHeaders();function
setCookie($name,$value,$expire,$path=NULL,$domain=NULL,$secure=NULL);function
deleteCookie($name,$path=NULL,$domain=NULL,$secure=NULL);}interface
IUser{function
login($username,$password,$extra=NULL);function
logout($clearIdentity=FALSE);function
isLoggedIn();function
getIdentity();function
setAuthenticationHandler(IAuthenticator$handler);function
getAuthenticationHandler();function
setNamespace($namespace);function
getNamespace();function
getRoles();function
isInRole($role);function
isAllowed();function
setAuthorizationHandler(IAuthorizator$handler);function
getAuthorizationHandler();}class
ArgumentOutOfRangeException
extends
InvalidArgumentException{}class
InvalidStateException
extends
RuntimeException{function
__construct($message='',$code=0,Exception$previous=NULL){if(PHP_VERSION_ID<50300){$this->previous=$previous;parent::__construct($message,$code);}else{parent::__construct($message,$code,$previous);}}}class
NotImplementedException
extends
LogicException{}class
NotSupportedException
extends
LogicException{}class
DeprecatedException
extends
NotSupportedException{}class
MemberAccessException
extends
LogicException{}class
IOException
extends
RuntimeException{}class
FileNotFoundException
extends
IOException{}class
DirectoryNotFoundException
extends
IOException{}class
FatalErrorException
extends
Exception{private$severity;function
__construct($message,$code,$severity,$file,$line,$context){parent::__construct($message,$code);$this->severity=$severity;$this->file=$file;$this->line=$line;$this->context=$context;}function
getSeverity(){return$this->severity;}}final
class
Framework{const
NAME='Nette Framework';const
VERSION='1.0-dev';const
REVISION='1ac0863 released on 2010-07-01';final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
promo(){echo'<a href="http://nette.org" title="Nette Framework - The Most Innovative PHP Framework"><img ','src="http://files.nette.org/icons/nette-powered.gif" alt="Powered by Nette Framework" width="80" height="15" /></a>';}}abstract
class
Object{function
getReflection(){return
new
ClassReflection($this);}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}static
function
__callStatic($name,$args){$class=get_called_class();throw
new
MemberAccessException("Call to undefined static method $class::$name().");}static
function
extensionMethod($name,$callback=NULL){if(strpos($name,'::')===FALSE){$class=get_called_class();}else{list($class,$name)=explode('::',$name);}$class=new
ClassReflection($class);if($callback===NULL){return$class->getExtensionMethod($name);}else{$class->setExtensionMethod($name,$callback);}}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}final
class
ObjectMixin{private
static$methods;final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
call($_this,$name,$args){$class=new
ClassReflection($_this);if($name===''){throw
new
MemberAccessException("Call to class '$class->name' method without name.");}if($class->hasEventProperty($name)){if(is_array($list=$_this->$name)||$list
instanceof
Traversable){foreach($list
as$handler){callback($handler)->invokeArgs($args);}}return
NULL;}if($cb=$class->getExtensionMethod($name)){array_unshift($args,$_this);return$cb->invokeArgs($args);}throw
new
MemberAccessException("Call to undefined method $class->name::$name().");}static
function&get($_this,$name){$class=get_class($_this);if($name===''){throw
new
MemberAccessException("Cannot read a class '$class' property without name.");}if(!isset(self::$methods[$class])){self::$methods[$class]=array_flip(get_class_methods($class));}$name[0]=$name[0]&"\xDF";$m='get'.$name;if(isset(self::$methods[$class][$m])){$val=$_this->$m();return$val;}$m='is'.$name;if(isset(self::$methods[$class][$m])){$val=$_this->$m();return$val;}$name=func_get_arg(1);throw
new
MemberAccessException("Cannot read an undeclared property $class::\$$name.");}static
function
set($_this,$name,$value){$class=get_class($_this);if($name===''){throw
new
MemberAccessException("Cannot write to a class '$class' property without name.");}if(!isset(self::$methods[$class])){self::$methods[$class]=array_flip(get_class_methods($class));}$name[0]=$name[0]&"\xDF";if(isset(self::$methods[$class]['get'.$name])||isset(self::$methods[$class]['is'.$name])){$m='set'.$name;if(isset(self::$methods[$class][$m])){$_this->$m($value);return;}else{$name=func_get_arg(1);throw
new
MemberAccessException("Cannot write to a read-only property $class::\$$name.");}}$name=func_get_arg(1);throw
new
MemberAccessException("Cannot write to an undeclared property $class::\$$name.");}static
function
has($_this,$name){if($name===''){return
FALSE;}$class=get_class($_this);if(!isset(self::$methods[$class])){self::$methods[$class]=array_flip(get_class_methods($class));}$name[0]=$name[0]&"\xDF";return
isset(self::$methods[$class]['get'.$name])||isset(self::$methods[$class]['is'.$name]);}}final
class
Callback
extends
Object{private$cb;function
__construct($t,$m=NULL){if($m===NULL){$this->cb=$t;}else{$this->cb=array($t,$m);}if(is_object($this->cb)){$this->cb=array($this->cb,'__invoke');}elseif(PHP_VERSION_ID<50202){if(is_string($this->cb)&&strpos($this->cb,':')){$this->cb=explode('::',$this->cb);}if(is_array($this->cb)&&is_string($this->cb[0])&&$a=strrpos($this->cb[0],'\\')){$this->cb[0]=substr($this->cb[0],$a+1);}}else{if(is_string($this->cb)&&$a=strrpos($this->cb,'\\')){$this->cb=substr($this->cb,$a+1);}elseif(is_array($this->cb)&&is_string($this->cb[0])&&$a=strrpos($this->cb[0],'\\')){$this->cb[0]=substr($this->cb[0],$a+1);}}if(!is_callable($this->cb,TRUE)){throw
new
InvalidArgumentException("Invalid callback.");}}function
__invoke(){if(!is_callable($this->cb)){throw
new
InvalidStateException("Callback '$this' is not callable.");}$args=func_get_args();return
call_user_func_array($this->cb,$args);}function
invoke(){if(!is_callable($this->cb)){throw
new
InvalidStateException("Callback '$this' is not callable.");}$args=func_get_args();return
call_user_func_array($this->cb,$args);}function
invokeArgs(array$args){if(!is_callable($this->cb)){throw
new
InvalidStateException("Callback '$this' is not callable.");}return
call_user_func_array($this->cb,$args);}function
isCallable(){return
is_callable($this->cb);}function
getNative(){return$this->cb;}function
__toString(){is_callable($this->cb,TRUE,$textual);return$textual;}}final
class
LimitedScope{private
static$vars;final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
evaluate(){if(func_num_args()>1){self::$vars=func_get_arg(1);extract(self::$vars);}return
eval('?>'.func_get_arg(0));}static
function
load(){if(func_num_args()>1){self::$vars=func_get_arg(1);extract(self::$vars);}return include func_get_arg(0);}}abstract
class
AutoLoader
extends
Object{static
private$loaders=array();public
static$count=0;final
static
function
load($type){foreach(func_get_args()as$type){if(!class_exists($type)){throw
new
InvalidStateException("Unable to load class or interface '$type'.");}}}final
static
function
getLoaders(){return
array_values(self::$loaders);}function
register(){if(!function_exists('spl_autoload_register')){throw
new
RuntimeException('spl_autoload does not exist in this PHP installation.');}spl_autoload_register(array($this,'tryLoad'));self::$loaders[spl_object_hash($this)]=$this;}function
unregister(){unset(self::$loaders[spl_object_hash($this)]);return
spl_autoload_unregister(array($this,'tryLoad'));}abstract
function
tryLoad($type);}abstract
class
Component
extends
Object
implements
IComponent{private$parent;private$name;private$monitors=array();function
__construct(IComponentContainer$parent=NULL,$name=NULL){if($parent!==NULL){$parent->addComponent($this,$name);}elseif(is_string($name)){$this->name=$name;}}function
lookup($type,$need=TRUE){if($a=strrpos($type,'\\'))$type=substr($type,$a+1);if(!isset($this->monitors[$type])){$obj=$this->parent;$path=self::NAME_SEPARATOR.$this->name;$depth=1;while($obj!==NULL){if($obj
instanceof$type)break;$path=self::NAME_SEPARATOR.$obj->getName().$path;$depth++;$obj=$obj->getParent();if($obj===$this)$obj=NULL;}if($obj){$this->monitors[$type]=array($obj,$depth,substr($path,1),FALSE);}else{$this->monitors[$type]=array(NULL,NULL,NULL,FALSE);}}if($need&&$this->monitors[$type][0]===NULL){throw
new
InvalidStateException("Component '$this->name' is not attached to '$type'.");}return$this->monitors[$type][0];}function
lookupPath($type,$need=TRUE){if($a=strrpos($type,'\\'))$type=substr($type,$a+1);$this->lookup($type,$need);return$this->monitors[$type][2];}function
monitor($type){if($a=strrpos($type,'\\'))$type=substr($type,$a+1);if(empty($this->monitors[$type][3])){if($obj=$this->lookup($type,FALSE)){$this->attached($obj);}$this->monitors[$type][3]=TRUE;}}function
unmonitor($type){if($a=strrpos($type,'\\'))$type=substr($type,$a+1);unset($this->monitors[$type]);}protected
function
attached($obj){}protected
function
detached($obj){}final
function
getName(){return$this->name;}final
function
getParent(){return$this->parent;}function
setParent(IComponentContainer$parent=NULL,$name=NULL){if($parent===NULL&&$this->parent===NULL&&$name!==NULL){$this->name=$name;return$this;}elseif($parent===$this->parent&&$name===NULL){return$this;}if($this->parent!==NULL&&$parent!==NULL){throw
new
InvalidStateException("Component '$this->name' already has a parent.");}if($parent===NULL){$this->refreshMonitors(0);$this->parent=NULL;}else{$this->validateParent($parent);$this->parent=$parent;if($name!==NULL)$this->name=$name;$tmp=array();$this->refreshMonitors(0,$tmp);}return$this;}protected
function
validateParent(IComponentContainer$parent){}private
function
refreshMonitors($depth,&$missing=NULL,&$listeners=array()){if($this
instanceof
IComponentContainer){foreach($this->getComponents()as$component){if($component
instanceof
Component){$component->refreshMonitors($depth+1,$missing,$listeners);}}}if($missing===NULL){foreach($this->monitors
as$type=>$rec){if(isset($rec[1])&&$rec[1]>$depth){if($rec[3]){$this->monitors[$type]=array(NULL,NULL,NULL,TRUE);$listeners[]=array($this,$rec[0]);}else{unset($this->monitors[$type]);}}}}else{foreach($this->monitors
as$type=>$rec){if(isset($rec[0])){continue;}elseif(!$rec[3]){unset($this->monitors[$type]);}elseif(isset($missing[$type])){$this->monitors[$type]=array(NULL,NULL,NULL,TRUE);}else{$this->monitors[$type]=NULL;if($obj=$this->lookup($type,FALSE)){$listeners[]=array($this,$obj);}else{$missing[$type]=TRUE;}$this->monitors[$type][3]=TRUE;}}}if($depth===0){$method=$missing===NULL?'detached':'attached';foreach($listeners
as$item){$item[0]->$method($item[1]);}}}function
__clone(){if($this->parent===NULL){return;}elseif($this->parent
instanceof
ComponentContainer){$this->parent=$this->parent->_isCloning();if($this->parent===NULL){$this->refreshMonitors(0);}}else{$this->parent=NULL;$this->refreshMonitors(0);}}final
function
__wakeup(){throw
new
NotImplementedException;}}class
ComponentContainer
extends
Component
implements
IComponentContainer{private$components=array();private$cloning;function
addComponent(IComponent$component,$name,$insertBefore=NULL){if($name===NULL){$name=$component->getName();}if(is_int($name)){$name=(string)$name;}elseif(!is_string($name)){throw
new
InvalidArgumentException("Component name must be integer or string, ".gettype($name)." given.");}elseif(!preg_match('#^[a-zA-Z0-9_]+$#',$name)){throw
new
InvalidArgumentException("Component name must be non-empty alphanumeric string, '$name' given.");}if(isset($this->components[$name])){throw
new
InvalidStateException("Component with name '$name' already exists.");}$obj=$this;do{if($obj===$component){throw
new
InvalidStateException("Circular reference detected while adding component '$name'.");}$obj=$obj->getParent();}while($obj!==NULL);$this->validateChildComponent($component);try{if(isset($this->components[$insertBefore])){$tmp=array();foreach($this->components
as$k=>$v){if($k===$insertBefore)$tmp[$name]=$component;$tmp[$k]=$v;}$this->components=$tmp;}else{$this->components[$name]=$component;}$component->setParent($this,$name);}catch(Exception$e){unset($this->components[$name]);throw$e;}}function
removeComponent(IComponent$component){$name=$component->getName();if(!isset($this->components[$name])||$this->components[$name]!==$component){throw
new
InvalidArgumentException("Component named '$name' is not located in this container.");}unset($this->components[$name]);$component->setParent(NULL);}final
function
getComponent($name,$need=TRUE){if(is_int($name)){$name=(string)$name;}elseif(!is_string($name)){throw
new
InvalidArgumentException("Component name must be integer or string, ".gettype($name)." given.");}else{$a=strpos($name,self::NAME_SEPARATOR);if($a!==FALSE){$ext=(string)substr($name,$a+1);$name=substr($name,0,$a);}if($name===''){throw
new
InvalidArgumentException("Component or subcomponent name must not be empty string.");}}if(!isset($this->components[$name])){$component=$this->createComponent($name);if($component
instanceof
IComponent&&$component->getParent()===NULL){$this->addComponent($component,$name);}}if(isset($this->components[$name])){if(!isset($ext)){return$this->components[$name];}elseif($this->components[$name]instanceof
IComponentContainer){return$this->components[$name]->getComponent($ext,$need);}elseif($need){throw
new
InvalidArgumentException("Component with name '$name' is not container and cannot have '$ext' component.");}}elseif($need){throw
new
InvalidArgumentException("Component with name '$name' does not exist.");}}protected
function
createComponent($name){$ucname=ucfirst($name);$method='createComponent'.$ucname;if($ucname!==$name&&method_exists($this,$method)&&$this->getReflection()->getMethod($method)->getName()===$method){return$this->$method($name);}}final
function
getComponents($deep=FALSE,$filterType=NULL){$iterator=new
RecursiveComponentIterator($this->components);if($deep){$deep=$deep>0?RecursiveIteratorIterator::SELF_FIRST:RecursiveIteratorIterator::CHILD_FIRST;$iterator=new
RecursiveIteratorIterator($iterator,$deep);}if($filterType){if($a=strrpos($filterType,'\\'))$filterType=substr($filterType,$a+1);$iterator=new
InstanceFilterIterator($iterator,$filterType);}return$iterator;}protected
function
validateChildComponent(IComponent$child){}function
__clone(){if($this->components){$oldMyself=reset($this->components)->getParent();$oldMyself->cloning=$this;foreach($this->components
as$name=>$component){$this->components[$name]=clone$component;}$oldMyself->cloning=NULL;}parent::__clone();}function
_isCloning(){return$this->cloning;}}class
RecursiveComponentIterator
extends
RecursiveArrayIterator
implements
Countable{function
hasChildren(){return$this->current()instanceof
IComponentContainer;}function
getChildren(){return$this->current()->getComponents();}function
count(){return
iterator_count($this);}}class
FormContainer
extends
ComponentContainer
implements
ArrayAccess,INamingContainer{public$onValidate;protected$currentGroup;protected$valid;function
setDefaults($values,$erase=FALSE){$form=$this->getForm(FALSE);if(!$form||!$form->isAnchored()||!$form->isSubmitted()){$this->setValues($values,$erase);}return$this;}function
setValues($values,$erase=FALSE){if($values
instanceof
Traversable){$values=iterator_to_array($values);}elseif(!is_array($values)){throw
new
InvalidArgumentException("Values must be an array, ".gettype($values)." given.");}$cursor=&$values;$iterator=$this->getComponents(TRUE);foreach($iterator
as$name=>$control){$sub=$iterator->getSubIterator();if(!isset($sub->cursor)){$sub->cursor=&$cursor;}if($control
instanceof
IFormControl){if((is_array($sub->cursor)||$sub->cursor
instanceof
ArrayAccess)&&array_key_exists($name,$sub->cursor)){$control->setValue($sub->cursor[$name]);}elseif($erase){$control->setValue(NULL);}}if($control
instanceof
INamingContainer){if((is_array($sub->cursor)||$sub->cursor
instanceof
ArrayAccess)&&isset($sub->cursor[$name])){$cursor=&$sub->cursor[$name];}else{unset($cursor);$cursor=NULL;}}}return$this;}function
getValues(){$values=array();$cursor=&$values;$iterator=$this->getComponents(TRUE);foreach($iterator
as$name=>$control){$sub=$iterator->getSubIterator();if(!isset($sub->cursor)){$sub->cursor=&$cursor;}if($control
instanceof
IFormControl&&!$control->isDisabled()&&!($control
instanceof
ISubmitterControl)){$sub->cursor[$name]=$control->getValue();}if($control
instanceof
INamingContainer){$cursor=&$sub->cursor[$name];$cursor=array();}}return$values;}function
isValid(){if($this->valid===NULL){$this->validate();}return$this->valid;}function
validate(){$this->valid=TRUE;$this->onValidate($this);foreach($this->getControls()as$control){if(!$control->getRules()->validate()){$this->valid=FALSE;}}}function
setCurrentGroup(FormGroup$group=NULL){$this->currentGroup=$group;return$this;}function
getCurrentGroup(){return$this->currentGroup;}function
addComponent(IComponent$component,$name,$insertBefore=NULL){parent::addComponent($component,$name,$insertBefore);if($this->currentGroup!==NULL&&$component
instanceof
IFormControl){$this->currentGroup->add($component);}}function
getControls(){return$this->getComponents(TRUE,'IFormControl');}function
getForm($need=TRUE){return$this->lookup('Form',$need);}function
addText($name,$label=NULL,$cols=NULL,$maxLength=NULL){return$this[$name]=new
TextInput($label,$cols,$maxLength);}function
addPassword($name,$label=NULL,$cols=NULL,$maxLength=NULL){$control=new
TextInput($label,$cols,$maxLength);$control->setPasswordMode(TRUE);return$this[$name]=$control;}function
addTextArea($name,$label=NULL,$cols=40,$rows=10){return$this[$name]=new
TextArea($label,$cols,$rows);}function
addFile($name,$label=NULL){return$this[$name]=new
FileUpload($label);}function
addHidden($name,$default=NULL){$control=new
HiddenField;$control->setDefaultValue($default);return$this[$name]=$control;}function
addCheckbox($name,$caption=NULL){return$this[$name]=new
Checkbox($caption);}function
addRadioList($name,$label=NULL,array$items=NULL){return$this[$name]=new
RadioList($label,$items);}function
addSelect($name,$label=NULL,array$items=NULL,$size=NULL){return$this[$name]=new
SelectBox($label,$items,$size);}function
addMultiSelect($name,$label=NULL,array$items=NULL,$size=NULL){return$this[$name]=new
MultiSelectBox($label,$items,$size);}function
addSubmit($name,$caption=NULL){return$this[$name]=new
SubmitButton($caption);}function
addButton($name,$caption){return$this[$name]=new
Button($caption);}function
addImage($name,$src=NULL,$alt=NULL){return$this[$name]=new
ImageButton($src,$alt);}function
addContainer($name){$control=new
FormContainer;$control->currentGroup=$this->currentGroup;return$this[$name]=$control;}final
function
offsetSet($name,$component){$this->addComponent($component,$name);}final
function
offsetGet($name){return$this->getComponent($name,TRUE);}final
function
offsetExists($name){return$this->getComponent($name,FALSE)!==NULL;}final
function
offsetUnset($name){$component=$this->getComponent($name,FALSE);if($component!==NULL){$this->removeComponent($component);}}final
function
__clone(){throw
new
NotImplementedException('Form cloning is not supported yet.');}}class
Form
extends
FormContainer{const
EQUAL=':equal';const
IS_IN=':equal';const
FILLED=':filled';const
VALID=':valid';const
SUBMITTED=':submitted';const
MIN_LENGTH=':minLength';const
MAX_LENGTH=':maxLength';const
LENGTH=':length';const
EMAIL=':email';const
URL=':url';const
REGEXP=':regexp';const
INTEGER=':integer';const
NUMERIC=':integer';const
FLOAT=':float';const
RANGE=':range';const
MAX_FILE_SIZE=':fileSize';const
MIME_TYPE=':mimeType';const
IMAGE=':image';const
SCRIPT='Nette\\Forms\\InstantClientScript::javascript';const
GET='get';const
POST='post';const
TRACKER_ID='_form_';const
PROTECTOR_ID='_token_';public$onSubmit;public$onInvalidSubmit;private$submittedBy;private$httpData;private$element;private$renderer;private$translator;private$groups=array();private$errors=array();function
__construct($name=NULL){$this->element=Html::el('form');$this->element->action='';$this->element->method=self::POST;$this->element->id='frm-'.$name;$this->monitor(__CLASS__);if($name!==NULL){$tracker=new
HiddenField($name);$tracker->unmonitor(__CLASS__);$this[self::TRACKER_ID]=$tracker;}parent::__construct(NULL,$name);}protected
function
attached($obj){if($obj
instanceof
self){throw
new
InvalidStateException('Nested forms are forbidden.');}}final
function
getForm($need=TRUE){return$this;}function
setAction($url){$this->element->action=$url;return$this;}function
getAction(){return$this->element->action;}function
setMethod($method){if($this->httpData!==NULL){throw
new
InvalidStateException(__METHOD__.'() must be called until the form is empty.');}$this->element->method=strtolower($method);return$this;}function
getMethod(){return$this->element->method;}function
addProtection($message=NULL,$timeout=NULL){$session=$this->getSession()->getNamespace('Nette.Forms.Form/CSRF');$key="key$timeout";if(isset($session->$key)){$token=$session->$key;}else{$session->$key=$token=md5(uniqid('',TRUE));}$session->setExpiration($timeout,$key);$this[self::PROTECTOR_ID]=new
HiddenField($token);$this[self::PROTECTOR_ID]->addRule(':equal',empty($message)?'Security token did not match. Possible CSRF attack.':$message,$token);}function
addGroup($caption=NULL,$setAsCurrent=TRUE){$group=new
FormGroup;$group->setOption('label',$caption);$group->setOption('visual',TRUE);if($setAsCurrent){$this->setCurrentGroup($group);}if(isset($this->groups[$caption])){return$this->groups[]=$group;}else{return$this->groups[$caption]=$group;}}function
removeGroup($name){if(is_string($name)&&isset($this->groups[$name])){$group=$this->groups[$name];}elseif($name
instanceof
FormGroup&&in_array($name,$this->groups,TRUE)){$group=$name;$name=array_search($group,$this->groups,TRUE);}else{throw
new
InvalidArgumentException("Group not found in form '$this->name'");}foreach($group->getControls()as$control){$this->removeComponent($control);}unset($this->groups[$name]);}function
getGroups(){return$this->groups;}function
getGroup($name){return
isset($this->groups[$name])?$this->groups[$name]:NULL;}function
setTranslator(ITranslator$translator=NULL){$this->translator=$translator;return$this;}final
function
getTranslator(){return$this->translator;}function
isAnchored(){return
TRUE;}final
function
isSubmitted(){if($this->submittedBy===NULL){$this->getHttpData();$this->submittedBy=!empty($this->httpData);}return$this->submittedBy;}function
setSubmittedBy(ISubmitterControl$by=NULL){$this->submittedBy=$by===NULL?FALSE:$by;return$this;}final
function
getHttpData(){if($this->httpData===NULL){if(!$this->isAnchored()){throw
new
InvalidStateException('Form is not anchored and therefore can not determine whether it was submitted.');}$this->httpData=(array)$this->receiveHttpData();}return$this->httpData;}function
fireEvents(){if(!$this->isSubmitted()){return;}elseif($this->submittedBy
instanceof
ISubmitterControl){if(!$this->submittedBy->getValidationScope()||$this->isValid()){$this->submittedBy->click();$this->onSubmit($this);}else{$this->submittedBy->onInvalidClick($this->submittedBy);$this->onInvalidSubmit($this);}}elseif($this->isValid()){$this->onSubmit($this);}else{$this->onInvalidSubmit($this);}}protected
function
receiveHttpData(){$httpRequest=$this->getHttpRequest();if(strcasecmp($this->getMethod(),$httpRequest->getMethod())){return;}$httpRequest->setEncoding('utf-8');if($httpRequest->isMethod('post')){$data=ArrayTools::mergeTree($httpRequest->getPost(),$httpRequest->getFiles());}else{$data=$httpRequest->getQuery();}if($tracker=$this->getComponent(self::TRACKER_ID,FALSE)){if(!isset($data[self::TRACKER_ID])||$data[self::TRACKER_ID]!==$tracker->getValue()){return;}}return$data;}function
getValues(){$values=parent::getValues();unset($values[self::TRACKER_ID],$values[self::PROTECTOR_ID]);return$values;}function
addError($message){$this->valid=FALSE;if($message!==NULL&&!in_array($message,$this->errors,TRUE)){$this->errors[]=$message;}}function
getErrors(){return$this->errors;}function
hasErrors(){return(bool)$this->getErrors();}function
cleanErrors(){$this->errors=array();$this->valid=NULL;}function
getElementPrototype(){return$this->element;}function
setRenderer(IFormRenderer$renderer){$this->renderer=$renderer;return$this;}final
function
getRenderer(){if($this->renderer===NULL){$this->renderer=new
ConventionalRenderer;}return$this->renderer;}function
render(){$args=func_get_args();array_unshift($args,$this);echo
call_user_func_array(array($this->getRenderer(),'render'),$args);}function
__toString(){try{return$this->getRenderer()->render($this);}catch(Exception$e){if(func_get_args()&&func_get_arg(0)){throw$e;}else{Debug::toStringException($e);}}}protected
function
getHttpRequest(){return
class_exists('Environment')?Environment::getHttpRequest():new
HttpRequest;}protected
function
getSession(){return
Environment::getSession();}}class
AppForm
extends
Form
implements
ISignalReceiver{function
__construct(IComponentContainer$parent=NULL,$name=NULL){parent::__construct();$this->monitor('Presenter');if($parent!==NULL){$parent->addComponent($this,$name);}}function
getPresenter($need=TRUE){return$this->lookup('Presenter',$need);}protected
function
attached($presenter){if($presenter
instanceof
Presenter){$name=$this->lookupPath('Presenter');if(!isset($this->getElementPrototype()->id)){$this->getElementPrototype()->id='frm-'.$name;}$this->setAction(new
Link($presenter,$name.self::NAME_SEPARATOR.'submit!',array()));if($this->isSubmitted()){foreach($this->getControls()as$control){$control->loadHttpData();}}}parent::attached($presenter);}function
isAnchored(){return(bool)$this->getPresenter(FALSE);}protected
function
receiveHttpData(){$presenter=$this->getPresenter();if(!$presenter->isSignalReceiver($this,'submit')){return;}$isPost=$this->getMethod()===self::POST;$request=$presenter->getRequest();if($request->isMethod('forward')||$request->isMethod('post')!==$isPost){return;}if($isPost){return
ArrayTools::mergeTree($request->getPost(),$request->getFiles());}else{return$request->getParams();}}function
signalReceived($signal){if($signal==='submit'){$this->fireEvents();}else{throw
new
BadSignalException("There is no handler for signal '$signal' in {$this->reflection->name}.");}}}class
Application
extends
Object{public
static$maxLoop=20;public$defaultServices=array('Nette\\Application\\IRouter'=>'MultiRouter','Nette\\Application\\IPresenterLoader'=>array(__CLASS__,'createPresenterLoader'));public$catchExceptions;public$errorPresenter;public$onStartup;public$onShutdown;public$onRequest;public$onError;public$allowedMethods=array('GET','POST','HEAD','PUT','DELETE');private$requests=array();private$presenter;private$serviceLocator;function
run(){$httpRequest=$this->getHttpRequest();$httpResponse=$this->getHttpResponse();$httpRequest->setEncoding('UTF-8');if(Environment::getVariable('baseUri')===NULL){Environment::setVariable('baseUri',$httpRequest->getUri()->getBasePath());}$session=$this->getSession();if(!$session->isStarted()&&$session->exists()){$session->start();}Debug::addPanel(new
RoutingDebugger($this->getRouter(),$httpRequest));if($this->allowedMethods){$method=$httpRequest->getMethod();if(!in_array($method,$this->allowedMethods,TRUE)){$httpResponse->setCode(IHttpResponse::S501_NOT_IMPLEMENTED);$httpResponse->setHeader('Allow',implode(',',$this->allowedMethods));echo'<h1>Method '.htmlSpecialChars($method).' is not implemented</h1>';return;}}$request=NULL;$repeatedError=FALSE;do{try{if(count($this->requests)>self::$maxLoop){throw
new
ApplicationException('Too many loops detected in application life cycle.');}if(!$request){$this->onStartup($this);$router=$this->getRouter();if($router
instanceof
MultiRouter&&!count($router)){$router[]=new
SimpleRouter(array('presenter'=>'Default','action'=>'default'));}$request=$router->match($httpRequest);if(!($request
instanceof
PresenterRequest)){$request=NULL;throw
new
BadRequestException('No route for HTTP request.');}if(strcasecmp($request->getPresenterName(),$this->errorPresenter)===0){throw
new
BadRequestException('Invalid request.');}}$this->requests[]=$request;$this->onRequest($this,$request);$presenter=$request->getPresenterName();try{$class=$this->getPresenterLoader()->getPresenterClass($presenter);$request->setPresenterName($presenter);}catch(InvalidPresenterException$e){throw
new
BadRequestException($e->getMessage(),404,$e);}$request->freeze();$this->presenter=new$class;$response=$this->presenter->run($request);if($response
instanceof
ForwardingResponse){$request=$response->getRequest();continue;}elseif($response
instanceof
IPresenterResponse){$response->send();}break;}catch(Exception$e){if($this->catchExceptions===NULL){$this->catchExceptions=Environment::isProduction();}$this->onError($this,$e);if(!$this->catchExceptions){$this->onShutdown($this,$e);throw$e;}if($repeatedError){$e=new
ApplicationException('An error occured while executing error-presenter',0,$e);}if(!$httpResponse->isSent()){$httpResponse->setCode($e
instanceof
BadRequestException?$e->getCode():500);}if(!$repeatedError&&$this->errorPresenter){$repeatedError=TRUE;$request=new
PresenterRequest($this->errorPresenter,PresenterRequest::FORWARD,array('exception'=>$e));}else{echo"<meta name='robots' content='noindex'>\n\n";if($e
instanceof
BadRequestException){echo"<title>404 Not Found</title>\n\n<h1>Not Found</h1>\n\n<p>The requested URL was not found on this server.</p>";}else{Debug::processException($e,FALSE);echo"<title>500 Internal Server Error</title>\n\n<h1>Server Error</h1>\n\n","<p>The server encountered an internal error and was unable to complete your request. Please try again later.</p>";}echo"\n\n<hr>\n<small><i>Nette Framework</i></small>";break;}}}while(1);$this->onShutdown($this,isset($e)?$e:NULL);}final
function
getRequests(){return$this->requests;}final
function
getPresenter(){return$this->presenter;}final
function
getServiceLocator(){if($this->serviceLocator===NULL){$this->serviceLocator=new
ServiceLocator(Environment::getServiceLocator());foreach($this->defaultServices
as$name=>$service){if(!$this->serviceLocator->hasService($name)){$this->serviceLocator->addService($name,$service);}}}return$this->serviceLocator;}final
function
getService($name,array$options=NULL){return$this->getServiceLocator()->getService($name,$options);}function
getRouter(){return$this->getServiceLocator()->getService('Nette\\Application\\IRouter');}function
setRouter(IRouter$router){$this->getServiceLocator()->addService('Nette\\Application\\IRouter',$router);return$this;}function
getPresenterLoader(){return$this->getServiceLocator()->getService('Nette\\Application\\IPresenterLoader');}static
function
createPresenterLoader(){return
new
PresenterLoader(Environment::getVariable('appDir'));}function
storeRequest($expiration='+ 10 minutes'){$session=$this->getSession('Nette.Application/requests');do{$key=substr(md5(lcg_value()),0,4);}while(isset($session[$key]));$session[$key]=end($this->requests);$session->setExpiration($expiration,$key);return$key;}function
restoreRequest($key){$session=$this->getSession('Nette.Application/requests');if(isset($session[$key])){$request=clone$session[$key];unset($session[$key]);$request->setFlag(PresenterRequest::RESTORED,TRUE);$this->presenter->terminate(new
ForwardingResponse($request));}}protected
function
getHttpRequest(){return
Environment::getHttpRequest();}protected
function
getHttpResponse(){return
Environment::getHttpResponse();}protected
function
getSession($namespace=NULL){return
Environment::getSession($namespace);}}abstract
class
PresenterComponent
extends
ComponentContainer
implements
ISignalReceiver,IStatePersistent,ArrayAccess{protected$params=array();function
__construct(IComponentContainer$parent=NULL,$name=NULL){$this->monitor('Presenter');parent::__construct($parent,$name);}function
getPresenter($need=TRUE){return$this->lookup('Presenter',$need);}function
getUniqueId(){return$this->lookupPath('Presenter',TRUE);}protected
function
attached($presenter){if($presenter
instanceof
Presenter){$this->loadState($presenter->popGlobalParams($this->getUniqueId()));}}protected
function
tryCall($method,array$params){$rc=$this->getReflection();if($rc->hasMethod($method)){$rm=$rc->getMethod($method);if($rm->isPublic()&&!$rm->isAbstract()&&!$rm->isStatic()){$rm->invokeNamedArgs($this,$params);return
TRUE;}}return
FALSE;}function
getReflection(){return
new
PresenterComponentReflection($this);}function
loadState(array$params){foreach($this->getReflection()->getPersistentParams()as$nm=>$meta){if(isset($params[$nm])){if(isset($meta['def'])){if(is_array($params[$nm])&&!is_array($meta['def'])){$params[$nm]=$meta['def'];}else{settype($params[$nm],gettype($meta['def']));}}$this->$nm=&$params[$nm];}}$this->params=$params;}function
saveState(array&$params,$reflection=NULL){$reflection=$reflection===NULL?$this->getReflection():$reflection;foreach($reflection->getPersistentParams()as$nm=>$meta){if(isset($params[$nm])){$val=$params[$nm];}elseif(array_key_exists($nm,$params)){continue;}elseif(!isset($meta['since'])||$this
instanceof$meta['since']){$val=$this->$nm;}else{continue;}if(is_object($val)){throw
new
InvalidStateException("Persistent parameter must be scalar or array, {$this->reflection->name}::\$$nm is ".gettype($val));}else{if(isset($meta['def'])){settype($val,gettype($meta['def']));if($val===$meta['def'])$val=NULL;}else{if((string)$val==='')$val=NULL;}$params[$nm]=$val;}}}final
function
getParam($name=NULL,$default=NULL){if(func_num_args()===0){return$this->params;}elseif(isset($this->params[$name])){return$this->params[$name];}else{return$default;}}final
function
getParamId($name){$uid=$this->getUniqueId();return$uid===''?$name:$uid.self::NAME_SEPARATOR.$name;}static
function
getPersistentParams(){$rc=new
ClassReflection(func_get_arg(0));$params=array();foreach($rc->getProperties(ReflectionProperty::IS_PUBLIC)as$rp){if(!$rp->isStatic()&&$rp->hasAnnotation('persistent')){$params[]=$rp->getName();}}return$params;}function
signalReceived($signal){if(!$this->tryCall($this->formatSignalMethod($signal),$this->params)){throw
new
BadSignalException("There is no handler for signal '$signal' in {$this->reflection->name} class.");}}function
formatSignalMethod($signal){return$signal==NULL?NULL:'handle'.$signal;}function
link($destination,$args=array()){if(!is_array($args)){$args=func_get_args();array_shift($args);}try{return$this->getPresenter()->createRequest($this,$destination,$args,'link');}catch(InvalidLinkException$e){return$this->getPresenter()->handleInvalidLink($e);}}function
lazyLink($destination,$args=array()){if(!is_array($args)){$args=func_get_args();array_shift($args);}return
new
Link($this,$destination,$args);}function
redirect($code,$destination=NULL,$args=array()){if(!is_numeric($code)){$args=$destination;$destination=$code;$code=NULL;}if(!is_array($args)){$args=func_get_args();if(is_numeric(array_shift($args)))array_shift($args);}$presenter=$this->getPresenter();$presenter->redirectUri($presenter->createRequest($this,$destination,$args,'redirect'),$code);}final
function
offsetSet($name,$component){$this->addComponent($component,$name);}final
function
offsetGet($name){return$this->getComponent($name,TRUE);}final
function
offsetExists($name){return$this->getComponent($name,FALSE)!==NULL;}final
function
offsetUnset($name){$component=$this->getComponent($name,FALSE);if($component!==NULL){$this->removeComponent($component);}}}abstract
class
Control
extends
PresenterComponent
implements
IPartiallyRenderable{private$template;private$invalidSnippets=array();final
function
getTemplate(){if($this->template===NULL){$value=$this->createTemplate();if(!($value
instanceof
ITemplate||$value===NULL)){$class=get_class($value);throw
new
UnexpectedValueException("Object returned by {$this->reflection->name}::createTemplate() must be instance of Nette\\Templates\\ITemplate, '$class' given.");}$this->template=$value;}return$this->template;}protected
function
createTemplate(){$template=new
Template;$presenter=$this->getPresenter(FALSE);$template->onPrepareFilters[]=callback($this,'templatePrepareFilters');$template->component=$this;$template->control=$this;$template->presenter=$presenter;$template->baseUri=Environment::getVariable('baseUri');$template->basePath=rtrim($template->baseUri,'/');if($presenter!==NULL&&$presenter->hasFlashSession()){$id=$this->getParamId('flash');$template->flashes=$presenter->getFlashSession()->$id;}if(!isset($template->flashes)||!is_array($template->flashes)){$template->flashes=array();}$template->registerHelper('escape','TemplateHelpers::escapeHtml');$template->registerHelper('escapeUrl','rawurlencode');$template->registerHelper('stripTags','strip_tags');$template->registerHelper('nl2br','nl2br');$template->registerHelper('substr','iconv_substr');$template->registerHelper('repeat','str_repeat');$template->registerHelper('replaceRE','String::replace');$template->registerHelper('implode','implode');$template->registerHelper('number','number_format');$template->registerHelperLoader('TemplateHelpers::loader');return$template;}function
templatePrepareFilters($template){$template->registerFilter(new
LatteFilter);}function
getWidget($name){return$this->getComponent($name);}function
flashMessage($message,$type='info'){$id=$this->getParamId('flash');$messages=$this->getPresenter()->getFlashSession()->$id;$messages[]=$flash=(object)array('message'=>$message,'type'=>$type);$this->getTemplate()->flashes=$messages;$this->getPresenter()->getFlashSession()->$id=$messages;return$flash;}function
invalidateControl($snippet=NULL){$this->invalidSnippets[$snippet]=TRUE;}function
validateControl($snippet=NULL){if($snippet===NULL){$this->invalidSnippets=array();}else{unset($this->invalidSnippets[$snippet]);}}function
isControlInvalid($snippet=NULL){if($snippet===NULL){if(count($this->invalidSnippets)>0){return
TRUE;}else{foreach($this->getComponents()as$component){if($component
instanceof
IRenderable&&$component->isControlInvalid()){return
TRUE;}}return
FALSE;}}else{return
isset($this->invalidSnippets[NULL])||isset($this->invalidSnippets[$snippet]);}}function
getSnippetId($name=NULL){return'snippet-'.$this->getUniqueId().'-'.$name;}}class
AbortException
extends
Exception{}class
ApplicationException
extends
Exception{function
__construct($message='',$code=0,Exception$previous=NULL){if(PHP_VERSION_ID<50300){$this->previous=$previous;parent::__construct($message,$code);}else{parent::__construct($message,$code,$previous);}}}class
BadRequestException
extends
Exception{protected$defaultCode=404;function
__construct($message='',$code=0,Exception$previous=NULL){if($code<200||$code>504){$code=$this->defaultCode;}if(PHP_VERSION_ID<50300){$this->previous=$previous;parent::__construct($message,$code);}else{parent::__construct($message,$code,$previous);}}}class
BadSignalException
extends
BadRequestException{protected$defaultCode=403;}class
ForbiddenRequestException
extends
BadRequestException{protected$defaultCode=403;}class
InvalidLinkException
extends
Exception{}class
InvalidPresenterException
extends
InvalidLinkException{}class
Link
extends
Object{private$component;private$destination;private$params;function
__construct(PresenterComponent$component,$destination,array$params){$this->component=$component;$this->destination=$destination;$this->params=$params;}function
getDestination(){return$this->destination;}function
setParam($key,$value){$this->params[$key]=$value;return$this;}function
getParam($key){return
isset($this->params[$key])?$this->params[$key]:NULL;}function
getParams(){return$this->params;}function
__toString(){try{return$this->component->link($this->destination,$this->params);}catch(Exception$e){Debug::toStringException($e);}}}abstract
class
Presenter
extends
Control
implements
IPresenter{const
INVALID_LINK_SILENT=1;const
INVALID_LINK_WARNING=2;const
INVALID_LINK_EXCEPTION=3;const
SIGNAL_KEY='do';const
ACTION_KEY='action';const
FLASH_KEY='_fid';public
static$defaultAction='default';public
static$invalidLinkMode;public$onShutdown;private$request;private$response;public$autoCanonicalize=TRUE;public$absoluteUrls=FALSE;private$globalParams;private$globalState;private$globalStateSinces;private$action;private$view;private$layout;private$payload;private$signalReceiver;private$signal;private$ajaxMode;private$startupCheck;private$lastCreatedRequest;private$lastCreatedRequestFlag;final
function
getRequest(){return$this->request;}final
function
getPresenter($need=TRUE){return$this;}final
function
getUniqueId(){return'';}function
run(PresenterRequest$request){try{$this->request=$request;$this->payload=(object)NULL;$this->setParent($this->getParent(),$request->getPresenterName());$this->initGlobalParams();$this->startup();if(!$this->startupCheck){$class=$this->reflection->getMethod('startup')->getDeclaringClass()->getName();throw
new
InvalidStateException("Method $class::startup() or its descendant doesn't call parent::startup().");}$this->tryCall($this->formatActionMethod($this->getAction()),$this->params);if($this->autoCanonicalize){$this->canonicalize();}if($this->getHttpRequest()->isMethod('head')){$this->terminate();}$this->processSignal();$this->beforeRender();$this->tryCall($this->formatRenderMethod($this->getView()),$this->params);$this->afterRender();$this->saveGlobalState();if($this->isAjax()){$this->payload->state=$this->getGlobalState();}$this->sendTemplate();}catch(AbortException$e){}{if($this->isAjax())try{$hasPayload=(array)$this->payload;unset($hasPayload['state']);if($this->response
instanceof
RenderResponse&&($this->isControlInvalid()||$hasPayload)){SnippetHelper::$outputAllowed=FALSE;$this->response->send();$this->sendPayload();}elseif(!$this->response&&$hasPayload){$this->sendPayload();}}catch(AbortException$e){}if($this->hasFlashSession()){$this->getFlashSession()->setExpiration($this->response
instanceof
RedirectingResponse?'+ 30 seconds':'+ 3 seconds');}$this->onShutdown($this,$this->response);$this->shutdown($this->response);return$this->response;}}protected
function
startup(){$this->startupCheck=TRUE;}protected
function
beforeRender(){}protected
function
afterRender(){}protected
function
shutdown($response){}function
processSignal(){if($this->signal===NULL)return;$component=$this->signalReceiver===''?$this:$this->getComponent($this->signalReceiver,FALSE);if($component===NULL){throw
new
BadSignalException("The signal receiver component '$this->signalReceiver' is not found.");}elseif(!$component
instanceof
ISignalReceiver){throw
new
BadSignalException("The signal receiver component '$this->signalReceiver' is not ISignalReceiver implementor.");}$component->signalReceived($this->signal);$this->signal=NULL;}final
function
getSignal(){return$this->signal===NULL?NULL:array($this->signalReceiver,$this->signal);}final
function
isSignalReceiver($component,$signal=NULL){if($component
instanceof
Component){$component=$component===$this?'':$component->lookupPath(__CLASS__,TRUE);}if($this->signal===NULL){return
FALSE;}elseif($signal===TRUE){return$component===''||strncmp($this->signalReceiver.'-',$component.'-',strlen($component)+1)===0;}elseif($signal===NULL){return$this->signalReceiver===$component;}else{return$this->signalReceiver===$component&&strcasecmp($signal,$this->signal)===0;}}final
function
getAction($fullyQualified=FALSE){return$fullyQualified?':'.$this->getName().':'.$this->action:$this->action;}function
changeAction($action){if(String::match($action,"#^[a-zA-Z0-9][a-zA-Z0-9_\x7f-\xff]*$#")){$this->action=$action;$this->view=$action;}else{throw
new
BadRequestException("Action name '$action' is not alphanumeric string.");}}final
function
getView(){return$this->view;}function
setView($view){$this->view=(string)$view;return$this;}final
function
getLayout(){return$this->layout;}function
setLayout($layout){$this->layout=$layout===FALSE?FALSE:(string)$layout;return$this;}function
sendTemplate(){$template=$this->getTemplate();if(!$template)return;if($template
instanceof
IFileTemplate&&!$template->getFile()){$files=$this->formatTemplateFiles($this->getName(),$this->view);foreach($files
as$file){if(is_file($file)){$template->setFile($file);break;}}if(!$template->getFile()){$file=str_replace(Environment::getVariable('appDir'),"\xE2\x80\xA6",reset($files));throw
new
BadRequestException("Page not found. Missing template '$file'.");}if($this->layout!==FALSE){$files=$this->formatLayoutTemplateFiles($this->getName(),$this->layout?$this->layout:'layout');foreach($files
as$file){if(is_file($file)){$template->layout=$file;$template->_extends=$file;break;}}if(empty($template->layout)&&$this->layout!==NULL){$file=str_replace(Environment::getVariable('appDir'),"\xE2\x80\xA6",reset($files));throw
new
FileNotFoundException("Layout not found. Missing template '$file'.");}}}$this->terminate(new
RenderResponse($template));}function
formatLayoutTemplateFiles($presenter,$layout){$appDir=Environment::getVariable('appDir');$path='/'.str_replace(':','Module/',$presenter);$pathP=substr_replace($path,'/templates',strrpos($path,'/'),0);$list=array("$appDir$pathP/@$layout.phtml","$appDir$pathP.@$layout.phtml");while(($path=substr($path,0,strrpos($path,'/')))!==FALSE){$list[]="$appDir$path/templates/@$layout.phtml";}return$list;}function
formatTemplateFiles($presenter,$view){$appDir=Environment::getVariable('appDir');$path='/'.str_replace(':','Module/',$presenter);$pathP=substr_replace($path,'/templates',strrpos($path,'/'),0);$path=substr_replace($path,'/templates',strrpos($path,'/'));return
array("$appDir$pathP/$view.phtml","$appDir$pathP.$view.phtml","$appDir$path/@global.$view.phtml");}protected
static
function
formatActionMethod($action){return'action'.$action;}protected
static
function
formatRenderMethod($view){return'render'.$view;}final
function
getPayload(){return$this->payload;}function
isAjax(){if($this->ajaxMode===NULL){$this->ajaxMode=$this->getHttpRequest()->isAjax();}return$this->ajaxMode;}function
sendPayload(){$this->terminate(new
JsonResponse($this->payload));}function
forward($destination,$args=array()){if($destination
instanceof
PresenterRequest){$this->terminate(new
ForwardingResponse($destination));}elseif(!is_array($args)){$args=func_get_args();array_shift($args);}$this->createRequest($this,$destination,$args,'forward');$this->terminate(new
ForwardingResponse($this->lastCreatedRequest));}function
redirectUri($uri,$code=NULL){if($this->isAjax()){$this->payload->redirect=(string)$uri;$this->sendPayload();}elseif(!$code){$code=$this->getHttpRequest()->isMethod('post')?IHttpResponse::S303_POST_GET:IHttpResponse::S302_FOUND;}$this->terminate(new
RedirectingResponse($uri,$code));}function
backlink(){return$this->getAction(TRUE);}function
getLastCreatedRequest(){return$this->lastCreatedRequest;}function
getLastCreatedRequestFlag($flag){return!empty($this->lastCreatedRequestFlag[$flag]);}function
terminate(IPresenterResponse$response=NULL){$this->response=$response;throw
new
AbortException();}function
canonicalize(){if(!$this->isAjax()&&($this->request->isMethod('get')||$this->request->isMethod('head'))){$uri=$this->createRequest($this,$this->action,$this->getGlobalState()+$this->request->params,'redirectX');if($uri!==NULL&&!$this->getHttpRequest()->getUri()->isEqual($uri)){$this->terminate(new
RedirectingResponse($uri,IHttpResponse::S301_MOVED_PERMANENTLY));}}}function
lastModified($lastModified,$etag=NULL,$expire=NULL){if(!Environment::isProduction()){return;}if($expire!==NULL){$this->getHttpResponse()->setExpiration($expire);}if(!$this->getHttpContext()->isModified($lastModified,$etag)){$this->terminate();}}final
protected
function
createRequest($component,$destination,array$args,$mode){static$presenterLoader,$router,$httpRequest;if($presenterLoader===NULL){$presenterLoader=$this->getApplication()->getPresenterLoader();$router=$this->getApplication()->getRouter();$httpRequest=$this->getHttpRequest();}$this->lastCreatedRequest=$this->lastCreatedRequestFlag=NULL;$a=strpos($destination,'#');if($a===FALSE){$fragment='';}else{$fragment=substr($destination,$a);$destination=substr($destination,0,$a);}$a=strpos($destination,'?');if($a!==FALSE){parse_str(substr($destination,$a+1),$args);$destination=substr($destination,0,$a);}$a=strpos($destination,'//');if($a===FALSE){$scheme=FALSE;}else{$scheme=substr($destination,0,$a);$destination=substr($destination,$a+2);}if(!($component
instanceof
Presenter)||substr($destination,-1)==='!'){$signal=rtrim($destination,'!');$a=strrpos($signal,':');if($a!==FALSE){$component=$component->getComponent(strtr(substr($signal,0,$a),':','-'));$signal=(string)substr($signal,$a+1);}if($signal==NULL){throw
new
InvalidLinkException("Signal must be non-empty string.");}$destination='this';}if($destination==NULL){throw
new
InvalidLinkException("Destination must be non-empty string.");}$current=FALSE;$a=strrpos($destination,':');if($a===FALSE){$action=$destination==='this'?$this->action:$destination;$presenter=$this->getName();$presenterClass=get_class($this);}else{$action=(string)substr($destination,$a+1);if($destination[0]===':'){if($a<2){throw
new
InvalidLinkException("Missing presenter name in '$destination'.");}$presenter=substr($destination,1,$a-1);}else{$presenter=$this->getName();$b=strrpos($presenter,':');if($b===FALSE){$presenter=substr($destination,0,$a);}else{$presenter=substr($presenter,0,$b+1).substr($destination,0,$a);}}$presenterClass=$presenterLoader->getPresenterClass($presenter);}if(isset($signal)){$reflection=new
PresenterComponentReflection(get_class($component));if($signal==='this'){$signal='';if(array_key_exists(0,$args)){throw
new
InvalidLinkException("Extra parameter for signal '{$reflection->name}:$signal!'.");}}elseif(strpos($signal,self::NAME_SEPARATOR)===FALSE){$method=$component->formatSignalMethod($signal);if(!$reflection->hasCallableMethod($method)){throw
new
InvalidLinkException("Unknown signal '{$reflection->name}:$signal!'.");}if($args){self::argsToParams(get_class($component),$method,$args);}}if($args&&array_intersect_key($args,$reflection->getPersistentParams())){$component->saveState($args);}if($args&&$component!==$this){$prefix=$component->getUniqueId().self::NAME_SEPARATOR;foreach($args
as$key=>$val){unset($args[$key]);$args[$prefix.$key]=$val;}}}if(is_subclass_of($presenterClass,__CLASS__)){if($action===''){$action=self::$defaultAction;}$current=($action==='*'||$action===$this->action)&&$presenterClass===get_class($this);$reflection=new
PresenterComponentReflection($presenterClass);if($args||$destination==='this'){$method=call_user_func(array($presenterClass,'formatActionMethod'),$action);if(!$reflection->hasCallableMethod($method)){$method=call_user_func(array($presenterClass,'formatRenderMethod'),$action);if(!$reflection->hasCallableMethod($method)){$method=NULL;}}if($method===NULL){if(array_key_exists(0,$args)){throw
new
InvalidLinkException("Extra parameter for '$presenter:$action'.");}}elseif($destination==='this'){self::argsToParams($presenterClass,$method,$args,$this->params);}else{self::argsToParams($presenterClass,$method,$args);}}if($args&&array_intersect_key($args,$reflection->getPersistentParams())){$this->saveState($args,$reflection);}$globalState=$this->getGlobalState($destination==='this'?NULL:$presenterClass);if($current&&$args){$tmp=$globalState+$this->params;foreach($args
as$key=>$val){if((string)$val!==(isset($tmp[$key])?(string)$tmp[$key]:'')){$current=FALSE;break;}}}$args+=$globalState;}$args[self::ACTION_KEY]=$action;if(!empty($signal)){$args[self::SIGNAL_KEY]=$component->getParamId($signal);$current=$current&&$args[self::SIGNAL_KEY]===$this->getParam(self::SIGNAL_KEY);}if(($mode==='redirect'||$mode==='forward')&&$this->hasFlashSession()){$args[self::FLASH_KEY]=$this->getParam(self::FLASH_KEY);}$this->lastCreatedRequest=new
PresenterRequest($presenter,PresenterRequest::FORWARD,$args,array(),array());$this->lastCreatedRequestFlag=array('current'=>$current);if($mode==='forward')return;$uri=$router->constructUrl($this->lastCreatedRequest,$httpRequest);if($uri===NULL){unset($args[self::ACTION_KEY]);$params=urldecode(http_build_query($args,NULL,', '));throw
new
InvalidLinkException("No route for $presenter:$action($params)");}if($mode==='link'&&$scheme===FALSE&&!$this->absoluteUrls){$hostUri=$httpRequest->getUri()->getHostUri();if(strncmp($uri,$hostUri,strlen($hostUri))===0){$uri=substr($uri,strlen($hostUri));}}return$uri.$fragment;}private
static
function
argsToParams($class,$method,&$args,$supplemental=array()){static$cache;$params=&$cache[strtolower($class.':'.$method)];if($params===NULL){$params=MethodReflection::from($class,$method)->getDefaultParameters();}$i=0;foreach($params
as$name=>$def){if(array_key_exists($i,$args)){$args[$name]=$args[$i];unset($args[$i]);$i++;}elseif(array_key_exists($name,$args)){}elseif(array_key_exists($name,$supplemental)){$args[$name]=$supplemental[$name];}else{continue;}if($def===NULL){if((string)$args[$name]==='')$args[$name]=NULL;}else{settype($args[$name],gettype($def));if($args[$name]===$def)$args[$name]=NULL;}}if(array_key_exists($i,$args)){throw
new
InvalidLinkException("Extra parameter for signal '$class:$method'.");}}protected
function
handleInvalidLink($e){if(self::$invalidLinkMode===NULL){self::$invalidLinkMode=Environment::isProduction()?self::INVALID_LINK_SILENT:self::INVALID_LINK_WARNING;}if(self::$invalidLinkMode===self::INVALID_LINK_SILENT){return'#';}elseif(self::$invalidLinkMode===self::INVALID_LINK_WARNING){return'error: '.$e->getMessage();}else{throw$e;}}static
function
getPersistentComponents(){return(array)ClassReflection::from(func_get_arg(0))->getAnnotation('persistent');}private
function
getGlobalState($forClass=NULL){$sinces=&$this->globalStateSinces;if($this->globalState===NULL){$state=array();foreach($this->globalParams
as$id=>$params){$prefix=$id.self::NAME_SEPARATOR;foreach($params
as$key=>$val){$state[$prefix.$key]=$val;}}$this->saveState($state,$forClass?new
PresenterComponentReflection($forClass):NULL);if($sinces===NULL){$sinces=array();foreach($this->getReflection()->getPersistentParams()as$nm=>$meta){$sinces[$nm]=$meta['since'];}}$components=$this->getReflection()->getPersistentComponents();$iterator=$this->getComponents(TRUE,'IStatePersistent');foreach($iterator
as$name=>$component){if($iterator->getDepth()===0){$since=isset($components[$name]['since'])?$components[$name]['since']:FALSE;}$prefix=$component->getUniqueId().self::NAME_SEPARATOR;$params=array();$component->saveState($params);foreach($params
as$key=>$val){$state[$prefix.$key]=$val;$sinces[$prefix.$key]=$since;}}}else{$state=$this->globalState;}if($forClass!==NULL){$since=NULL;foreach($state
as$key=>$foo){if(!isset($sinces[$key])){$x=strpos($key,self::NAME_SEPARATOR);$x=$x===FALSE?$key:substr($key,0,$x);$sinces[$key]=isset($sinces[$x])?$sinces[$x]:FALSE;}if($since!==$sinces[$key]){$since=$sinces[$key];$ok=$since&&(is_subclass_of($forClass,$since)||$forClass===$since);}if(!$ok){unset($state[$key]);}}}return$state;}protected
function
saveGlobalState(){foreach($this->globalParams
as$id=>$foo){$this->getComponent($id,FALSE);}$this->globalParams=array();$this->globalState=$this->getGlobalState();}private
function
initGlobalParams(){$this->globalParams=array();$selfParams=array();$params=$this->request->getParams();if($this->isAjax()){$params=$this->request->getPost()+$params;}foreach($params
as$key=>$value){$a=strlen($key)>2?strrpos($key,self::NAME_SEPARATOR,-2):FALSE;if($a===FALSE){$selfParams[$key]=$value;}else{$this->globalParams[substr($key,0,$a)][substr($key,$a+1)]=$value;}}$this->changeAction(isset($selfParams[self::ACTION_KEY])?$selfParams[self::ACTION_KEY]:self::$defaultAction);$this->signalReceiver=$this->getUniqueId();if(!empty($selfParams[self::SIGNAL_KEY])){$param=$selfParams[self::SIGNAL_KEY];$pos=strrpos($param,'-');if($pos){$this->signalReceiver=substr($param,0,$pos);$this->signal=substr($param,$pos+1);}else{$this->signalReceiver=$this->getUniqueId();$this->signal=$param;}if($this->signal==NULL){$this->signal=NULL;}}$this->loadState($selfParams);}final
function
popGlobalParams($id){if(isset($this->globalParams[$id])){$res=$this->globalParams[$id];unset($this->globalParams[$id]);return$res;}else{return
array();}}function
hasFlashSession(){return!empty($this->params[self::FLASH_KEY])&&$this->getSession()->hasNamespace('Nette.Application.Flash/'.$this->params[self::FLASH_KEY]);}function
getFlashSession(){if(empty($this->params[self::FLASH_KEY])){$this->params[self::FLASH_KEY]=substr(md5(lcg_value()),0,4);}return$this->getSession('Nette.Application.Flash/'.$this->params[self::FLASH_KEY]);}protected
function
getHttpRequest(){return
Environment::getHttpRequest();}protected
function
getHttpResponse(){return
Environment::getHttpResponse();}protected
function
getHttpContext(){return
Environment::getHttpContext();}function
getApplication(){return
Environment::getApplication();}protected
function
getSession($namespace=NULL){return
Environment::getSession($namespace);}protected
function
getUser(){return
Environment::getUser();}}class
ClassReflection
extends
ReflectionClass{private
static$extMethods;static
function
from($class){return
new
self($class);}function
__toString(){return'Class '.$this->getName();}function
hasEventProperty($name){if(preg_match('#^on[A-Z]#',$name)&&$this->hasProperty($name)){$rp=$this->getProperty($name);return$rp->isPublic()&&!$rp->isStatic();}return
FALSE;}function
setExtensionMethod($name,$callback){$l=&self::$extMethods[strtolower($name)];$l[strtolower($this->getName())]=callback($callback);$l['']=NULL;return$this;}function
getExtensionMethod($name){if(self::$extMethods===NULL||$name===NULL){$list=get_defined_functions();foreach($list['user']as$fce){$pair=explode('_prototype_',$fce);if(count($pair)===2){self::$extMethods[$pair[1]][$pair[0]]=callback($fce);self::$extMethods[$pair[1]]['']=NULL;}}if($name===NULL)return
NULL;}$class=strtolower($this->getName());$l=&self::$extMethods[strtolower($name)];if(empty($l)){return
FALSE;}elseif(isset($l[''][$class])){return$l[''][$class];}$cl=$class;do{if(isset($l[$cl])){return$l[''][$class]=$l[$cl];}}while(($cl=strtolower(get_parent_class($cl)))!=='');foreach(class_implements($class)as$cl){$cl=strtolower($cl);if(isset($l[$cl])){return$l[''][$class]=$l[$cl];}}return$l[''][$class]=FALSE;}static
function
import(ReflectionClass$ref){return
new
self($ref->getName());}function
getConstructor(){return($ref=parent::getConstructor())?MethodReflection::import($ref):NULL;}function
getExtension(){return($ref=parent::getExtension())?ExtensionReflection::import($ref):NULL;}function
getInterfaces(){return
array_map(array('ClassReflection','import'),parent::getInterfaces());}function
getMethod($name){return
MethodReflection::import(parent::getMethod($name));}function
getMethods($filter=-1){return
array_map(array('MethodReflection','import'),parent::getMethods($filter));}function
getParentClass(){return($ref=parent::getParentClass())?self::import($ref):NULL;}function
getProperties($filter=-1){return
array_map(array('PropertyReflection','import'),parent::getProperties($filter));}function
getProperty($name){return
PropertyReflection::import(parent::getProperty($name));}function
hasAnnotation($name){$res=AnnotationsParser::getAll($this);return!empty($res[$name]);}function
getAnnotation($name){$res=AnnotationsParser::getAll($this);return
isset($res[$name])?end($res[$name]):NULL;}function
getAnnotations(){return
AnnotationsParser::getAll($this);}function
getReflection(){return
new
ClassReflection($this);}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}class
PresenterComponentReflection
extends
ClassReflection{private
static$ppCache=array();private
static$pcCache=array();private
static$mcCache=array();function
getPersistentParams($class=NULL){$class=$class===NULL?$this->getName():$class;$params=&self::$ppCache[$class];if($params!==NULL)return$params;$params=array();if(is_subclass_of($class,'PresenterComponent')){$defaults=get_class_vars($class);foreach(call_user_func(array($class,'getPersistentParams'),$class)as$name=>$meta){if(is_string($meta))$name=$meta;$params[$name]=array('def'=>$defaults[$name],'since'=>$class);}$params=$this->getPersistentParams(get_parent_class($class))+$params;}return$params;}function
getPersistentComponents(){$class=$this->getName();$components=&self::$pcCache[$class];if($components!==NULL)return$components;$components=array();if(is_subclass_of($class,'Presenter')){foreach(call_user_func(array($class,'getPersistentComponents'),$class)as$name=>$meta){if(is_string($meta))$name=$meta;$components[$name]=array('since'=>$class);}$components=self::getPersistentComponents(get_parent_class($class))+$components;}return$components;}function
hasCallableMethod($method){$class=$this->getName();$cache=&self::$mcCache[strtolower($class.':'.$method)];if($cache===NULL)try{$cache=FALSE;$rm=MethodReflection::from($class,$method);$cache=$this->isInstantiable()&&$rm->isPublic()&&!$rm->isAbstract()&&!$rm->isStatic();}catch(ReflectionException$e){}return$cache;}}class
PresenterLoader
implements
IPresenterLoader{public$caseSensitive=FALSE;private$baseDir;private$cache=array();function
__construct($baseDir){$this->baseDir=$baseDir;}function
getPresenterClass(&$name){if(isset($this->cache[$name])){list($class,$name)=$this->cache[$name];return$class;}if(!is_string($name)||!String::match($name,"#^[a-zA-Z\x7f-\xff][a-zA-Z0-9\x7f-\xff:]*$#")){throw
new
InvalidPresenterException("Presenter name must be alphanumeric string, '$name' is invalid.");}$class=$this->formatPresenterClass($name);if(!class_exists($class)){$file=$this->formatPresenterFile($name);if(is_file($file)&&is_readable($file)){LimitedScope::load($file);}if(!class_exists($class)){throw
new
InvalidPresenterException("Cannot load presenter '$name', class '$class' was not found in '$file'.");}}$reflection=new
ClassReflection($class);$class=$reflection->getName();if(!$reflection->implementsInterface('IPresenter')){throw
new
InvalidPresenterException("Cannot load presenter '$name', class '$class' is not Nette\\Application\\IPresenter implementor.");}if($reflection->isAbstract()){throw
new
InvalidPresenterException("Cannot load presenter '$name', class '$class' is abstract.");}$realName=$this->unformatPresenterClass($class);if($name!==$realName){if($this->caseSensitive){throw
new
InvalidPresenterException("Cannot load presenter '$name', case mismatch. Real name is '$realName'.");}else{$this->cache[$name]=array($class,$realName);$name=$realName;}}else{$this->cache[$name]=array($class,$realName);}return$class;}function
formatPresenterClass($presenter){return
strtr($presenter,':','_').'Presenter';return
str_replace(':','Module\\',$presenter).'Presenter';}function
unformatPresenterClass($class){return
strtr(substr($class,0,-9),'_',':');return
str_replace('Module\\',':',substr($class,0,-9));}function
formatPresenterFile($presenter){$path='/'.str_replace(':','Module/',$presenter);return$this->baseDir.substr_replace($path,'/presenters',strrpos($path,'/'),0).'Presenter.php';}}abstract
class
FreezableObject
extends
Object{private$frozen=FALSE;function
freeze(){$this->frozen=TRUE;}final
function
isFrozen(){return$this->frozen;}function
__clone(){$this->frozen=FALSE;}protected
function
updating(){if($this->frozen){throw
new
InvalidStateException("Cannot modify a frozen object {$this->reflection->name}.");}}}final
class
PresenterRequest
extends
FreezableObject{const
FORWARD='FORWARD';const
SECURED='secured';const
RESTORED='restored';private$method;private$flags=array();private$name;private$params;private$post;private$files;function
__construct($name,$method,array$params,array$post=array(),array$files=array(),array$flags=array()){$this->name=$name;$this->method=$method;$this->params=$params;$this->post=$post;$this->files=$files;$this->flags=$flags;}function
setPresenterName($name){$this->updating();$this->name=$name;return$this;}function
getPresenterName(){return$this->name;}function
setParams(array$params){$this->updating();$this->params=$params;return$this;}function
getParams(){return$this->params;}function
setPost(array$params){$this->updating();$this->post=$params;return$this;}function
getPost(){return$this->post;}function
setFiles(array$files){$this->updating();$this->files=$files;return$this;}function
getFiles(){return$this->files;}function
setMethod($method){$this->method=$method;return$this;}function
getMethod(){return$this->method;}function
isMethod($method){return
strcasecmp($this->method,$method)===0;}function
isPost(){return
strcasecmp($this->method,'post')===0;}function
setFlag($flag,$value=TRUE){$this->updating();$this->flags[$flag]=(bool)$value;return$this;}function
hasFlag($flag){return!empty($this->flags[$flag]);}}class
DownloadResponse
extends
Object
implements
IPresenterResponse{private$file;private$contentType;private$name;function
__construct($file,$name=NULL,$contentType=NULL){if(!is_file($file)){throw
new
BadRequestException("File '$file' doesn't exist.");}$this->file=$file;$this->name=$name?$name:basename($file);$this->contentType=$contentType?$contentType:'application/octet-stream';}final
function
getFile(){return$this->file;}final
function
getName(){return$this->name;}final
function
getContentType(){return$this->contentType;}function
send(){Environment::getHttpResponse()->setContentType($this->contentType);Environment::getHttpResponse()->setHeader('Content-Disposition','attachment; filename="'.$this->name.'"');readfile($this->file);}}class
ForwardingResponse
extends
Object
implements
IPresenterResponse{private$request;function
__construct(PresenterRequest$request){$this->request=$request;}final
function
getRequest(){return$this->request;}function
send(){}}class
JsonResponse
extends
Object
implements
IPresenterResponse{private$payload;private$contentType;function
__construct($payload,$contentType=NULL){if(!is_array($payload)&&!($payload
instanceof
stdClass)){throw
new
InvalidArgumentException("Payload must be array or anonymous class, ".gettype($payload)." given.");}$this->payload=$payload;$this->contentType=$contentType?$contentType:'application/json';}final
function
getPayload(){return$this->payload;}function
send(){Environment::getHttpResponse()->setContentType($this->contentType);Environment::getHttpResponse()->setExpiration(FALSE);echo
json_encode($this->payload);}}class
RedirectingResponse
extends
Object
implements
IPresenterResponse{private$uri;private$code;function
__construct($uri,$code=IHttpResponse::S302_FOUND){$this->uri=(string)$uri;$this->code=(int)$code;}final
function
getUri(){return$this->uri;}final
function
getCode(){return$this->code;}function
send(){Environment::getHttpResponse()->redirect($this->uri,$this->code);}}class
RenderResponse
extends
Object
implements
IPresenterResponse{private$source;function
__construct($source){$this->source=$source;}final
function
getSource(){return$this->source;}function
send(){if($this->source
instanceof
ITemplate){$this->source->render();}else{echo$this->source;}}}class
CliRouter
extends
Object
implements
IRouter{const
PRESENTER_KEY='action';private$defaults;function
__construct($defaults=array()){$this->defaults=$defaults;}function
match(IHttpRequest$httpRequest){if(empty($_SERVER['argv'])||!is_array($_SERVER['argv'])){return
NULL;}$names=array(self::PRESENTER_KEY);$params=$this->defaults;$args=$_SERVER['argv'];array_shift($args);$args[]='--';foreach($args
as$arg){$opt=preg_replace('#/|-+#A','',$arg);if($opt===$arg){if(isset($flag)||$flag=array_shift($names)){$params[$flag]=$arg;}else{$params[]=$arg;}$flag=NULL;continue;}if(isset($flag)){$params[$flag]=TRUE;$flag=NULL;}if($opt!==''){$pair=explode('=',$opt,2);if(isset($pair[1])){$params[$pair[0]]=$pair[1];}else{$flag=$pair[0];}}}if(!isset($params[self::PRESENTER_KEY])){throw
new
InvalidStateException('Missing presenter & action in route definition.');}$presenter=$params[self::PRESENTER_KEY];if($a=strrpos($presenter,':')){$params[self::PRESENTER_KEY]=substr($presenter,$a+1);$presenter=substr($presenter,0,$a);}return
new
PresenterRequest($presenter,'CLI',$params);}function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){return
NULL;}function
getDefaults(){return$this->defaults;}}class
MultiRouter
extends
Object
implements
IRouter,ArrayAccess,Countable,IteratorAggregate{private$routes;private$cachedRoutes;function
__construct(){$this->routes=new
ArrayList;}function
match(IHttpRequest$httpRequest){foreach($this->routes
as$route){$appRequest=$route->match($httpRequest);if($appRequest!==NULL){return$appRequest;}}return
NULL;}function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){if($this->cachedRoutes===NULL){$routes=array();$routes['*']=array();foreach($this->routes
as$route){$presenter=$route
instanceof
Route?$route->getTargetPresenter():NULL;if($presenter===FALSE)continue;if(is_string($presenter)){$presenter=strtolower($presenter);if(!isset($routes[$presenter])){$routes[$presenter]=$routes['*'];}$routes[$presenter][]=$route;}else{foreach($routes
as$id=>$foo){$routes[$id][]=$route;}}}$this->cachedRoutes=$routes;}$presenter=strtolower($appRequest->getPresenterName());if(!isset($this->cachedRoutes[$presenter]))$presenter='*';foreach($this->cachedRoutes[$presenter]as$route){$uri=$route->constructUrl($appRequest,$httpRequest);if($uri!==NULL){return$uri;}}return
NULL;}function
offsetSet($index,$route){if(!($route
instanceof
IRouter)){throw
new
InvalidArgumentException("Argument must be IRouter descendant.");}$this->routes[$index]=$route;}function
offsetGet($index){return$this->routes[$index];}function
offsetExists($index){return
isset($this->routes[$index]);}function
offsetUnset($index){unset($this->routes[$index]);}function
getIterator(){return$this->routes;}function
count(){return
count($this->routes);}}class
Route
extends
Object
implements
IRouter{const
PRESENTER_KEY='presenter';const
MODULE_KEY='module';const
CASE_SENSITIVE=256;const
HOST=1;const
PATH=2;const
RELATIVE=3;const
VALUE='value';const
PATTERN='pattern';const
FILTER_IN='filterIn';const
FILTER_OUT='filterOut';const
FILTER_TABLE='filterTable';const
OPTIONAL=0;const
PATH_OPTIONAL=1;const
CONSTANT=2;public
static$defaultFlags=0;public
static$styles=array('#'=>array(self::PATTERN=>'[^/]+',self::FILTER_IN=>'rawurldecode',self::FILTER_OUT=>'rawurlencode'),'?#'=>array(),'module'=>array(self::PATTERN=>'[a-z][a-z0-9.-]*',self::FILTER_IN=>array(__CLASS__,'path2presenter'),self::FILTER_OUT=>array(__CLASS__,'presenter2path')),'presenter'=>array(self::PATTERN=>'[a-z][a-z0-9.-]*',self::FILTER_IN=>array(__CLASS__,'path2presenter'),self::FILTER_OUT=>array(__CLASS__,'presenter2path')),'action'=>array(self::PATTERN=>'[a-z][a-z0-9-]*',self::FILTER_IN=>array(__CLASS__,'path2action'),self::FILTER_OUT=>array(__CLASS__,'action2path')),'?module'=>array(),'?presenter'=>array(),'?action'=>array());private$mask;private$sequence;private$re;private$metadata=array();private$xlat;private$type;private$flags;function
__construct($mask,array$metadata=array(),$flags=0){$this->flags=$flags|self::$defaultFlags;$this->setMask($mask,$metadata);}function
match(IHttpRequest$httpRequest){$uri=$httpRequest->getUri();if($this->type===self::HOST){$path='//'.$uri->getHost().$uri->getPath();}elseif($this->type===self::RELATIVE){$basePath=$uri->getBasePath();if(strncmp($uri->getPath(),$basePath,strlen($basePath))!==0){return
NULL;}$path=(string)substr($uri->getPath(),strlen($basePath));}else{$path=$uri->getPath();}if($path!==''){$path=rtrim($path,'/').'/';}if(!$matches=String::match($path,$this->re)){return
NULL;}$params=array();foreach($matches
as$k=>$v){if(is_string($k)&&$v!==''){$params[str_replace('___','-',$k)]=$v;}}foreach($this->metadata
as$name=>$meta){if(isset($params[$name])){}elseif(isset($meta['fixity'])&&$meta['fixity']!==self::OPTIONAL){$params[$name]=NULL;}}if($this->xlat){$params+=self::renameKeys($httpRequest->getQuery(),array_flip($this->xlat));}else{$params+=$httpRequest->getQuery();}foreach($this->metadata
as$name=>$meta){if(isset($params[$name])){if(!is_scalar($params[$name])){}elseif(isset($meta[self::FILTER_TABLE][$params[$name]])){$params[$name]=$meta[self::FILTER_TABLE][$params[$name]];}elseif(isset($meta[self::FILTER_IN])){$params[$name]=call_user_func($meta[self::FILTER_IN],(string)$params[$name]);if($params[$name]===NULL&&!isset($meta['fixity'])){return
NULL;}}}elseif(isset($meta['fixity'])){$params[$name]=$meta[self::VALUE];}}if(!isset($params[self::PRESENTER_KEY])){throw
new
InvalidStateException('Missing presenter in route definition.');}if(isset($this->metadata[self::MODULE_KEY])){if(!isset($params[self::MODULE_KEY])){throw
new
InvalidStateException('Missing module in route definition.');}$presenter=$params[self::MODULE_KEY].':'.$params[self::PRESENTER_KEY];unset($params[self::MODULE_KEY],$params[self::PRESENTER_KEY]);}else{$presenter=$params[self::PRESENTER_KEY];unset($params[self::PRESENTER_KEY]);}return
new
PresenterRequest($presenter,$httpRequest->getMethod(),$params,$httpRequest->getPost(),$httpRequest->getFiles(),array(PresenterRequest::SECURED=>$httpRequest->isSecured()));}function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){if($this->flags&self::ONE_WAY){return
NULL;}$params=$appRequest->getParams();$metadata=$this->metadata;$presenter=$appRequest->getPresenterName();$params[self::PRESENTER_KEY]=$presenter;if(isset($metadata[self::MODULE_KEY])){$module=$metadata[self::MODULE_KEY];if(isset($module['fixity'])&&strncasecmp($presenter,$module[self::VALUE].':',strlen($module[self::VALUE])+1)===0){$a=strlen($module[self::VALUE]);}else{$a=strrpos($presenter,':');}if($a===FALSE){$params[self::MODULE_KEY]='';}else{$params[self::MODULE_KEY]=substr($presenter,0,$a);$params[self::PRESENTER_KEY]=substr($presenter,$a+1);}}foreach($metadata
as$name=>$meta){if(!isset($params[$name]))continue;if(isset($meta['fixity'])){if(is_scalar($params[$name])&&strcasecmp($params[$name],$meta[self::VALUE])===0){unset($params[$name]);continue;}elseif($meta['fixity']===self::CONSTANT){return
NULL;}}if(!is_scalar($params[$name])){}elseif(isset($meta['filterTable2'][$params[$name]])){$params[$name]=$meta['filterTable2'][$params[$name]];}elseif(isset($meta[self::FILTER_OUT])){$params[$name]=call_user_func($meta[self::FILTER_OUT],$params[$name]);}if(isset($meta[self::PATTERN])&&!preg_match($meta[self::PATTERN],rawurldecode($params[$name]))){return
NULL;}}$sequence=$this->sequence;$brackets=array();$required=0;$uri='';$i=count($sequence)-1;do{$uri=$sequence[$i].$uri;if($i===0)break;$i--;$name=$sequence[$i];$i--;if($name===']'){$brackets[]=$uri;}elseif($name[0]==='['){$tmp=array_pop($brackets);if($required<count($brackets)+1){if($name!=='[!'){$uri=$tmp;}}else{$required=count($brackets);}}elseif($name[0]==='?'){continue;}elseif(isset($params[$name])&&$params[$name]!=''){$required=count($brackets);$uri=$params[$name].$uri;unset($params[$name]);}elseif(isset($metadata[$name]['fixity'])){$uri=$metadata[$name]['defOut'].$uri;}else{return
NULL;}}while(TRUE);if($this->xlat){$params=self::renameKeys($params,$this->xlat);}$sep=ini_get('arg_separator.input');$query=http_build_query($params,'',$sep?$sep[0]:'&');if($query!='')$uri.='?'.$query;if($this->type===self::RELATIVE){$uri='//'.$httpRequest->getUri()->getAuthority().$httpRequest->getUri()->getBasePath().$uri;}elseif($this->type===self::PATH){$uri='//'.$httpRequest->getUri()->getAuthority().$uri;}if(strpos($uri,'//',2)!==FALSE){return
NULL;}$uri=($this->flags&self::SECURED?'https:':'http:').$uri;return$uri;}private
function
setMask($mask,array$metadata){$this->mask=$mask;if(substr($mask,0,2)==='//'){$this->type=self::HOST;}elseif(substr($mask,0,1)==='/'){$this->type=self::PATH;}else{$this->type=self::RELATIVE;}foreach($metadata
as$name=>$meta){if(!is_array($meta)){$metadata[$name]=array(self::VALUE=>$meta,'fixity'=>self::CONSTANT);}elseif(array_key_exists(self::VALUE,$meta)){$metadata[$name]['fixity']=self::CONSTANT;}}$parts=String::split($mask,'/<([^># ]+) *([^>#]*)(#?[^>\[\]]*)>|(\[!?|\]|\s*\?.*)/');$this->xlat=array();$i=count($parts)-1;if(isset($parts[$i-1])&&substr(ltrim($parts[$i-1]),0,1)==='?'){$matches=String::matchAll($parts[$i-1],'/(?:([a-zA-Z0-9_.-]+)=)?<([^># ]+) *([^>#]*)(#?[^>]*)>/');foreach($matches
as$match){list(,$param,$name,$pattern,$class)=$match;if($class!==''){if(!isset(self::$styles[$class])){throw
new
InvalidStateException("Parameter '$name' has '$class' flag, but Route::\$styles['$class'] is not set.");}$meta=self::$styles[$class];}elseif(isset(self::$styles['?'.$name])){$meta=self::$styles['?'.$name];}else{$meta=self::$styles['?#'];}if(isset($metadata[$name])){$meta=$metadata[$name]+$meta;}if(array_key_exists(self::VALUE,$meta)){$meta['fixity']=self::OPTIONAL;}unset($meta['pattern']);$meta['filterTable2']=empty($meta[self::FILTER_TABLE])?NULL:array_flip($meta[self::FILTER_TABLE]);$metadata[$name]=$meta;if($param!==''){$this->xlat[$name]=$param;}}$i-=5;}$brackets=0;$re='';$sequence=array();$autoOptional=array(0,0);do{array_unshift($sequence,$parts[$i]);$re=preg_quote($parts[$i],'#').$re;if($i===0)break;$i--;$part=$parts[$i];if($part==='['||$part===']'||$part==='[!'){$brackets+=$part[0]==='['?-1:1;if($brackets<0){throw
new
InvalidArgumentException("Unexpected '$part' in mask '$mask'.");}array_unshift($sequence,$part);$re=($part[0]==='['?'(?:':')?').$re;$i-=4;continue;}$class=$parts[$i];$i--;$pattern=trim($parts[$i]);$i--;$name=$parts[$i];$i--;array_unshift($sequence,$name);if($name[0]==='?'){$re='(?:'.preg_quote(substr($name,1),'#').'|'.$pattern.')'.$re;$sequence[1]=substr($name,1).$sequence[1];continue;}if(preg_match('#[^a-z0-9_-]#i',$name)){throw
new
InvalidArgumentException("Parameter name must be alphanumeric string due to limitations of PCRE, '$name' given.");}if($class!==''){if(!isset(self::$styles[$class])){throw
new
InvalidStateException("Parameter '$name' has '$class' flag, but Route::\$styles['$class'] is not set.");}$meta=self::$styles[$class];}elseif(isset(self::$styles[$name])){$meta=self::$styles[$name];}else{$meta=self::$styles['#'];}if(isset($metadata[$name])){$meta=$metadata[$name]+$meta;}if($pattern==''&&isset($meta[self::PATTERN])){$pattern=$meta[self::PATTERN];}$meta['filterTable2']=empty($meta[self::FILTER_TABLE])?NULL:array_flip($meta[self::FILTER_TABLE]);if(array_key_exists(self::VALUE,$meta)){if(isset($meta['filterTable2'][$meta[self::VALUE]])){$meta['defOut']=$meta['filterTable2'][$meta[self::VALUE]];}elseif(isset($meta[self::FILTER_OUT])){$meta['defOut']=call_user_func($meta[self::FILTER_OUT],$meta[self::VALUE]);}else{$meta['defOut']=$meta[self::VALUE];}}$meta[self::PATTERN]="#(?:$pattern)$#A".($this->flags&self::CASE_SENSITIVE?'':'iu');$re='(?P<'.str_replace('-','___',$name).'>'.$pattern.')'.$re;if($brackets){if(!isset($meta[self::VALUE])){$meta[self::VALUE]=$meta['defOut']=NULL;}$meta['fixity']=self::PATH_OPTIONAL;}elseif(isset($meta['fixity'])){$re='(?:'.substr_replace($re,')?',strlen($re)-$autoOptional[0],0);array_splice($sequence,count($sequence)-$autoOptional[1],0,array(']',''));array_unshift($sequence,'[','');$meta['fixity']=self::PATH_OPTIONAL;}else{$autoOptional=array(strlen($re),count($sequence));}$metadata[$name]=$meta;}while(TRUE);if($brackets){throw
new
InvalidArgumentException("Missing closing ']' in mask '$mask'.");}$this->re='#'.$re.'/?$#A'.($this->flags&self::CASE_SENSITIVE?'':'iu');$this->metadata=$metadata;$this->sequence=$sequence;}function
getMask(){return$this->mask;}function
getDefaults(){$defaults=array();foreach($this->metadata
as$name=>$meta){if(isset($meta['fixity'])){$defaults[$name]=$meta[self::VALUE];}}return$defaults;}function
getTargetPresenter(){if($this->flags&self::ONE_WAY){return
FALSE;}$m=$this->metadata;$module='';if(isset($m[self::MODULE_KEY])){if(isset($m[self::MODULE_KEY]['fixity'])&&$m[self::MODULE_KEY]['fixity']===self::CONSTANT){$module=$m[self::MODULE_KEY][self::VALUE].':';}else{return
NULL;}}if(isset($m[self::PRESENTER_KEY]['fixity'])&&$m[self::PRESENTER_KEY]['fixity']===self::CONSTANT){return$module.$m[self::PRESENTER_KEY][self::VALUE];}return
NULL;}private
static
function
renameKeys($arr,$xlat){if(empty($xlat))return$arr;$res=array();$occupied=array_flip($xlat);foreach($arr
as$k=>$v){if(isset($xlat[$k])){$res[$xlat[$k]]=$v;}elseif(!isset($occupied[$k])){$res[$k]=$v;}}return$res;}private
static
function
action2path($s){$s=preg_replace('#(.)(?=[A-Z])#','$1-',$s);$s=strtolower($s);$s=rawurlencode($s);return$s;}private
static
function
path2action($s){$s=strtolower($s);$s=preg_replace('#-(?=[a-z])#',' ',$s);$s=substr(ucwords('x'.$s),1);$s=str_replace(' ','',$s);return$s;}private
static
function
presenter2path($s){$s=strtr($s,':','.');$s=preg_replace('#([^.])(?=[A-Z])#','$1-',$s);$s=strtolower($s);$s=rawurlencode($s);return$s;}private
static
function
path2presenter($s){$s=strtolower($s);$s=preg_replace('#([.-])(?=[a-z])#','$1 ',$s);$s=ucwords($s);$s=str_replace('. ',':',$s);$s=str_replace('- ','',$s);return$s;}static
function
addStyle($style,$parent='#'){if(isset(self::$styles[$style])){throw
new
InvalidArgumentException("Style '$style' already exists.");}if($parent!==NULL){if(!isset(self::$styles[$parent])){throw
new
InvalidArgumentException("Parent style '$parent' doesn't exist.");}self::$styles[$style]=self::$styles[$parent];}else{self::$styles[$style]=array();}}static
function
setStyleProperty($style,$key,$value){if(!isset(self::$styles[$style])){throw
new
InvalidArgumentException("Style '$style' doesn't exist.");}self::$styles[$style][$key]=$value;}}class
SimpleRouter
extends
Object
implements
IRouter{const
PRESENTER_KEY='presenter';const
MODULE_KEY='module';private$module='';private$defaults;private$flags;function
__construct($defaults=array(),$flags=0){if(is_string($defaults)){$a=strrpos($defaults,':');$defaults=array(self::PRESENTER_KEY=>substr($defaults,0,$a),'action'=>substr($defaults,$a+1));}if(isset($defaults[self::MODULE_KEY])){$this->module=$defaults[self::MODULE_KEY].':';unset($defaults[self::MODULE_KEY]);}$this->defaults=$defaults;$this->flags=$flags;}function
match(IHttpRequest$httpRequest){$params=$httpRequest->getQuery();$params+=$this->defaults;if(!isset($params[self::PRESENTER_KEY])){throw
new
InvalidStateException('Missing presenter.');}$presenter=$this->module.$params[self::PRESENTER_KEY];unset($params[self::PRESENTER_KEY]);return
new
PresenterRequest($presenter,$httpRequest->getMethod(),$params,$httpRequest->getPost(),$httpRequest->getFiles(),array(PresenterRequest::SECURED=>$httpRequest->isSecured()));}function
constructUrl(PresenterRequest$appRequest,IHttpRequest$httpRequest){$params=$appRequest->getParams();$presenter=$appRequest->getPresenterName();if(strncasecmp($presenter,$this->module,strlen($this->module))===0){$params[self::PRESENTER_KEY]=substr($presenter,strlen($this->module));}else{return
NULL;}foreach($this->defaults
as$key=>$value){if(isset($params[$key])&&$params[$key]==$value){unset($params[$key]);}}$uri=$httpRequest->getUri();$uri=($this->flags&self::SECURED?'https://':'http://').$uri->getAuthority().$uri->getScriptPath();$sep=ini_get('arg_separator.input');$query=http_build_query($params,'',$sep?$sep[0]:'&');if($query!=''){$uri.='?'.$query;}return$uri;}function
getDefaults(){return$this->defaults;}}class
DebugPanel
extends
Object
implements
IDebugPanel{private$id;private$tabCb;private$panelCb;function
__construct($id,$tabCb,$panelCb){$this->id=$id;$this->tabCb=$tabCb;$this->panelCb=$panelCb;}function
getId(){return$this->id;}function
getTab(){ob_start();call_user_func($this->tabCb,$this->id);return
ob_get_clean();}function
getPanel(){ob_start();call_user_func($this->panelCb,$this->id);return
ob_get_clean();}}class
RoutingDebugger
extends
DebugPanel{private$router;private$httpRequest;private$routers;private$request;function
__construct(IRouter$router,IHttpRequest$httpRequest){$this->router=$router;$this->httpRequest=$httpRequest;$this->routers=new
ArrayObject;parent::__construct('RoutingDebugger',array($this,'renderTab'),array($this,'renderPanel'));}function
renderTab(){$this->analyse($this->router);?>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJHSURBVDjLlZPNi81hFMc/z7137p1mTCFvNZfGSzLIWNjZKRvFRoqNhRCSYm8xS3+AxRRZ2JAFJWJHSQqTQkbEzYwIM+6Yid/znJfH4prLXShOnb6r8/nWOd8Tcs78bz0/f+KMu50y05nK/wy+uHDylbutqS5extvGcxaWqtoGDA8PZ3dnrs2srQc2Zko41UXLmLdyDW5OfvsUkUgbYGbU63UAQggdmvMzFmzZCgTi7CQmkZwdEaX0JwDgTnGbTCaE0G4zw80omhPI92lcEtkNkdgJCCHwJX7mZvNaB0A14SaYJlwTrpHsTkoFlV1nt2c3x5YYo1/vM9A/gKpxdfwyu/v3teCayKq4JEwT5EB2R6WgYmrs2bYbcUNNUVfEhIfFYy69uci+1fuRX84mkawFSxd/4nVWUopUVIykwlQxRTJBTIDA4Pp1jBZPuNW4wUAPmCqWIn29X1k4f5Ku8g9mpKCkakRLVEs1auVuauVuyqHMo8ejNCe+sWPVTkQKXCMmkeZUmUZjETF1tc6ooly+fgUVw9So1/tRN6YnZji46QghBFKKuAouERNhMlbAHZFE6e7pB+He8MMw+GGI4xtOMf1+lsl3TQ4NHf19BSlaO1DB9BfMHdX0O0iqSgiBbJkjm491hClJbA1LxCURgpPzXwAHhg63necAIi3XngXLcRU0fof8ETMljIyM5LGxMcbHxzvy/6fuXdWgt6+PWncv1e4euqo1ZmabvHs5+jn8yzufO7hiiZmuNpNBM13rbvVSpbrXJE7/BMkHtU9jFIC/AAAAAElFTkSuQmCC"
><?php if(empty($this->request)):?>no route<?php else:echo$this->request->getPresenterName().':'.(isset($this->request->params[Presenter::ACTION_KEY])?$this->request->params[Presenter::ACTION_KEY]:Presenter::$defaultAction);endif?>
<?php }function
renderPanel(){?>
<style>#nette-debug-RoutingDebugger table{font:9pt/1.5 Consolas,monospace}#nette-debug-RoutingDebugger .yes td{color:green}#nette-debug-RoutingDebugger .may td{color:#67F}#nette-debug-RoutingDebugger pre,#nette-debug-RoutingDebugger code{display:inline}</style>

<h1>
<?php if(empty($this->request)):?>
	no route
<?php else:?>
	<?php echo$this->request->getPresenterName().':'.(isset($this->request->params[Presenter::ACTION_KEY])?$this->request->params[Presenter::ACTION_KEY]:Presenter::$defaultAction)?>
<?php endif?>
</h1>

<?php if(!empty($this->request)):?>
	<?php $params=$this->request->getParams()?>
	<?php if(empty($params)):?>
		<p>No parameters.</p>

	<?php else:?>
		<table>
		<thead>
		<tr>
			<th>Parameter</th>
			<th>Value</th>
		</tr>
		</thead>
		<tbody>
		<?php unset($params[Presenter::ACTION_KEY])?>
		<?php foreach($params
as$key=>$value):?>
		<tr>
			<td><code><?php echo
htmlSpecialChars($key)?></code></td>
			<td><?php if(is_string($value)):?><code><?php echo
htmlSpecialChars($value)?></code><?php else:echo
Debug::dump($value,TRUE);endif?></td>
		</tr>
		<?php endforeach?>
		</tbody>
		</table>
	<?php endif?>
<?php endif?>

<h2>Routers</h2>

<?php if(empty($this->routers)):?>
	<p>No routers defined.</p>

<?php else:?>
	<div class="nette-inner">
	<table>
	<thead>
	<tr>
		<th>Matched?</th>
		<th>Class</th>
		<th>Mask</th>
		<th>Defaults</th>
		<th>Request</th>
	</tr>
	</thead>

	<tbody>
	<?php foreach($this->routers
as$router):?>
	<tr class="<?php echo$router['matched']?>">
		<td><?php echo$router['matched']?></td>

		<td><code><?php echo
htmlSpecialChars($router['class'])?></code></td>

		<td><code><strong><?php echo
htmlSpecialChars($router['mask'])?></strong></code></td>

		<td><code>
		<?php foreach($router['defaults']as$key=>$value):?>
			<?php echo
htmlSpecialChars($key),"&nbsp;=&nbsp;",is_string($value)?htmlSpecialChars($value):str_replace("\n</pre",'</pre',Debug::dump($value,TRUE))?><br>
		<?php endforeach?>
		</code></td>

		<td><?php if($router['request']):?><code>
		<?php $params=$router['request']->getParams();?>
		<strong><?php echo
htmlSpecialChars($router['request']->getPresenterName().':'.(isset($params[Presenter::ACTION_KEY])?$params[Presenter::ACTION_KEY]:Presenter::$defaultAction))?></strong><br>
		<?php unset($params[Presenter::ACTION_KEY])?>
		<?php foreach($params
as$key=>$value):?>
			<?php echo
htmlSpecialChars($key),"&nbsp;=&nbsp;",is_string($value)?htmlSpecialChars($value):str_replace("\n</pre",'</pre',Debug::dump($value,TRUE))?><br>
		<?php endforeach?>
		</code><?php endif?></td>
	</tr>
	<?php endforeach?>
	</tbody>
	</table>
	</div>
<?php endif?>
<?php }private
function
analyse($router){if($router
instanceof
MultiRouter){foreach($router
as$subRouter){$this->analyse($subRouter);}return;}$request=$router->match($this->httpRequest);$matched=$request===NULL?'no':'may';if($request!==NULL&&empty($this->request)){$this->request=$request;$matched='yes';}$this->routers[]=array('matched'=>$matched,'class'=>get_class($router),'defaults'=>$router
instanceof
Route||$router
instanceof
SimpleRouter?$router->getDefaults():array(),'mask'=>$router
instanceof
Route?$router->getMask():NULL,'request'=>$request);}}class
Cache
extends
Object
implements
ArrayAccess{const
PRIORITY='priority';const
EXPIRE='expire';const
SLIDING='sliding';const
TAGS='tags';const
FILES='files';const
ITEMS='items';const
CONSTS='consts';const
CALLBACKS='callbacks';const
ALL='all';const
NAMESPACE_SEPARATOR="\x00";private$storage;private$namespace;private$key;private$data;function
__construct(ICacheStorage$storage,$namespace=NULL){$this->storage=$storage;$this->namespace=(string)$namespace;if(strpos($this->namespace,self::NAMESPACE_SEPARATOR)!==FALSE){throw
new
InvalidArgumentException("Namespace name contains forbidden character.");}}function
getStorage(){return$this->storage;}function
getNamespace(){return$this->namespace;}function
release(){$this->key=$this->data=NULL;}function
save($key,$data,array$dp=NULL){if(!is_string($key)&&!is_int($key)){throw
new
InvalidArgumentException("Cache key name must be string or integer, ".gettype($key)." given.");}$this->key=(string)$key;$key=$this->namespace.self::NAMESPACE_SEPARATOR.$key;if(!empty($dp[Cache::EXPIRE])){$dp[Cache::EXPIRE]=Tools::createDateTime($dp[Cache::EXPIRE])->format('U')-time();}if(isset($dp[self::FILES])){foreach((array)$dp[self::FILES]as$item){$dp[self::CALLBACKS][]=array(array(__CLASS__,'checkFile'),$item,@filemtime($item));}unset($dp[self::FILES]);}if(isset($dp[self::ITEMS])){$dp[self::ITEMS]=(array)$dp[self::ITEMS];foreach($dp[self::ITEMS]as$k=>$item){$dp[self::ITEMS][$k]=$this->namespace.self::NAMESPACE_SEPARATOR.$item;}}if(isset($dp[self::CONSTS])){foreach((array)$dp[self::CONSTS]as$item){$dp[self::CALLBACKS][]=array(array(__CLASS__,'checkConst'),$item,constant($item));}unset($dp[self::CONSTS]);}if($data
instanceof
Callback||$data
instanceof
Closure){Environment::enterCriticalSection('Nette\Caching/'.$key);$data=$data->__invoke();Environment::leaveCriticalSection('Nette\Caching/'.$key);}if(is_object($data)){$dp[self::CALLBACKS][]=array(array(__CLASS__,'checkSerializationVersion'),get_class($data),ClassReflection::from($data)->getAnnotation('serializationVersion'));}$this->data=$data;if($data===NULL){$this->storage->remove($key);}else{$this->storage->write($key,$data,(array)$dp);}return$data;}function
clean(array$conds=NULL){$this->release();$this->storage->clean((array)$conds);}function
offsetSet($key,$data){$this->save($key,$data);}function
offsetGet($key){if(!is_string($key)&&!is_int($key)){throw
new
InvalidArgumentException("Cache key name must be string or integer, ".gettype($key)." given.");}$key=(string)$key;if($this->key===$key){return$this->data;}$this->key=$key;$this->data=$this->storage->read($this->namespace.self::NAMESPACE_SEPARATOR.$key);return$this->data;}function
offsetExists($key){return$this->offsetGet($key)!==NULL;}function
offsetUnset($key){$this->save($key,NULL);}static
function
checkCallbacks($callbacks){foreach($callbacks
as$callback){$func=array_shift($callback);if(!call_user_func_array($func,$callback)){return
FALSE;}}return
TRUE;}private
static
function
checkConst($const,$value){return
defined($const)&&constant($const)===$value;}private
static
function
checkFile($file,$time){return@filemtime($file)==$time;}private
static
function
checkSerializationVersion($class,$value){return
ClassReflection::from($class)->getAnnotation('serializationVersion')===$value;}}class
DummyStorage
extends
Object
implements
ICacheStorage{function
read($key){return
NULL;}function
write($key,$data,array$dp){}function
remove($key){}function
clean(array$conds){}}class
FileStorage
extends
Object
implements
ICacheStorage{const
META_HEADER_LEN=28;const
META_TIME='time';const
META_SERIALIZED='serialized';const
META_EXPIRE='expire';const
META_DELTA='delta';const
META_ITEMS='di';const
META_CALLBACKS='callbacks';const
FILE='file';const
HANDLE='handle';public
static$gcProbability=0.001;public
static$useDirectories;private$dir;private$useDirs;private$db;function
__construct($dir){if(self::$useDirectories===NULL){$uniq=uniqid('_',TRUE);umask(0000);if(!@mkdir("$dir/$uniq",0777)){throw
new
InvalidStateException("Unable to write to directory '$dir'. Make this directory writable.");}self::$useDirectories=!ini_get('safe_mode');if(!self::$useDirectories&&@file_put_contents("$dir/$uniq/_",'')!==FALSE){self::$useDirectories=TRUE;unlink("$dir/$uniq/_");}rmdir("$dir/$uniq");}$this->dir=$dir;$this->useDirs=(bool)self::$useDirectories;if(mt_rand()/mt_getrandmax()<self::$gcProbability){$this->clean(array());}}function
read($key){$meta=$this->readMeta($this->getCacheFile($key),LOCK_SH);if($meta&&$this->verify($meta)){return$this->readData($meta);}else{return
NULL;}}private
function
verify($meta){do{if(!empty($meta[self::META_DELTA])){if(filemtime($meta[self::FILE])+$meta[self::META_DELTA]<time())break;touch($meta[self::FILE]);}elseif(!empty($meta[self::META_EXPIRE])&&$meta[self::META_EXPIRE]<time()){break;}if(!empty($meta[self::META_CALLBACKS])&&!Cache::checkCallbacks($meta[self::META_CALLBACKS])){break;}if(!empty($meta[self::META_ITEMS])){foreach($meta[self::META_ITEMS]as$depFile=>$time){$m=$this->readMeta($depFile,LOCK_SH);if($m[self::META_TIME]!==$time)break
2;if($m&&!$this->verify($m))break
2;}}return
TRUE;}while(FALSE);$this->delete($meta[self::FILE],$meta[self::HANDLE]);return
FALSE;}function
write($key,$data,array$dp){$meta=array(self::META_TIME=>microtime());if(!empty($dp[Cache::EXPIRE])){if(empty($dp[Cache::SLIDING])){$meta[self::META_EXPIRE]=$dp[Cache::EXPIRE]+time();}else{$meta[self::META_DELTA]=(int)$dp[Cache::EXPIRE];}}if(!empty($dp[Cache::ITEMS])){foreach((array)$dp[Cache::ITEMS]as$item){$depFile=$this->getCacheFile($item);$m=$this->readMeta($depFile,LOCK_SH);$meta[self::META_ITEMS][$depFile]=$m[self::META_TIME];unset($m);}}if(!empty($dp[Cache::CALLBACKS])){$meta[self::META_CALLBACKS]=$dp[Cache::CALLBACKS];}$cacheFile=$this->getCacheFile($key);if($this->useDirs&&!is_dir($dir=dirname($cacheFile))){umask(0000);if(!mkdir($dir,0777,TRUE)){return;}}$handle=@fopen($cacheFile,'r+b');if(!$handle){$handle=fopen($cacheFile,'wb');if(!$handle){return;}}if(!empty($dp[Cache::TAGS])||isset($dp[Cache::PRIORITY])){$db=$this->getDb();$dbFile=sqlite_escape_string($cacheFile);$query='';if(!empty($dp[Cache::TAGS])){foreach((array)$dp[Cache::TAGS]as$tag){$query.="INSERT INTO cache (file, tag) VALUES ('$dbFile', '".sqlite_escape_string($tag)."');";}}if(isset($dp[Cache::PRIORITY])){$query.="INSERT INTO cache (file, priority) VALUES ('$dbFile', '".(int)$dp[Cache::PRIORITY]."');";}if(!sqlite_exec($db,"BEGIN; DELETE FROM cache WHERE file = '$dbFile'; $query COMMIT;")){sqlite_exec($db,"ROLLBACK");return;}}flock($handle,LOCK_EX);ftruncate($handle,0);if(!is_string($data)){$data=serialize($data);$meta[self::META_SERIALIZED]=TRUE;}$head=serialize($meta).'?>';$head='<?php //netteCache[01]'.str_pad((string)strlen($head),6,'0',STR_PAD_LEFT).$head;$headLen=strlen($head);$dataLen=strlen($data);do{if(fwrite($handle,str_repeat("\x00",$headLen),$headLen)!==$headLen){break;}if(fwrite($handle,$data,$dataLen)!==$dataLen){break;}fseek($handle,0);if(fwrite($handle,$head,$headLen)!==$headLen){break;}fclose($handle);return
TRUE;}while(FALSE);$this->delete($cacheFile,$handle);}function
remove($key){$this->delete($this->getCacheFile($key));}function
clean(array$conds){$all=!empty($conds[Cache::ALL]);$collector=empty($conds);if($all||$collector){$now=time();$base=$this->dir.DIRECTORY_SEPARATOR.'c';$iterator=new
RecursiveIteratorIterator(new
RecursiveDirectoryIterator($this->dir),RecursiveIteratorIterator::CHILD_FIRST);foreach($iterator
as$entry){$path=(string)$entry;if(strncmp($path,$base,strlen($base))){continue;}if($entry->isDir()){@rmdir($path);continue;}if($all){$this->delete($path);}else{$meta=$this->readMeta($path,LOCK_SH);if(!$meta)continue;if(!empty($meta[self::META_EXPIRE])&&$meta[self::META_EXPIRE]<$now){$this->delete($path,$meta[self::HANDLE]);continue;}fclose($meta[self::HANDLE]);}}if($all&&extension_loaded('sqlite')){sqlite_exec("DELETE FROM cache",$this->getDb());}return;}if(!empty($conds[Cache::TAGS])){$db=$this->getDb();foreach((array)$conds[Cache::TAGS]as$tag){$tmp[]="'".sqlite_escape_string($tag)."'";}$query[]="tag IN (".implode(',',$tmp).")";}if(isset($conds[Cache::PRIORITY])){$query[]="priority <= ".(int)$conds[Cache::PRIORITY];}if(isset($query)){$db=$this->getDb();$query=implode(' OR ',$query);$files=sqlite_single_query("SELECT file FROM cache WHERE $query",$db,FALSE);foreach($files
as$file){$this->delete($file);}sqlite_exec("DELETE FROM cache WHERE $query",$db);}}protected
function
readMeta($file,$lock){$handle=@fopen($file,'r+b');if(!$handle)return
NULL;flock($handle,$lock);$head=stream_get_contents($handle,self::META_HEADER_LEN);if($head&&strlen($head)===self::META_HEADER_LEN){$size=(int)substr($head,-6);$meta=stream_get_contents($handle,$size,self::META_HEADER_LEN);$meta=@unserialize($meta);if(is_array($meta)){fseek($handle,$size+self::META_HEADER_LEN);$meta[self::FILE]=$file;$meta[self::HANDLE]=$handle;return$meta;}}fclose($handle);return
NULL;}protected
function
readData($meta){$data=stream_get_contents($meta[self::HANDLE]);fclose($meta[self::HANDLE]);if(empty($meta[self::META_SERIALIZED])){return$data;}else{return@unserialize($data);}}protected
function
getCacheFile($key){if($this->useDirs){$key=explode(Cache::NAMESPACE_SEPARATOR,$key,2);return$this->dir.'/c'.(isset($key[1])?'-'.urlencode($key[0]).'/_'.urlencode($key[1]):'_'.urlencode($key[0]));}else{return$this->dir.'/c_'.urlencode($key);}}private
static
function
delete($file,$handle=NULL){if(@unlink($file)){if($handle)fclose($handle);return;}if(!$handle){$handle=@fopen($file,'r+');}if($handle){flock($handle,LOCK_EX);ftruncate($handle,0);fclose($handle);@unlink($file);}}protected
function
getDb(){if($this->db===NULL){if(!extension_loaded('sqlite')){throw
new
InvalidStateException("SQLite extension is required for storing tags and priorities.");}$this->db=sqlite_open($this->dir.'/cachejournal.sdb');@sqlite_exec($this->db,'CREATE TABLE cache (file VARCHAR NOT NULL, priority, tag VARCHAR);
			CREATE INDEX IDX_FILE ON cache (file); CREATE INDEX IDX_PRI ON cache (priority); CREATE INDEX IDX_TAG ON cache (tag);');}return$this->db;}}class
MemcachedStorage
extends
Object
implements
ICacheStorage{const
META_CALLBACKS='callbacks';const
META_DATA='data';const
META_DELTA='delta';private$memcache;private$prefix;static
function
isAvailable(){return
extension_loaded('memcache');}function
__construct($host='localhost',$port=11211,$prefix=''){if(!self::isAvailable()){throw
new
Exception("PHP extension 'memcache' is not loaded.");}$this->prefix=$prefix;$this->memcache=new
Memcache;$this->memcache->connect($host,$port);}function
read($key){$key=$this->prefix.$key;$meta=$this->memcache->get($key);if(!$meta)return
NULL;if(!empty($meta[self::META_CALLBACKS])&&!Cache::checkCallbacks($meta[self::META_CALLBACKS])){$this->memcache->delete($key);return
NULL;}if(!empty($meta[self::META_DELTA])){$this->memcache->replace($key,$meta,0,$meta[self::META_DELTA]+time());}return$meta[self::META_DATA];}function
write($key,$data,array$dp){if(!empty($dp[Cache::TAGS])||isset($dp[Cache::PRIORITY])||!empty($dp[Cache::ITEMS])){throw
new
NotSupportedException('Tags, priority and dependent items are not supported by MemcachedStorage.');}$meta=array(self::META_DATA=>$data);$expire=0;if(!empty($dp[Cache::EXPIRE])){$expire=(int)$dp[Cache::EXPIRE];if(!empty($dp[Cache::SLIDING])){$meta[self::META_DELTA]=$expire;}}if(!empty($dp[Cache::CALLBACKS])){$meta[self::META_CALLBACKS]=$dp[Cache::CALLBACKS];}$this->memcache->set($this->prefix.$key,$meta,0,$expire);}function
remove($key){$this->memcache->delete($this->prefix.$key);}function
clean(array$conds){if(!empty($conds[Cache::ALL])){$this->memcache->flush();}elseif(isset($conds[Cache::TAGS])||isset($conds[Cache::PRIORITY])){throw
new
NotSupportedException('Tags and priority is not supported by MemcachedStorage.');}}}class
Config
implements
ArrayAccess,IteratorAggregate{private
static$extensions=array('ini'=>'ConfigAdapterIni');static
function
registerExtension($extension,$class){if(!class_exists($class)){throw
new
InvalidArgumentException("Class '$class' was not found.");}if(!ClassReflection::from($class)->implementsInterface('IConfigAdapter')){throw
new
InvalidArgumentException("Configuration adapter '$class' is not Nette\\Config\\IConfigAdapter implementor.");}self::$extensions[strtolower($extension)]=$class;}static
function
fromFile($file,$section=NULL){$extension=strtolower(pathinfo($file,PATHINFO_EXTENSION));if(isset(self::$extensions[$extension])){$arr=call_user_func(array(self::$extensions[$extension],'load'),$file,$section);return
new
self($arr);}else{throw
new
InvalidArgumentException("Unknown file extension '$file'.");}}function
__construct($arr=NULL){foreach((array)$arr
as$k=>$v){$this->$k=is_array($v)?new
self($v):$v;}}function
save($file,$section=NULL){$extension=strtolower(pathinfo($file,PATHINFO_EXTENSION));if(isset(self::$extensions[$extension])){return
call_user_func(array(self::$extensions[$extension],'save'),$this,$file,$section);}else{throw
new
InvalidArgumentException("Unknown file extension '$file'.");}}function
__set($key,$value){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer.");}elseif($value===NULL){unset($this->$key);}else{$this->$key=$value;}}function&__get($key){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer.");}return$this->$key;}function
__isset($key){return
FALSE;}function
__unset($key){}function
offsetSet($key,$value){$this->__set($key,$value);}function
offsetGet($key){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer.");}elseif(!isset($this->$key)){return
NULL;}return$this->$key;}function
offsetExists($key){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer.");}return
isset($this->$key);}function
offsetUnset($key){if(!is_scalar($key)){throw
new
InvalidArgumentException("Key must be either a string or an integer.");}unset($this->$key);}function
getIterator(){return
new
GenericRecursiveIterator(new
ArrayIterator($this));}function
toArray(){$arr=array();foreach($this
as$k=>$v){$arr[$k]=$v
instanceof
self?$v->toArray():$v;}return$arr;}}final
class
ConfigAdapterIni
implements
IConfigAdapter{public
static$keySeparator='.';public
static$sectionSeparator=' < ';public
static$rawSection='!';final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
load($file,$section=NULL){if(!is_file($file)||!is_readable($file)){throw
new
FileNotFoundException("File '$file' is missing or is not readable.");}Tools::tryError();$ini=parse_ini_file($file,TRUE);if(Tools::catchError($msg)){throw
new
Exception($msg);}$separator=trim(self::$sectionSeparator);$data=array();foreach($ini
as$secName=>$secData){if(is_array($secData)){if(substr($secName,-1)===self::$rawSection){$secName=substr($secName,0,-1);}elseif(self::$keySeparator){$tmp=array();foreach($secData
as$key=>$val){$cursor=&$tmp;foreach(explode(self::$keySeparator,$key)as$part){if(!isset($cursor[$part])||is_array($cursor[$part])){$cursor=&$cursor[$part];}else{throw
new
InvalidStateException("Invalid key '$key' in section [$secName] in '$file'.");}}$cursor=$val;}$secData=$tmp;}$parts=$separator?explode($separator,strtr($secName,':',$separator)):array($secName);if(count($parts)>1){$parent=trim($parts[1]);$cursor=&$data;foreach(self::$keySeparator?explode(self::$keySeparator,$parent):array($parent)as$part){if(isset($cursor[$part])&&is_array($cursor[$part])){$cursor=&$cursor[$part];}else{throw
new
InvalidStateException("Missing parent section [$parent] in '$file'.");}}$secData=ArrayTools::mergeTree($secData,$cursor);}$secName=trim($parts[0]);if($secName===''){throw
new
InvalidStateException("Invalid empty section name in '$file'.");}}if(self::$keySeparator){$cursor=&$data;foreach(explode(self::$keySeparator,$secName)as$part){if(!isset($cursor[$part])||is_array($cursor[$part])){$cursor=&$cursor[$part];}else{throw
new
InvalidStateException("Invalid section [$secName] in '$file'.");}}}else{$cursor=&$data[$secName];}if(is_array($secData)&&is_array($cursor)){$secData=ArrayTools::mergeTree($secData,$cursor);}$cursor=$secData;}if($section===NULL){return$data;}elseif(!isset($data[$section])||!is_array($data[$section])){throw
new
InvalidStateException("There is not section [$section] in '$file'.");}else{return$data[$section];}}static
function
save($config,$file,$section=NULL){$output=array();$output[]='; generated by Nette';$output[]='';if($section===NULL){foreach($config
as$secName=>$secData){if(!(is_array($secData)||$secData
instanceof
Traversable)){throw
new
InvalidStateException("Invalid section '$section'.");}$output[]="[$secName]";self::build($secData,$output,'');$output[]='';}}else{$output[]="[$section]";self::build($config,$output,'');$output[]='';}if(!file_put_contents($file,implode(PHP_EOL,$output))){throw
new
IOException("Cannot write file '$file'.");}}private
static
function
build($input,&$output,$prefix){foreach($input
as$key=>$val){if(is_array($val)||$val
instanceof
Traversable){self::build($val,$output,$prefix.$key.self::$keySeparator);}elseif(is_bool($val)){$output[]="$prefix$key = ".($val?'true':'false');}elseif(is_numeric($val)){$output[]="$prefix$key = $val";}elseif(is_string($val)){$output[]="$prefix$key = \"$val\"";}else{throw
new
InvalidArgumentException("The '$prefix$key' item must be scalar or array, ".gettype($val)." given.");}}}}final
class
Debug{public
static$productionMode;public
static$consoleMode;public
static$time;private
static$firebugDetected;private
static$ajaxDetected;private
static$uri;public
static$maxDepth=3;public
static$maxLen=150;public
static$showLocation=FALSE;const
DEVELOPMENT=FALSE;const
PRODUCTION=TRUE;const
DETECT=NULL;public
static$strictMode=FALSE;public
static$onFatalError=array();public
static$mailer=array(__CLASS__,'defaultMailer');public
static$emailSnooze=172800;private
static$enabled=FALSE;private
static$logFile;private
static$logHandle;private
static$sendEmails;private
static$emailHeaders=array('To'=>'','From'=>'noreply@%host%','X-Mailer'=>'Nette Framework','Subject'=>'PHP: An error occurred on the server %host%','Body'=>'[%date%] %message%');public
static$showBar=TRUE;private
static$panels=array();private
static$dumps;private
static$errors;public
static$counters=array();const
LOG='LOG';const
INFO='INFO';const
WARN='WARN';const
ERROR='ERROR';const
TRACE='TRACE';const
EXCEPTION='EXCEPTION';const
GROUP_START='GROUP_START';const
GROUP_END='GROUP_END';final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
_init(){self::$time=microtime(TRUE);self::$consoleMode=PHP_SAPI==='cli';self::$productionMode=self::DETECT;self::$firebugDetected=isset($_SERVER['HTTP_USER_AGENT'])&&strpos($_SERVER['HTTP_USER_AGENT'],'FirePHP/');self::$ajaxDetected=isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&$_SERVER['HTTP_X_REQUESTED_WITH']==='XMLHttpRequest';if(isset($_SERVER['REQUEST_URI'])){self::$uri=(isset($_SERVER['HTTPS'])&&strcasecmp($_SERVER['HTTPS'],'off')?'https://':'http://').(isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'')).$_SERVER['REQUEST_URI'];}$tab=array(__CLASS__,'renderTab');$panel=array(__CLASS__,'renderPanel');self::addPanel(new
DebugPanel('time',$tab,$panel));self::addPanel(new
DebugPanel('memory',$tab,$panel));self::addPanel(new
DebugPanel('errors',$tab,$panel));self::addPanel(new
DebugPanel('dumps',$tab,$panel));}static
function
dump($var,$return=FALSE){if(!$return&&self::$productionMode){return$var;}$output="<pre class=\"nette-dump\">".self::_dump($var,0)."</pre>\n";if(!$return&&self::$showLocation){$trace=debug_backtrace();$i=isset($trace[1]['class'])&&$trace[1]['class']===__CLASS__?1:0;if(isset($trace[$i]['file'],$trace[$i]['line'])){$output=substr_replace($output,' <small>'.htmlspecialchars("in file {$trace[$i]['file']} on line {$trace[$i]['line']}",ENT_NOQUOTES).'</small>',-8,0);}}if(self::$consoleMode){$output=htmlspecialchars_decode(strip_tags($output),ENT_NOQUOTES);}if($return){return$output;}else{echo$output;return$var;}}static
function
barDump($var,$title=NULL){if(!self::$productionMode){$dump=array();foreach((is_array($var)?$var:array(''=>$var))as$key=>$val){$dump[$key]=self::dump($val,TRUE);}self::$dumps[]=array('title'=>$title,'dump'=>$dump);}return$var;}private
static
function
_dump(&$var,$level){static$tableUtf,$tableBin,$re='#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{10FFFF}]#u';if($tableUtf===NULL){foreach(range("\x00","\xFF")as$ch){if(ord($ch)<32&&strpos("\r\n\t",$ch)===FALSE)$tableUtf[$ch]=$tableBin[$ch]='\\x'.str_pad(dechex(ord($ch)),2,'0',STR_PAD_LEFT);elseif(ord($ch)<127)$tableUtf[$ch]=$tableBin[$ch]=$ch;else{$tableUtf[$ch]=$ch;$tableBin[$ch]='\\x'.dechex(ord($ch));}}$tableUtf['\\x']=$tableBin['\\x']='\\\\x';}if(is_bool($var)){return"<span>bool</span>(".($var?'TRUE':'FALSE').")\n";}elseif($var===NULL){return"<span>NULL</span>\n";}elseif(is_int($var)){return"<span>int</span>($var)\n";}elseif(is_float($var)){return"<span>float</span>($var)\n";}elseif(is_string($var)){if(self::$maxLen&&strlen($var)>self::$maxLen){$s=htmlSpecialChars(substr($var,0,self::$maxLen),ENT_NOQUOTES).' ... ';}else{$s=htmlSpecialChars($var,ENT_NOQUOTES);}$s=strtr($s,preg_match($re,$s)||preg_last_error()?$tableBin:$tableUtf);return"<span>string</span>(".strlen($var).") \"$s\"\n";}elseif(is_array($var)){$s="<span>array</span>(".count($var).") ";$space=str_repeat($space1='   ',$level);static$marker;if($marker===NULL)$marker=uniqid("\x00",TRUE);if(empty($var)){}elseif(isset($var[$marker])){$s.="{\n$space$space1*RECURSION*\n$space}";}elseif($level<self::$maxDepth||!self::$maxDepth){$s.="<code>{\n";$var[$marker]=0;foreach($var
as$k=>&$v){if($k===$marker)continue;$k=is_int($k)?$k:'"'.strtr($k,preg_match($re,$k)||preg_last_error()?$tableBin:$tableUtf).'"';$s.="$space$space1$k => ".self::_dump($v,$level+1);}unset($var[$marker]);$s.="$space}</code>";}else{$s.="{\n$space$space1...\n$space}";}return$s."\n";}elseif(is_object($var)){$arr=(array)$var;$s="<span>object</span>(".get_class($var).") (".count($arr).") ";$space=str_repeat($space1='   ',$level);static$list=array();if(empty($arr)){$s.="{}";}elseif(in_array($var,$list,TRUE)){$s.="{\n$space$space1*RECURSION*\n$space}";}elseif($level<self::$maxDepth||!self::$maxDepth){$s.="<code>{\n";$list[]=$var;foreach($arr
as$k=>&$v){$m='';if($k[0]==="\x00"){$m=$k[1]==='*'?' <span>protected</span>':' <span>private</span>';$k=substr($k,strrpos($k,"\x00")+1);}$k=strtr($k,preg_match($re,$k)||preg_last_error()?$tableBin:$tableUtf);$s.="$space$space1\"$k\"$m => ".self::_dump($v,$level+1);}array_pop($list);$s.="$space}</code>";}else{$s.="{\n$space$space1...\n$space}";}return$s."\n";}elseif(is_resource($var)){return"<span>resource of type</span>(".get_resource_type($var).")\n";}else{return"<span>unknown type</span>\n";}}static
function
timer($name=NULL){static$time=array();$now=microtime(TRUE);$delta=isset($time[$name])?$now-$time[$name]:0;$time[$name]=$now;return$delta;}static
function
enable($mode=NULL,$logFile=NULL,$email=NULL){error_reporting(E_ALL|E_STRICT);if(is_bool($mode)){self::$productionMode=$mode;}elseif(is_string($mode)){$mode=preg_split('#[,\s]+#',$mode);}if(is_array($mode)){self::$productionMode=!isset($_SERVER['REMOTE_ADDR'])||!in_array($_SERVER['REMOTE_ADDR'],$mode,TRUE);}if(self::$productionMode===self::DETECT){if(class_exists('Environment')){self::$productionMode=Environment::isProduction();}elseif(isset($_SERVER['SERVER_ADDR'])||isset($_SERVER['LOCAL_ADDR'])){$addr=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR'];$oct=explode('.',$addr);self::$productionMode=$addr!=='::1'&&(count($oct)!==4||($oct[0]!=='10'&&$oct[0]!=='127'&&($oct[0]!=='172'||$oct[1]<16||$oct[1]>31)&&($oct[0]!=='169'||$oct[1]!=='254')&&($oct[0]!=='192'||$oct[1]!=='168')));}else{self::$productionMode=!self::$consoleMode;}}if(self::$productionMode&&$logFile!==FALSE){self::$logFile='log/php_error.log';if(class_exists('Environment')){if(is_string($logFile)){self::$logFile=Environment::expand($logFile);}else
try{self::$logFile=Environment::expand('%logDir%/php_error.log');}catch(InvalidStateException$e){}}elseif(is_string($logFile)){self::$logFile=$logFile;}ini_set('error_log',self::$logFile);}if(function_exists('ini_set')){ini_set('display_errors',!self::$productionMode);ini_set('html_errors',!self::$logFile&&!self::$consoleMode);ini_set('log_errors',FALSE);}elseif(ini_get('display_errors')!=!self::$productionMode&&ini_get('display_errors')!==(self::$productionMode?'stderr':'stdout')){throw
new
NotSupportedException('Function ini_set() must be enabled.');}self::$sendEmails=self::$logFile&&$email;if(self::$sendEmails){if(is_string($email)){self::$emailHeaders['To']=$email;}elseif(is_array($email)){self::$emailHeaders=$email+self::$emailHeaders;}}if(!defined('E_DEPRECATED')){define('E_DEPRECATED',8192);}if(!defined('E_USER_DEPRECATED')){define('E_USER_DEPRECATED',16384);}register_shutdown_function(array(__CLASS__,'_shutdownHandler'));set_exception_handler(array(__CLASS__,'_exceptionHandler'));set_error_handler(array(__CLASS__,'_errorHandler'));self::$enabled=TRUE;}static
function
isEnabled(){return
self::$enabled;}static
function
log($message){error_log(@date('[Y-m-d H-i-s] ').trim($message).PHP_EOL,3,self::$logFile);}static
function
_shutdownHandler(){static$types=array(E_ERROR=>1,E_CORE_ERROR=>1,E_COMPILE_ERROR=>1,E_PARSE=>1);$error=error_get_last();if(isset($types[$error['type']])){if(!headers_sent()){header('HTTP/1.1 500 Internal Server Error');}if(ini_get('html_errors')){$error['message']=html_entity_decode(strip_tags($error['message']),ENT_QUOTES,'UTF-8');}self::processException(new
FatalErrorException($error['message'],0,$error['type'],$error['file'],$error['line'],NULL),TRUE);}if(self::$showBar&&!self::$productionMode&&!self::$ajaxDetected&&!self::$consoleMode){foreach(headers_list()as$header){if(strncasecmp($header,'Content-Type:',13)===0){if(substr($header,14,9)==='text/html'){break;}return;}}$panels=array();foreach(self::$panels
as$panel){$panels[]=array('id'=>preg_replace('#[^a-z0-9]+#i','-',$panel->getId()),'tab'=>$tab=(string)$panel->getTab(),'panel'=>$tab?(string)$panel->getPanel():NULL);}ob_start();?>

<!-- Nette Debug Bar -->

<style id="nette-debug-style">#nette-debug{display:none}body#nette-debug{margin:5px 5px 0;display:block}#nette-debug *{font:inherit;color:inherit;background:transparent;margin:0;padding:0;border:none;text-align:inherit;list-style:inherit}#nette-debug .nette-fixed-coords{position:fixed;_position:absolute;right:0;bottom:0}#nette-debug a{color:#125eae;text-decoration:none}#nette-debug .nette-panel a{color:#125eae;text-decoration:none}#nette-debug a:hover,#nette-debug a:active,#nette-debug a:focus{background-color:#125eae;color:white}#nette-debug .nette-panel h2,#nette-debug .nette-panel h3,#nette-debug .nette-panel p{margin:.4em 0}#nette-debug .nette-panel table{border-collapse:collapse;background:#fcfae5}#nette-debug .nette-panel .nette-alt td{background:#f5f2db}#nette-debug .nette-panel td,#nette-debug .nette-panel th{border:1px solid #dcd7c8;padding:2px 5px;vertical-align:top;text-align:left}#nette-debug .nette-panel th{background:#f0eee6;color:#655e5e;font-size:90%;font-weight:bold}#nette-debug .nette-panel pre,#nette-debug .nette-panel code{font:9pt/1.5 Consolas,monospace}.nette-hidden{display:none}#nette-debug-bar{font:normal normal 12px/21px Tahoma,sans-serif;color:#333;background:#edeae0;position:relative;top:-5px;left:-5px;height:21px;_float:left;min-width:50px;white-space:nowrap;z-index:23181;opacity:.9}#nette-debug-bar:hover{opacity:1}#nette-debug-bar ul{list-style:none none}#nette-debug-bar li{float:left}#nette-debug-bar img{vertical-align:middle;position:relative;top:-1px;margin-right:3px}#nette-debug-bar li a{color:#000;display:block;padding:0 4px}#nette-debug-bar li a:hover{color:black;background:#c3c1b8}#nette-debug-bar li .nette-warning{color:#d32b2b;font-weight:bold}#nette-debug-bar li div{padding:0 4px}#nette-debug-logo{background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC0AAAAPCAYAAABwfkanAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABiFJREFUSMe1VglPlGcQ5i+1xjZNqxREtGq8ahCPWsVGvEDBA1BBRQFBDjkE5BYUzwpovRBUREBEBbl3OVaWPfj2vi82eTrvbFHamLRJ4yYTvm+u95mZZ96PoKAv+LOatXBYZ+Bx6uFy6DGnt1m0EOKwSmQzwmHTgX5B/1W+yM9GYJ02CX6/B/5ZF+w2A4x6FYGTYDVp4PdY2Tbrs5N+mnRa2Km4/wV6rhPzQQj5fDc1mJM5nd0iYdZtQWtrCxobGnDpUiledTynbuvg99mgUMhw924Trl2rR01NNSTNJE9iDpTV8innv4K2kZPLroPXbYLHZeSu2K1aeF0muJ2GvwGzmNSwU2E+svm8ZrgdBliMaha/34Vx+RAKCgpwpa4OdbW1UE/L2cc/68WtWzdRVlaG6uoqtD1/BA/pA1MIxLvtes7pc5vhoDOE/rOgbVSdf9aJWa8dBp0Kyg+jdLiTx2vQKWEyqGmcNkqg4iTC1+dzQatWkK+cJqPD7KyFaKEjvRuNjY24fLkGdXW1ePjwAeX4QHonDNI0A75+/RpqqqshH+6F2UAUMaupYXouykV0mp6SQ60coxgL8Z4aMg/4x675/V60v3jKB+Xl5WJibIC4KPEIS0qKqWv5GOh7BZ/HSIk9kA33o7y8DOfPZ6GQOipkXDZAHXKxr4ipqqpkKS6+iIrycgz2dyMnJxtVlZUsotNZWZmor79KBbvgpdjm5sfIzc1hv4L8fKJPDTfJZZc+gRYKr8sAEy2DcBRdEEk62ltx9uwZ5qNILoDU1l6mbrvx5EkzUlKSuTiR7PHjR3x4fv4FyIbeIic7G5WVFUyN+qtX+Lnt2SPcvn2LfURjhF7kE4WPDr+Bx+NEUVEhkpNPoImm5CSOl5aUIC3tLOMR59gtAY4HidGIzj14cB8ZGRkM8kJeHk6cOI4xWR8vSl5uLlJTT6O74xnT5lB8PM6cSYXVqILb5UBWZiYSExMYkE4zzjqX00QHG+h9AjPqMei0k3ywy2khMdNiq6BVCf04T6ekuBgJCUdRUVHOBQwPvkNSUiLjaGi4Q/5qFgYtHgTXRJdTT59GenoaA5gY64deq0Bc3EGuNj4+DnppEheLijhZRkY6SktLsGPHdi6irOwSFTRAgO04deokTSIFsbExuHfvLnFSx8DevelAfFwcA0lJTqZi5PDS9aci/sbE7Oe4wsICbtD27b/ye1NTI3FeSX4W2gdFALRD3A4eM44ePcKViuD79/8gnZP5Kg4+cCAW2dnnqUM2Lujw4UM4ePAA2ztfPsHIYA/sdOt43A50d7UFCjkUj+joXVBMDJDeDhcVk08cjd61C3v37uFYp8PKXX3X8xJRUTtw7FgSn3Xzxg10d7ZCqRjkM+02C7pettDNogqAFjzxuI3YHR2Nffv2coXy0V44HGZERm7kJNu2/cK8bW9rwbp1axnMnj27uUijQQOb1QyTcYZ3YMOGn/Hbzp1crAAvaDfY38O5hW3//n0ce+TIYWiUcub1xo0R2Lp1y8cYsUMWM125VhPe93Zj7do1vEPi26GfUdBFbhK8tGHrli1YsWwpgoOD0dXRQqAtXMCy8DBs3rwJoSGLsWrVclylBdoUGYlVK1dg9eqVCFsSSs8/4btvvmUwEnE0KTERISE/IiIiAsGLF2HhwgU8qbc97QgPX8qFr1mzGgu+/opzdL5o5l1aEhqC9evXYWlYKFYsD6e/YVj0w/dMGZVyBDMqeaDTRuKpkxYjIz2dOyeup6H3r2kkOuJ1H3N5Z1QUzp3LQF9vJ4xGLQYHXiM9LY0pEhsTg+PHj9HNcJu4OcL3uaQZY86LiZw8mcJTkmhBTUYJbU8fcoygobgWR4Z6iKtTPLE7d35HYkICT1dIZuY59HQ9412StBPQTMvw8Z6WaMNFxy3Gab4TeQT0M9IHwUT/G0i0MGIJ9CTiJjBIH+iQaQbC7+QnfEXiQL6xgF09TjETHCt8RbeMuil+D8RNsV1LHdQoZfR/iJJzCZuYmEE/Bd3MJNs/+0UURgFWJJ//aQ8k+CsxVTqnVytHObkQrUoG8T4/bs4u4ubbxLPwFzYNPc8HI2zijLm84l39Dx8hfwJenFezFBKKQwAAAABJRU5ErkJggg==') 0 50% no-repeat;min-width:45px;cursor:move}#nette-debug-logo span{display:none}#nette-debug-bar-bgl,#nette-debug-bar-bgx,#nette-debug-bar-bgr{position:absolute;z-index:-1;top:-7px;height:37px}#nette-debug-bar-bgl{background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAlCAMAAABBCPgrAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALdQTFRFPj4+AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAISEhAAAAIyMjAAAAHR0dISEhNzc3Nzc3NjY2NTU1PT09PT09PDw8Pj4+SkpJS0tKy8nDxcO7zMrEx8S97evj4+HY5uPb7uzk5+Ta6OXb6Obc6ebd6ufe6+jf7Onf7erg7uvh7+zi8O3j8O3k8e7k8e7l8e/l8u/m8u/n8vDn8vDo8/Do8/Ho8/HpTk+bYwAAACd0Uk5TAAECAwQFBgcICQoLDA0ODxAQERMVLi8wM0hJSk9TU6qsrLDs7vHxx6CxnwAAAKJJREFUGBkFwUGKVEEARMF49WuEQdy5Ee9/R6HpTiMCCIEQISRFpNSFUlVSVacuqO8f93Gp+vmr+8eN6uv5i6N07hccVOc3HKr4jAtgc0UZuUjGXBKDCxhcwIYLGHMBM7uAjV3AZrvM+JhdzMy2a9lstgN8/m3bYdveL9ueSj338zl7orx7v1+vVJ1zzjnnEcLskcJsD2Ha9hAwHgQzgRABhP+DAUz8vYhDMAAAAABJRU5ErkJggg==') no-repeat;left:-12px;width:12px}#nette-debug-bar-bgx{background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAlCAYAAACDKIOpAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAF5JREFUeNpUjjEOgDAMA932DXynA/8X6sAnGBErjYlLW8Fy8jlRFAAI0RH/SC9yziuu86AUDjroiR+ldA6a0hDNHLUqSWmj6yvo2i6rS1vZi2xRd0/UNL4ypSDgEWAAvWU1L9Te5UYAAAAASUVORK5CYII=') repeat-x;left:0;right:5px}#nette-debug-bar-bgr{background:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA0AAAAlCAYAAACZFGMnAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAb5JREFUeNq8Vb1OwzAQtlNTIoRYYIhEFfUVGLp2YykDA48FG8/AUvEKqFIGQDwEA20CKv9UUFWkPuzUTi/pOW0XTjolvfrL951/PnPGGGelaLfbwIiIoih7aoBX+g9cH7AgHcJkzaRnkuNU4BygC3XEAI73ggqB5EFpsFOylYVB0vEBMMqgprR2wjDcD4JAy/wZjUayiiWLz7cBPD/dv9xe97pHnc5Jo9HYU+Utlb7pV6AJ4jno41VnH+5uepetVutAlbcNcFPlBuo9m0kPYC692QwPfd8P0AAPLb6d/uwLTAN1CiF2OOd1MxhQ8xz3JixgxlhYPypn6ySlzAEI55mpJ8MwsWVMuA6KybKQG5uXnohpcf04AZj3BCCrmKp6Wh2Qz966IdZlMSD2P0yeA0Qdd8Ant2r2KJ9YykQd+ZmpWGCapl9qCX6RT9A94R8P/fhqPB4PCSZY6EkxvA/ix+j07PwiSZLYMLlcKXPOYy1pMpkM4zhWmORb1acGNEW2lkvWO3cXFaQjCzK1vJQwSnIw7ild0WELtjwldoGsugwEMpBVbo2C68hSPwvy8OUmCKuCZVepoOhdd66NPwEGACivHvMuwfIKAAAAAElFTkSuQmCC') no-repeat;right:-8px;width:13px}#nette-debug .nette-panel{font:normal normal 12px/1.5 sans-serif;background:white;color:#333}#nette-debug h1{font:normal normal 23px/1.4 Tahoma,sans-serif;color:#575753;background:#edeae0;margin:-5px -5px 5px;padding:0 25px 5px 5px}#nette-debug .nette-mode-peek .nette-inner,#nette-debug .nette-mode-float .nette-inner{max-width:700px;max-height:500px;overflow:auto}#nette-debug .nette-panel .nette-icons{display:none}#nette-debug .nette-mode-peek{display:none;position:relative;z-index:23180;padding:5px;min-width:150px;min-height:50px;border:5px solid #edeae0;border-radius:5px;-moz-border-radius:5px}#nette-debug .nette-mode-peek h1{cursor:move}#nette-debug .nette-mode-float{position:relative;z-index:23179;padding:5px;min-width:150px;min-height:50px;border:5px solid #edeae0;border-radius:5px;-moz-border-radius:5px;opacity:.9;-moz-box-shadow:1px 1px 6px #666;-webkit-box-shadow:1px 1px 6px #666;box-shadow:1px 1px 6px #666}#nette-debug .nette-focused{z-index:23180;opacity:1}#nette-debug .nette-mode-float h1{cursor:move}#nette-debug .nette-mode-float .nette-icons{display:block;position:absolute;top:0;right:0;font-size:18px}#nette-debug .nette-icons a{color:#575753}#nette-debug .nette-icons a:hover{color:white}</style>

<!--[if lt IE 8]><style>#nette-debug-bar img{display:none}#nette-debug-bar li{border-left:1px solid #DCD7C8;padding:0 3px}#nette-debug-logo span{background:#edeae0;display:inline}</style><![endif]-->


<script id="nette-debug-script">
/* <![CDATA[ */
<?php ?>/**
 * NetteJs
 *
 * @copyright  Copyright (c) 2010 David Grudl
 * @license    http://nette.org/license  Nette license
 */

var Nette = Nette || {};

(function(){

// simple class builder
Nette.Class = function(def) {
	var cl = def.constructor || function(){}, nm;
	delete def.constructor;

	if (def.Extends) {
		var foo = function() { this.constructor = cl };
		foo.prototype = def.Extends.prototype;
		cl.prototype = new foo;
		delete def.Extends;
	}

	if (def.Static) {
		for (nm in def.Static) cl[nm] = def.Static[nm];
		delete def.Static;
	}

	for (nm in def) cl.prototype[nm] = def[nm];
	return cl;
};


// supported cross-browser selectors: #id  |  div  |  div.class  |  .class
Nette.Q = Nette.Class({

	Static: {
		factory: function(selector) {
			return new Nette.Q(selector)
		},

		implement: function(methods) {
			var nm, fn = Nette.Q.implement, prot = Nette.Q.prototype;
			for (nm in methods) {
				fn[nm] = methods[nm];
				prot[nm] = (function(nm){
					return function() { return this.each(fn[nm], arguments) }
				}(nm));
			}
		}
	},

	constructor: function(selector) {
		if (typeof selector === "string") {
			selector = this._find(document, selector);

		} else if (!selector || selector.nodeType || selector.length === void 0 || selector === window) {
			selector = [selector];
		}

		for (var i = 0, len = selector.length; i < len; i++) {
			if (selector[i]) this[this.length++] = selector[i];
		}
	},

	length: 0,

	find: function(selector) {
		return new Nette.Q(this._find(this[0], selector));
	},

	_find: function(context, selector) {
		if (!context || !selector) {
			return [];

		} else if (document.querySelectorAll) {
			return context.querySelectorAll(selector);

		} else if (selector.charAt(0) === '#') { // #id
			return [document.getElementById(selector.substring(1))];

		} else { // div  |  div.class  |  .class
			selector = selector.split('.');
			var elms = context.getElementsByTagName(selector[0] || '*');

			if (selector[1]) {
				var list = [], pattern = new RegExp('(^|\\s)' + selector[1] + '(\\s|$)');
				for (var i = 0, len = elms.length; i < len; i++) {
					if (pattern.test(elms[i].className)) list.push(elms[i]);
				}
				return list;
			} else {
				return elms;
			}
		}
	},

	dom: function() {
		return this[0];
	},

	each: function(callback, args) {
		for (var i = 0, res; i < this.length; i++) {
			if ((res = callback.apply(this[i], args || [])) !== void 0) { return res; }
		}
		return this;
	}
});


var $ = Nette.Q.factory, fn = Nette.Q.implement;

fn({
	// cross-browser event attach
	bind: function(event, handler) {
		if (document.addEventListener && (event === 'mouseenter' || event === 'mouseleave')) { // simulate mouseenter & mouseleave using mouseover & mouseout
			var old = handler;
			event = event === 'mouseenter' ? 'mouseover' : 'mouseout';
			handler = function(e) {
				for (var target = e.relatedTarget; target; target = target.parentNode) {
					if (target === this) return; // target must not be inside this
				}
				old.call(this, e);
			};
		}

		var data = fn.data.call(this),
			events = data.events = data.events || {}; // use own handler queue

		if (!events[event]) {
			var el = this, // fixes 'this' in iE
				handlers = events[event] = [],
				generic = fn.bind.genericHandler = function(e) { // dont worry, 'e' is passed in IE
					if (!e.target) e.target = e.srcElement;
					if (!e.preventDefault) e.preventDefault = function() { e.returnValue = false }; // emulate preventDefault()
					if (!e.stopPropagation) e.stopPropagation = function() { e.cancelBubble = true }; // emulate stopPropagation()
					e.stopImmediatePropagation = function() { this.stopPropagation(); i = handlers.length };
					for (var i = 0; i < handlers.length; i++) {
						handlers[i].call(el, e);
					}
				};

			if (document.addEventListener) { // non-IE
				this.addEventListener(event, generic, false);
			} else if (document.attachEvent) { // IE < 9
				this.attachEvent('on' + event, generic);
			}
		}

		events[event].push(handler);
	},

	// adds class to element
	addClass: function(className) {
		this.className = this.className.replace(/^|\s+|$/g, ' ').replace(' '+className+' ', ' ') + ' ' + className;
	},

	// removes class from element
	removeClass: function(className) {
		this.className = this.className.replace(/^|\s+|$/g, ' ').replace(' '+className+' ', ' ');
	},

	// tests whether element has given class
	hasClass: function(className) {
		return this.className.replace(/^|\s+|$/g, ' ').indexOf(' '+className+' ') > -1;
	},

	show: function() {
		var dsp = fn.show.display = fn.show.display || {}, tag = this.tagName;
		if (!dsp[tag]) {
			var el = document.body.appendChild(document.createElement(tag));
			dsp[tag] = fn.css.call(el, 'display');
		}
		this.style.display = dsp[tag];
	},

	hide: function() {
		this.style.display = 'none';
	},

	css: function(property) {
		return this.currentStyle ? this.currentStyle[property]
			: (window.getComputedStyle ? document.defaultView.getComputedStyle(this, null).getPropertyValue(property) : void 0);
	},

	data: function() {
		return this.nette = this.nette || {};
	},

	_trav: function(el, selector, fce) {
		selector = selector.split('.');
		while (el && !(el.nodeType === 1 && (!selector[0] || el.tagName.toLowerCase() === selector[0]) && (!selector[1] || fn.hasClass.call(el, selector[1])))) el = el[fce];
		return $(el);
	},

	closest: function(selector) {
		return fn._trav(this, selector, 'parentNode');
	},

	prev: function(selector) {
		return fn._trav(this.previousSibling, selector, 'previousSibling');
	},

	next: function(selector) {
		return fn._trav(this.nextSibling, selector, 'nextSibling');
	},

	// returns total offset for element
	offset: function(coords) {
		var el = this, ofs = coords ? {left: -coords.left || 0, top: -coords.top || 0} : fn.position.call(el);
		while (el = el.offsetParent) { ofs.left += el.offsetLeft; ofs.top += el.offsetTop; }

		if (coords) {
			fn.position.call(this, {left: -ofs.left, top: -ofs.top});
		} else {
			return ofs;
		}
	},

	// returns current position or move to new position
	position: function(coords) {
		if (coords) {
			this.nette && this.nette.onmove && this.nette.onmove.call(this, coords);
			this.style.left = (coords.left || 0) + 'px';
			this.style.top = (coords.top || 0) + 'px';
		} else {
			return {left: this.offsetLeft, top: this.offsetTop, width: this.offsetWidth, height: this.offsetHeight};
		}
	},

	// makes element draggable
	draggable: function(options) {
		var $el = $(this), dE = document.documentElement, started, options = options || {};

		$(options.handle || this).bind('mousedown', function(e) {
			e.preventDefault();
			e.stopPropagation();

			if (fn.draggable.binded) { // missed mouseup out of window?
				return dE.onmouseup(e);
			}

			var deltaX = $el[0].offsetLeft - e.clientX, deltaY = $el[0].offsetTop - e.clientY;
			fn.draggable.binded = true;
			started = false;

			dE.onmousemove = function(e) {
				e = e || event;
				if (!started) {
					options.draggedClass && $el.addClass(options.draggedClass);
					options.start && options.start(e, $el);
					started = true;
				}
				$el.position({left: e.clientX + deltaX, top: e.clientY + deltaY});
				return false;
			};

			dE.onmouseup = function(e) {
				if (started) {
					options.draggedClass && $el.removeClass(options.draggedClass);
					options.stop && options.stop(e || event, $el);
				}
				fn.draggable.binded = dE.onmousemove = dE.onmouseup = null;
				return false;
			};

		}).bind('click', function(e) {
			if (started) {
				e.stopImmediatePropagation();
				preventClick = false;
			}
		});
	}
});

})();

(function(){
Nette.Debug = {};

var $ = Nette.Q.factory;

var Panel = Nette.Debug.Panel = Nette.Class({
	Extends: Nette.Q,

	Static: {
		PEEK: 'nette-mode-peek',
		FLOAT: 'nette-mode-float',
		WINDOW: 'nette-mode-window',
		FOCUSED: 'nette-focused',

		factory: function(selector) {
			return new Panel(selector)
		},

		_toggle: function(link) { // .nette-toggler
			var rel = link.rel, el = rel.charAt(0) === '#' ? $(rel) : $(link)[rel.charAt(0) === '<' ? 'prev' : 'next'](rel.substring(1));
			if (el.css('display') === 'none') {
				el.show(); link.innerHTML = link.innerHTML.replace("\u25ba", "\u25bc");
			} else {
				el.hide(); link.innerHTML = link.innerHTML.replace("\u25bc", "\u25ba");
			}
		}
	},

	constructor: function(id) {
		Nette.Q.call(this, '#nette-debug-panel-' + id.replace('nette-debug-panel-', ''));
	},

	reposition: function() {
		if (this.hasClass(Panel.WINDOW)) {
			window.resizeBy(document.documentElement.scrollWidth - document.documentElement.clientWidth, document.documentElement.scrollHeight - document.documentElement.clientHeight);
		} else {
			this.position(this.position());
			if (this.position().width) { // is visible?
				document.cookie = this.dom().id + '=' + this.position().left + ':' + this.position().top + '; path=/';
			}
		}
	},

	focus: function() {
		if (this.hasClass(Panel.WINDOW)) {
			this.data().win.focus();
		} else {
			clearTimeout(this.data().blurTimeout);
			this.addClass(Panel.FOCUSED).show();
		}
	},

	blur: function() {
		this.removeClass(Panel.FOCUSED);
		if (this.hasClass(Panel.PEEK)) {
			var panel = this;
			this.data().blurTimeout = setTimeout(function() {
				panel.hide();
			}, 50);
		}
	},

	toFloat: function() {
		this.removeClass(Panel.WINDOW).removeClass(Panel.PEEK).addClass(Panel.FLOAT).show().reposition();
		return this;
	},

	toPeek: function() {
		this.removeClass(Panel.WINDOW).removeClass(Panel.FLOAT).addClass(Panel.PEEK).hide();
		document.cookie = this.dom().id + '=; path=/'; // delete position
	},

	toWindow: function() {
		var panel = this, win, doc, offset = this.offset(), id = this.dom().id;

		offset.left += typeof window.screenLeft === 'number' ? window.screenLeft : (window.screenX + 10);
		offset.top += typeof window.screenTop === 'number' ? window.screenTop : (window.screenY + 50);

		win = window.open('', id.replace(/-/g, '_'), 'left='+offset.left+',top='+offset.top+',width='+offset.width+',height='+(offset.height+15)+',resizable=yes,scrollbars=yes');
		if (!win) return;

		doc = win.document;
		doc.write('<!DOCTYPE html><meta http-equiv="Content-Type" content="text\/html; charset=utf-8"><style>' + $('#nette-debug-style').dom().innerHTML + '<\/style><script>' + $('#nette-debug-script').dom().innerHTML + '<\/script><body id="nette-debug">');
		doc.body.innerHTML = '<div class="nette-panel nette-mode-window" id="' + id + '">' + this.dom().innerHTML + '<\/div>';
		win.Nette.Debug.Panel.factory(id).initToggler().reposition();
		doc.title = panel.find('h1').dom().innerHTML;

		$([win]).bind('unload', function() {
			panel.toPeek();
			win.close(); // forces closing, can be invoked by F5
		});

		$(doc).bind('keyup', function(e) {
			if (e.keyCode === 27) win.close();
		});

		document.cookie = id + '=window; path=/'; // save position
		this.hide().removeClass(Panel.FLOAT).removeClass(Panel.PEEK).addClass(Panel.WINDOW).data().win = win;
	},

	init: function() {
		var panel = this, pos;

		panel.data().onmove = function(coords) { // forces constrained inside window
			var d = document, width = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth, height = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
			coords.left = Math.max(Math.min(coords.left, .8 * this.offsetWidth), .2 * this.offsetWidth - width);
			coords.top = Math.max(Math.min(coords.top, .8 * this.offsetHeight), this.offsetHeight - height);
		};

		$(window).bind('resize', function() {
			panel.reposition();
		});

		panel.draggable({
			handle: panel.find('h1'),
			stop: function() {
				panel.toFloat();
			}

		}).bind('mouseenter', function(e) {
			panel.focus();

		}).bind('mouseleave', function(e) {
			panel.blur();
		});

		this.initToggler();

		panel.find('.nette-icons').find('a').bind('click', function(e) {
			if (this.rel === 'close') panel.toPeek(); else panel.toWindow();
			e.preventDefault();
		});

		// restore saved position
		if (pos = document.cookie.match(new RegExp(panel.dom().id + '=(window|(-?[0-9]+):(-?[0-9]+))'))) {
			if (pos[2]) {
				panel.toFloat().position({left: pos[2], top: pos[3]});
			} else {
				panel.toWindow();
			}
		} else {
			panel.addClass(Panel.PEEK);
		}
	},

	initToggler: function() { // enable .nette-toggler
		var panel = this;
		this.bind('click', function(e) {
			var $link = $(e.target).closest('a'), link = $link.dom();
			if (link && $link.hasClass('nette-toggler')) {
				Panel._toggle(link);
				e.preventDefault();
				panel.reposition();
			}
		});
		return this;
	}

});



Nette.Debug.Bar = Nette.Class({
	Extends: Nette.Q,

	constructor: function() {
		Nette.Q.call(this, '#nette-debug-bar');
	},

	init: function() {
		var bar = this, pos;

		bar.data().onmove = function(coords) { // forces constrained inside window
			var d = document, width = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth, height = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
			coords.left = Math.max(Math.min(coords.left, 0), this.offsetWidth - width);
			coords.top = Math.max(Math.min(coords.top, 0), this.offsetHeight - height);
		};

		$(window).bind('resize', function() {
			bar.position(bar.position());
		});

		bar.draggable({
			draggedClass: 'nette-dragged',
			stop: function() {
				document.cookie = bar.dom().id + '=' + bar.position().left + ':' + bar.position().top + '; path=/';
			}
		});

		bar.find('a').bind('click', function(e) {
			if (this.rel === 'close') {
				$('#nette-debug').hide();
			} else if (this.rel) {
				var panel = Panel.factory(this.rel);
				if (e.shiftKey) {
					panel.toFloat().toWindow();
				} else if (panel.hasClass(Panel.FLOAT)) {
					var offset = $(this).offset();
					panel.offset({left: offset.left - panel.position().width + offset.width + 4, top: offset.top - panel.position().height - 4}).toPeek();
				} else {
					panel.toFloat().position({left: panel.position().left - Math.round(Math.random() * 100) - 20, top: panel.position().top - Math.round(Math.random() * 100) - 20}).reposition();
				}
			}
			e.preventDefault();

		}).bind('mouseenter', function(e) {
			if (!this.rel || this.rel === 'close' || bar.hasClass('nette-dragged')) return;
			var panel = Panel.factory(this.rel);
			panel.focus();
			if (panel.hasClass(Panel.PEEK)) {
				var offset = $(this).offset();
				panel.offset({left: offset.left - panel.position().width + offset.width + 4, top: offset.top - panel.position().height - 4});
			}

		}).bind('mouseleave', function(e) {
			if (!this.rel || this.rel === 'close' || bar.hasClass('nette-dragged')) return;
			Panel.factory(this.rel).blur();
		});

		// restore saved position
		if (pos = document.cookie.match(new RegExp(bar.dom().id + '=(-?[0-9]+):(-?[0-9]+)'))) {
			bar.position({left: pos[1], top: pos[2]}); // TODO
		}

		bar.find('a').each(function() {
			if (!this.rel || this.rel === 'close') return;
			Panel.factory(this.rel).init();
		});
	}

});


})();

/* ]]> */
</script>



<div id="nette-debug">

<?php foreach($panels
as$id=>$panel):?>
<div class="nette-fixed-coords">
	<div class="nette-panel" id="nette-debug-panel-<?php echo$panel['id']?>">
		<div id="nette-debug-<?php echo$panel['id']?>"><?php echo$panel['panel']?></div>
		<div class="nette-icons">
			<a href="#" title="open in window">&curren;</a>
			<a href="#" rel="close" title="close window">&times;</a>
		</div>
	</div>
</div>
<?php endforeach?>

<div class="nette-fixed-coords">
	<div id="nette-debug-bar">
		<ul>
			<li id="nette-debug-logo">&nbsp;<span>Nette Framework</span></li>
			<?php foreach($panels
as$panel):if(!$panel['tab'])continue;?>
			<li><?php if($panel['panel']):?><a href="#" rel="<?php echo$panel['id']?>"><?php echo
trim($panel['tab'])?></a><?php else:echo'<div>',trim($panel['tab']),'</div>';endif?></li>
			<?php endforeach?>
			<li><a href="#" rel="close" title="close debug bar">&times;</a></li>
		</ul>

		<div id="nette-debug-bar-bgl"></div><div id="nette-debug-bar-bgx"></div><div id="nette-debug-bar-bgr"></div>
	</div>
</div>

</div>


<script>(function(){(new Nette.Debug.Bar).init();document.body.appendChild(Nette.Q.factory("#nette-debug").show().dom())})();</script>

<!-- /Nette Debug Bar -->
<?php
echo"\n\n\n\n".preg_replace_callback('#(</textarea|</pre|</script|^).*?(?=<textarea|<pre|<script|$)#si',create_function('$m','return trim(preg_replace("#[ \t\r\n]+#", " ", $m[0]));'),ob_get_clean());}}static
function
_exceptionHandler(Exception$exception){if(!headers_sent()){header('HTTP/1.1 500 Internal Server Error');}self::processException($exception,TRUE);exit;}static
function
_errorHandler($severity,$message,$file,$line,$context){if($severity===E_RECOVERABLE_ERROR||$severity===E_USER_ERROR){throw
new
FatalErrorException($message,0,$severity,$file,$line,$context);}elseif(($severity&error_reporting())!==$severity){return
NULL;}elseif(self::$strictMode){self::_exceptionHandler(new
FatalErrorException($message,0,$severity,$file,$line,$context),TRUE);}static$types=array(E_WARNING=>'Warning',E_USER_WARNING=>'Warning',E_NOTICE=>'Notice',E_USER_NOTICE=>'Notice',E_STRICT=>'Strict standards',E_DEPRECATED=>'Deprecated',E_USER_DEPRECATED=>'Deprecated');$message='PHP '.(isset($types[$severity])?$types[$severity]:'Unknown error').": $message in $file:$line";if(self::$logFile){if(self::$uri)$message.='  @  '.self::$uri;self::log($message);if(self::$sendEmails){self::sendEmail($message);}return
NULL;}elseif(!self::$productionMode){if(self::$showBar){self::$errors[]=$message;}if(self::$firebugDetected&&!headers_sent()){self::fireLog(strip_tags($message),self::ERROR);}return
self::$consoleMode||(!self::$showBar&&!self::$ajaxDetected)?FALSE:NULL;}return
FALSE;}static
function
processException(Exception$exception,$outputAllowed=FALSE){if(!self::$enabled){return;}elseif(self::$logFile){try{$hash=md5($exception.(method_exists($exception,'getPrevious')?$exception->getPrevious():(isset($exception->previous)?$exception->previous:'')));self::log("PHP Fatal error: Uncaught ".str_replace("Stack trace:\n".$exception->getTraceAsString(),'',$exception));foreach(new
DirectoryIterator(dirname(self::$logFile))as$entry){if(strpos($entry,$hash)){$skip=TRUE;break;}}$file=dirname(self::$logFile)."/exception ".@date('Y-m-d H-i-s')." $hash.html";if(empty($skip)&&self::$logHandle=@fopen($file,'w')){ob_start();ob_start(array(__CLASS__,'_writeFile'),1);self::_paintBlueScreen($exception);ob_end_flush();ob_end_clean();fclose(self::$logHandle);}if(self::$sendEmails){self::sendEmail((string)$exception);}}catch(Exception$e){if(!headers_sent()){header('HTTP/1.1 500 Internal Server Error');}echo'Debug fatal error: ',get_class($e),': ',($e->getCode()?'#'.$e->getCode().' ':'').$e->getMessage(),"\n";exit;}}elseif(self::$productionMode){}elseif(self::$consoleMode){if($outputAllowed){echo"$exception\n";}}elseif(self::$firebugDetected&&self::$ajaxDetected&&!headers_sent()){self::fireLog($exception,self::EXCEPTION);}elseif($outputAllowed){if(!headers_sent()){@ob_end_clean();while(ob_get_level()&&@ob_end_clean());if(in_array('Content-Encoding: gzip',headers_list()))header('Content-Encoding: identity',TRUE);}self::_paintBlueScreen($exception);}elseif(self::$firebugDetected&&!headers_sent()){self::fireLog($exception,self::EXCEPTION);}foreach(self::$onFatalError
as$handler){call_user_func($handler,$exception);}}static
function
toStringException(Exception$exception){if(self::$enabled){self::_exceptionHandler($exception);}else{trigger_error($exception->getMessage(),E_USER_ERROR);}}static
function
_paintBlueScreen(Exception$exception){$internals=array();foreach(array('Object','ObjectMixin')as$class){if(class_exists($class,FALSE)){$rc=new
ReflectionClass($class);$internals[$rc->getFileName()]=TRUE;}}if(class_exists('Environment',FALSE)){$application=Environment::getServiceLocator()->hasService('Nette\\Application\\Application',TRUE)?Environment::getServiceLocator()->getService('Nette\\Application\\Application'):NULL;}if(!function_exists('_netteDebugPrintCode')){function
_netteDebugPrintCode($file,$line,$count=15){if(function_exists('ini_set')){ini_set('highlight.comment','#999; font-style: italic');ini_set('highlight.default','#000');ini_set('highlight.html','#06b');ini_set('highlight.keyword','#d24; font-weight: bold');ini_set('highlight.string','#080');}$start=max(1,$line-floor($count/2));$source=@file_get_contents($file);if(!$source)return;$source=explode("\n",highlight_string($source,TRUE));$spans=1;echo$source[0];$source=explode('<br />',$source[1]);array_unshift($source,NULL);$i=$start;while(--$i>=1){if(preg_match('#.*(</?span[^>]*>)#',$source[$i],$m)){if($m[1]!=='</span>'){$spans++;echo$m[1];}break;}}$source=array_slice($source,$start,$count,TRUE);end($source);$numWidth=strlen((string)key($source));foreach($source
as$n=>$s){$spans+=substr_count($s,'<span')-substr_count($s,'</span');$s=str_replace(array("\r","\n"),array('',''),$s);if($n===$line){printf("<span class='highlight'>Line %{$numWidth}s:    %s\n</span>%s",$n,strip_tags($s),preg_replace('#[^>]*(<[^>]+>)[^<]*#','$1',$s));}else{printf("<span class='line'>Line %{$numWidth}s:</span>    %s\n",$n,$s);}}echo
str_repeat('</span>',$spans),'</code>';}function
_netteDump($dump){return'<pre class="nette-dump">'.preg_replace_callback('#(^|\s+)?(.*)\((\d+)\) <code>#','_netteDumpCb',$dump).'</pre>';}function
_netteDumpCb($m){return"$m[1]<a href='#' onclick='return !netteToggle(this)'>$m[2]($m[3]) ".(trim($m[1])||$m[3]<7?'<abbr>&#x25bc;</abbr> </a><code>':'<abbr>&#x25ba;</abbr> </a><code class="collapsed">');}function
_netteOpenPanel($name,$collapsed){static$id;$id++;?>
	<div class="panel">
		<h2><a href="#" onclick="return !netteToggle(this, 'pnl<?php echo$id?>')"><?php echo
htmlSpecialChars($name)?> <abbr><?php echo$collapsed?'&#x25ba;':'&#x25bc;'?></abbr></a></h2>

		<div id="pnl<?php echo$id?>" class="<?php echo$collapsed?'collapsed ':''?>inner">
	<?php
}function
_netteClosePanel(){?>
		</div>
	</div>
	<?php
}}static$errorTypes=array(E_ERROR=>'Fatal Error',E_USER_ERROR=>'User Error',E_RECOVERABLE_ERROR=>'Recoverable Error',E_CORE_ERROR=>'Core Error',E_COMPILE_ERROR=>'Compile Error',E_PARSE=>'Parse Error',E_WARNING=>'Warning',E_CORE_WARNING=>'Core Warning',E_COMPILE_WARNING=>'Compile Warning',E_USER_WARNING=>'User Warning',E_NOTICE=>'Notice',E_USER_NOTICE=>'User Notice',E_STRICT=>'Strict',E_DEPRECATED=>'Deprecated',E_USER_DEPRECATED=>'User Deprecated');$title=($exception
instanceof
FatalErrorException&&isset($errorTypes[$exception->getSeverity()]))?$errorTypes[$exception->getSeverity()]:get_class($exception);$rn=0;if(headers_sent()){echo'</pre></xmp></table>';}?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="robots" content="noindex,noarchive">
	<meta name="generator" content="Nette Framework">

	<title><?php echo
htmlspecialchars($title)?></title><!-- <?php echo$exception->getMessage(),($exception->getCode()?' #'.$exception->getCode():'')?> -->

	<style type="text/css">body{margin:0 0 2em;padding:0}#netteBluescreen{font:9pt/1.5 Verdana,sans-serif;background:white;color:#333;position:absolute;left:0;top:0;width:100%;z-index:23178;text-align:left}#netteBluescreen *{color:inherit;background:inherit;text-align:inherit}#netteBluescreenIcon{position:absolute;right:.5em;top:.5em;z-index:23179;text-decoration:none;background:red;padding:3px}#netteBluescreenIcon abbr{color:black!important}#netteBluescreen h1{font:18pt/1.5 Verdana,sans-serif!important;margin:.6em 0}#netteBluescreen h2{font:14pt/1.5 sans-serif!important;color:#888;margin:.6em 0}#netteBluescreen a{text-decoration:none;color:#4197E3}#netteBluescreen a abbr{font-family:sans-serif;color:#999}#netteBluescreen h3{font:bold 10pt/1.5 Verdana,sans-serif!important;margin:1em 0;padding:0}#netteBluescreen p{margin:.8em 0}#netteBluescreen pre,#netteBluescreen code,#netteBluescreen table{font:9pt/1.5 Consolas,monospace!important}#netteBluescreen pre,#netteBluescreen table{background:#fffbcc;padding:.4em .7em;border:1px dotted silver}#netteBluescreen table pre{padding:0;margin:0;border:none}#netteBluescreen pre.nette-dump span{color:#c16549}#netteBluescreen pre.nette-dump a{color:#333}#netteBluescreen div.panel{border-bottom:1px solid #eee;padding:1px 2em}#netteBluescreen div.inner{padding:.1em 1em 1em;background:#f5f5f5}#netteBluescreen table{border-collapse:collapse;width:100%}#netteBluescreen td,#netteBluescreen th{vertical-align:top;text-align:left;padding:2px 3px;border:1px solid #eeb}#netteBluescreen th{width:10%;font-weight:bold}#netteBluescreen .odd,#netteBluescreen .odd pre{background-color:#faf5c3}#netteBluescreen ul{font:7pt/1.5 Verdana,sans-serif!important;padding:1em 2em 50px}#netteBluescreen .highlight,#netteBluescreenError{background:red;color:white;font-weight:bold;font-style:normal;display:block}#netteBluescreen .line{color:#9e9e7e;font-weight:normal;font-style:normal}</style>


	<script type="text/javascript">/*<![CDATA[*/document.write("<style> .collapsed { display: none; } </style>");function netteToggle(a,b){var c=a.getElementsByTagName("abbr")[0];for(a=b?document.getElementById(b):a.nextSibling;a.nodeType!==1;)a=a.nextSibling;b=a.currentStyle?a.currentStyle.display=="none":getComputedStyle(a,null).display=="none";try{c.innerHTML=String.fromCharCode(b?9660:9658)}catch(d){}a.style.display=b?a.tagName.toLowerCase()==="code"?"inline":"block":"none";return true};/*]]>*/</script>
</head>



<body>
<div id="netteBluescreen">
	<a id="netteBluescreenIcon" href="#" onclick="return !netteToggle(this)"><abbr>&#x25bc;</abbr></a

	><div>
		<div id="netteBluescreenError" class="panel">
			<h1><?php echo
htmlspecialchars($title),($exception->getCode()?' #'.$exception->getCode():'')?></h1>

			<p><?php echo
htmlspecialchars($exception->getMessage())?></p>
		</div>



		<?php $ex=$exception;$level=0;?>
		<?php do{?>

			<?php if($level++):?>
				<?php _netteOpenPanel('Caused by',$level>2)?>
				<div class="panel">
					<h1><?php echo
htmlspecialchars(get_class($ex)),($ex->getCode()?' #'.$ex->getCode():'')?></h1>

					<p><?php echo
htmlspecialchars($ex->getMessage())?></p>
				</div>
			<?php endif?>

			<?php $collapsed=isset($internals[$ex->getFile()]);?>
			<?php if(is_file($ex->getFile())):?>
			<?php _netteOpenPanel('Source file',$collapsed)?>
				<p><strong>File:</strong> <?php echo
htmlspecialchars($ex->getFile())?> &nbsp; <strong>Line:</strong> <?php echo$ex->getLine()?></p>
				<pre><?php _netteDebugPrintCode($ex->getFile(),$ex->getLine())?></pre>
			<?php _netteClosePanel()?>
			<?php endif?>



			<?php _netteOpenPanel('Call stack',FALSE)?>
				<ol>
					<?php foreach($ex->getTrace()as$key=>$row):?>
					<li><p>

					<?php if(isset($row['file'])):?>
						<span title="<?php echo
htmlSpecialChars($row['file'])?>"><?php echo
htmlSpecialChars(basename(dirname($row['file']))),'/<b>',htmlSpecialChars(basename($row['file'])),'</b></span> (',$row['line'],')'?>
					<?php else:?>
						&lt;PHP inner-code&gt;
					<?php endif?>

					<?php if(isset($row['file'])&&is_file($row['file'])):?><a href="#" onclick="return !netteToggle(this, 'src<?php echo"$level-$key"?>')">source <abbr>&#x25ba;</abbr></a>&nbsp; <?php endif?>

					<?php if(isset($row['class']))echo$row['class'].$row['type']?>
					<?php echo$row['function']?>

					(<?php if(!empty($row['args'])):?><a href="#" onclick="return !netteToggle(this, 'args<?php echo"$level-$key"?>')">arguments <abbr>&#x25ba;</abbr></a><?php endif?>)
					</p>

					<?php if(!empty($row['args'])):?>
						<div class="collapsed" id="args<?php echo"$level-$key"?>">
						<table>
						<?php

try{$r=isset($row['class'])?new
ReflectionMethod($row['class'],$row['function']):new
ReflectionFunction($row['function']);$params=$r->getParameters();}catch(Exception$e){$params=array();}foreach($row['args']as$k=>$v){echo'<tr><th>',(isset($params[$k])?'$'.$params[$k]->name:"#$k"),'</th><td>';echo
_netteDump(self::_dump($v,0));echo"</td></tr>\n";}?>
						</table>
						</div>
					<?php endif?>


					<?php if(isset($row['file'])&&is_file($row['file'])):?>
						<pre <?php if(!$collapsed||isset($internals[$row['file']]))echo'class="collapsed"';else$collapsed=FALSE?> id="src<?php echo"$level-$key"?>"><?php _netteDebugPrintCode($row['file'],$row['line'])?></pre>
					<?php endif?>

					</li>
					<?php endforeach?>

					<?php if(!isset($row)):?>
					<li><i>empty</i></li>
					<?php endif?>
				</ol>
			<?php _netteClosePanel()?>



			<?php if($ex
instanceof
IDebugPanel&&($tab=$ex->getTab())&&($panel=$ex->getPanel())):?>
			<?php _netteOpenPanel($tab,FALSE)?>
				<?php echo$panel?>
			<?php _netteClosePanel()?>
			<?php endif?>



			<?php if(isset($ex->context)&&is_array($ex->context)):?>
			<?php _netteOpenPanel('Variables',TRUE)?>
			<table>
			<?php

foreach($ex->context
as$k=>$v){echo'<tr><th>$',htmlspecialchars($k),'</th><td>',_netteDump(self::_dump($v,0)),"</td></tr>\n";}?>
			</table>
			<?php _netteClosePanel()?>
			<?php endif?>

		<?php }while((method_exists($ex,'getPrevious')&&$ex=$ex->getPrevious())||(isset($ex->previous)&&$ex=$ex->previous));?>
		<?php while(--$level)_netteClosePanel()?>



		<?php if(!empty($application)):?>
		<?php _netteOpenPanel('Nette Application',TRUE)?>
			<h3>Requests</h3>
			<?php $tmp=$application->getRequests();echo
_netteDump(self::_dump($tmp,0))?>

			<h3>Presenter</h3>
			<?php $tmp=$application->getPresenter();echo
_netteDump(self::_dump($tmp,0))?>
		<?php _netteClosePanel()?>
		<?php endif?>



		<?php _netteOpenPanel('Environment',TRUE)?>
			<?php
$list=get_defined_constants(TRUE);if(!empty($list['user'])):?>
			<h3><a href="#" onclick="return !netteToggle(this, 'pnl-env-const')">Constants <abbr>&#x25bc;</abbr></a></h3>
			<table id="pnl-env-const">
			<?php

foreach($list['user']as$k=>$v){echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th>';echo'<td>',_netteDump(self::_dump($v,0)),"</td></tr>\n";}?>
			</table>
			<?php endif?>


			<h3><a href="#" onclick="return !netteToggle(this, 'pnl-env-files')">Included files <abbr>&#x25ba;</abbr></a>(<?php echo
count(get_included_files())?>)</h3>
			<table id="pnl-env-files" class="collapsed">
			<?php

foreach(get_included_files()as$v){echo'<tr'.($rn++%2?' class="odd"':'').'><td>',htmlspecialchars($v),"</td></tr>\n";}?>
			</table>


			<h3>$_SERVER</h3>
			<?php if(empty($_SERVER)):?>
			<p><i>empty</i></p>
			<?php else:?>
			<table>
			<?php

foreach($_SERVER
as$k=>$v)echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th><td>',_netteDump(self::_dump($v,0)),"</td></tr>\n";?>
			</table>
			<?php endif?>
		<?php _netteClosePanel()?>



		<?php _netteOpenPanel('HTTP request',TRUE)?>
			<?php if(function_exists('apache_request_headers')):?>
			<h3>Headers</h3>
			<table>
			<?php

foreach(apache_request_headers()as$k=>$v)echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th><td>',htmlspecialchars($v),"</td></tr>\n";?>
			</table>
			<?php endif?>


			<?php foreach(array('_GET','_POST','_COOKIE')as$name):?>
			<h3>$<?php echo$name?></h3>
			<?php if(empty($GLOBALS[$name])):?>
			<p><i>empty</i></p>
			<?php else:?>
			<table>
			<?php

foreach($GLOBALS[$name]as$k=>$v)echo'<tr'.($rn++%2?' class="odd"':'').'><th>',htmlspecialchars($k),'</th><td>',_netteDump(self::_dump($v,0)),"</td></tr>\n";?>
			</table>
			<?php endif?>
			<?php endforeach?>
		<?php _netteClosePanel()?>



		<?php _netteOpenPanel('HTTP response',TRUE)?>
			<h3>Headers</h3>
			<?php if(headers_list()):?>
			<pre><?php

foreach(headers_list()as$s)echo
htmlspecialchars($s),'<br>';?></pre>
			<?php else:?>
			<p><i>no headers</i></p>
			<?php endif?>
		<?php _netteClosePanel()?>


		<ul>
			<li>Report generated at <?php echo@date('Y/m/d H:i:s',self::$time)?></li>
			<?php if(self::$uri):?>
				<li><a href="<?php echo
htmlSpecialChars(self::$uri)?>"><?php echo
htmlSpecialChars(self::$uri)?></a></li>
			<?php endif?>
			<li>PHP <?php echo
htmlSpecialChars(PHP_VERSION)?></li>
			<?php if(isset($_SERVER['SERVER_SOFTWARE'])):?><li><?php echo
htmlSpecialChars($_SERVER['SERVER_SOFTWARE'])?></li><?php endif?>
			<?php if(class_exists('Framework')):?><li><?php echo
htmlSpecialChars('Nette Framework '.Framework::VERSION)?> <i>(revision <?php echo
htmlSpecialChars(Framework::REVISION)?>)</i></li><?php endif?>
		</ul>
	</div>
</div>

<script type="text/javascript">document.body.appendChild(document.getElementById("netteBluescreen"));</script>
</body>
</html><?php }static
function
_writeFile($buffer){fwrite(self::$logHandle,$buffer);}private
static
function
sendEmail($message){$monitorFile=self::$logFile.'.monitor';if(@filemtime($monitorFile)+self::$emailSnooze<time()&&@file_put_contents($monitorFile,'sent')){call_user_func(self::$mailer,$message);}}private
static
function
defaultMailer($message){$host=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'');$headers=str_replace(array('%host%','%date%','%message%'),array($host,@date('Y-m-d H:i:s',self::$time),$message),self::$emailHeaders);$subject=$headers['Subject'];$to=$headers['To'];$body=str_replace("\r\n",PHP_EOL,$headers['Body']);unset($headers['Subject'],$headers['To'],$headers['Body']);$header='';foreach($headers
as$key=>$value){$header.="$key: $value".PHP_EOL;}mail($to,$subject,$body,$header);}static
function
addPanel(IDebugPanel$panel){self::$panels[]=$panel;}static
function
renderTab($id){switch($id){case'time':?>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAJ6SURBVDjLjZO7T1NhGMY7Mji6uJgYt8bElTjof6CDg4sMSqIxJsRGB5F4TwQSIg1QKC0KWmkZEEsKtEcSxF5ohV5pKSicXqX3aqGn957z+PUEGopiGJ583/A+v3znvPkJAAjWR0VNJG0kGhKahCFhXcN3YBFfx8Kry6ym4xIzce88/fbWGY2k5WRb77UTTbWuYA9gDGg7EVmSIOF4g5T7HZKuMcSW5djWDyL0uRf0dCc8inYYxTcw9fAiCMBYB3gVj1z7gLhNTjKCqHkYP79KENC9Bq3uxrrqORzy+9D3tPAAccspVx1gWg0KbaZFbGllWFM+xrKkFQudV0CeDfJsjN4+C2nracjunoPq5VXIBrowMK4V1gG1LGyWdbZwCalsBYUyh2KFQzpXxVqkAGswD3+qBDpZwow9iYE5v26/VwfUQnnznyhvjguQYabIIpKpYD1ahI8UTT92MUSFuP5Z/9TBTgOgFrVjp3nakaG/0VmEfpX58pwzjUEquNk362s+PP8XYD/KpYTBHmRg9Wch0QX1R80dCZhYipudYQY2Auib8RmODVCa4hfUK4ngaiiLNFNFdKeCWWscXZMbWy9Unv9/gsIQU09a4pwvUeA3Uapy2C2wCKXL0DqTePLexbWPOv79E8f0UWrencZ2poxciUWZlKssB4bcHeE83NsFuMgpo2iIpMuNa1TNu4XjhggWvb+R2K3wZdLlAZl8Fd9jRb5sD+Xx0RJBx5gdom6VsMEFDyWF0WyCeSOFcDKPnRxZYTQL5Rc/nn1w4oFsBaIhC3r6FRh5erPRhYMyHdeFw4C6zkRhmijM7CnMu0AUZonCDCnRJBqSus5/ABD6Ba5CkQS8AAAAAElFTkSuQmCC"
><?php echo
number_format((microtime(TRUE)-self::$time)*1000,1,'.',' ')?>ms
<?php 
return;case'memory':?>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAGvSURBVDjLpZO7alZREEbXiSdqJJDKYJNCkPBXYq12prHwBezSCpaidnY+graCYO0DpLRTQcR3EFLl8p+9525xgkRIJJApB2bN+gZmqCouU+NZzVef9isyUYeIRD0RTz482xouBBBNHi5u4JlkgUfx+evhxQ2aJRrJ/oFjUWysXeG45cUBy+aoJ90Sj0LGFY6anw2o1y/mK2ZS5pQ50+2XiBbdCvPk+mpw2OM/Bo92IJMhgiGCox+JeNEksIC11eLwvAhlzuAO37+BG9y9x3FTuiWTzhH61QFvdg5AdAZIB3Mw50AKsaRJYlGsX0tymTzf2y1TR9WwbogYY3ZhxR26gBmocrxMuhZNE435FtmSx1tP8QgiHEvj45d3jNlONouAKrjjzWaDv4CkmmNu/Pz9CzVh++Yd2rIz5tTnwdZmAzNymXT9F5AtMFeaTogJYkJfdsaaGpyO4E62pJ0yUCtKQFxo0hAT1JU2CWNOJ5vvP4AIcKeao17c2ljFE8SKEkVdWWxu42GYK9KE4c3O20pzSpyyoCx4v/6ECkCTCqccKorNxR5uSXgQnmQkw2Xf+Q+0iqQ9Ap64TwAAAABJRU5ErkJggg=="
><?php echo
number_format(memory_get_peak_usage()/1000,1,'.',' ')?> kB
<?php 
return;case'dumps':if(!Debug::$dumps)return;?>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIASURBVDjLpVPPaxNREJ6Vt01caH4oWk1T0ZKlGIo9RG+BUsEK4kEP/Q8qPXnpqRdPBf8A8Wahhx7FQ0GF9FJ6UksqwfTSBDGyB5HkkphC9tfb7jfbtyQQTx142byZ75v5ZnZWC4KALmICPy+2DkvKIX2f/POz83LxCL7nrz+WPNcll49DrhM9v7xdO9JW330DuXrrqkFSgig5iR2Cfv3t3gNxOnv5BwU+eZ5HuON5/PMPJZKJ+yKQfpW0S7TxdC6WJaWkyvff1LDaFRAeLZj05MHsiPTS6hua0PUqtwC5sHq9zv9RYWl+nu5cETcnJ1M0M5WlWq3GsX6/T+VymRzHDluZiGYAAsw0TQahV8uyyGq1qFgskm0bHIO/1+sx1rFtchJhArwEyIQ1Gg2WD2A6nWawHQJVDIWgIJfLhQowTIeE9D0mKAU8qPC0220afsWFQoH93W6X7yCDJ+DEBeBmsxnPIJVKxWQVUwry+XyUwBlKMKwA8jqdDhOVCqVAzQDVvXAXhOdGBFgymYwrGoZBmUyGjxCCdF0fSahaFdgoTHRxfTveMCXvWfkuE3Y+f40qhgT/nMitupzApdvT18bu+YeDQwY9Xl4aG9/d/URiMBhQq/dvZMeVghtT17lSZW9/rAKsvPa/r9Fc2dw+Pe0/xI6kM9mT5vtXy+Nw2kU/5zOGRpvuMIu0YAAAAABJRU5ErkJggg==">variables
<?php 
return;case'errors':if(!Debug::$errors)return;?>
<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAIsSURBVDjLpVNLSJQBEP7+h6uu62vLVAJDW1KQTMrINQ1vPQzq1GOpa9EppGOHLh0kCEKL7JBEhVCHihAsESyJiE4FWShGRmauu7KYiv6Pma+DGoFrBQ7MzGFmPr5vmDFIYj1mr1WYfrHPovA9VVOqbC7e/1rS9ZlrAVDYHig5WB0oPtBI0TNrUiC5yhP9jeF4X8NPcWfopoY48XT39PjjXeF0vWkZqOjd7LJYrmGasHPCCJbHwhS9/F8M4s8baid764Xi0Ilfp5voorpJfn2wwx/r3l77TwZUvR+qajXVn8PnvocYfXYH6k2ioOaCpaIdf11ivDcayyiMVudsOYqFb60gARJYHG9DbqQFmSVNjaO3K2NpAeK90ZCqtgcrjkP9aUCXp0moetDFEeRXnYCKXhm+uTW0CkBFu4JlxzZkFlbASz4CQGQVBFeEwZm8geyiMuRVntzsL3oXV+YMkvjRsydC1U+lhwZsWXgHb+oWVAEzIwvzyVlk5igsi7DymmHlHsFQR50rjl+981Jy1Fw6Gu0ObTtnU+cgs28AKgDiy+Awpj5OACBAhZ/qh2HOo6i+NeA73jUAML4/qWux8mt6NjW1w599CS9xb0mSEqQBEDAtwqALUmBaG5FV3oYPnTHMjAwetlWksyByaukxQg2wQ9FlccaK/OXA3/uAEUDp3rNIDQ1ctSk6kHh1/jRFoaL4M4snEMeD73gQx4M4PsT1IZ5AfYH68tZY7zv/ApRMY9mnuVMvAAAAAElFTkSuQmCC"
><span class="nette-warning"><?php echo
count(self::$errors)?> errors</span>
<?php }}static
function
renderPanel($id){switch($id){case'dumps':if(!function_exists('_netteDumpCb2')){function
_netteDumpCb2($m){return"$m[1]<a href='#' class='nette-toggler'>$m[2]($m[3]) ".($m[3]<7?'<abbr>&#x25bc;</abbr> </a><code>':'<abbr>&#x25ba;</abbr> </a><code class="nette-hidden">');}}?>
<style>#nette-debug-dumps h2{font:11pt/1.5 sans-serif;margin:0;padding:2px 8px;background:#3484d2;color:white}#nette-debug-dumps table{width:100%}#nette-debug #nette-debug-dumps a{color:#333;background:transparent}#nette-debug-dumps a abbr{font-family:sans-serif;color:#999}#nette-debug-dumps pre.nette-dump span{color:#c16549}</style>


<h1>Dumped variables</h1>

<div class="nette-inner">
<?php foreach(self::$dumps
as$item):?>
	<?php if($item['title']):?>
	<h2><?php echo
htmlspecialchars($item['title'])?></h2>
	<?php endif?>

	<table>
	<?php $i=0?>
	<?php foreach($item['dump']as$key=>$dump):?>
	<tr class="<?php echo$i++%
2?'nette-alt':''?>">
		<th><?php echo
htmlspecialchars($key)?></th>
		<td><?php echo
preg_replace_callback('#(<pre class="nette-dump">|\s+)?(.*)\((\d+)\) <code>#','_netteDumpCb2',$dump)?></td>
	</tr>
	<?php endforeach?>
	</table>
<?php endforeach?>
</div>
<?php 
return;case'errors':?>
<h1>Errors</h1>

<?php $relative=isset($_SERVER['SCRIPT_FILENAME'])?strtr(dirname(dirname($_SERVER['SCRIPT_FILENAME'])),'/',DIRECTORY_SEPARATOR):NULL?>

<div class="nette-inner">
<table>
<?php foreach(self::$errors
as$i=>$item):?>
<tr class="<?php echo$i++%
2?'nette-alt':''?>">
	<td><pre><?php echo$relative?str_replace($relative,"...",$item):$item?></pre></td>
</tr>
<?php endforeach?>
</table>
</div><?php }}static
function
addColophon(){}static
function
consoleDump(){}static
function
fireLog($message,$priority=self::LOG,$label=NULL){if($message
instanceof
Exception){if($priority!==self::EXCEPTION&&$priority!==self::TRACE){$priority=self::TRACE;}$message=array('Class'=>get_class($message),'Message'=>$message->getMessage(),'File'=>$message->getFile(),'Line'=>$message->getLine(),'Trace'=>$message->getTrace(),'Type'=>'','Function'=>'');foreach($message['Trace']as&$row){if(empty($row['file']))$row['file']='?';if(empty($row['line']))$row['line']='?';}}elseif($priority===self::GROUP_START){$label=$message;$message=NULL;}return
self::fireSend('FirebugConsole/0.1',self::replaceObjects(array(array('Type'=>$priority,'Label'=>$label),$message)));}private
static
function
fireSend($struct,$payload){if(self::$productionMode)return
NULL;if(headers_sent())return
FALSE;header('X-Wf-Protocol-nette: http://meta.wildfirehq.org/Protocol/JsonStream/0.2');header('X-Wf-nette-Plugin-1: http://meta.firephp.org/Wildfire/Plugin/FirePHP/Library-FirePHPCore/0.2.0');static$structures;$index=isset($structures[$struct])?$structures[$struct]:($structures[$struct]=count($structures)+1);header("X-Wf-nette-Structure-$index: http://meta.firephp.org/Wildfire/Structure/FirePHP/$struct");$payload=json_encode($payload);static$counter;foreach(str_split($payload,4990)as$s){$num=++$counter;header("X-Wf-nette-$index-1-n$num: |$s|\\");}header("X-Wf-nette-$index-1-n$num: |$s|");return
TRUE;}static
private
function
replaceObjects($val){if(is_object($val)){return'object '.get_class($val).'';}elseif(is_string($val)){return@iconv('UTF-16','UTF-8//IGNORE',iconv('UTF-8','UTF-16//IGNORE',$val));}elseif(is_array($val)){foreach($val
as$k=>$v){unset($val[$k]);$k=@iconv('UTF-16','UTF-8//IGNORE',iconv('UTF-8','UTF-16//IGNORE',$k));$val[$k]=self::replaceObjects($v);}}return$val;}}Debug::_init();class
Configurator
extends
Object{public$defaultConfigFile='%appDir%/config.ini';public$defaultServices=array('Nette\\Application\\Application'=>'Application','Nette\\Web\\HttpContext'=>'HttpContext','Nette\\Web\\IHttpRequest'=>'HttpRequest','Nette\\Web\\IHttpResponse'=>'HttpResponse','Nette\\Web\\IUser'=>'User','Nette\\Caching\\ICacheStorage'=>array(__CLASS__,'createCacheStorage'),'Nette\\Web\\Session'=>'Session','Nette\\Loaders\\RobotLoader'=>array(__CLASS__,'createRobotLoader'));function
detect($name){switch($name){case'environment':if($this->detect('console')){return
Environment::CONSOLE;}else{return
Environment::getMode('production')?Environment::PRODUCTION:Environment::DEVELOPMENT;}case'production':if(PHP_SAPI==='cli'){return
FALSE;}elseif(isset($_SERVER['SERVER_ADDR'])||isset($_SERVER['LOCAL_ADDR'])){$addr=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:$_SERVER['LOCAL_ADDR'];$oct=explode('.',$addr);return$addr!=='::1'&&(count($oct)!==4||($oct[0]!=='10'&&$oct[0]!=='127'&&($oct[0]!=='172'||$oct[1]<16||$oct[1]>31)&&($oct[0]!=='169'||$oct[1]!=='254')&&($oct[0]!=='192'||$oct[1]!=='168')));}else{return
TRUE;}case'console':return
PHP_SAPI==='cli';default:return
NULL;}}function
loadConfig($file){$name=Environment::getName();if($file
instanceof
Config){$config=$file;$file=NULL;}else{if($file===NULL){$file=$this->defaultConfigFile;}$file=Environment::expand($file);$config=Config::fromFile($file,$name);}if($config->variable
instanceof
Config){foreach($config->variable
as$key=>$value){Environment::setVariable($key,$value);}}$iterator=new
RecursiveIteratorIterator($config);foreach($iterator
as$key=>$value){$tmp=$iterator->getDepth()?$iterator->getSubIterator($iterator->getDepth()-1)->current():$config;$tmp[$key]=Environment::expand($value);}$runServices=array();$locator=Environment::getServiceLocator();if($config->service
instanceof
Config){foreach($config->service
as$key=>$value){$key=strtr($key,'-','\\');if(is_string($value)){$locator->removeService($key);$locator->addService($key,$value);}else{if($value->factory){$locator->removeService($key);$locator->addService($key,$value->factory,isset($value->singleton)?$value->singleton:TRUE,(array)$value->option);}if($value->run){$runServices[]=$key;}}}}if(!$config->php){$config->php=$config->set;unset($config->set);}if($config->php
instanceof
Config){if(PATH_SEPARATOR!==';'&&isset($config->php->include_path)){$config->php->include_path=str_replace(';',PATH_SEPARATOR,$config->php->include_path);}foreach(clone$config->php
as$key=>$value){if($value
instanceof
Config){unset($config->php->$key);foreach($value
as$k=>$v){$config->php->{"$key.$k"}=$v;}}}foreach($config->php
as$key=>$value){$key=strtr($key,'-','.');if(!is_scalar($value)){throw
new
InvalidStateException("Configuration value for directive '$key' is not scalar.");}if($key==='date.timezone'){date_default_timezone_set($value);}if(function_exists('ini_set')){ini_set($key,$value);}else{switch($key){case'include_path':set_include_path($value);break;case'iconv.internal_encoding':iconv_set_encoding('internal_encoding',$value);break;case'mbstring.internal_encoding':mb_internal_encoding($value);break;case'date.timezone':date_default_timezone_set($value);break;case'error_reporting':error_reporting($value);break;case'ignore_user_abort':ignore_user_abort($value);break;case'max_execution_time':set_time_limit($value);break;default:if(ini_get($key)!=$value){throw
new
NotSupportedException('Required function ini_set() is disabled.');}}}}}if($config->const
instanceof
Config){foreach($config->const
as$key=>$value){define($key,$value);}}if(isset($config->mode)){foreach($config->mode
as$mode=>$state){Environment::setMode($mode,$state);}}foreach($runServices
as$name){$locator->getService($name);}return$config;}function
createServiceLocator(){$locator=new
ServiceLocator;foreach($this->defaultServices
as$name=>$service){$locator->addService($name,$service);}return$locator;}static
function
createCacheStorage(){return
new
FileStorage(Environment::getVariable('tempDir'));}static
function
createRobotLoader($options){$loader=new
RobotLoader;$loader->autoRebuild=!Environment::isProduction();$dirs=isset($options['directory'])?$options['directory']:array(Environment::getVariable('appDir'),Environment::getVariable('libsDir'));$loader->addDirectory($dirs);$loader->register();return$loader;}}final
class
Environment{const
DEVELOPMENT='development';const
PRODUCTION='production';const
CONSOLE='console';const
LAB='lab';const
DEBUG='debug';const
PERFORMANCE='performance';private
static$configurator;private
static$modes=array();private
static$config;private
static$serviceLocator;private
static$vars=array('encoding'=>array('UTF-8',FALSE),'lang'=>array('en',FALSE),'cacheBase'=>array('%tempDir%',TRUE),'tempDir'=>array('%appDir%/temp',TRUE),'logDir'=>array('%appDir%/log',TRUE));private
static$aliases=array('getHttpContext'=>'Nette\\Web\\HttpContext','getHttpRequest'=>'Nette\\Web\\IHttpRequest','getHttpResponse'=>'Nette\\Web\\IHttpResponse','getApplication'=>'Nette\\Application\\Application','getUser'=>'Nette\\Web\\IUser');private
static$criticalSections;final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
setConfigurator(Configurator$configurator){self::$configurator=$configurator;}static
function
getConfigurator(){if(self::$configurator===NULL){self::$configurator=new
Configurator;}return
self::$configurator;}static
function
setName($name){if(!isset(self::$vars['environment'])){self::setVariable('environment',$name,FALSE);}else{throw
new
InvalidStateException('Environment name has been already set.');}}static
function
getName(){$name=self::getVariable('environment');if($name===NULL){$name=self::getConfigurator()->detect('environment');self::setVariable('environment',$name,FALSE);}return$name;}static
function
setMode($mode,$value=TRUE){self::$modes[$mode]=(bool)$value;}static
function
getMode($mode){if(isset(self::$modes[$mode])){return
self::$modes[$mode];}else{return
self::$modes[$mode]=self::getConfigurator()->detect($mode);}}static
function
isConsole(){return
self::getMode('console');}static
function
isProduction(){return
self::getMode('production');}static
function
setVariable($name,$value,$expand=TRUE){if(!is_string($value)){$expand=FALSE;}self::$vars[$name]=array($value,(bool)$expand);}static
function
getVariable($name,$default=NULL){if(isset(self::$vars[$name])){list($var,$exp)=self::$vars[$name];if($exp){$var=self::expand($var);self::$vars[$name]=array($var,FALSE);}return$var;}else{$const=strtoupper(preg_replace('#(.)([A-Z]+)#','$1_$2',$name));$list=get_defined_constants(TRUE);if(isset($list['user'][$const])){self::$vars[$name]=array($list['user'][$const],FALSE);return$list['user'][$const];}else{return$default;}}}static
function
getVariables(){$res=array();foreach(self::$vars
as$name=>$foo){$res[$name]=self::getVariable($name);}return$res;}static
function
expand($var){if(is_string($var)&&strpos($var,'%')!==FALSE){return@preg_replace_callback('#%([a-z0-9_-]*)%#i',array(__CLASS__,'expandCb'),$var);}return$var;}private
static
function
expandCb($m){list(,$var)=$m;if($var==='')return'%';static$livelock;if(isset($livelock[$var])){throw
new
InvalidStateException("Circular reference detected for variables: ".implode(', ',array_keys($livelock)).".");}try{$livelock[$var]=TRUE;$val=self::getVariable($var);unset($livelock[$var]);}catch(Exception$e){$livelock=array();throw$e;}if($val===NULL){throw
new
InvalidStateException("Unknown environment variable '$var'.");}elseif(!is_scalar($val)){throw
new
InvalidStateException("Environment variable '$var' is not scalar.");}return$val;}static
function
getServiceLocator(){if(self::$serviceLocator===NULL){self::$serviceLocator=self::getConfigurator()->createServiceLocator();}return
self::$serviceLocator;}static
function
getService($name,array$options=NULL){return
self::getServiceLocator()->getService($name,$options);}static
function
setServiceAlias($service,$alias){self::$aliases['get'.ucfirst($alias)]=$service;}static
function
__callStatic($name,$args){if(isset(self::$aliases[$name])){return
self::getServiceLocator()->getService(self::$aliases[$name],$args);}else{throw
new
MemberAccessException("Call to undefined static method Nette\\Environment::$name().");}}static
function
getHttpRequest(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}static
function
getHttpContext(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}static
function
getHttpResponse(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}static
function
getApplication(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}static
function
getUser(){return
self::getServiceLocator()->getService(self::$aliases[__FUNCTION__]);}static
function
getCache($namespace=''){return
new
Cache(self::getService('Nette\\Caching\\ICacheStorage'),$namespace);}static
function
getSession($namespace=NULL){$handler=self::getService('Nette\\Web\\Session');return$namespace===NULL?$handler:$handler->getNamespace($namespace);}static
function
loadConfig($file=NULL){return
self::$config=self::getConfigurator()->loadConfig($file);}static
function
getConfig($key=NULL,$default=NULL){if(func_num_args()){return
isset(self::$config[$key])?self::$config[$key]:$default;}else{return
self::$config;}}static
function
enterCriticalSection($key){$file=self::getVariable('tempDir')."/criticalSection-".md5($key);$handle=fopen($file,'w');flock($handle,LOCK_EX);self::$criticalSections[$key]=array($file,$handle);}static
function
leaveCriticalSection($key){if(!isset(self::$criticalSections[$key])){throw
new
InvalidStateException('Critical section has not been initialized.');}list($file,$handle)=self::$criticalSections[$key];@unlink($file);fclose($handle);unset(self::$criticalSections[$key]);}}class
ServiceLocator
extends
Object
implements
IServiceLocator{private$parent;private$registry=array();private$factories=array();function
__construct(IServiceLocator$parent=NULL){$this->parent=$parent;}function
addService($name,$service,$singleton=TRUE,array$options=NULL){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);if(isset($this->registry[$lower])){throw
new
AmbiguousServiceException("Service named '$name' has been already registered.");}if(is_object($service)){if(!$singleton||$options){throw
new
InvalidArgumentException("Service named '$name' is an instantiated object and must therefore be singleton without options.");}$this->registry[$lower]=$service;}else{if(!$service){throw
new
InvalidArgumentException("Service named '$name' is empty.");}$this->factories[$lower]=array($service,$singleton,$options);}}function
removeService($name){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);unset($this->registry[$lower],$this->factories[$lower]);}function
getService($name,array$options=NULL){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);if(isset($this->registry[$lower])){if($options){throw
new
InvalidArgumentException("Service named '$name' is singleton and therefore can not have options.");}return$this->registry[$lower];}elseif(isset($this->factories[$lower])){list($factory,$singleton,$defOptions)=$this->factories[$lower];if($singleton&&$options){throw
new
InvalidArgumentException("Service named '$name' is singleton and therefore can not have options.");}elseif($defOptions){$options=$options?$options+$defOptions:$defOptions;}if(is_string($factory)&&strpos($factory,':')===FALSE){if($a=strrpos($factory,'\\'))$factory=substr($factory,$a+1);if(!class_exists($factory)){throw
new
AmbiguousServiceException("Cannot instantiate service '$name', class '$factory' not found.");}$service=new$factory;if($options&&method_exists($service,'setOptions')){$service->setOptions($options);}}else{$factory=callback($factory);if(!$factory->isCallable()){throw
new
InvalidStateException("Cannot instantiate service '$name', handler '$factory' is not callable.");}$service=$factory->invoke($options);if(!is_object($service)){throw
new
AmbiguousServiceException("Cannot instantiate service '$name', value returned by '$factory' is not object.");}}if($singleton){$this->registry[$lower]=$service;unset($this->factories[$lower]);}return$service;}if($this->parent!==NULL){return$this->parent->getService($name,$options);}else{throw
new
InvalidStateException("Service '$name' not found.");}}function
hasService($name,$created=FALSE){if(!is_string($name)||$name===''){throw
new
InvalidArgumentException("Service name must be a non-empty string, ".gettype($name)." given.");}$lower=strtolower($name);return
isset($this->registry[$lower])||(!$created&&isset($this->factories[$lower]))||($this->parent!==NULL&&$this->parent->hasService($name,$created));}function
getParent(){return$this->parent;}}class
AmbiguousServiceException
extends
Exception{}abstract
class
FormControl
extends
Component
implements
IFormControl{public
static$idMask='frm%s-%s';public$caption;protected$value;protected$control;protected$label;private$errors=array();private$disabled=FALSE;private$htmlId;private$htmlName;private$rules;private$translator=TRUE;private$options=array();function
__construct($caption=NULL){$this->monitor('Form');parent::__construct();$this->control=Html::el('input');$this->label=Html::el('label');$this->caption=$caption;$this->rules=new
Rules($this);}protected
function
attached($form){if(!$this->disabled&&$form
instanceof
Form&&$form->isAnchored()&&$form->isSubmitted()){$this->htmlName=NULL;$this->loadHttpData();}}function
getForm($need=TRUE){return$this->lookup('Form',$need);}function
getHtmlName(){if($this->htmlName===NULL){$s='';$name=$this->getName();$obj=$this->lookup('INamingContainer',TRUE);while(!($obj
instanceof
Form)){$s="[$name]$s";$name=$obj->getName();$obj=$obj->lookup('INamingContainer',TRUE);}$name.=$s;if($name==='submit'){throw
new
InvalidArgumentException("Form control name 'submit' is not allowed due to JavaScript limitations.");}$this->htmlName=$name;}return$this->htmlName;}function
setHtmlId($id){$this->htmlId=$id;return$this;}function
getHtmlId(){if($this->htmlId===FALSE){return
NULL;}elseif($this->htmlId===NULL){$this->htmlId=sprintf(self::$idMask,$this->getForm()->getName(),$this->getHtmlName());$this->htmlId=str_replace(array('[]','[',']'),array('','-',''),$this->htmlId);}return$this->htmlId;}function
setOption($key,$value){if($value===NULL){unset($this->options[$key]);}else{$this->options[$key]=$value;}return$this;}final
function
getOption($key,$default=NULL){return
isset($this->options[$key])?$this->options[$key]:$default;}final
function
getOptions(){return$this->options;}function
setTranslator(ITranslator$translator=NULL){$this->translator=$translator;return$this;}final
function
getTranslator(){if($this->translator===TRUE){return$this->getForm(FALSE)?$this->getForm()->getTranslator():NULL;}return$this->translator;}function
translate($s,$count=NULL){$translator=$this->getTranslator();return$translator===NULL?$s:$translator->translate($s,$count);}function
setValue($value){$this->value=$value;return$this;}function
getValue(){return$this->value;}function
setDefaultValue($value){$form=$this->getForm(FALSE);if(!$form||!$form->isAnchored()||!$form->isSubmitted()){$this->setValue($value);}return$this;}function
loadHttpData(){$path=explode('[',strtr(str_replace(array('[]',']'),'',$this->getHtmlName()),'.','_'));$this->setValue(ArrayTools::get($this->getForm()->getHttpData(),$path));}function
setDisabled($value=TRUE){$this->disabled=(bool)$value;return$this;}function
isDisabled(){return$this->disabled;}function
getControl(){$this->setOption('rendered',TRUE);$control=clone$this->control;$control->name=$this->getHtmlName();$control->disabled=$this->disabled;$control->id=$this->getHtmlId();return$control;}function
getLabel($caption=NULL){$label=clone$this->label;$label->for=$this->getHtmlId();if($caption!==NULL){$label->setText($this->translate($caption));}elseif($this->caption
instanceof
Html){$label->add($this->caption);}else{$label->setText($this->translate($this->caption));}return$label;}final
function
getControlPrototype(){return$this->control;}final
function
getLabelPrototype(){return$this->label;}function
setRendered($value=TRUE){$this->setOption('rendered',$value);return$this;}function
isRendered(){return!empty($this->options['rendered']);}function
addRule($operation,$message=NULL,$arg=NULL){$this->rules->addRule($operation,$message,$arg);return$this;}function
addCondition($operation,$value=NULL){return$this->rules->addCondition($operation,$value);}function
addConditionOn(IFormControl$control,$operation,$value=NULL){return$this->rules->addConditionOn($control,$operation,$value);}final
function
getRules(){return$this->rules;}final
function
setRequired($message=NULL){$this->rules->addRule(':Filled',$message);return$this;}final
function
isRequired(){return!empty($this->options['required']);}function
notifyRule(Rule$rule){if(is_string($rule->operation)&&strcasecmp($rule->operation,':filled')===0){$this->setOption('required',TRUE);}}static
function
validateEqual(IFormControl$control,$arg){$value=$control->getValue();foreach((is_array($value)?$value:array($value))as$val){foreach((is_array($arg)?$arg:array($arg))as$item){if((string)$val===(string)($item
instanceof
IFormControl?$item->value:$item)){return
TRUE;}}}return
FALSE;}static
function
validateFilled(IFormControl$control){return(string)$control->getValue()!=='';}static
function
validateValid(IFormControl$control){return$control->rules->validate(TRUE);}function
addError($message){if(!in_array($message,$this->errors,TRUE)){$this->errors[]=$message;}$this->getForm()->addError($message);}function
getErrors(){return$this->errors;}function
hasErrors(){return(bool)$this->errors;}function
cleanErrors(){$this->errors=array();}}class
Button
extends
FormControl{function
__construct($caption=NULL){parent::__construct($caption);$this->control->type='button';}function
getLabel($caption=NULL){return
NULL;}function
getControl($caption=NULL){$control=parent::getControl();$control->value=$this->translate($caption===NULL?$this->caption:$caption);return$control;}}class
Checkbox
extends
FormControl{function
__construct($label=NULL){parent::__construct($label);$this->control->type='checkbox';$this->value=FALSE;}function
setValue($value){$this->value=is_scalar($value)?(bool)$value:FALSE;return$this;}function
getControl(){return
parent::getControl()->checked($this->value);}}class
FileUpload
extends
FormControl{function
__construct($label=NULL){parent::__construct($label);$this->control->type='file';}protected
function
attached($form){if($form
instanceof
Form){if($form->getMethod()!==Form::POST){throw
new
InvalidStateException('File upload requires method POST.');}$form->getElementPrototype()->enctype='multipart/form-data';}parent::attached($form);}function
setValue($value){if(is_array($value)){$this->value=new
HttpUploadedFile($value);}elseif($value
instanceof
HttpUploadedFile){$this->value=$value;}else{$this->value=new
HttpUploadedFile(NULL);}return$this;}static
function
validateFilled(IFormControl$control){$file=$control->getValue();return$file
instanceof
HttpUploadedFile&&$file->isOK();}static
function
validateFileSize(FileUpload$control,$limit){$file=$control->getValue();return$file
instanceof
HttpUploadedFile&&$file->getSize()<=$limit;}static
function
validateMimeType(FileUpload$control,$mimeType){$file=$control->getValue();if($file
instanceof
HttpUploadedFile){$type=strtolower($file->getContentType());$mimeTypes=is_array($mimeType)?$mimeType:explode(',',$mimeType);if(in_array($type,$mimeTypes,TRUE)){return
TRUE;}if(in_array(preg_replace('#/.*#','/*',$type),$mimeTypes,TRUE)){return
TRUE;}}return
FALSE;}static
function
validateImage(FileUpload$control){$file=$control->getValue();return$file
instanceof
HttpUploadedFile&&$file->isImage();}}class
HiddenField
extends
FormControl{private$forcedValue;function
__construct($forcedValue=NULL){parent::__construct();$this->control->type='hidden';$this->value=(string)$forcedValue;$this->forcedValue=$forcedValue;}function
getLabel($caption=NULL){return
NULL;}function
setValue($value){$this->value=is_scalar($value)?(string)$value:'';return$this;}function
getControl(){return
parent::getControl()->value($this->forcedValue===NULL?$this->value:$this->forcedValue);}}class
SubmitButton
extends
Button
implements
ISubmitterControl{public$onClick;public$onInvalidClick;private$validationScope=TRUE;function
__construct($caption=NULL){parent::__construct($caption);$this->control->type='submit';}function
setValue($value){$this->value=is_scalar($value)&&(bool)$value;$form=$this->getForm();if($this->value||!is_object($form->isSubmitted())){$this->value=TRUE;$form->setSubmittedBy($this);}return$this;}function
isSubmittedBy(){return$this->getForm()->isSubmitted()===$this;}function
setValidationScope($scope){$this->validationScope=(bool)$scope;return$this;}final
function
getValidationScope(){return$this->validationScope;}function
click(){$this->onClick($this);}static
function
validateSubmitted(ISubmitterControl$control){return$control->isSubmittedBy();}}class
ImageButton
extends
SubmitButton{function
__construct($src=NULL,$alt=NULL){parent::__construct();$this->control->type='image';$this->control->src=$src;$this->control->alt=$alt;}function
getHtmlName(){$name=parent::getHtmlName();return
strpos($name,'[')===FALSE?$name:$name.'[]';}function
loadHttpData(){$path=$this->getHtmlName();$path=explode('[',strtr(str_replace(']','',strpos($path,'[')===FALSE?$path.'.x':substr($path,0,-2)),'.','_'));$this->setValue(ArrayTools::get($this->getForm()->getHttpData(),$path)!==NULL);}}class
SelectBox
extends
FormControl{private$items=array();protected$allowed=array();private$skipFirst=FALSE;private$useKeys=TRUE;function
__construct($label=NULL,array$items=NULL,$size=NULL){parent::__construct($label);$this->control->setName('select');$this->control->size=$size>1?(int)$size:NULL;$this->control->onfocus='this.onmousewheel=function(){return false}';$this->label->onclick='document.getElementById(this.htmlFor).focus();return false';if($items!==NULL){$this->setItems($items);}}function
getValue(){$allowed=$this->allowed;if($this->skipFirst){$allowed=array_slice($allowed,1,count($allowed),TRUE);}return
is_scalar($this->value)&&isset($allowed[$this->value])?$this->value:NULL;}function
getRawValue(){return
is_scalar($this->value)?$this->value:NULL;}function
skipFirst($item=NULL){if(is_bool($item)){$this->skipFirst=$item;}else{$this->skipFirst=TRUE;if($item!==NULL){$this->items=array(''=>$item)+$this->items;$this->allowed=array(''=>'')+$this->allowed;}}return$this;}final
function
isFirstSkipped(){return$this->skipFirst;}final
function
areKeysUsed(){return$this->useKeys;}function
setItems(array$items,$useKeys=TRUE){$this->items=$items;$this->allowed=array();$this->useKeys=(bool)$useKeys;foreach($items
as$key=>$value){if(!is_array($value)){$value=array($key=>$value);}foreach($value
as$key2=>$value2){if(!$this->useKeys){if(!is_scalar($value2)){throw
new
InvalidArgumentException("All items must be scalars.");}$key2=$value2;}if(isset($this->allowed[$key2])){throw
new
InvalidArgumentException("Items contain duplication for key '$key2'.");}$this->allowed[$key2]=$value2;}}return$this;}final
function
getItems(){return$this->items;}function
getSelectedItem(){if(!$this->useKeys){return$this->getValue();}else{$value=$this->getValue();return$value===NULL?NULL:$this->allowed[$value];}}function
getControl(){$control=parent::getControl();$selected=$this->getValue();$selected=is_array($selected)?array_flip($selected):array($selected=>TRUE);$option=Html::el('option');foreach($this->items
as$key=>$value){if(!is_array($value)){$value=array($key=>$value);$dest=$control;}else{$dest=$control->create('optgroup')->label($key);}foreach($value
as$key2=>$value2){if($value2
instanceof
Html){$dest->add((string)$value2->selected(isset($selected[$key2])));}elseif($this->useKeys){$dest->add((string)$option->value($key2)->selected(isset($selected[$key2]))->setText($this->translate($value2)));}else{$dest->add((string)$option->selected(isset($selected[$value2]))->setText($this->translate($value2)));}}}return$control;}static
function
validateFilled(IFormControl$control){$value=$control->getValue();return
is_array($value)?count($value)>0:$value!==NULL;}}class
MultiSelectBox
extends
SelectBox{function
getValue(){$allowed=array_keys($this->allowed);if($this->isFirstSkipped()){unset($allowed[0]);}return
array_intersect($this->getRawValue(),$allowed);}function
getRawValue(){if(is_scalar($this->value)){$value=array($this->value);}elseif(!is_array($this->value)){$value=array();}else{$value=$this->value;}$res=array();foreach($value
as$val){if(is_scalar($val)){$res[]=$val;}}return$res;}function
getSelectedItem(){if(!$this->areKeysUsed()){return$this->getValue();}else{$res=array();foreach($this->getValue()as$value){$res[$value]=$this->allowed[$value];}return$res;}}function
getHtmlName(){return
parent::getHtmlName().'[]';}function
getControl(){$control=parent::getControl();$control->multiple=TRUE;return$control;}}class
RadioList
extends
FormControl{protected$separator;protected$container;protected$items=array();function
__construct($label=NULL,array$items=NULL){parent::__construct($label);$this->control->type='radio';$this->container=Html::el();$this->separator=Html::el('br');if($items!==NULL)$this->setItems($items);}function
getValue($raw=FALSE){return
is_scalar($this->value)&&($raw||isset($this->items[$this->value]))?$this->value:NULL;}function
setItems(array$items){$this->items=$items;return$this;}final
function
getItems(){return$this->items;}final
function
getSeparatorPrototype(){return$this->separator;}final
function
getContainerPrototype(){return$this->container;}function
getControl($key=NULL){if($key===NULL){$container=clone$this->container;$separator=(string)$this->separator;}elseif(!isset($this->items[$key])){return
NULL;}$control=parent::getControl();$id=$control->id;$counter=-1;$value=$this->value===NULL?NULL:(string)$this->getValue();$label=Html::el('label');foreach($this->items
as$k=>$val){$counter++;if($key!==NULL&&$key!=$k)continue;$control->id=$label->for=$id.'-'.$counter;$control->checked=(string)$k===$value;$control->value=$k;if($val
instanceof
Html){$label->setHtml($val);}else{$label->setText($this->translate($val));}if($key!==NULL){return(string)$control.(string)$label;}$container->add((string)$control.(string)$label.$separator);}return$container;}function
getLabel($caption=NULL){$label=parent::getLabel($caption);$label->for=NULL;return$label;}static
function
validateFilled(IFormControl$control){return$control->getValue()!==NULL;}}abstract
class
TextBase
extends
FormControl{protected$emptyValue='';protected$filters=array();function
setValue($value){$this->value=is_scalar($value)?(string)$value:'';return$this;}function
getValue(){$value=$this->value;foreach($this->filters
as$filter){$value=(string)$filter->invoke($value);}return$value===$this->translate($this->emptyValue)?'':$value;}function
setEmptyValue($value){$this->emptyValue=$value;return$this;}final
function
getEmptyValue(){return$this->emptyValue;}function
addFilter($filter){$this->filters[]=callback($filter);return$this;}function
notifyRule(Rule$rule){if(is_string($rule->operation)&&strcasecmp($rule->operation,':float')===0){$this->addFilter(callback(__CLASS__,'filterFloat'));}parent::notifyRule($rule);}static
function
validateMinLength(TextBase$control,$length){return
String::length($control->getValue())>=$length;}static
function
validateMaxLength(TextBase$control,$length){return
String::length($control->getValue())<=$length;}static
function
validateLength(TextBase$control,$range){if(!is_array($range)){$range=array($range,$range);}$len=String::length($control->getValue());return($range[0]===NULL||$len>=$range[0])&&($range[1]===NULL||$len<=$range[1]);}static
function
validateEmail(TextBase$control){$atom="[-a-z0-9!#$%&'*+/=?^_`{|}~]";$localPart="(?:\"(?:[ !\\x23-\\x5B\\x5D-\\x7E]*|\\\\[ -~])+\"|$atom+(?:\\.$atom+)*)";$chars="a-z0-9\x80-\xFF";$domain="[$chars](?:[-$chars]{0,61}[$chars])";return(bool)String::match($control->getValue(),"(^$localPart@(?:$domain?\\.)+[-$chars]{2,19}\\z)i");}static
function
validateUrl(TextBase$control){return(bool)String::match($control->getValue(),'/^.+\.[a-z]{2,6}(?:\\/.*)?$/i');}static
function
validateRegexp(TextBase$control,$regexp){return(bool)String::match($control->getValue(),$regexp);}static
function
validateInteger(TextBase$control){return(bool)String::match($control->getValue(),'/^-?[0-9]+$/');}static
function
validateFloat(TextBase$control){return(bool)String::match($control->getValue(),'/^-?[0-9]*[.,]?[0-9]+$/');}static
function
validateRange(TextBase$control,$range){return($range[0]===NULL||$control->getValue()>=$range[0])&&($range[1]===NULL||$control->getValue()<=$range[1]);}static
function
filterFloat($s){return
str_replace(array(' ',','),array('','.'),$s);}}class
TextArea
extends
TextBase{function
__construct($label=NULL,$cols=NULL,$rows=NULL){parent::__construct($label);$this->control->setName('textarea');$this->control->cols=$cols;$this->control->rows=$rows;$this->value='';}function
getControl(){$control=parent::getControl();$control->setText($this->getValue()===''?$this->translate($this->emptyValue):$this->value);return$control;}}class
TextInput
extends
TextBase{function
__construct($label=NULL,$cols=NULL,$maxLength=NULL){parent::__construct($label);$this->control->type='text';$this->control->size=$cols;$this->control->maxlength=$maxLength;$this->filters[]=callback('String','trim');$this->filters[]=callback($this,'checkMaxLength');$this->value='';}function
checkMaxLength($value){if($this->control->maxlength&&String::length($value)>$this->control->maxlength){$value=iconv_substr($value,0,$this->control->maxlength,'UTF-8');}return$value;}function
setPasswordMode($mode=TRUE){$this->control->type=$mode?'password':'text';return$this;}function
getControl(){$control=parent::getControl();if($this->control->type!=='password'){$control->value=$this->getValue()===''?$this->translate($this->emptyValue):$this->value;}return$control;}function
notifyRule(Rule$rule){if(is_string($rule->operation)&&strcasecmp($rule->operation,':length')===0&&!$rule->isNegative){$this->control->maxlength=is_array($rule->arg)?$rule->arg[1]:$rule->arg;}elseif(is_string($rule->operation)&&strcasecmp($rule->operation,':maxLength')===0&&!$rule->isNegative){$this->control->maxlength=$rule->arg;}parent::notifyRule($rule);}}class
FormGroup
extends
Object{protected$controls;private$options=array();function
__construct(){$this->controls=new
SplObjectStorage;}function
add(){foreach(func_get_args()as$num=>$item){if($item
instanceof
IFormControl){$this->controls->attach($item);}elseif($item
instanceof
Traversable||is_array($item)){foreach($item
as$control){$this->controls->attach($control);}}else{throw
new
InvalidArgumentException("Only IFormControl items are allowed, the #$num parameter is invalid.");}}return$this;}function
getControls(){return
iterator_to_array($this->controls);}function
setOption($key,$value){if($value===NULL){unset($this->options[$key]);}else{$this->options[$key]=$value;}return$this;}final
function
getOption($key,$default=NULL){return
isset($this->options[$key])?$this->options[$key]:$default;}final
function
getOptions(){return$this->options;}}class
ConventionalRenderer
extends
Object
implements
IFormRenderer{public$wrappers=array('form'=>array('container'=>NULL,'errors'=>TRUE),'error'=>array('container'=>'ul class=error','item'=>'li'),'group'=>array('container'=>'fieldset','label'=>'legend','description'=>'p'),'controls'=>array('container'=>'table'),'pair'=>array('container'=>'tr','.required'=>'required','.optional'=>NULL,'.odd'=>NULL),'control'=>array('container'=>'td','.odd'=>NULL,'errors'=>FALSE,'description'=>'small','requiredsuffix'=>'','.required'=>'required','.text'=>'text','.password'=>'text','.file'=>'text','.submit'=>'button','.image'=>'imagebutton','.button'=>'button'),'label'=>array('container'=>'th','suffix'=>NULL,'requiredsuffix'=>''),'hidden'=>array('container'=>'div'));protected$form;protected$clientScript=TRUE;protected$counter;function
render(Form$form,$mode=NULL){if($this->form!==$form){$this->form=$form;$this->init();}$s='';if(!$mode||$mode==='begin'){$s.=$this->renderBegin();}if((!$mode&&$this->getValue('form errors'))||$mode==='errors'){$s.=$this->renderErrors();}if(!$mode||$mode==='body'){$s.=$this->renderBody();}if(!$mode||$mode==='end'){$s.=$this->renderEnd();}return$s;}function
setClientScript($clientScript=NULL){$this->clientScript=$clientScript;return$this;}function
getClientScript(){if($this->clientScript===TRUE){$this->clientScript=new
InstantClientScript($this->form);}return$this->clientScript;}protected
function
init(){$clientScript=$this->getClientScript();if($clientScript!==NULL){$clientScript->enable();}$wrapper=&$this->wrappers['control'];foreach($this->form->getControls()as$control){if($control->getOption('required')&&isset($wrapper['.required'])){$control->getLabelPrototype()->class($wrapper['.required'],TRUE);}$el=$control->getControlPrototype();if($el->getName()==='input'&&isset($wrapper['.'.$el->type])){$el->class($wrapper['.'.$el->type],TRUE);}}}function
renderBegin(){$this->counter=0;foreach($this->form->getControls()as$control){$control->setOption('rendered',FALSE);}if(strcasecmp($this->form->getMethod(),'get')===0){$el=clone$this->form->getElementPrototype();$uri=explode('?',(string)$el->action,2);$el->action=$uri[0];$s='';if(isset($uri[1])){foreach(preg_split('#[;&]#',$uri[1])as$param){$parts=explode('=',$param,2);$name=urldecode($parts[0]);if(!isset($this->form[$name])){$s.=Html::el('input',array('type'=>'hidden','name'=>$name,'value'=>urldecode($parts[1])));}}$s="\n\t".$this->getWrapper('hidden container')->setHtml($s);}return$el->startTag().$s;}else{return$this->form->getElementPrototype()->startTag();}}function
renderEnd(){$s='';foreach($this->form->getControls()as$control){if($control
instanceof
HiddenField&&!$control->getOption('rendered')){$s.=(string)$control->getControl();}}if($s){$s=$this->getWrapper('hidden container')->setHtml($s)."\n";}$s.=$this->form->getElementPrototype()->endTag()."\n";$clientScript=$this->getClientScript();if($clientScript!==NULL){$s.=$clientScript->renderClientScript()."\n";}return$s;}function
renderErrors(IFormControl$control=NULL){$errors=$control===NULL?$this->form->getErrors():$control->getErrors();if(count($errors)){$ul=$this->getWrapper('error container');$li=$this->getWrapper('error item');foreach($errors
as$error){$item=clone$li;if($error
instanceof
Html){$item->add($error);}else{$item->setText($error);}$ul->add($item);}return"\n".$ul->render(0);}}function
renderBody(){$s=$remains='';$defaultContainer=$this->getWrapper('group container');$translator=$this->form->getTranslator();foreach($this->form->getGroups()as$group){if(!$group->getControls()||!$group->getOption('visual'))continue;$container=$group->getOption('container',$defaultContainer);$container=$container
instanceof
Html?clone$container:Html::el($container);$s.="\n".$container->startTag();$text=$group->getOption('label');if($text
instanceof
Html){$s.=$text;}elseif(is_string($text)){if($translator!==NULL){$text=$translator->translate($text);}$s.="\n".$this->getWrapper('group label')->setText($text)."\n";}$text=$group->getOption('description');if($text
instanceof
Html){$s.=$text;}elseif(is_string($text)){if($translator!==NULL){$text=$translator->translate($text);}$s.=$this->getWrapper('group description')->setText($text)."\n";}$s.=$this->renderControls($group);$remains=$container->endTag()."\n".$remains;if(!$group->getOption('embedNext')){$s.=$remains;$remains='';}}$s.=$remains.$this->renderControls($this->form);$container=$this->getWrapper('form container');$container->setHtml($s);return$container->render(0);}function
renderControls($parent){if(!($parent
instanceof
FormContainer||$parent
instanceof
FormGroup)){throw
new
InvalidArgumentException("Argument must be FormContainer or FormGroup instance.");}$container=$this->getWrapper('controls container');$buttons=NULL;foreach($parent->getControls()as$control){if($control->getOption('rendered')||$control
instanceof
HiddenField||$control->getForm(FALSE)!==$this->form){}elseif($control
instanceof
Button){$buttons[]=$control;}else{if($buttons){$container->add($this->renderPairMulti($buttons));$buttons=NULL;}$container->add($this->renderPair($control));}}if($buttons){$container->add($this->renderPairMulti($buttons));}$s='';if(count($container)){$s.="\n".$container."\n";}return$s;}function
renderPair(IFormControl$control){$pair=$this->getWrapper('pair container');$pair->add($this->renderLabel($control));$pair->add($this->renderControl($control));$pair->class($this->getValue($control->getOption('required')?'pair .required':'pair .optional'),TRUE);$pair->class($control->getOption('class'),TRUE);if(++$this->counter
%
2)$pair->class($this->getValue('pair .odd'),TRUE);$pair->id=$control->getOption('id');return$pair->render(0);}function
renderPairMulti(array$controls){$s=array();foreach($controls
as$control){if(!($control
instanceof
IFormControl)){throw
new
InvalidArgumentException("Argument must be array of IFormControl instances.");}$s[]=(string)$control->getControl();}$pair=$this->getWrapper('pair container');$pair->add($this->renderLabel($control));$pair->add($this->getWrapper('control container')->setHtml(implode(" ",$s)));return$pair->render(0);}function
renderLabel(IFormControl$control){$head=$this->getWrapper('label container');if($control
instanceof
Checkbox||$control
instanceof
Button){return$head->setHtml(($head->getName()==='td'||$head->getName()==='th')?'&nbsp;':'');}else{$label=$control->getLabel();$suffix=$this->getValue('label suffix').($control->getOption('required')?$this->getValue('label requiredsuffix'):'');if($label
instanceof
Html){$label->setHtml($label->getHtml().$suffix);$suffix='';}return$head->setHtml((string)$label.$suffix);}}function
renderControl(IFormControl$control){$body=$this->getWrapper('control container');if($this->counter
%
2)$body->class($this->getValue('control .odd'),TRUE);$description=$control->getOption('description');if($description
instanceof
Html){$description=' '.$control->getOption('description');}elseif(is_string($description)){$description=' '.$this->getWrapper('control description')->setText($control->translate($description));}else{$description='';}if($control->getOption('required')){$description=$this->getValue('control requiredsuffix').$description;}if($this->getValue('control errors')){$description.=$this->renderErrors($control);}if($control
instanceof
Checkbox||$control
instanceof
Button){return$body->setHtml((string)$control->getControl().(string)$control->getLabel().$description);}else{return$body->setHtml((string)$control->getControl().$description);}}protected
function
getWrapper($name){$data=$this->getValue($name);return$data
instanceof
Html?clone$data:Html::el($data);}protected
function
getValue($name){$name=explode(' ',$name);$data=&$this->wrappers[$name[0]][$name[1]];return$data;}}final
class
InstantClientScript
extends
Object{private$validateScripts;private$toggleScript;private$central;private$form;function
__construct(Form$form){$this->form=$form;}function
enable(){$this->validateScripts=array();$this->toggleScript='';$this->central=TRUE;foreach($this->form->getControls()as$control){$script=$this->getValidateScript($control->getRules());if($script){$this->validateScripts[$control->getHtmlName()]=$script;}$this->toggleScript.=$this->getToggleScript($control->getRules());if($control
instanceof
ISubmitterControl&&$control->getValidationScope()!==TRUE){$this->central=FALSE;}}if($this->validateScripts||$this->toggleScript){if($this->central){$this->form->getElementPrototype()->onsubmit("return nette.validateForm(this)",TRUE);}else{foreach($this->form->getComponents(TRUE,'ISubmitterControl')as$control){if($control->getValidationScope()){$control->getControlPrototype()->onclick("return nette.validateForm(this)",TRUE);}}}}}function
renderClientScript(){if(!$this->validateScripts&&!$this->toggleScript){return;}$formName=json_encode((string)$this->form->getElementPrototype()->id);ob_start();?>
<!-- Nette Form validator -->

<script type="text/javascript">/*<![CDATA[*/var nette=nette||{};nette.getValue=function(a){if(a){if(!a.nodeName){for(var b=0,d=a.length;b<d;b++)if(a[b].checked)return a[b].value;return null}if(a.nodeName.toLowerCase()==="select"){b=a.selectedIndex;var c=a.options;if(b<0)return null;else if(a.type==="select-one")return c[b].value;b=0;a=[];for(d=c.length;b<d;b++)c[b].selected&&a.push(c[b].value);return a}if(a.type==="checkbox")return a.checked;return a.value.replace(/^\s+|\s+$/g,"")}};
nette.getFormValidators=function(a){a=a.getAttributeNode("id").nodeValue;return this.forms[a]?this.forms[a].validators:[]};nette.validateControl=function(a){var b=this.getFormValidators(a.form)[a.name];return b?b(a):null};nette.validateForm=function(a){var b=a.form||a,d=this.getFormValidators(b);for(var c in d){var e=d[c](a);if(e){b[c].focus&&b[c].focus();alert(e);return false}}return true};nette.toggle=function(a,b){if(a=document.getElementById(a))a.style.display=b?"":"none"};/*]]>*/</script>

<script type="text/javascript">
/* <![CDATA[ */

nette.forms = nette.forms || { };

nette.forms[<?php echo$formName?>] = {
	validators: {
<?php $count=count($this->validateScripts);?>
<?php foreach($this->validateScripts
as$name=>$validateScript):?>
		<?php echo
json_encode((string)$name)?>: function(sender) {
			var res, val, form = sender.form || sender;
<?php echo
String::indent($validateScript,3)?>
		}<?php echo--$count?',':''?>

<?php endforeach?>
	},

	toggle: function(sender) {
		var visible, res, form = sender.form || sender;
<?php echo
String::indent($this->toggleScript,2)?>
	}
}


<?php if($this->toggleScript):?>
nette.forms[<?php echo$formName?>].toggle(document.getElementById(<?php echo$formName?>));
<?php endif?>

/* ]]> */
</script>

<!-- /Nette Form validator -->
<?php 
return
ob_get_clean();}private
function
getValidateScript(Rules$rules){$res='';foreach($rules
as$rule){if(!is_string($rule->operation))continue;if(strcasecmp($rule->operation,'Nette\\Forms\\InstantClientScript::javascript')===0){$res.="$rule->arg\n";continue;}$script=$this->getClientScript($rule->control,$rule->operation,$rule->arg);if(!$script)continue;if(!empty($rule->message)){$message=Rules::formatMessage($rule,FALSE);$res.="$script\n"."if (".($rule->isNegative?'':'!')."res) "."return ".json_encode((string)$message).(strpos($message,'%value')===FALSE?'':".replace('%value', val);\n").";\n";}if($rule->type===Rule::CONDITION){$innerScript=$this->getValidateScript($rule->subRules);if($innerScript){$res.="$script\nif (".($rule->isNegative?'!':'')."res) {\n".String::indent($innerScript)."}\n";if($rule->control
instanceof
ISubmitterControl){$this->central=FALSE;}}}}return$res;}private
function
getToggleScript(Rules$rules,$cond=NULL){$s='';foreach($rules->getToggles()as$id=>$visible){$s.="visible = true; {$cond}\n"."nette.toggle(".json_encode((string)$id).", ".($visible?'':'!')."visible);\n";}$formName=json_encode((string)$this->form->getElementPrototype()->id);foreach($rules
as$rule){if($rule->type===Rule::CONDITION&&is_string($rule->operation)){$script=$this->getClientScript($rule->control,$rule->operation,$rule->arg);if($script){$res=$this->getToggleScript($rule->subRules,$cond."$script visible = visible && ".($rule->isNegative?'!':'')."res;\n");if($res){$el=$rule->control->getControlPrototype();if($el->getName()==='select'){$el->onchange("nette.forms[$formName].toggle(this)",TRUE);}else{$el->onclick("nette.forms[$formName].toggle(this)",TRUE);}$s.=$res;}}}}return$s;}private
function
getClientScript(IFormControl$control,$operation,$arg){$operation=strtolower($operation);$elem='form['.json_encode($control->getHtmlName()).']';switch(TRUE){case$control
instanceof
HiddenField||$control->isDisabled():return
NULL;case$operation===':filled'&&$control
instanceof
RadioList:return"res = (val = nette.getValue($elem)) !== null;";case$operation===':submitted'&&$control
instanceof
SubmitButton:return"res = sender && sender.name==".json_encode($control->getHtmlName()).";";case$operation===':equal'&&$control
instanceof
MultiSelectBox:$tmp=array();foreach((is_array($arg)?$arg:array($arg))as$item){$tmp[]="options[i].value==".json_encode((string)$item);}$first=$control->isFirstSkipped()?1:0;return"var options = $elem.options; res = false;\n"."for (var i=$first, len=options.length; i<len; i++)\n\t"."if (options[i].selected && (".implode(' || ',$tmp).")) { res = true; break; }";case$operation===':filled'&&$control
instanceof
SelectBox:return"res = $elem.selectedIndex >= ".($control->isFirstSkipped()?1:0).";";case$operation===':filled'&&$control
instanceof
TextBase:return"val = nette.getValue($elem); res = val!='' && val!=".json_encode((string)$control->getEmptyValue()).";";case$operation===':minlength'&&$control
instanceof
TextBase:return"res = (val = nette.getValue($elem)).length>=".(int)$arg.";";case$operation===':maxlength'&&$control
instanceof
TextBase:return"res = (val = nette.getValue($elem)).length<=".(int)$arg.";";case$operation===':length'&&$control
instanceof
TextBase:if(!is_array($arg)){$arg=array($arg,$arg);}return"val = nette.getValue($elem); res = ".($arg[0]===NULL?"true":"val.length>=".(int)$arg[0])." && ".($arg[1]===NULL?"true":"val.length<=".(int)$arg[1]).";";case$operation===':email'&&$control
instanceof
TextBase:return'res = /^[^@\s]+@[^@\s]+\.[a-z]{2,10}$/i.test(val = nette.getValue('.$elem.'));';case$operation===':url'&&$control
instanceof
TextBase:return'res = /^.+\.[a-z]{2,6}(\\/.*)?$/i.test(val = nette.getValue('.$elem.'));';case$operation===':regexp'&&$control
instanceof
TextBase:if(!preg_match('#^(/.*/)([imu]*)$#',$arg,$matches)){return
NULL;}$arg=$matches[1].str_replace('u','',$matches[2]);return"res = $arg.test(val = nette.getValue($elem));";case$operation===':integer'&&$control
instanceof
TextBase:return"res = /^-?[0-9]+$/.test(val = nette.getValue($elem));";case$operation===':float'&&$control
instanceof
TextBase:return"res = /^-?[0-9]*[.,]?[0-9]+$/.test(val = nette.getValue($elem));";case$operation===':range'&&$control
instanceof
TextBase:return"val = nette.getValue($elem); res = ".($arg[0]===NULL?"true":"parseFloat(val)>=".json_encode((float)$arg[0]))." && ".($arg[1]===NULL?"true":"parseFloat(val)<=".json_encode((float)$arg[1])).";";case$operation===':filled'&&$control
instanceof
FormControl:return"res = (val = nette.getValue($elem)) != '';";case$operation===':valid'&&$control
instanceof
FormControl:return"res = !this[".json_encode($control->getHtmlName())."](sender);";case$operation===':equal'&&$control
instanceof
FormControl:if($control
instanceof
Checkbox)$arg=(bool)$arg;$tmp=array();foreach((is_array($arg)?$arg:array($arg))as$item){if($item
instanceof
IFormControl){$tmp[]="val==nette.getValue(form[".json_encode($item->getHtmlName())."])";}else{$tmp[]="val==".json_encode($item);}}return"val = nette.getValue($elem); res = (".implode(' || ',$tmp).");";}}static
function
javascript(){return
TRUE;}}final
class
Rule
extends
Object{const
CONDITION=1;const
VALIDATOR=2;const
FILTER=3;const
TERMINATOR=4;public$control;public$operation;public$arg;public$type;public$isNegative=FALSE;public$message;public$breakOnFailure=TRUE;public$subRules;}final
class
Rules
extends
Object
implements
IteratorAggregate{const
VALIDATE_PREFIX='validate';public
static$defaultMessages=array();private$rules=array();private$parent;private$toggles=array();private$control;function
__construct(IFormControl$control){$this->control=$control;}function
addRule($operation,$message=NULL,$arg=NULL){$rule=new
Rule;$rule->control=$this->control;$rule->operation=$operation;$this->adjustOperation($rule);$rule->arg=$arg;$rule->type=Rule::VALIDATOR;if($message===NULL&&isset(self::$defaultMessages[$rule->operation])){$rule->message=self::$defaultMessages[$rule->operation];}else{$rule->message=$message;}if($this->parent===NULL){$this->control->notifyRule($rule);}$this->rules[]=$rule;return$this;}function
addCondition($operation,$arg=NULL){return$this->addConditionOn($this->control,$operation,$arg);}function
addConditionOn(IFormControl$control,$operation,$arg=NULL){$rule=new
Rule;$rule->control=$control;$rule->operation=$operation;$this->adjustOperation($rule);$rule->arg=$arg;$rule->type=Rule::CONDITION;$rule->subRules=new
self($this->control);$rule->subRules->parent=$this;$this->rules[]=$rule;return$rule->subRules;}function
elseCondition(){$rule=clone
end($this->parent->rules);$rule->isNegative=!$rule->isNegative;$rule->subRules=new
self($this->parent->control);$rule->subRules->parent=$this->parent;$this->parent->rules[]=$rule;return$rule->subRules;}function
endCondition(){return$this->parent;}function
toggle($id,$hide=TRUE){$this->toggles[$id]=$hide;return$this;}function
validate($onlyCheck=FALSE){$valid=TRUE;foreach($this->rules
as$rule){if($rule->control->isDisabled())continue;$success=($rule->isNegative
xor$this->getCallback($rule)->invoke($rule->control,$rule->arg));if($rule->type===Rule::CONDITION&&$success){$success=$rule->subRules->validate($onlyCheck);$valid=$valid&&$success;}elseif($rule->type===Rule::VALIDATOR&&!$success){if($onlyCheck){return
FALSE;}$rule->control->addError(self::formatMessage($rule,TRUE));$valid=FALSE;if($rule->breakOnFailure){break;}}}return$valid;}final
function
getIterator(){return
new
ArrayIterator($this->rules);}final
function
getToggles(){return$this->toggles;}private
function
adjustOperation($rule){if(is_string($rule->operation)&&ord($rule->operation[0])>127){$rule->isNegative=TRUE;$rule->operation=~$rule->operation;}if(!$this->getCallback($rule)->isCallable()){$operation=is_scalar($rule->operation)?" '$rule->operation'":'';throw
new
InvalidArgumentException("Unknown operation$operation for control '{$rule->control->name}'.");}}private
function
getCallback($rule){$op=$rule->operation;if(is_string($op)&&strncmp($op,':',1)===0){return
callback(get_class($rule->control),self::VALIDATE_PREFIX.ltrim($op,':'));}else{return
callback($op);}}static
function
formatMessage($rule,$withValue){$message=$rule->message;if($translator=$rule->control->getForm()->getTranslator()){$message=$translator->translate($message,is_int($rule->arg)?$rule->arg:NULL);}$message=str_replace('%name',$rule->control->getName(),$message);$message=str_replace('%label',$rule->control->translate($rule->control->caption),$message);if(strpos($message,'%value')!==FALSE){$message=str_replace('%value',$withValue?(string)$rule->control->getValue():'%%value',$message);}$message=vsprintf($message,(array)$rule->arg);return$message;}}class
RobotLoader
extends
AutoLoader{public$scanDirs;public$ignoreDirs='.*, *.old, *.bak, *.tmp, temp';public$acceptFiles='*.php, *.php5';public$autoRebuild;private$list=array();private$files;private$rebuilded=FALSE;private$acceptMask;private$ignoreMask;function
__construct(){if(!extension_loaded('tokenizer')){throw
new
Exception("PHP extension Tokenizer is not loaded.");}}function
register(){$cache=$this->getCache();$key=$this->getKey();if(isset($cache[$key])){$this->list=$cache[$key];}else{$this->rebuild();}if(isset($this->list[strtolower(__CLASS__)])&&class_exists('NetteLoader',FALSE)){NetteLoader::getInstance()->unregister();}parent::register();}function
tryLoad($type){$type=ltrim(strtolower($type),'\\');if(isset($this->list[$type])){if($this->list[$type]!==FALSE){LimitedScope::load($this->list[$type][0]);self::$count++;}}else{$this->list[$type]=FALSE;if($this->autoRebuild===NULL){$this->autoRebuild=!$this->isProduction();}if($this->autoRebuild){if($this->rebuilded){$this->getCache()->save($this->getKey(),$this->list);}else{$this->rebuild();}}if($this->list[$type]!==FALSE){LimitedScope::load($this->list[$type][0]);self::$count++;}}}function
rebuild(){$this->getCache()->save($this->getKey(),callback($this,'_rebuildCallback'));$this->rebuilded=TRUE;}function
_rebuildCallback(){$this->acceptMask=self::wildcards2re($this->acceptFiles);$this->ignoreMask=self::wildcards2re($this->ignoreDirs);foreach($this->list
as$pair){if($pair)$this->files[$pair[0]]=$pair[1];}foreach(array_unique($this->scanDirs)as$dir){$this->scanDirectory($dir);}$this->files=NULL;return$this->list;}function
getIndexedClasses(){$res=array();foreach($this->list
as$class=>$pair){if($pair)$res[$class]=$pair[0];}return$res;}function
addDirectory($path){foreach((array)$path
as$val){$real=realpath($val);if($real===FALSE){throw
new
DirectoryNotFoundException("Directory '$val' not found.");}$this->scanDirs[]=$real;}}private
function
addClass($class,$file,$time){$class=strtolower($class);if(!empty($this->list[$class])&&$this->list[$class][0]!==$file){spl_autoload_call($class);throw
new
InvalidStateException("Ambiguous class '$class' resolution; defined in $file and in ".$this->list[$class][0].".");}$this->list[$class]=array($file,$time);}private
function
scanDirectory($dir){if(is_file($dir)){if(!isset($this->files[$dir])||$this->files[$dir]!==filemtime($dir)){$this->scanScript($dir);}return;}$iterator=dir($dir);if(!$iterator)return;$disallow=array();if(is_file($dir.'/netterobots.txt')){foreach(file($dir.'/netterobots.txt')as$s){if($matches=String::match($s,'#^disallow\\s*:\\s*(\\S+)#i')){$disallow[trim($matches[1],'/')]=TRUE;}}if(isset($disallow['']))return;}while(FALSE!==($entry=$iterator->read())){if($entry=='.'||$entry=='..'||isset($disallow[$entry]))continue;$path=$dir.DIRECTORY_SEPARATOR.$entry;if(is_dir($path)){if(!String::match($entry,$this->ignoreMask)){$this->scanDirectory($path);}continue;}if(is_file($path)&&String::match($entry,$this->acceptMask)){if(!isset($this->files[$path])||$this->files[$path]!==filemtime($path)){$this->scanScript($path);}}}$iterator->close();}private
function
scanScript($file){$T_NAMESPACE=PHP_VERSION_ID<50300?-1:T_NAMESPACE;$T_NS_SEPARATOR=PHP_VERSION_ID<50300?-1:T_NS_SEPARATOR;$expected=FALSE;$namespace='';$level=0;$time=filemtime($file);$s=file_get_contents($file);if($matches=String::match($s,'#//nette'.'loader=(\S*)#')){foreach(explode(',',$matches[1])as$name){$this->addClass($name,$file,$time);}return;}foreach(token_get_all($s)as$token){if(is_array($token)){switch($token[0]){case
T_COMMENT:case
T_DOC_COMMENT:case
T_WHITESPACE:continue
2;case$T_NS_SEPARATOR:case
T_STRING:if($expected){$name.=$token[1];}continue
2;case$T_NAMESPACE:case
T_CLASS:case
T_INTERFACE:$expected=$token[0];$name='';continue
2;case
T_CURLY_OPEN:case
T_DOLLAR_OPEN_CURLY_BRACES:$level++;}}if($expected){switch($expected){case
T_CLASS:case
T_INTERFACE:if($level===0){$this->addClass($namespace.$name,$file,$time);}break;case$T_NAMESPACE:$namespace=$name.'\\';}$expected=NULL;}if($token==='{'){$level++;}elseif($token==='}'){$level--;}}}private
static
function
wildcards2re($wildcards){$mask=array();foreach(explode(',',$wildcards)as$wildcard){$wildcard=trim($wildcard);$wildcard=addcslashes($wildcard,'.\\+[^]$(){}=!><|:#');$wildcard=strtr($wildcard,array('*'=>'.*','?'=>'.'));$mask[]=$wildcard;}return'#^('.implode('|',$mask).')$#i';}protected
function
getCache(){return
Environment::getCache('Nette.RobotLoader');}protected
function
getKey(){return
md5("v2|$this->ignoreDirs|$this->acceptFiles|".implode('|',$this->scanDirs));}protected
function
isProduction(){return
Environment::isProduction();}}class
MailMimePart
extends
Object{const
ENCODING_BASE64='base64';const
ENCODING_7BIT='7bit';const
ENCODING_8BIT='8bit';const
ENCODING_QUOTED_PRINTABLE='quoted-printable';const
EOL="\r\n";const
LINE_LENGTH=76;private$headers=array();private$parts=array();private$body;function
setHeader($name,$value,$append=FALSE){if(!$name||preg_match('#[^a-z0-9-]#i',$name)){throw
new
InvalidArgumentException("Header name must be non-empty alphanumeric string, '$name' given.");}if($value==NULL){if(!$append){unset($this->headers[$name]);}}elseif(is_array($value)){$tmp=&$this->headers[$name];if(!$append||!is_array($tmp)){$tmp=array();}foreach($value
as$email=>$name){if($name!==NULL&&!String::checkEncoding($name)){throw
new
InvalidArgumentException("Name is not valid UTF-8 string.");}if(!preg_match('#^[^@",\s]+@[^@",\s]+\.[a-z]{2,10}$#i',$email)){throw
new
InvalidArgumentException("Email address '$email' is not valid.");}if(preg_match('#[\r\n]#',$name)){throw
new
InvalidArgumentException("Name cannot contain the line separator.");}$tmp[$email]=$name;}}else{$value=(string)$value;if(!String::checkEncoding($value)){throw
new
InvalidArgumentException("Header is not valid UTF-8 string.");}$this->headers[$name]=preg_replace('#[\r\n]+#',' ',$value);}return$this;}function
getHeader($name){return
isset($this->headers[$name])?$this->headers[$name]:NULL;}function
clearHeader($name){unset($this->headers[$name]);return$this;}function
getEncodedHeader($name){$offset=strlen($name)+2;if(!isset($this->headers[$name])){return
NULL;}elseif(is_array($this->headers[$name])){$s='';foreach($this->headers[$name]as$email=>$name){if($name!=NULL){$s.=self::encodeHeader(strspn($name,'.,;<@>()[]"=?')?'"'.addcslashes($name,'"\\').'"':$name,$offset);$email=" <$email>";}$email.=',';if($s!==''&&$offset+strlen($email)>self::LINE_LENGTH){$s.=self::EOL."\t";$offset=1;}$s.=$email;$offset+=strlen($email);}return
substr($s,0,-1);}else{return
self::encodeHeader($this->headers[$name],$offset);}}function
getHeaders(){return$this->headers;}function
setContentType($contentType,$charset=NULL){$this->setHeader('Content-Type',$contentType.($charset?"; charset=$charset":''));return$this;}function
setEncoding($encoding){$this->setHeader('Content-Transfer-Encoding',$encoding);return$this;}function
getEncoding(){return$this->getHeader('Content-Transfer-Encoding');}function
addPart(MailMimePart$part=NULL){return$this->parts[]=$part===NULL?new
self:$part;}function
setBody($body){$this->body=$body;return$this;}function
getBody(){return$this->body;}function
generateMessage(){$output='';$boundary='--------'.md5(uniqid('',TRUE));foreach($this->headers
as$name=>$value){$output.=$name.': '.$this->getEncodedHeader($name);if($this->parts&&$name==='Content-Type'){$output.=';'.self::EOL."\tboundary=\"$boundary\"";}$output.=self::EOL;}$output.=self::EOL;$body=(string)$this->body;if($body!==''){switch($this->getEncoding()){case
self::ENCODING_QUOTED_PRINTABLE:$output.=function_exists('quoted_printable_encode')?quoted_printable_encode($body):self::encodeQuotedPrintable($body);break;case
self::ENCODING_BASE64:$output.=rtrim(chunk_split(base64_encode($body),self::LINE_LENGTH,self::EOL));break;case
self::ENCODING_7BIT:$body=preg_replace('#[\x80-\xFF]+#','',$body);case
self::ENCODING_8BIT:$body=str_replace(array("\x00","\r"),'',$body);$body=str_replace("\n",self::EOL,$body);$output.=$body;break;default:throw
new
InvalidStateException('Unknown encoding');}}if($this->parts){if(substr($output,-strlen(self::EOL))!==self::EOL)$output.=self::EOL;foreach($this->parts
as$part){$output.='--'.$boundary.self::EOL.$part->generateMessage().self::EOL;}$output.='--'.$boundary.'--';}return$output;}private
static
function
encodeHeader($s,&$offset=0){if(strspn($s,"!\"#$%&\'()*+,-./0123456789:;<>@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^`abcdefghijklmnopqrstuvwxyz{|}=? _\r\n\t")===strlen($s)&&($offset+strlen($s)<=self::LINE_LENGTH)){$offset+=strlen($s);return$s;}$o=str_replace("\n ","\n\t",substr(iconv_mime_encode(str_repeat(' ',$offset),$s,array('scheme'=>'B','input-charset'=>'UTF-8','output-charset'=>'UTF-8')),$offset+2));$offset=strlen($o)-strrpos($o,"\n");return$o;}static
function
encodeQuotedPrintable($s){$range='!"#$%&\'()*+,-./0123456789:;<>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}';$pos=0;$len=0;$o='';$size=strlen($s);while($pos<$size){if($l=strspn($s,$range,$pos)){while($len+$l>self::LINE_LENGTH-1){$lx=self::LINE_LENGTH-$len-1;$o.=substr($s,$pos,$lx).'='.self::EOL;$pos+=$lx;$l-=$lx;$len=0;}$o.=substr($s,$pos,$l);$len+=$l;$pos+=$l;}else{$len+=3;if($len>self::LINE_LENGTH-1){$o.='='.self::EOL;$len=3;}$o.='='.strtoupper(bin2hex($s[$pos]));$pos++;}}return
rtrim($o,'='.self::EOL);}}class
Mail
extends
MailMimePart{const
HIGH=1;const
NORMAL=3;const
LOW=5;public
static$defaultMailer='SendmailMailer';public
static$defaultHeaders=array('MIME-Version'=>'1.0','X-Mailer'=>'Nette Framework');private$mailer;private$attachments=array();private$inlines=array();private$html;private$basePath;function
__construct(){foreach(self::$defaultHeaders
as$name=>$value){$this->setHeader($name,$value);}$this->setHeader('Date',date('r'));}function
setFrom($email,$name=NULL){$this->setHeader('From',$this->formatEmail($email,$name));return$this;}function
getFrom(){return$this->getHeader('From');}function
addReplyTo($email,$name=NULL){$this->setHeader('Reply-To',$this->formatEmail($email,$name),TRUE);return$this;}function
setSubject($subject){$this->setHeader('Subject',$subject);return$this;}function
getSubject(){return$this->getHeader('Subject');}function
addTo($email,$name=NULL){$this->setHeader('To',$this->formatEmail($email,$name),TRUE);return$this;}function
addCc($email,$name=NULL){$this->setHeader('Cc',$this->formatEmail($email,$name),TRUE);return$this;}function
addBcc($email,$name=NULL){$this->setHeader('Bcc',$this->formatEmail($email,$name),TRUE);return$this;}private
function
formatEmail($email,$name){if(!$name&&preg_match('#^(.+) +<(.*)>$#',$email,$matches)){return
array($matches[2]=>$matches[1]);}else{return
array($email=>$name);}}function
setReturnPath($email){$this->setHeader('Return-Path',$email);return$this;}function
getReturnPath(){return$this->getHeader('From');}function
setPriority($priority){$this->setHeader('X-Priority',(int)$priority);return$this;}function
getPriority(){return$this->getHeader('X-Priority');}function
setHtmlBody($html,$basePath=NULL){$this->html=$html;$this->basePath=$basePath;return$this;}function
getHtmlBody(){return$this->html;}function
addEmbeddedFile($file,$content=NULL,$contentType=NULL){$part=new
MailMimePart;$part->setBody($content===NULL?$this->readFile($file,$contentType):(string)$content);$part->setContentType($contentType?$contentType:'application/octet-stream');$part->setEncoding(self::ENCODING_BASE64);$part->setHeader('Content-Disposition','inline; filename="'.String::fixEncoding(basename($file)).'"');$part->setHeader('Content-ID','<'.md5(uniqid('',TRUE)).'>');return$this->inlines[$file]=$part;}function
addAttachment($file,$content=NULL,$contentType=NULL){$part=new
MailMimePart;$part->setBody($content===NULL?$this->readFile($file,$contentType):(string)$content);$part->setContentType($contentType?$contentType:'application/octet-stream');$part->setEncoding(self::ENCODING_BASE64);$part->setHeader('Content-Disposition','attachment; filename="'.String::fixEncoding(basename($file)).'"');return$this->attachments[]=$part;}private
function
readFile($file,&$contentType){if(!is_file($file)){throw
new
FileNotFoundException("File '$file' not found.");}if(!$contentType&&$info=getimagesize($file)){$contentType=$info['mime'];}return
file_get_contents($file);}function
send(){$this->getMailer()->send($this->build());}function
setMailer(IMailer$mailer){$this->mailer=$mailer;return$this;}function
getMailer(){if($this->mailer===NULL){if($a=strrpos(self::$defaultMailer,'\\'))self::$defaultMailer=substr(self::$defaultMailer,$a+1);$this->mailer=is_object(self::$defaultMailer)?self::$defaultMailer:new
self::$defaultMailer;}return$this->mailer;}protected
function
build(){$mail=clone$this;$hostname=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'localhost');$mail->setHeader('Message-ID','<'.md5(uniqid('',TRUE))."@$hostname>");$mail->buildHtml();$mail->buildText();$cursor=$mail;if($mail->attachments){$tmp=$cursor->setContentType('multipart/mixed');$cursor=$cursor->addPart();foreach($mail->attachments
as$value){$tmp->addPart($value);}}if($mail->html!=NULL){$tmp=$cursor->setContentType('multipart/alternative');$cursor=$cursor->addPart();$alt=$tmp->addPart();if($mail->inlines){$tmp=$alt->setContentType('multipart/related');$alt=$alt->addPart();foreach($mail->inlines
as$name=>$value){$tmp->addPart($value);}}$alt->setContentType('text/html','UTF-8')->setEncoding(preg_match('#[\x80-\xFF]#',$mail->html)?self::ENCODING_8BIT:self::ENCODING_7BIT)->setBody($mail->html);}$text=$mail->getBody();$mail->setBody(NULL);$cursor->setContentType('text/plain','UTF-8')->setEncoding(preg_match('#[\x80-\xFF]#',$text)?self::ENCODING_8BIT:self::ENCODING_7BIT)->setBody($text);return$mail;}protected
function
buildHtml(){if($this->html
instanceof
ITemplate){$this->html->mail=$this;if($this->basePath===NULL&&$this->html
instanceof
IFileTemplate){$this->basePath=dirname($this->html->getFile());}$this->html=$this->html->__toString(TRUE);}if($this->basePath!==FALSE){$cids=array();$matches=String::matchAll($this->html,'#(src\s*=\s*|background\s*=\s*|url\()(["\'])(?![a-z]+:|[/\\#])(.+?)\\2#i',PREG_OFFSET_CAPTURE);foreach(array_reverse($matches)as$m){$file=rtrim($this->basePath,'/\\').'/'.$m[3][0];$cid=isset($cids[$file])?$cids[$file]:$cids[$file]=substr($this->addEmbeddedFile($file)->getHeader("Content-ID"),1,-1);$this->html=substr_replace($this->html,"{$m[1][0]}{$m[2][0]}cid:$cid{$m[2][0]}",$m[0][1],strlen($m[0][0]));}}if(!$this->getSubject()&&$matches=String::match($this->html,'#<title>(.+?)</title>#is')){$this->setSubject(html_entity_decode($matches[1],ENT_QUOTES,'UTF-8'));}}protected
function
buildText(){$text=$this->getBody();if($text
instanceof
ITemplate){$text->mail=$this;$this->setBody($text->__toString(TRUE));}elseif($text==NULL&&$this->html!=NULL){$text=String::replace($this->html,array('#<(style|script|head).*</\\1>#Uis'=>'','#<t[dh][ >]#i'=>" $0",'#[ \t\r\n]+#'=>' ','#<(/?p|/?h\d|li|br|/tr)[ >]#i'=>"\n$0"));$text=html_entity_decode(strip_tags($text),ENT_QUOTES,'UTF-8');$this->setBody(trim($text));}}}class
SendmailMailer
extends
Object
implements
IMailer{function
send(Mail$mail){$tmp=clone$mail;$tmp->setHeader('Subject',NULL);$tmp->setHeader('To',NULL);$parts=explode(Mail::EOL.Mail::EOL,$tmp->generateMessage(),2);Tools::tryError();$res=mail(str_replace(Mail::EOL,PHP_EOL,$mail->getEncodedHeader('To')),str_replace(Mail::EOL,PHP_EOL,$mail->getEncodedHeader('Subject')),str_replace(Mail::EOL,PHP_EOL,$parts[1]),str_replace(Mail::EOL,PHP_EOL,$parts[0]));if(Tools::catchError($msg)){throw
new
InvalidStateException($msg);}elseif(!$res){throw
new
InvalidStateException('Unable to send email.');}}}class
Annotation
extends
Object
implements
IAnnotation{function
__construct(array$values){foreach($values
as$k=>$v){$this->$k=$v;}}function
__toString(){return$this->value;}}/**
 * Annotations support for PHP.
 *
 * @copyright  Copyright (c) 2004, 2010 David Grudl
 * @package    Nette\Reflection
 * @Annotation
 */final
class
AnnotationsParser{const
RE_STRING='\'(?:\\\\.|[^\'\\\\])*\'|"(?:\\\\.|[^"\\\\])*"';const
RE_IDENTIFIER='[_a-zA-Z\x7F-\xFF][_a-zA-Z0-9\x7F-\xFF]*';public
static$useReflection;private
static$cache;private
static$timestamps;final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
getAll(Reflector$r){if($r
instanceof
ReflectionClass){$type=$r->getName();$member='';}elseif($r
instanceof
ReflectionMethod){$type=$r->getDeclaringClass()->getName();$member=$r->getName();}else{$type=$r->getDeclaringClass()->getName();$member='$'.$r->getName();}if(!self::$useReflection){$file=$r
instanceof
ReflectionClass?$r->getFileName():$r->getDeclaringClass()->getFileName();if($file&&isset(self::$timestamps[$file])&&self::$timestamps[$file]!==filemtime($file)){unset(self::$cache[$type]);}unset(self::$timestamps[$file]);}if(isset(self::$cache[$type][$member])){return
self::$cache[$type][$member];}if(self::$useReflection===NULL){self::$useReflection=(bool)ClassReflection::from(__CLASS__)->getDocComment();}if(self::$useReflection){return
self::$cache[$type][$member]=self::parseComment($r->getDocComment());}else{if(self::$cache===NULL){self::$cache=(array)self::getCache()->offsetGet('list');self::$timestamps=isset(self::$cache['*'])?self::$cache['*']:array();}if(!isset(self::$cache[$type])&&$file){self::$cache['*'][$file]=filemtime($file);self::parseScript($file);self::getCache()->save('list',self::$cache);}if(isset(self::$cache[$type][$member])){return
self::$cache[$type][$member];}else{return
self::$cache[$type][$member]=array();}}}private
static
function
parseComment($comment){static$tokens=array('true'=>TRUE,'false'=>FALSE,'null'=>NULL,''=>TRUE);$matches=String::matchAll(trim($comment,'/*'),'~
				@('.self::RE_IDENTIFIER.')[ \t]*             ##  annotation
				(
					\((?>'.self::RE_STRING.'|[^\'")@]+)+\)|  ##  (value)
					[^(@\r\n][^@\r\n]*|)                     ##  value
			~xi');$res=array();foreach($matches
as$match){list(,$name,$value)=$match;if(substr($value,0,1)==='('){$items=array();$key='';$val=TRUE;$value[0]=',';while($m=String::match($value,'#\s*,\s*(?>('.self::RE_IDENTIFIER.')\s*=\s*)?('.self::RE_STRING.'|[^\'"),\s][^\'"),]*)#A')){$value=substr($value,strlen($m[0]));list(,$key,$val)=$m;if($val[0]==="'"||$val[0]==='"'){$val=substr($val,1,-1);}elseif(is_numeric($val)){$val=1*$val;}else{$lval=strtolower($val);$val=array_key_exists($lval,$tokens)?$tokens[$lval]:$val;}if($key===''){$items[]=$val;}else{$items[$key]=$val;}}$value=count($items)<2&&$key===''?$val:$items;}else{$value=trim($value);if(is_numeric($value)){$value=1*$value;}else{$lval=strtolower($value);$value=array_key_exists($lval,$tokens)?$tokens[$lval]:$value;}}$class=$name.'Annotation';if(class_exists($class)){$res[$name][]=new$class(is_array($value)?$value:array('value'=>$value));}else{$res[$name][]=is_array($value)?new
ArrayObject($value,ArrayObject::ARRAY_AS_PROPS):$value;}}return$res;}private
static
function
parseScript($file){$T_NAMESPACE=PHP_VERSION_ID<50300?-1:T_NAMESPACE;$T_NS_SEPARATOR=PHP_VERSION_ID<50300?-1:T_NS_SEPARATOR;$s=file_get_contents($file);if(String::match($s,'#//nette'.'loader=(\S*)#')){return;}$expected=$namespace=$class=$docComment=NULL;$level=$classLevel=0;foreach(token_get_all($s)as$token){if(is_array($token)){switch($token[0]){case
T_DOC_COMMENT:$docComment=$token[1];case
T_WHITESPACE:case
T_COMMENT:continue
2;case
T_STRING:case$T_NS_SEPARATOR:case
T_VARIABLE:if($expected){$name.=$token[1];}continue
2;case
T_FUNCTION:case
T_VAR:case
T_PUBLIC:case
T_PROTECTED:case$T_NAMESPACE:case
T_CLASS:case
T_INTERFACE:$expected=$token[0];$name=NULL;continue
2;case
T_STATIC:case
T_ABSTRACT:case
T_FINAL:continue
2;case
T_CURLY_OPEN:case
T_DOLLAR_OPEN_CURLY_BRACES:$level++;}}if($expected){switch($expected){case
T_CLASS:case
T_INTERFACE:$class=$namespace.$name;$classLevel=$level;$name='';case
T_FUNCTION:if($token==='&')continue
2;case
T_VAR:case
T_PUBLIC:case
T_PROTECTED:if($class&&$name!==NULL&&$docComment){self::$cache[$class][$name]=self::parseComment($docComment);}break;case$T_NAMESPACE:$namespace=$name.'\\';}$expected=$docComment=NULL;}if($token===';'){$docComment=NULL;}elseif($token==='{'){$docComment=NULL;$level++;}elseif($token==='}'){$level--;if($level===$classLevel){$class=NULL;}}}}protected
static
function
getCache(){return
Environment::getCache('Nette.Annotations');}}class
ExtensionReflection
extends
ReflectionExtension{function
__toString(){return'Extension '.$this->getName();}static
function
import(ReflectionExtension$ref){return
new
self($ref->getName());}function
getClasses(){return
array_map(array('ClassReflection','import'),parent::getClasses());}function
getFunctions(){return
array_map(array('FunctionReflection','import'),parent::getFunctions());}function
getReflection(){return
new
ClassReflection($this);}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}class
FunctionReflection
extends
ReflectionFunction{function
__toString(){return'Function '.$this->getName().'()';}static
function
import(ReflectionFunction$ref){return
new
self($ref->getName());}function
getExtension(){return($ref=parent::getExtension())?ExtensionReflection::import($ref):NULL;}function
getParameters(){return
array_map(array('MethodParameterReflection','import'),parent::getParameters());}function
getReflection(){return
new
ClassReflection($this);}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}class
MethodParameterReflection
extends
ReflectionParameter{static
function
import(ReflectionParameter$ref){$method=$ref->getDeclaringFunction();return
new
self($method
instanceof
ReflectionMethod?array($ref->getDeclaringClass()->getName(),$method->getName()):$method->getName(),$ref->getName());}function
getClass(){return($ref=parent::getClass())?ClassReflection::import($ref):NULL;}function
getDeclaringClass(){return($ref=parent::getDeclaringClass())?ClassReflection::import($ref):NULL;}function
getDeclaringFunction(){return($ref=parent::getDeclaringFunction())instanceof
ReflectionMethod?MethodReflection::import($ref):FunctionReflection::import($ref);}function
getReflection(){return
new
ClassReflection($this);}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}class
MethodReflection
extends
ReflectionMethod{static
function
from($class,$method){return
new
self(is_object($class)?get_class($class):$class,$method);}function
getDefaultParameters(){$res=array();foreach(parent::getParameters()as$param){$res[$param->getName()]=$param->isDefaultValueAvailable()?$param->getDefaultValue():NULL;if($param->isArray()){settype($res[$param->getName()],'array');}}return$res;}function
invokeNamedArgs($object,$args){$res=array();$i=0;foreach($this->getDefaultParameters()as$name=>$def){if(isset($args[$name])){$val=$args[$name];if($def!==NULL){settype($val,gettype($def));}$res[$i++]=$val;}else{$res[$i++]=$def;}}return$this->invokeArgs($object,$res);}function
getCallback(){return
new
Callback(parent::getDeclaringClass()->getName(),$this->getName());}function
__toString(){return'Method '.parent::getDeclaringClass()->getName().'::'.$this->getName().'()';}static
function
import(ReflectionMethod$ref){return
new
self($ref->getDeclaringClass()->getName(),$ref->getName());}function
getDeclaringClass(){return
ClassReflection::import(parent::getDeclaringClass());}function
getExtension(){return($ref=parent::getExtension())?ExtensionReflection::import($ref):NULL;}function
getParameters(){return
array_map(array('MethodParameterReflection','import'),parent::getParameters());}function
hasAnnotation($name){$res=AnnotationsParser::getAll($this);return!empty($res[$name]);}function
getAnnotation($name){$res=AnnotationsParser::getAll($this);return
isset($res[$name])?end($res[$name]):NULL;}function
getAnnotations(){return
AnnotationsParser::getAll($this);}function
getReflection(){return
new
ClassReflection($this);}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}class
PropertyReflection
extends
ReflectionProperty{function
__toString(){return'Property '.parent::getDeclaringClass()->getName().'::$'.$this->getName();}static
function
import(ReflectionProperty$ref){return
new
self($ref->getDeclaringClass()->getName(),$ref->getName());}function
getDeclaringClass(){return
ClassReflection::import(parent::getDeclaringClass());}function
hasAnnotation($name){$res=AnnotationsParser::getAll($this);return!empty($res[$name]);}function
getAnnotation($name){$res=AnnotationsParser::getAll($this);return
isset($res[$name])?end($res[$name]):NULL;}function
getAnnotations(){return
AnnotationsParser::getAll($this);}function
getReflection(){return
new
ClassReflection($this);}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}class
AuthenticationException
extends
Exception{}class
Identity
extends
FreezableObject
implements
IIdentity{private$id;private$roles;private$data;function
__construct($id,$roles=NULL,$data=NULL){$this->setId($id);$this->setRoles((array)$roles);$this->data=$data
instanceof
Traversable?iterator_to_array($data):(array)$data;}function
setId($id){$this->updating();$this->id=is_numeric($id)?1*$id:$id;return$this;}function
getId(){return$this->id;}function
setRoles(array$roles){$this->updating();$this->roles=$roles;return$this;}function
getRoles(){return$this->roles;}function
getData(){return$this->data;}function
__set($key,$value){$this->updating();if(parent::__isset($key)){parent::__set($key,$value);}else{$this->data[$key]=$value;}}function&__get($key){if(parent::__isset($key)){return
parent::__get($key);}else{return$this->data[$key];}}function
__isset($key){return
isset($this->data[$key])||parent::__isset($key);}function
__unset($name){throw
new
MemberAccessException("Cannot unset the property {$this->reflection->name}::\$$name.");}}class
Permission
extends
Object
implements
IAuthorizator{private$roles=array();private$resources=array();private$rules=array('allResources'=>array('allRoles'=>array('allPrivileges'=>array('type'=>self::DENY,'assert'=>NULL),'byPrivilege'=>array()),'byRole'=>array()),'byResource'=>array());private$queriedRole,$queriedResource;function
addRole($role,$parents=NULL){$this->checkRole($role,FALSE);if(isset($this->roles[$role])){throw
new
InvalidStateException("Role '$role' already exists in the list.");}$roleParents=array();if($parents!==NULL){if(!is_array($parents)){$parents=array($parents);}foreach($parents
as$parent){$this->checkRole($parent);$roleParents[$parent]=TRUE;$this->roles[$parent]['children'][$role]=TRUE;}}$this->roles[$role]=array('parents'=>$roleParents,'children'=>array());return$this;}function
hasRole($role){$this->checkRole($role,FALSE);return
isset($this->roles[$role]);}private
function
checkRole($role,$need=TRUE){if(!is_string($role)||$role===''){throw
new
InvalidArgumentException("Role must be a non-empty string.");}elseif($need&&!isset($this->roles[$role])){throw
new
InvalidStateException("Role '$role' does not exist.");}}function
getRoleParents($role){$this->checkRole($role);return
array_keys($this->roles[$role]['parents']);}function
roleInheritsFrom($role,$inherit,$onlyParents=FALSE){$this->checkRole($role);$this->checkRole($inherit);$inherits=isset($this->roles[$role]['parents'][$inherit]);if($inherits||$onlyParents){return$inherits;}foreach($this->roles[$role]['parents']as$parent=>$foo){if($this->roleInheritsFrom($parent,$inherit)){return
TRUE;}}return
FALSE;}function
removeRole($role){$this->checkRole($role);foreach($this->roles[$role]['children']as$child=>$foo)unset($this->roles[$child]['parents'][$role]);foreach($this->roles[$role]['parents']as$parent=>$foo)unset($this->roles[$parent]['children'][$role]);unset($this->roles[$role]);foreach($this->rules['allResources']['byRole']as$roleCurrent=>$rules){if($role===$roleCurrent){unset($this->rules['allResources']['byRole'][$roleCurrent]);}}foreach($this->rules['byResource']as$resourceCurrent=>$visitor){if(isset($visitor['byRole'])){foreach($visitor['byRole']as$roleCurrent=>$rules){if($role===$roleCurrent){unset($this->rules['byResource'][$resourceCurrent]['byRole'][$roleCurrent]);}}}}return$this;}function
removeAllRoles(){$this->roles=array();foreach($this->rules['allResources']['byRole']as$roleCurrent=>$rules)unset($this->rules['allResources']['byRole'][$roleCurrent]);foreach($this->rules['byResource']as$resourceCurrent=>$visitor){foreach($visitor['byRole']as$roleCurrent=>$rules){unset($this->rules['byResource'][$resourceCurrent]['byRole'][$roleCurrent]);}}return$this;}function
addResource($resource,$parent=NULL){$this->checkResource($resource,FALSE);if(isset($this->resources[$resource])){throw
new
InvalidStateException("Resource '$resource' already exists in the list.");}if($parent!==NULL){$this->checkResource($parent);$this->resources[$parent]['children'][$resource]=TRUE;}$this->resources[$resource]=array('parent'=>$parent,'children'=>array());return$this;}function
hasResource($resource){$this->checkResource($resource,FALSE);return
isset($this->resources[$resource]);}private
function
checkResource($resource,$need=TRUE){if(!is_string($resource)||$resource===''){throw
new
InvalidArgumentException("Resource must be a non-empty string.");}elseif($need&&!isset($this->resources[$resource])){throw
new
InvalidStateException("Resource '$resource' does not exist.");}}function
resourceInheritsFrom($resource,$inherit,$onlyParent=FALSE){$this->checkResource($resource);$this->checkResource($inherit);if($this->resources[$resource]['parent']===NULL){return
FALSE;}$parent=$this->resources[$resource]['parent'];if($inherit===$parent){return
TRUE;}elseif($onlyParent){return
FALSE;}while($this->resources[$parent]['parent']!==NULL){$parent=$this->resources[$parent]['parent'];if($inherit===$parent){return
TRUE;}}return
FALSE;}function
removeResource($resource){$this->checkResource($resource);$parent=$this->resources[$resource]['parent'];if($parent!==NULL){unset($this->resources[$parent]['children'][$resource]);}$removed=array($resource);foreach($this->resources[$resource]['children']as$child=>$foo){$this->removeResource($child);$removed[]=$child;}foreach($removed
as$resourceRemoved){foreach($this->rules['byResource']as$resourceCurrent=>$rules){if($resourceRemoved===$resourceCurrent){unset($this->rules['byResource'][$resourceCurrent]);}}}unset($this->resources[$resource]);return$this;}function
removeAllResources(){foreach($this->resources
as$resource=>$foo){foreach($this->rules['byResource']as$resourceCurrent=>$rules){if($resource===$resourceCurrent){unset($this->rules['byResource'][$resourceCurrent]);}}}$this->resources=array();return$this;}function
allow($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL,$assertion=NULL){$this->setRule(TRUE,self::ALLOW,$roles,$resources,$privileges,$assertion);return$this;}function
deny($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL,$assertion=NULL){$this->setRule(TRUE,self::DENY,$roles,$resources,$privileges,$assertion);return$this;}function
removeAllow($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL){$this->setRule(FALSE,self::ALLOW,$roles,$resources,$privileges);return$this;}function
removeDeny($roles=self::ALL,$resources=self::ALL,$privileges=self::ALL){$this->setRule(FALSE,self::DENY,$roles,$resources,$privileges);return$this;}protected
function
setRule($toAdd,$type,$roles,$resources,$privileges,$assertion=NULL){if($roles===self::ALL){$roles=array(self::ALL);}else{if(!is_array($roles)){$roles=array($roles);}foreach($roles
as$role){$this->checkRole($role);}}if($resources===self::ALL){$resources=array(self::ALL);}else{if(!is_array($resources)){$resources=array($resources);}foreach($resources
as$resource){$this->checkResource($resource);}}if($privileges===self::ALL){$privileges=array();}elseif(!is_array($privileges)){$privileges=array($privileges);}$assertion=$assertion?callback($assertion):NULL;if($toAdd){foreach($resources
as$resource){foreach($roles
as$role){$rules=&$this->getRules($resource,$role,TRUE);if(count($privileges)===0){$rules['allPrivileges']['type']=$type;$rules['allPrivileges']['assert']=$assertion;if(!isset($rules['byPrivilege'])){$rules['byPrivilege']=array();}}else{foreach($privileges
as$privilege){$rules['byPrivilege'][$privilege]['type']=$type;$rules['byPrivilege'][$privilege]['assert']=$assertion;}}}}}else{foreach($resources
as$resource){foreach($roles
as$role){$rules=&$this->getRules($resource,$role);if($rules===NULL){continue;}if(count($privileges)===0){if($resource===self::ALL&&$role===self::ALL){if($type===$rules['allPrivileges']['type']){$rules=array('allPrivileges'=>array('type'=>self::DENY,'assert'=>NULL),'byPrivilege'=>array());}continue;}if($type===$rules['allPrivileges']['type']){unset($rules['allPrivileges']);}}else{foreach($privileges
as$privilege){if(isset($rules['byPrivilege'][$privilege])&&$type===$rules['byPrivilege'][$privilege]['type']){unset($rules['byPrivilege'][$privilege]);}}}}}}return$this;}function
isAllowed($role=self::ALL,$resource=self::ALL,$privilege=self::ALL){$this->queriedRole=$role;if($role!==self::ALL){if($role
instanceof
IRole){$role=$role->getRoleId();}$this->checkRole($role);}$this->queriedResource=$resource;if($resource!==self::ALL){if($resource
instanceof
IResource){$resource=$resource->getResourceId();}$this->checkResource($resource);}if($privilege===self::ALL){do{if($role!==NULL&&NULL!==($result=$this->roleDFSAllPrivileges($role,$resource))){break;}if(NULL!==($rules=$this->getRules($resource,self::ALL))){foreach($rules['byPrivilege']as$privilege=>$rule){if(self::DENY===($ruleTypeOnePrivilege=$this->getRuleType($resource,NULL,$privilege))){$result=self::DENY;break
2;}}if(NULL!==($ruleTypeAllPrivileges=$this->getRuleType($resource,NULL,NULL))){$result=self::ALLOW===$ruleTypeAllPrivileges;break;}}$resource=$this->resources[$resource]['parent'];}while(TRUE);}else{do{if($role!==NULL&&NULL!==($result=$this->roleDFSOnePrivilege($role,$resource,$privilege))){break;}if(NULL!==($ruleType=$this->getRuleType($resource,NULL,$privilege))){$result=self::ALLOW===$ruleType;break;}elseif(NULL!==($ruleTypeAllPrivileges=$this->getRuleType($resource,NULL,NULL))){$result=self::ALLOW===$ruleTypeAllPrivileges;break;}$resource=$this->resources[$resource]['parent'];}while(TRUE);}$this->queriedRole=$this->queriedResource=NULL;return$result;}function
getQueriedRole(){return$this->queriedRole;}function
getQueriedResource(){return$this->queriedResource;}private
function
roleDFSAllPrivileges($role,$resource){$dfs=array('visited'=>array(),'stack'=>array($role));while(NULL!==($role=array_pop($dfs['stack']))){if(!isset($dfs['visited'][$role])){if(NULL!==($result=$this->roleDFSVisitAllPrivileges($role,$resource,$dfs))){return$result;}}}return
NULL;}private
function
roleDFSVisitAllPrivileges($role,$resource,&$dfs){if(NULL!==($rules=$this->getRules($resource,$role))){foreach($rules['byPrivilege']as$privilege=>$rule){if(self::DENY===$this->getRuleType($resource,$role,$privilege)){return
self::DENY;}}if(NULL!==($type=$this->getRuleType($resource,$role,NULL))){return
self::ALLOW===$type;}}$dfs['visited'][$role]=TRUE;foreach($this->roles[$role]['parents']as$roleParent=>$foo){$dfs['stack'][]=$roleParent;}return
NULL;}private
function
roleDFSOnePrivilege($role,$resource,$privilege){$dfs=array('visited'=>array(),'stack'=>array($role));while(NULL!==($role=array_pop($dfs['stack']))){if(!isset($dfs['visited'][$role])){if(NULL!==($result=$this->roleDFSVisitOnePrivilege($role,$resource,$privilege,$dfs))){return$result;}}}return
NULL;}private
function
roleDFSVisitOnePrivilege($role,$resource,$privilege,&$dfs){if(NULL!==($type=$this->getRuleType($resource,$role,$privilege))){return
self::ALLOW===$type;}if(NULL!==($type=$this->getRuleType($resource,$role,NULL))){return
self::ALLOW===$type;}$dfs['visited'][$role]=TRUE;foreach($this->roles[$role]['parents']as$roleParent=>$foo)$dfs['stack'][]=$roleParent;return
NULL;}private
function
getRuleType($resource,$role,$privilege){if(NULL===($rules=$this->getRules($resource,$role))){return
NULL;}if($privilege===self::ALL){if(isset($rules['allPrivileges'])){$rule=$rules['allPrivileges'];}else{return
NULL;}}elseif(!isset($rules['byPrivilege'][$privilege])){return
NULL;}else{$rule=$rules['byPrivilege'][$privilege];}if($rule['assert']===NULL||$rule['assert']->__invoke($this,$role,$resource,$privilege)){return$rule['type'];}elseif($resource!==self::ALL||$role!==self::ALL||$privilege!==self::ALL){return
NULL;}elseif(self::ALLOW===$rule['type']){return
self::DENY;}else{return
self::ALLOW;}}private
function&getRules($resource,$role,$create=FALSE){if($resource===self::ALL){$visitor=&$this->rules['allResources'];}else{if(!isset($this->rules['byResource'][$resource])){if(!$create){$null=NULL;return$null;}$this->rules['byResource'][$resource]=array();}$visitor=&$this->rules['byResource'][$resource];}if($role===self::ALL){if(!isset($visitor['allRoles'])){if(!$create){$null=NULL;return$null;}$visitor['allRoles']['byPrivilege']=array();}return$visitor['allRoles'];}if(!isset($visitor['byRole'][$role])){if(!$create){$null=NULL;return$null;}$visitor['byRole'][$role]['byPrivilege']=array();}return$visitor['byRole'][$role];}}class
SimpleAuthenticator
extends
Object
implements
IAuthenticator{private$userlist;function
__construct(array$userlist){$this->userlist=$userlist;}function
authenticate(array$credentials){$username=$credentials[self::USERNAME];foreach($this->userlist
as$name=>$pass){if(strcasecmp($name,$credentials[self::USERNAME])===0){if(strcasecmp($pass,$credentials[self::PASSWORD])===0){return
new
Identity($name);}throw
new
AuthenticationException("Invalid password.",self::INVALID_CREDENTIAL);}}throw
new
AuthenticationException("User '$username' not found.",self::IDENTITY_NOT_FOUND);}}abstract
class
BaseTemplate
extends
Object
implements
ITemplate{public$warnOnUndefined=TRUE;public$onPrepareFilters=array();private$params=array();private$filters=array();private$helpers=array();private$helperLoaders=array();function
registerFilter($callback){$callback=callback($callback);if(in_array($callback,$this->filters)){throw
new
InvalidStateException("Filter '$callback' was registered twice.");}$this->filters[]=$callback;}final
function
getFilters(){return$this->filters;}function
render(){}function
__toString(){ob_start();try{$this->render();return
ob_get_clean();}catch(Exception$e){ob_end_clean();if(func_num_args()&&func_get_arg(0)){throw$e;}else{Debug::toStringException($e);}}}protected
function
compile($content,$label=NULL){if(!$this->filters){$this->onPrepareFilters($this);}try{foreach($this->filters
as$filter){$content=self::extractPhp($content,$blocks);$content=$filter->invoke($content);$content=strtr($content,$blocks);}}catch(Exception$e){throw
new
InvalidStateException("Filter $filter: ".$e->getMessage().($label?" (in $label)":''),0,$e);}if($label){$content="<?php\n// $label\n//\n?>$content";}return
self::optimizePhp($content);}function
registerHelper($name,$callback){$this->helpers[strtolower($name)]=callback($callback);}function
registerHelperLoader($callback){$this->helperLoaders[]=callback($callback);}final
function
getHelpers(){return$this->helpers;}function
__call($name,$args){$lname=strtolower($name);if(!isset($this->helpers[$lname])){foreach($this->helperLoaders
as$loader){$helper=$loader->invoke($lname);if($helper){$this->registerHelper($lname,$helper);return$this->helpers[$lname]->invokeArgs($args);}}return
parent::__call($name,$args);}return$this->helpers[$lname]->invokeArgs($args);}function
setTranslator(ITranslator$translator=NULL){$this->registerHelper('translate',$translator===NULL?NULL:array($translator,'translate'));return$this;}function
add($name,$value){if(array_key_exists($name,$this->params)){throw
new
InvalidStateException("The variable '$name' exists yet.");}$this->params[$name]=$value;}function
setParams(array$params){$this->params=$params;return$this;}function
getParams(){return$this->params;}function
__set($name,$value){$this->params[$name]=$value;}function&__get($name){if($this->warnOnUndefined&&!array_key_exists($name,$this->params)){trigger_error("The variable '$name' does not exist in template.",E_USER_NOTICE);}return$this->params[$name];}function
__isset($name){return
isset($this->params[$name]);}function
__unset($name){unset($this->params[$name]);}private
static
function
extractPhp($source,&$blocks){$res='';$blocks=array();$tokens=token_get_all($source);foreach($tokens
as$n=>$token){if(is_array($token)){if($token[0]===T_INLINE_HTML){$res.=$token[1];continue;}elseif($token[0]===T_OPEN_TAG&&$token[1]==='<?'&&isset($tokens[$n+1][1])&&$tokens[$n+1][1]==='xml'){$php=&$res;$token[1]='<<?php ?>?';}elseif($token[0]===T_OPEN_TAG||$token[0]===T_OPEN_TAG_WITH_ECHO){$res.=$id="\x01@php:p".count($blocks)."@\x02";$php=&$blocks[$id];}$php.=$token[1];}else{$php.=$token;}}return$res;}static
function
optimizePhp($source){$res=$php='';$lastChar=';';$tokens=new
ArrayIterator(token_get_all($source));foreach($tokens
as$key=>$token){if(is_array($token)){if($token[0]===T_INLINE_HTML){$lastChar='';$res.=$token[1];}elseif($token[0]===T_CLOSE_TAG){$next=isset($tokens[$key+1])?$tokens[$key+1]:NULL;if(substr($res,-1)!=='<'&&preg_match('#^<\?php\s*$#',$php)){$php='';}elseif(is_array($next)&&$next[0]===T_OPEN_TAG){if($lastChar!==';'&&$lastChar!=='{'&&$lastChar!=='}'&&$lastChar!==':'&&$lastChar!=='/')$php.=$lastChar=';';if(substr($next[1],-1)==="\n")$php.="\n";$tokens->next();}elseif($next){$res.=preg_replace('#;?(\s)*$#','$1',$php).$token[1];$php='';}else{if($lastChar!=='}'&&$lastChar!==';')$php.=';';}}elseif($token[0]===T_ELSE||$token[0]===T_ELSEIF){if($tokens[$key+1]===':'&&$lastChar==='}')$php.=';';$lastChar='';$php.=$token[1];}else{if(!in_array($token[0],array(T_WHITESPACE,T_COMMENT,T_DOC_COMMENT,T_OPEN_TAG)))$lastChar='';$php.=$token[1];}}else{$php.=$lastChar=$token;}}return$res.$php;}}class
CachingHelper
extends
Object{private$frame;private$key;static
function
create($key,$file,$tags){$cache=self::getCache();if(isset($cache[$key])){echo$cache[$key];return
FALSE;}else{$obj=new
self;$obj->key=$key;$obj->frame=array(Cache::FILES=>array($file),Cache::TAGS=>$tags,Cache::EXPIRE=>rand(86400*4,86400*7));ob_start();return$obj;}}function
save(){$this->getCache()->save($this->key,ob_get_flush(),$this->frame);$this->key=$this->frame=NULL;}function
addFile($file){$this->frame[Cache::FILES][]=$file;}function
addItem($item){$this->frame[Cache::ITEMS][]=$item;}protected
static
function
getCache(){return
Environment::getCache('Nette.Template.Curly');}}class
LatteFilter
extends
Object{const
RE_STRING='\'(?:\\\\.|[^\'\\\\])*\'|"(?:\\\\.|[^"\\\\])*"';const
RE_IDENTIFIER='[_a-zA-Z\x7F-\xFF][_a-zA-Z0-9\x7F-\xFF]*';const
HTML_PREFIX='n:';private$handler;private$macroRe;private$input,$output;private$offset;private$quote;private$tags;public$context,$escape;const
CONTEXT_TEXT='text';const
CONTEXT_CDATA='cdata';const
CONTEXT_TAG='tag';const
CONTEXT_ATTRIBUTE='attribute';const
CONTEXT_NONE='none';const
CONTEXT_COMMENT='comment';function
setHandler($handler){$this->handler=$handler;return$this;}function
getHandler(){if($this->handler===NULL){$this->handler=new
LatteMacros;}return$this->handler;}function
__invoke($s){if(!$this->macroRe){$this->setDelimiters('\\{(?![\\s\'"{}])','\\}');}$this->context=LatteFilter::CONTEXT_NONE;$this->escape='$template->escape';$this->getHandler()->initialize($this,$s);$s=$this->parse("\n".$s);$this->getHandler()->finalize($s);return$s;}private
function
parse($s){$this->input=&$s;$this->offset=0;$this->output='';$this->tags=array();$len=strlen($s);while($this->offset<$len){$matches=$this->{"context$this->context"}();if(!$matches){break;}elseif(!empty($matches['macro'])){list(,$macro,$value,$modifiers)=String::match($matches['macro'],'#^(/?[a-z]+)?(.*?)(\\|[a-z](?:'.self::RE_STRING.'|[^\'"]+)*)?$()#is');$code=$this->handler->macro($macro,trim($value),isset($modifiers)?$modifiers:'');if($code===NULL){throw
new
InvalidStateException("Unknown macro {{$matches['macro']}} on line $this->line.");}$nl=isset($matches['newline'])?"\n":'';if($nl&&$matches['indent']&&strncmp($code,'<?php echo ',11)){$this->output.="\n".$code;}else{$this->output.=$matches['indent'].$code.(substr($code,-2)==='?>'?$nl:'');}}else{$this->output.=$matches[0];}}foreach($this->tags
as$tag){if(!$tag->isMacro&&!empty($tag->attrs)){throw
new
InvalidStateException("Missing end tag </$tag->name> for macro-attribute ".self::HTML_PREFIX.implode(' and '.self::HTML_PREFIX,array_keys($tag->attrs)).".");}}return$this->output.substr($this->input,$this->offset);}private
function
contextText(){$matches=$this->match('~
			(?:\n[ \t]*)?<(?P<closing>/?)(?P<tag>[a-z0-9:]+)|  ##  begin of HTML tag <tag </tag - ignores <!DOCTYPE
			<(?P<comment>!--)|           ##  begin of HTML comment <!--
			'.$this->macroRe.'           ##  curly tag
		~xsi');if(!$matches||!empty($matches['macro'])){}elseif(!empty($matches['comment'])){$this->context=self::CONTEXT_COMMENT;$this->escape='TemplateHelpers::escapeHtmlComment';}elseif(empty($matches['closing'])){$tag=$this->tags[]=(object)NULL;$tag->name=$matches['tag'];$tag->closing=FALSE;$tag->isMacro=String::startsWith($tag->name,self::HTML_PREFIX);$tag->attrs=array();$tag->pos=strlen($this->output);$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}else{do{$tag=array_pop($this->tags);if(!$tag){$tag=(object)NULL;$tag->name=$matches['tag'];$tag->isMacro=String::startsWith($tag->name,self::HTML_PREFIX);}}while(strcasecmp($tag->name,$matches['tag']));$this->tags[]=$tag;$tag->closing=TRUE;$tag->pos=strlen($this->output);$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextCData(){$tag=end($this->tags);$matches=$this->match('~
			</'.$tag->name.'(?![a-z0-9:])| ##  end HTML tag </tag
			'.$this->macroRe.'           ##  curly tag
		~xsi');if($matches&&empty($matches['macro'])){$tag->closing=TRUE;$tag->pos=strlen($this->output);$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextTag(){$matches=$this->match('~
			(?P<end>\ ?/?>)(?P<tagnewline>[\ \t]*(?=\r|\n))?|  ##  end of HTML tag
			'.$this->macroRe.'|          ##  curly tag
			\s*(?P<attr>[^\s/>={]+)(?:\s*=\s*(?P<value>["\']|[^\s/>{]+))? ## begin of HTML attribute
		~xsi');if(!$matches||!empty($matches['macro'])){}elseif(!empty($matches['end'])){$tag=end($this->tags);$isEmpty=!$tag->closing&&(strpos($matches['end'],'/')!==FALSE||isset(Html::$emptyElements[strtolower($tag->name)]));if($isEmpty){$matches[0]=(Html::$xhtml?' />':'>').(isset($matches['tagnewline'])?$matches['tagnewline']:'');}if($tag->isMacro||!empty($tag->attrs)){if($tag->isMacro){$code=$this->handler->tagMacro(substr($tag->name,strlen(self::HTML_PREFIX)),$tag->attrs,$tag->closing);if($code===NULL){throw
new
InvalidStateException("Unknown tag-macro <$tag->name> on line $this->line.");}if($isEmpty){$code.=$this->handler->tagMacro(substr($tag->name,strlen(self::HTML_PREFIX)),$tag->attrs,TRUE);}}else{$code=substr($this->output,$tag->pos).$matches[0].(isset($matches['tagnewline'])?"\n":'');$code=$this->handler->attrsMacro($code,$tag->attrs,$tag->closing);if($code===NULL){throw
new
InvalidStateException("Unknown macro-attribute ".self::HTML_PREFIX.implode(' or '.self::HTML_PREFIX,array_keys($tag->attrs))." on line $this->line.");}if($isEmpty){$code=$this->handler->attrsMacro($code,$tag->attrs,TRUE);}}$this->output=substr_replace($this->output,$code,$tag->pos);$matches[0]='';}if($isEmpty){$tag->closing=TRUE;}if(!$tag->closing&&(strcasecmp($tag->name,'script')===0||strcasecmp($tag->name,'style')===0)){$this->context=self::CONTEXT_CDATA;$this->escape=strcasecmp($tag->name,'style')?'TemplateHelpers::escapeJs':'TemplateHelpers::escapeCss';}else{$this->context=self::CONTEXT_TEXT;$this->escape='TemplateHelpers::escapeHtml';if($tag->closing)array_pop($this->tags);}}else{$name=$matches['attr'];$value=empty($matches['value'])?TRUE:$matches['value'];if($isSpecial=String::startsWith($name,self::HTML_PREFIX)){$name=substr($name,strlen(self::HTML_PREFIX));}$tag=end($this->tags);if($isSpecial||$tag->isMacro){if($value==='"'||$value==="'"){if($matches=$this->match('~(.*?)'.$value.'~xsi')){$value=$matches[1];}}$tag->attrs[$name]=$value;$matches[0]='';}elseif($value==='"'||$value==="'"){$this->context=self::CONTEXT_ATTRIBUTE;$this->quote=$value;$this->escape=strncasecmp($name,'on',2)?(strcasecmp($name,'style')?'TemplateHelpers::escapeHtml':'TemplateHelpers::escapeHtmlCss'):'TemplateHelpers::escapeHtmlJs';}}return$matches;}private
function
contextAttribute(){$matches=$this->match('~
			('.$this->quote.')|      ##  1) end of HTML attribute
			'.$this->macroRe.'           ##  curly tag
		~xsi');if($matches&&empty($matches['macro'])){$this->context=self::CONTEXT_TAG;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextComment(){$matches=$this->match('~
			(--\s*>)|                    ##  1) end of HTML comment
			'.$this->macroRe.'           ##  curly tag
		~xsi');if($matches&&empty($matches['macro'])){$this->context=self::CONTEXT_TEXT;$this->escape='TemplateHelpers::escapeHtml';}return$matches;}private
function
contextNone(){$matches=$this->match('~
			'.$this->macroRe.'           ##  curly tag
		~xsi');return$matches;}private
function
match($re){if($matches=String::match($this->input,$re,PREG_OFFSET_CAPTURE,$this->offset)){$this->output.=substr($this->input,$this->offset,$matches[0][1]-$this->offset);$this->offset=$matches[0][1]+strlen($matches[0][0]);foreach($matches
as$k=>$v)$matches[$k]=$v[0];}return$matches;}function
getLine(){return
substr_count($this->input,"\n",0,$this->offset);}function
setDelimiters($left,$right){$this->macroRe='
			(?P<indent>\n[\ \t]*)?
			'.$left.'
				(?P<macro>(?:'.self::RE_STRING.'|[^\'"]+?)*?)
			'.$right.'
			(?P<newline>[\ \t]*(?=\r|\n))?
		';return$this;}static
function
formatModifiers($var,$modifiers){if(!$modifiers)return$var;$tokens=String::matchAll($modifiers.'|','~
				'.self::RE_STRING.'|  ## single or double quoted string
				[^\'"|:,\s]+|         ## symbol
				[|:,]                 ## separator
			~xs',PREG_PATTERN_ORDER);$inside=FALSE;$prev='';foreach($tokens[0]as$token){if($token==='|'||$token===':'||$token===','){if($prev===''){}elseif(!$inside){if(!String::match($prev,'#^'.self::RE_IDENTIFIER.'$#')){throw
new
InvalidStateException("Modifier name must be alphanumeric string, '$prev' given.");}$var="\$template->$prev($var";$prev='';$inside=TRUE;}else{$var.=', '.self::formatString($prev);$prev='';}if($token==='|'&&$inside){$var.=')';$inside=FALSE;}}else{$prev.=$token;}}return$var;}static
function
fetchToken(&$s){if($matches=String::match($s,'#^((?>'.self::RE_STRING.'|[^\'"\s,]+)+)\s*,?\s*(.*)$#')){$s=$matches[2];return$matches[1];}return
NULL;}static
function
formatArray($s,$prefix=''){$s=String::replace(trim($s),'~
				'.self::RE_STRING.'|                          ## single or double quoted string
				(?<=[,=(]|=>|^)\s*([a-z\d_]+)(?=\s*[,=)]|$)   ## 1) symbol
			~xi',callback(__CLASS__,'cbArgs'));$s=String::replace($s,'#\$('.self::RE_IDENTIFIER.')\s*=>#','"$1" =>');return$s===''?'':$prefix."array($s)";}static
function
cbArgs($matches){if(!empty($matches[1])){list(,$symbol)=$matches;static$keywords=array('true'=>1,'false'=>1,'null'=>1,'and'=>1,'or'=>1,'xor'=>1,'clone'=>1,'new'=>1);return
is_numeric($symbol)||isset($keywords[strtolower($symbol)])?$matches[0]:"'$symbol'";}else{return$matches[0];}}static
function
formatString($s){static$keywords=array('true'=>1,'false'=>1,'null'=>1);return(is_numeric($s)||strspn($s,'\'"$')||isset($keywords[strtolower($s)]))?$s:'"'.$s.'"';}}class
LatteMacros
extends
Object{public
static$defaultMacros=array('syntax'=>'%:macroSyntax%','/syntax'=>'%:macroSyntax%','block'=>'<?php %:macroBlock% ?>','/block'=>'<?php %:macroBlockEnd% ?>','capture'=>'<?php %:macroCapture% ?>','/capture'=>'<?php %:macroCaptureEnd% ?>','snippet'=>'<?php %:macroSnippet% ?>','/snippet'=>'<?php %:macroSnippetEnd% ?>','cache'=>'<?php if ($_cb->foo = CachingHelper::create($_cb->key = md5(__FILE__) . __LINE__, $template->getFile(), array(%%))) { $_cb->caches[] = $_cb->foo ?>','/cache'=>'<?php array_pop($_cb->caches)->save(); } if (!empty($_cb->caches)) end($_cb->caches)->addItem($_cb->key) ?>','if'=>'<?php if (%%): ?>','elseif'=>'<?php elseif (%%): ?>','else'=>'<?php else: ?>','/if'=>'<?php endif ?>','ifset'=>'<?php if (isset(%%)): ?>','/ifset'=>'<?php endif ?>','elseifset'=>'<?php elseif (isset(%%)): ?>','foreach'=>'<?php foreach (%:macroForeach%): ?>','/foreach'=>'<?php endforeach; array_pop($_cb->its); $iterator = end($_cb->its) ?>','for'=>'<?php for (%%): ?>','/for'=>'<?php endfor ?>','while'=>'<?php while (%%): ?>','/while'=>'<?php endwhile ?>','continueIf'=>'<?php if (%%) continue ?>','breakIf'=>'<?php if (%%) break ?>','include'=>'<?php %:macroInclude% ?>','extends'=>'<?php %:macroExtends% ?>','layout'=>'<?php %:macroExtends% ?>','plink'=>'<?php echo %:macroEscape%(%:macroPlink%) ?>','link'=>'<?php echo %:macroEscape%(%:macroLink%) ?>','ifCurrent'=>'<?php %:macroIfCurrent%; if ($presenter->getLastCreatedRequestFlag("current")): ?>','widget'=>'<?php %:macroWidget% ?>','control'=>'<?php %:macroWidget% ?>','attr'=>'<?php echo Html::el(NULL)->%:macroAttr%attributes() ?>','contentType'=>'<?php %:macroContentType% ?>','status'=>'<?php Environment::getHttpResponse()->setCode(%%) ?>','var'=>'<?php %:macroAssign% ?>','assign'=>'<?php %:macroAssign% ?>','default'=>'<?php %:macroDefault% ?>','dump'=>'<?php Debug::barDump(%:macroDump%, "Template " . str_replace(Environment::getVariable("appDir"), "\xE2\x80\xA6", $template->getFile())) ?>','debugbreak'=>'<?php if (function_exists("debugbreak")) debugbreak(); elseif (function_exists("xdebug_break")) xdebug_break() ?>','!_'=>'<?php echo %:macroTranslate% ?>','_'=>'<?php echo %:macroEscape%(%:macroTranslate%) ?>','!='=>'<?php echo %:macroModifiers% ?>','='=>'<?php echo %:macroEscape%(%:macroModifiers%) ?>','!$'=>'<?php echo %:macroVar% ?>','$'=>'<?php echo %:macroEscape%(%:macroVar%) ?>','?'=>'<?php %:macroModifiers% ?>');public$macros;private$filter;private$current;private$blocks=array();private$namedBlocks=array();private$extends;private$uniq;private$oldSnippetMode=TRUE;const
BLOCK_NAMED=1;const
BLOCK_CAPTURE=2;const
BLOCK_ANONYMOUS=3;function
__construct(){$this->macros=self::$defaultMacros;}function
initialize($filter,&$s){$this->filter=$filter;$this->blocks=array();$this->namedBlocks=array();$this->extends=NULL;$this->uniq=substr(md5(uniqid('',TRUE)),0,10);$filter->context=LatteFilter::CONTEXT_TEXT;$filter->escape='TemplateHelpers::escapeHtml';$s=String::replace($s,'#\\{\\*.*?\\*\\}[\r\n]*#s','');$s=String::replace($s,'#@(\\{[^}]+?\\})#s','<?php } ?>$1<?php if (SnippetHelper::$outputAllowed) { ?>');}function
finalize(&$s){if(count($this->blocks)===1){$s.=$this->macro('/block','','');}elseif($this->blocks){throw
new
InvalidStateException("There are some unclosed blocks.");}$s="<?php\nif (".'SnippetHelper::$outputAllowed'.") {\n?>$s<?php\n}\n?>";if($this->namedBlocks||$this->extends){$s="<?php\n".'if ($_cb->extends) { ob_start(); }'."\n".'elseif (isset($presenter, $control) && $presenter->isAjax()) { LatteMacros::renderSnippets($control, $_cb, get_defined_vars()); }'."\n".'?>'.$s."<?php\n".'if ($_cb->extends) { ob_end_clean(); LatteMacros::includeTemplate($_cb->extends, get_defined_vars(), $template)->render(); }'."\n";}else{$s="<?php\n".'if (isset($presenter, $control) && $presenter->isAjax()) { LatteMacros::renderSnippets($control, $_cb, get_defined_vars()); }'."\n".'?>'.$s;}if($this->namedBlocks){foreach(array_reverse($this->namedBlocks,TRUE)as$name=>$foo){$name=preg_quote($name,'#');$s=String::replace($s,"#{block ($name)} \?>(.*)<\?php {/block $name}#sU",callback($this,'cbNamedBlocks'));}$s="<?php\n\n".implode("\n\n\n",$this->namedBlocks)."\n\n//\n// end of blocks\n//\n?>".$s;}$s="<?php\n".'$_cb = LatteMacros::initRuntime($template, '.var_export($this->extends,TRUE).', '.var_export($this->uniq,TRUE)."); unset(\$_extends);\n".'?>'.$s;}function
macro($macro,$content,$modifiers){if($macro===''){$macro=substr($content,0,2);if(!isset($this->macros[$macro])){$macro=substr($content,0,1);if(!isset($this->macros[$macro])){return
NULL;}}$content=substr($content,strlen($macro));}elseif(!isset($this->macros[$macro])){return
NULL;}$this->current=array($content,$modifiers);return
String::replace($this->macros[$macro],'#%(.*?)%#',callback($this,'cbMacro'));}function
cbMacro($m){list($content,$modifiers)=$this->current;if($m[1]){return
callback($m[1][0]===':'?array($this,substr($m[1],1)):$m[1])->invoke($content,$modifiers);}else{return$content;}}function
tagMacro($name,$attrs,$closing){$knownTags=array('include'=>'block','for'=>'each','block'=>'name','if'=>'cond','elseif'=>'cond');return$this->macro($closing?"/$name":$name,isset($knownTags[$name],$attrs[$knownTags[$name]])?$attrs[$knownTags[$name]]:substr(var_export($attrs,TRUE),8,-1),isset($attrs['modifiers'])?$attrs['modifiers']:'');}function
attrsMacro($code,$attrs,$closing){$left=$right='';foreach($this->macros
as$name=>$foo){if(!isset($this->macros["/$name"])){continue;}$macro=$closing?"/$name":$name;if(isset($attrs[$name])){if($closing){$right.=$this->macro($macro,'','');}else{$left=$this->macro($macro,$attrs[$name],'').$left;}}$innerName="inner-$name";if(isset($attrs[$innerName])){if($closing){$left.=$this->macro($macro,'','');}else{$right=$this->macro($macro,$attrs[$innerName],'').$right;}}$tagName="tag-$name";if(isset($attrs[$tagName])){$left=$this->macro($name,$attrs[$tagName],'').$left;$right.=$this->macro("/$name",'','');}unset($attrs[$name],$attrs[$innerName],$attrs[$tagName]);}return$attrs?NULL:$left.$code.$right;}function
macroVar($var,$modifiers){return
LatteFilter::formatModifiers('$'.$var,$modifiers);}function
macroTranslate($var,$modifiers){return
LatteFilter::formatModifiers($var,'translate|'.$modifiers);}function
macroSyntax($var){switch($var){case'':case'latte':$this->filter->setDelimiters('\\{(?![\\s\'"{}])','\\}');break;case'double':$this->filter->setDelimiters('\\{\\{(?![\\s\'"{}])','\\}\\}');break;case'asp':$this->filter->setDelimiters('<%\s*','\s*%>');break;case'python':$this->filter->setDelimiters('\\{[{%]\s*','\s*[%}]\\}');break;case'off':$this->filter->setDelimiters('[^\x00-\xFF]','');break;default:throw
new
InvalidStateException("Unknown macro syntax '$var' on line {$this->filter->line}.");}}function
macroInclude($content,$modifiers,$isDefinition=FALSE){$destination=LatteFilter::fetchToken($content);$params=LatteFilter::formatArray($content).($content?' + ':'');if($destination===NULL){throw
new
InvalidStateException("Missing destination in {include} on line {$this->filter->line}.");}elseif($destination[0]==='#'){$destination=ltrim($destination,'#');if(!String::match($destination,'#^'.LatteFilter::RE_IDENTIFIER.'$#')){throw
new
InvalidStateException("Included block name must be alphanumeric string, '$destination' given on line {$this->filter->line}.");}$parent=$destination==='parent';if($destination==='parent'||$destination==='this'){$item=end($this->blocks);while($item&&$item[0]!==self::BLOCK_NAMED)$item=prev($this->blocks);if(!$item){throw
new
InvalidStateException("Cannot include $destination block outside of any block on line {$this->filter->line}.");}$destination=$item[1];}$name=var_export($destination,TRUE);$params.=$isDefinition?'get_defined_vars()':'$template->getParams()';$cmd=isset($this->namedBlocks[$destination])&&!$parent?"call_user_func(reset(\$_cb->blocks[$name]), \$_cb, $params)":'LatteMacros::callBlock'.($parent?'Parent':'')."(\$_cb, $name, $params)";return$modifiers?"ob_start(); $cmd; echo ".LatteFilter::formatModifiers('ob_get_clean()',$modifiers):$cmd;}else{$destination=LatteFilter::formatString($destination);$params.='$template->getParams()';return$modifiers?'echo '.LatteFilter::formatModifiers('LatteMacros::includeTemplate'."($destination, $params, \$_cb->templates[".var_export($this->uniq,TRUE).'])->__toString(TRUE)',$modifiers):'LatteMacros::includeTemplate'."($destination, $params, \$_cb->templates[".var_export($this->uniq,TRUE).'])->render()';}}function
macroExtends($content){$destination=LatteFilter::fetchToken($content);if($destination===NULL){throw
new
InvalidStateException("Missing destination in {extends} on line {$this->filter->line}.");}if(!empty($this->blocks)){throw
new
InvalidStateException("{extends} must be placed outside any block; on line {$this->filter->line}.");}if($this->extends!==NULL){throw
new
InvalidStateException("Multiple {extends} declarations are not allowed; on line {$this->filter->line}.");}$this->extends=$destination!=='none';return$this->extends?'$_cb->extends = '.LatteFilter::formatString($destination):'';}function
macroBlock($content,$modifiers){$name=LatteFilter::fetchToken($content);if($name===NULL){$this->blocks[]=array(self::BLOCK_ANONYMOUS,NULL,$modifiers);return$modifiers===''?'':'ob_start()';}else{$name=ltrim($name,'#');if(!String::match($name,'#^'.LatteFilter::RE_IDENTIFIER.'$#')){throw
new
InvalidStateException("Block name must be alphanumeric string, '$name' given on line {$this->filter->line}.");}elseif(isset($this->namedBlocks[$name])){throw
new
InvalidStateException("Cannot redeclare block '$name'; on line {$this->filter->line}.");}$top=empty($this->blocks);$this->namedBlocks[$name]=$name;$this->blocks[]=array(self::BLOCK_NAMED,$name,'');if($name[0]==='_'){$tag=LatteFilter::fetchToken($content);$tag=trim($tag,'<>');$namePhp=var_export(substr($name,1),TRUE);if(!$tag)$tag='div';return"?><$tag id=\"<?php echo \$control->getSnippetId($namePhp) ?>\"><?php ".$this->macroInclude('#'.$name,$modifiers)." ?></$tag><?php {block $name}";}elseif(!$top){return$this->macroInclude('#'.$name,$modifiers,TRUE)."{block $name}";}elseif($this->extends){return"{block $name}";}else{return'if (!$_cb->extends) { '.$this->macroInclude('#'.$name,$modifiers,TRUE)."; } {block $name}";}}}function
macroBlockEnd($content){list($type,$name,$modifiers)=array_pop($this->blocks);if($type===self::BLOCK_CAPTURE){$this->blocks[]=array($type,$name,$modifiers);return$this->macroCaptureEnd($content);}if(($type!==self::BLOCK_NAMED&&$type!==self::BLOCK_ANONYMOUS)||($content&&$content!==$name)){throw
new
InvalidStateException("Tag {/block $content} was not expected here on line {$this->filter->line}.");}elseif($type===self::BLOCK_NAMED){return"{/block $name}";}else{return$modifiers===''?'':'echo '.LatteFilter::formatModifiers('ob_get_clean()',$modifiers);}}function
macroSnippet($content){if(substr($content,0,1)===':'||!$this->oldSnippetMode){$this->oldSnippetMode=FALSE;return$this->macroBlock('_'.ltrim($content,':'),'');}$args=array('');if($snippet=LatteFilter::fetchToken($content)){$args[]=LatteFilter::formatString($snippet);}if($content){$args[]=LatteFilter::formatString($content);}return'} if ($_cb->foo = SnippetHelper::create($control'.implode(', ',$args).')) { $_cb->snippets[] = $_cb->foo';}function
macroSnippetEnd($content){if(!$this->oldSnippetMode){return$this->macroBlockEnd('','');}return'array_pop($_cb->snippets)->finish(); } if (SnippetHelper::$outputAllowed) {';}function
macroCapture($content,$modifiers){$name=LatteFilter::fetchToken($content);if(substr($name,0,1)!=='$'){throw
new
InvalidStateException("Invalid capture block parameter '$name' on line {$this->filter->line}.");}$this->blocks[]=array(self::BLOCK_CAPTURE,$name,$modifiers);return'ob_start()';}function
macroCaptureEnd($content){list($type,$name,$modifiers)=array_pop($this->blocks);if($type!==self::BLOCK_CAPTURE||($content&&$content!==$name)){throw
new
InvalidStateException("Tag {/capture $content} was not expected here on line {$this->filter->line}.");}return$name.'='.LatteFilter::formatModifiers('ob_get_clean()',$modifiers);}function
cbNamedBlocks($matches){list(,$name,$content)=$matches;$func='_cbb'.substr(md5($this->uniq.$name),0,10).'_'.preg_replace('#[^a-z0-9_]#i','_',$name);$this->namedBlocks[$name]="//\n// block $name\n//\n"."if (!function_exists(\$_cb->blocks[".var_export($name,TRUE)."][] = '$func')) { function $func(\$_cb, \$_args) { extract(\$_args)\n?>$content<?php\n}}";return'';}function
macroForeach($content){return'$iterator = $_cb->its[] = new SmartCachingIterator('.preg_replace('# +as +#i',') as ',$content,1);}function
macroAttr($content){return
String::replace($content.' ','#\)\s+#',')->');}function
macroContentType($content){if(strpos($content,'html')!==FALSE){$this->filter->escape='TemplateHelpers::escapeHtml';$this->filter->context=LatteFilter::CONTEXT_TEXT;}elseif(strpos($content,'xml')!==FALSE){$this->filter->escape='TemplateHelpers::escapeXml';$this->filter->context=LatteFilter::CONTEXT_NONE;}elseif(strpos($content,'javascript')!==FALSE){$this->filter->escape='TemplateHelpers::escapeJs';$this->filter->context=LatteFilter::CONTEXT_NONE;}elseif(strpos($content,'css')!==FALSE){$this->filter->escape='TemplateHelpers::escapeCss';$this->filter->context=LatteFilter::CONTEXT_NONE;}elseif(strpos($content,'plain')!==FALSE){$this->filter->escape='';$this->filter->context=LatteFilter::CONTEXT_NONE;}else{$this->filter->escape='$template->escape';$this->filter->context=LatteFilter::CONTEXT_NONE;}return
strpos($content,'/')?'Environment::getHttpResponse()->setHeader("Content-Type", "'.$content.'")':'';}function
macroDump($content){return$content?"array(".var_export($content,TRUE)." => $content)":'get_defined_vars()';}function
macroWidget($content){$pair=LatteFilter::fetchToken($content);if($pair===NULL){throw
new
InvalidStateException("Missing widget name in {widget} on line {$this->filter->line}.");}$pair=explode(':',$pair,2);$widget=LatteFilter::formatString($pair[0]);$method=isset($pair[1])?ucfirst($pair[1]):'';$method=String::match($method,'#^('.LatteFilter::RE_IDENTIFIER.'|)$#')?"render$method":"{\"render$method\"}";$param=LatteFilter::formatArray($content);if(strpos($content,'=>')===FALSE)$param=substr($param,6,-1);return($widget[0]==='$'?"if (is_object($widget)) {$widget}->$method($param); else ":'')."\$control->getWidget($widget)->$method($param)";}function
macroLink($content,$modifiers){return
LatteFilter::formatModifiers('$control->link('.$this->formatLink($content).')',$modifiers);}function
macroPlink($content,$modifiers){return
LatteFilter::formatModifiers('$presenter->link('.$this->formatLink($content).')',$modifiers);}function
macroIfCurrent($content){return$content?'try { $presenter->link('.$this->formatLink($content).'); } catch (InvalidLinkException $e) {}':'';}private
function
formatLink($content){return
LatteFilter::formatString(LatteFilter::fetchToken($content)).LatteFilter::formatArray($content,', ');}function
macroAssign($content,$modifiers){if(!$content){throw
new
InvalidStateException("Missing arguments in {var} or {assign} on line {$this->filter->line}.");}if(strpos($content,'=>')===FALSE){return'$'.ltrim(LatteFilter::fetchToken($content),'$').' = '.LatteFilter::formatModifiers($content===''?'NULL':$content,$modifiers);}return'extract('.LatteFilter::formatArray($content).')';}function
macroDefault($content){if(!$content){throw
new
InvalidStateException("Missing arguments in {default} on line {$this->filter->line}.");}return'extract('.LatteFilter::formatArray($content).', EXTR_SKIP)';}function
macroEscape($content){return$this->filter->escape;}function
macroModifiers($content,$modifiers){return
LatteFilter::formatModifiers($content,$modifiers);}static
function
callBlock($context,$name,$params){if(empty($context->blocks[$name])){throw
new
InvalidStateException("Call to undefined block '$name'.");}$block=reset($context->blocks[$name]);$block($context,$params);}static
function
callBlockParent($context,$name,$params){if(empty($context->blocks[$name])||($block=next($context->blocks[$name]))===FALSE){throw
new
InvalidStateException("Call to undefined parent block '$name'.");}$block($context,$params);}static
function
includeTemplate($destination,$params,$template){if($destination
instanceof
ITemplate){$tpl=$destination;}elseif($destination==NULL){throw
new
InvalidArgumentException("Template file name was not specified.");}else{$tpl=clone$template;if($template
instanceof
IFileTemplate){if(substr($destination,0,1)!=='/'&&substr($destination,1,1)!==':'){$destination=dirname($template->getFile()).'/'.$destination;}$tpl->setFile($destination);}}$tpl->setParams($params);return$tpl;}static
function
initRuntime($template,$extends,$realFile){$cb=(object)NULL;if(isset($template->_cb)){$cb->blocks=&$template->_cb->blocks;$cb->templates=&$template->_cb->templates;}$cb->templates[$realFile]=$template;$cb->extends=is_bool($extends)?$extends:(empty($template->_extends)?FALSE:$template->_extends);unset($template->_cb,$template->_extends);if(!empty($cb->caches)){end($cb->caches)->addFile($template->getFile());}return$cb;}static
function
renderSnippets($control,$cb,$params){$payload=$control->getPresenter()->getPayload();if(isset($cb->blocks)){foreach($cb->blocks
as$name=>$function){if($name[0]!=='_'||!$control->isControlInvalid(substr($name,1)))continue;ob_start();$function=reset($function);$function($cb,$params);$payload->snippets[$control->getSnippetId(substr($name,1))]=ob_get_clean();}}}}class
SnippetHelper
extends
Object{public
static$outputAllowed=TRUE;private$id;private$tag;private$payload;private$level;static
function
create(Control$control,$name=NULL,$tag='div'){if(self::$outputAllowed){$obj=new
self;$obj->tag=trim($tag,'<>');if($obj->tag)echo'<',$obj->tag,' id="',$control->getSnippetId($name),'">';return$obj;}elseif($control->isControlInvalid($name)){$obj=new
self;$obj->id=$control->getSnippetId($name);$obj->payload=$control->getPresenter()->getPayload();ob_start();$obj->level=ob_get_level();self::$outputAllowed=TRUE;return$obj;}else{return
FALSE;}}function
finish(){if($this->tag!==NULL){if($this->tag)echo"</$this->tag>";}else{if($this->level!==ob_get_level()){throw
new
InvalidStateException("Snippet '$this->id' cannot be ended here.");}$this->payload->snippets[$this->id]=ob_get_clean();self::$outputAllowed=FALSE;}}}final
class
TemplateFilters{final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
removePhp($s){return
String::replace($s,'#\x01@php:p\d+@\x02#','<?php ?>');}static
function
relativeLinks($s){return
String::replace($s,'#(src|href|action)\s*=\s*(["\'])(?![a-z]+:|[\x01/\\#])#','$1=$2<?php echo \\$baseUri ?>');}static
function
netteLinks($s){return
String::replace($s,'#(src|href|action)\s*=\s*(["\'])(nette:.*?)([\#"\'])#',callback(__CLASS__,'netteLinksCb'));}static
function
netteLinksCb($m){list(,$attr,$quote,$uri,$fragment)=$m;$parts=parse_url($uri);if(isset($parts['scheme'])&&$parts['scheme']==='nette'){return$attr.'='.$quote.'<?php echo $template->escape($control->'."link('".(isset($parts['path'])?$parts['path']:'this!').(isset($parts['query'])?'?'.$parts['query']:'').'\'))?>'.$fragment;}else{return$m[0];}}public
static$texy;static
function
texyElements($s){return
String::replace($s,'#<texy([^>]*)>(.*?)</texy>#s',callback(__CLASS__,'texyCb'));}static
function
texyCb($m){list(,$mAttrs,$mContent)=$m;$attrs=array();if($mAttrs){foreach(String::matchAll($mAttrs,'#([a-z0-9:-]+)\s*(?:=\s*(\'[^\']*\'|"[^"]*"|[^\'"\s]+))?()#isu')as$m){$key=strtolower($m[1]);$val=$m[2];if($val==NULL)$attrs[$key]=TRUE;elseif($val{0}==='\''||$val{0}==='"')$attrs[$key]=html_entity_decode(substr($val,1,-1),ENT_QUOTES,'UTF-8');else$attrs[$key]=html_entity_decode($val,ENT_QUOTES,'UTF-8');}}return
self::$texy->process($m[2]);}}final
class
TemplateHelpers{final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
loader($helper){$callback=callback('TemplateHelpers',$helper);if($callback->isCallable()){return$callback;}$callback=callback('String',$helper);if($callback->isCallable()){return$callback;}}static
function
escapeHtml($s){if(is_object($s)&&($s
instanceof
ITemplate||$s
instanceof
Html||$s
instanceof
Form)){return$s->__toString(TRUE);}return
htmlSpecialChars($s,ENT_QUOTES);}static
function
escapeHtmlComment($s){return
str_replace('--','--><!--',$s);}static
function
escapeXML($s){return
htmlSpecialChars(preg_replace('#[\x00-\x08\x0B\x0C\x0E-\x1F]+#','',$s),ENT_QUOTES);}static
function
escapeCss($s){return
addcslashes($s,"\x00..\x2C./:;<=>?@[\\]^`{|}~");}static
function
escapeHtmlCss($s){return
htmlSpecialChars(self::escapeCss($s),ENT_QUOTES);}static
function
escapeJs($s){if(is_object($s)&&($s
instanceof
ITemplate||$s
instanceof
Html||$s
instanceof
Form)){$s=$s->__toString(TRUE);}return
str_replace(']]>',']]\x3E',json_encode($s));}static
function
escapeHtmlJs($s){return
htmlSpecialChars(self::escapeJs($s),ENT_QUOTES);}static
function
strip($s){return
String::replace($s,'#(</textarea|</pre|</script|^).*?(?=<textarea|<pre|<script|$)#si',callback(create_function('$m','return trim(preg_replace("#[ \t\r\n]+#", " ", $m[0]));')));}static
function
indent($s,$level=1,$chars="\t"){if($level>=1){$s=String::replace($s,'#<(textarea|pre).*?</\\1#si',callback(create_function('$m','return strtr($m[0], " \t\r\n", "\x1F\x1E\x1D\x1A");')));$s=String::indent($s,$level,$chars);$s=strtr($s,"\x1F\x1E\x1D\x1A"," \t\r\n");}return$s;}static
function
date($time,$format="%x"){if($time==NULL){return
NULL;}$time=Tools::createDateTime($time);return
strpos($format,'%')===FALSE?$time->format($format):strftime($format,$time->format('U'));}static
function
bytes($bytes,$precision=2){$bytes=round($bytes);$units=array('B','kB','MB','GB','TB','PB');foreach($units
as$unit){if(abs($bytes)<1024||$unit===end($units))break;$bytes=$bytes/1024;}return
round($bytes,$precision).' '.$unit;}static
function
length($var){return
is_string($var)?String::length($var):count($var);}static
function
replace($subject,$search,$replacement=''){return
str_replace($search,$replacement,$subject);}static
function
null($value){return'';}}class
Template
extends
BaseTemplate
implements
IFileTemplate{public
static$cacheExpire=FALSE;private
static$cacheStorage;private$file;function
__construct($file=NULL){if($file!==NULL){$this->setFile($file);}}function
setFile($file){if(!is_file($file)){throw
new
FileNotFoundException("Missing template file '$file'.");}$this->file=$file;return$this;}function
getFile(){return$this->file;}function
render(){if($this->file==NULL){throw
new
InvalidStateException("Template file name was not specified.");}$this->__set('template',$this);$shortName=str_replace(Environment::getVariable('appDir'),'',$this->file);$cache=new
Cache($this->getCacheStorage(),'Nette.Template');$key=trim(strtr($shortName,'\\/@','.._'),'.').'-'.md5($this->file);$cached=$content=$cache[$key];if($content===NULL){if(!$this->getFilters()){$this->onPrepareFilters($this);}if(!$this->getFilters()){LimitedScope::load($this->file,$this->getParams());return;}$content=$this->compile(file_get_contents($this->file),"file \xE2\x80\xA6$shortName");$cache->save($key,$content,array(Cache::FILES=>$this->file,Cache::EXPIRE=>self::$cacheExpire));$cache->release();$cached=$cache[$key];}if($cached!==NULL&&self::$cacheStorage
instanceof
TemplateCacheStorage){LimitedScope::load($cached['file'],$this->getParams());fclose($cached['handle']);}else{LimitedScope::evaluate($content,$this->getParams());}}static
function
setCacheStorage(ICacheStorage$storage){self::$cacheStorage=$storage;}static
function
getCacheStorage(){if(self::$cacheStorage===NULL){self::$cacheStorage=new
TemplateCacheStorage(Environment::getVariable('tempDir'));}return
self::$cacheStorage;}}class
TemplateCacheStorage
extends
FileStorage{protected
function
readData($meta){return
array('file'=>$meta[self::FILE],'handle'=>$meta[self::HANDLE]);}protected
function
getCacheFile($key){return
parent::getCacheFile($key).'.php';}}class
ArrayList
implements
ArrayAccess,Countable,IteratorAggregate{private$list=array();function
getIterator(){return
new
ArrayIterator($this->list);}function
count(){return
count($this->list);}function
offsetSet($index,$value){if($index===NULL){$this->list[]=$value;}elseif($index<0||$index>=count($this->list)){throw
new
OutOfRangeException("Offset invalid or out of range");}else{$this->list[(int)$index]=$value;}}function
offsetGet($index){if($index<0||$index>=count($this->list)){throw
new
OutOfRangeException("Offset invalid or out of range");}return$this->list[(int)$index];}function
offsetExists($index){return$index>=0&&$index<count($this->list);}function
offsetUnset($index){if($index<0||$index>=count($this->list)){throw
new
OutOfRangeException("Offset invalid or out of range");}array_splice($this->list,(int)$index,1);}}final
class
ArrayTools{final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
get(array$arr,$key,$default=NULL){foreach(is_array($key)?$key:array($key)as$k){if(is_array($arr)&&array_key_exists($k,$arr)){$arr=$arr[$k];}else{return$default;}}return$arr;}static
function&getRef(&$arr,$key){foreach(is_array($key)?$key:array($key)as$k){if(is_array($arr)||$arr===NULL){$arr=&$arr[$k];}else{throw
new
InvalidArgumentException('Traversed item is not an array.');}}return$arr;}static
function
mergeTree($arr1,$arr2){$res=$arr1+$arr2;foreach(array_intersect_key($arr1,$arr2)as$k=>$v){if(is_array($v)&&is_array($arr2[$k])){$res[$k]=self::mergeTree($v,$arr2[$k]);}}return$res;}static
function
searchKey($arr,$key){$foo=array($key=>NULL);return
array_search(key($foo),array_keys($arr),TRUE);}static
function
insertBefore(array&$arr,$key,array$inserted){$offset=self::searchKey($arr,$key);$arr=array_slice($arr,0,$offset,TRUE)+$inserted+array_slice($arr,$offset,count($arr),TRUE);}static
function
insertAfter(array&$arr,$key,array$inserted){$offset=self::searchKey($arr,$key);$offset=$offset===FALSE?count($arr):$offset+1;$arr=array_slice($arr,0,$offset,TRUE)+$inserted+array_slice($arr,$offset,count($arr),TRUE);}static
function
renameKey(array&$arr,$oldKey,$newKey){$offset=self::searchKey($arr,$oldKey);if($offset!==FALSE){$keys=array_keys($arr);$keys[$offset]=$newKey;$arr=array_combine($keys,$arr);}}}class
DateTime53
extends
DateTime{function
__sleep(){$this->fix=array($this->format('Y-m-d H:i:s'),$this->getTimezone()->getName());return
array('fix');}function
__wakeup(){$this->__construct($this->fix[0],new
DateTimeZone($this->fix[1]));unset($this->fix);}function
getTimestamp(){return(int)$this->format('U');}function
setTimestamp($timestamp){return$this->__construct(gmdate('Y-m-d H:i:s',$timestamp),new
DateTimeZone($this->getTimezone()->getName()));}}class
Image
extends
Object{const
ENLARGE=1;const
STRETCH=2;const
FIT=0;const
FILL=4;const
JPEG=IMAGETYPE_JPEG;const
PNG=IMAGETYPE_PNG;const
GIF=IMAGETYPE_GIF;const
EMPTY_GIF="GIF89a\x01\x00\x01\x00\x80\x00\x00\x00\x00\x00\x00\x00\x00!\xf9\x04\x01\x00\x00\x00\x00,\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02D\x01\x00;";public
static$useImageMagick=FALSE;private$image;static
function
rgb($red,$green,$blue,$transparency=0){return
array('red'=>max(0,min(255,(int)$red)),'green'=>max(0,min(255,(int)$green)),'blue'=>max(0,min(255,(int)$blue)),'alpha'=>max(0,min(127,(int)$transparency)));}static
function
fromFile($file,&$format=NULL){if(!extension_loaded('gd')){throw
new
Exception("PHP extension GD is not loaded.");}$info=@getimagesize($file);if(self::$useImageMagick&&(empty($info)||$info[0]*$info[1]>9e5)){return
new
ImageMagick($file,$format);}switch($format=$info[2]){case
self::JPEG:return
new
self(imagecreatefromjpeg($file));case
self::PNG:return
new
self(imagecreatefrompng($file));case
self::GIF:return
new
self(imagecreatefromgif($file));default:if(self::$useImageMagick){return
new
ImageMagick($file,$format);}throw
new
Exception("Unknown image type or file '$file' not found.");}}static
function
fromString($s,&$format=NULL){if(!extension_loaded('gd')){throw
new
Exception("PHP extension GD is not loaded.");}if(strncmp($s,"\xff\xd8",2)===0){$format=self::JPEG;}elseif(strncmp($s,"\x89PNG",4)===0){$format=self::PNG;}elseif(strncmp($s,"GIF",3)===0){$format=self::GIF;}else{$format=NULL;}return
new
self(imagecreatefromstring($s));}static
function
fromBlank($width,$height,$color=NULL){if(!extension_loaded('gd')){throw
new
Exception("PHP extension GD is not loaded.");}$width=(int)$width;$height=(int)$height;if($width<1||$height<1){throw
new
InvalidArgumentException('Image width and height must be greater than zero.');}$image=imagecreatetruecolor($width,$height);if(is_array($color)){$color+=array('alpha'=>0);$color=imagecolorallocatealpha($image,$color['red'],$color['green'],$color['blue'],$color['alpha']);imagealphablending($image,FALSE);imagefilledrectangle($image,0,0,$width-1,$height-1,$color);imagealphablending($image,TRUE);}return
new
self($image);}function
__construct($image){$this->setImageResource($image);}function
getWidth(){return
imagesx($this->image);}function
getHeight(){return
imagesy($this->image);}protected
function
setImageResource($image){if(!is_resource($image)||get_resource_type($image)!=='gd'){throw
new
InvalidArgumentException('Image is not valid.');}$this->image=$image;return$this;}function
getImageResource(){return$this->image;}function
resize($width,$height,$flags=self::FIT){list($newWidth,$newHeight)=self::calculateSize($this->getWidth(),$this->getHeight(),$width,$height,$flags);if($newWidth!==$this->getWidth()||$newHeight!==$this->getHeight()){$newImage=self::fromBlank($newWidth,$newHeight,self::RGB(0,0,0,127))->getImageResource();imagecopyresampled($newImage,$this->getImageResource(),0,0,0,0,$newWidth,$newHeight,$this->getWidth(),$this->getHeight());$this->image=$newImage;}if($width<0||$height<0){$newImage=self::fromBlank($newWidth,$newHeight,self::RGB(0,0,0,127))->getImageResource();imagecopyresampled($newImage,$this->getImageResource(),0,0,$width<0?$newWidth-1:0,$height<0?$newHeight-1:0,$newWidth,$newHeight,$width<0?-$newWidth:$newWidth,$height<0?-$newHeight:$newHeight);$this->image=$newImage;}return$this;}static
function
calculateSize($srcWidth,$srcHeight,$newWidth,$newHeight,$flags=self::FIT){if(substr($newWidth,-1)==='%'){$newWidth=round($srcWidth/100*abs($newWidth));$flags|=self::ENLARGE;$percents=TRUE;}else{$newWidth=(int)abs($newWidth);}if(substr($newHeight,-1)==='%'){$newHeight=round($srcHeight/100*abs($newHeight));$flags|=empty($percents)?self::ENLARGE:self::STRETCH;}else{$newHeight=(int)abs($newHeight);}if($flags&self::STRETCH){if(empty($newWidth)||empty($newHeight)){throw
new
InvalidArgumentException('For stretching must be both width and height specified.');}if(($flags&self::ENLARGE)===0){$newWidth=round($srcWidth*min(1,$newWidth/$srcWidth));$newHeight=round($srcHeight*min(1,$newHeight/$srcHeight));}}else{if(empty($newWidth)&&empty($newHeight)){throw
new
InvalidArgumentException('At least width or height must be specified.');}$scale=array();if($newWidth>0){$scale[]=$newWidth/$srcWidth;}if($newHeight>0){$scale[]=$newHeight/$srcHeight;}if($flags&self::FILL){$scale=array(max($scale));}if(($flags&self::ENLARGE)===0){$scale[]=1;}$scale=min($scale);$newWidth=round($srcWidth*$scale);$newHeight=round($srcHeight*$scale);}return
array((int)$newWidth,(int)$newHeight);}function
crop($left,$top,$width,$height){if(substr($left,-1)==='%'){$left=round(($this->getWidth()-$width)/100*$left);}if(substr($top,-1)==='%'){$top=round(($this->getHeight()-$height)/100*$top);}$left=max(0,(int)$left);$top=max(0,(int)$top);$width=min((int)$width,$this->getWidth()-$left);$height=min((int)$height,$this->getHeight()-$top);$newImage=self::fromBlank($width,$height,self::RGB(0,0,0,127))->getImageResource();imagecopy($newImage,$this->getImageResource(),0,0,$left,$top,$width,$height);$this->image=$newImage;return$this;}function
sharpen(){imageconvolution($this->getImageResource(),array(array(-1,-1,-1),array(-1,24,-1),array(-1,-1,-1)),16,0);return$this;}function
place(Image$image,$left=0,$top=0,$opacity=100){$opacity=max(0,min(100,(int)$opacity));if(substr($left,-1)==='%'){$left=round(($this->getWidth()-$image->getWidth())/100*$left);}if(substr($top,-1)==='%'){$top=round(($this->getHeight()-$image->getHeight())/100*$top);}if($opacity===100){imagecopy($this->getImageResource(),$image->getImageResource(),$left,$top,0,0,$image->getWidth(),$image->getHeight());}elseif($opacity<>0){imagecopymerge($this->getImageResource(),$image->getImageResource(),$left,$top,0,0,$image->getWidth(),$image->getHeight(),$opacity);}return$this;}function
save($file=NULL,$quality=NULL,$type=NULL){if($type===NULL){switch(strtolower(pathinfo($file,PATHINFO_EXTENSION))){case'jpg':case'jpeg':$type=self::JPEG;break;case'png':$type=self::PNG;break;case'gif':$type=self::GIF;}}switch($type){case
self::JPEG:$quality=$quality===NULL?85:max(0,min(100,(int)$quality));return
imagejpeg($this->getImageResource(),$file,$quality);case
self::PNG:$quality=$quality===NULL?9:max(0,min(9,(int)$quality));return
imagepng($this->getImageResource(),$file,$quality);case
self::GIF:return$file===NULL?imagegif($this->getImageResource()):imagegif($this->getImageResource(),$file);default:throw
new
Exception("Unsupported image type.");}}function
toString($type=self::JPEG,$quality=NULL){ob_start();$this->save(NULL,$quality,$type);return
ob_get_clean();}function
__toString(){try{return$this->toString();}catch(Exception$e){Debug::toStringException($e);}}function
send($type=self::JPEG,$quality=NULL){if($type!==self::GIF&&$type!==self::PNG&&$type!==self::JPEG){throw
new
Exception("Unsupported image type.");}header('Content-Type: '.image_type_to_mime_type($type));return$this->save(NULL,$quality,$type);}function
__call($name,$args){$function='image'.$name;if(function_exists($function)){foreach($args
as$key=>$value){if($value
instanceof
self){$args[$key]=$value->getImageResource();}elseif(is_array($value)&&isset($value['red'])){$args[$key]=imagecolorallocatealpha($this->getImageResource(),$value['red'],$value['green'],$value['blue'],$value['alpha']);}}array_unshift($args,$this->getImageResource());$res=call_user_func_array($function,$args);return
is_resource($res)?new
self($res):$res;}return
parent::__call($name,$args);}}class
ImageMagick
extends
Image{public
static$path='';public
static$tempDir;private$file;private$isTemporary=FALSE;private$width;private$height;function
__construct($file,&$format=NULL){if(!is_file($file)){throw
new
InvalidArgumentException("File '$file' not found.");}$format=$this->setFile(realpath($file));if($format==='JPEG')$format=self::JPEG;elseif($format==='PNG')$format=self::PNG;elseif($format==='GIF')$format=self::GIF;}function
getWidth(){return$this->file===NULL?parent::getWidth():$this->width;}function
getHeight(){return$this->file===NULL?parent::getHeight():$this->height;}function
getImageResource(){if($this->file!==NULL){if(!$this->isTemporary){$this->execute("convert -strip %input %output",self::PNG);}$this->setImageResource(imagecreatefrompng($this->file));if($this->isTemporary){unlink($this->file);}$this->file=NULL;}return
parent::getImageResource();}function
resize($width,$height,$flags=self::FIT){if($this->file===NULL){return
parent::resize($width,$height,$flags);}$mirror='';if($width<0)$mirror.=' -flop';if($height<0)$mirror.=' -flip';list($newWidth,$newHeight)=self::calculateSize($this->getWidth(),$this->getHeight(),$width,$height,$flags);$this->execute("convert -resize {$newWidth}x{$newHeight}! {$mirror} -strip %input %output",self::PNG);return$this;}function
crop($left,$top,$width,$height){if($this->file===NULL){return
parent::crop($left,$top,$width,$height);}$left=max(0,(int)$left);$top=max(0,(int)$top);$width=min((int)$width,$this->getWidth()-$left);$height=min((int)$height,$this->getHeight()-$top);$this->execute("convert -crop {$width}x{$height}+{$left}+{$top} -strip %input %output",self::PNG);return$this;}function
save($file=NULL,$quality=NULL,$type=NULL){if($this->file===NULL){return
parent::save($file,$quality,$type);}$quality=$quality===NULL?'':'-quality '.max(0,min(100,(int)$quality));if($file===NULL){$this->execute("convert $quality -strip %input %output",$type===NULL?self::PNG:$type);readfile($this->file);}else{$this->execute("convert $quality -strip %input %output",(string)$file);}return
TRUE;}private
function
setFile($file){$this->file=$file;$res=$this->execute('identify -format "%w,%h,%m" '.escapeshellarg($this->file));if(!$res){throw
new
Exception("Unknown image type in file '$file' or ImageMagick not available.");}list($this->width,$this->height,$format)=explode(',',$res,3);return$format;}private
function
execute($command,$output=NULL){$command=str_replace('%input',escapeshellarg($this->file),$command);if($output){$newFile=is_string($output)?$output:(self::$tempDir?self::$tempDir:dirname($this->file)).'/'.uniqid('_tempimage',TRUE).image_type_to_extension($output);$command=str_replace('%output',escapeshellarg($newFile),$command);}$lines=array();exec(self::$path.$command,$lines,$status);if($output){if($status!=0){throw
new
Exception("Unknown error while calling ImageMagick.");}if($this->isTemporary){unlink($this->file);}$this->setFile($newFile);$this->isTemporary=!is_string($output);}return$lines?$lines[0]:FALSE;}function
__destruct(){if($this->file!==NULL&&$this->isTemporary){unlink($this->file);}}}class
GenericRecursiveIterator
extends
IteratorIterator
implements
RecursiveIterator,Countable{function
hasChildren(){$obj=$this->current();return($obj
instanceof
IteratorAggregate&&$obj->getIterator()instanceof
RecursiveIterator)||$obj
instanceof
RecursiveIterator;}function
getChildren(){$obj=$this->current();return$obj
instanceof
IteratorAggregate?$obj->getIterator():$obj;}function
count(){return
iterator_count($this);}}class
InstanceFilterIterator
extends
FilterIterator
implements
Countable{private$type;function
__construct(Iterator$iterator,$type){$this->type=$type;parent::__construct($iterator);}function
accept(){return$this->current()instanceof$this->type;}function
count(){return
iterator_count($this);}}class
SmartCachingIterator
extends
CachingIterator
implements
Countable{private$counter=0;function
__construct($iterator){if(is_array($iterator)||$iterator
instanceof
stdClass){$iterator=new
ArrayIterator($iterator);}elseif($iterator
instanceof
Traversable){if($iterator
instanceof
IteratorAggregate){$iterator=$iterator->getIterator();}elseif(!($iterator
instanceof
Iterator)){$iterator=new
IteratorIterator($iterator);}}else{throw
new
InvalidArgumentException("Invalid argument passed to foreach resp. ".__CLASS__."; array or Traversable expected, ".(is_object($iterator)?get_class($iterator):gettype($iterator))." given.");}parent::__construct($iterator,0);}function
isFirst($width=NULL){return$width===NULL?$this->counter===1:($this->counter
%$width)===1;}function
isLast($width=NULL){return!$this->hasNext()||($width!==NULL&&($this->counter
%$width)===0);}function
isEmpty(){return$this->counter===0;}function
isOdd(){return$this->counter
%
2===1;}function
isEven(){return$this->counter
%
2===0;}function
getCounter(){return$this->counter;}function
count(){$inner=$this->getInnerIterator();if($inner
instanceof
Countable){return$inner->count();}else{throw
new
NotSupportedException('Iterator is not countable.');}}function
next(){parent::next();if(parent::valid()){$this->counter++;}}function
rewind(){parent::rewind();$this->counter=parent::valid()?1:0;}function
getNextKey(){return$this->getInnerIterator()->key();}function
getNextValue(){return$this->getInnerIterator()->current();}function
__call($name,$args){return
ObjectMixin::call($this,$name,$args);}function&__get($name){return
ObjectMixin::get($this,$name);}function
__set($name,$value){return
ObjectMixin::set($this,$name,$value);}function
__isset($name){return
ObjectMixin::has($this,$name);}function
__unset($name){$class=get_class($this);throw
new
MemberAccessException("Cannot unset the property $class::\$$name.");}}class
NeonParser
extends
Object{private
static$patterns=array('(\'[^\'\n]*\'|"(?:\\\\.|[^"\\\\\n])*")','(@[a-zA-Z_0-9\\\\]+)','([:-](?=\s|$)|[,=[\]{}()])','#.*','(\n *)','literal'=>'([^#"\',:=@[\]{}()<>\s](?:[^#,:=\]})>\n]+|:(?!\s)|(?<!\s)#)*)(?<!\s)',' +');private
static$regexp;private
static$brackets=array('['=>']','{'=>'}','('=>')');private$input;private$tokens;private$n;function
parse($s){$this->tokenize($s);$this->n=0;$res=$this->_parse();while(isset($this->tokens[$this->n])){if($this->tokens[$this->n][0]==="\n")$this->n++;else$this->error();}return$res;}private
function
_parse($indent=NULL,$endBracket=NULL){$inlineParser=$endBracket!==NULL;$result=$inlineParser||$indent?array():NULL;$value=$key=$object=NULL;$hasValue=$hasKey=FALSE;$tokens=$this->tokens;$n=&$this->n;$count=count($tokens);for(;$n<$count;$n++){$t=$tokens[$n];if($t===','){if(!$hasValue||!$inlineParser){$this->error();}if($hasKey)$result[$key]=$value;else$result[]=$value;$hasKey=$hasValue=FALSE;}elseif($t===':'||$t==='='){if($hasKey||!$hasValue){$this->error();}$key=(string)$value;$hasKey=TRUE;$hasValue=FALSE;}elseif($t==='-'){if($hasKey||$hasValue||$inlineParser){$this->error();}$key=NULL;$hasKey=TRUE;}elseif(isset(self::$brackets[$t])){if($hasValue){$this->error();}$hasValue=TRUE;$value=$this->_parse(NULL,self::$brackets[$tokens[$n++]]);}elseif($t===']'||$t==='}'||$t===')'){if($t!==$endBracket){$this->error();}if($hasValue){if($hasKey)$result[$key]=$value;else$result[]=$value;}elseif($hasKey){$this->error();}return$result;}elseif($t[0]==='@'){$object=$t;}elseif($t[0]==="\n"){if($inlineParser){if($hasValue){if($hasKey)$result[$key]=$value;else$result[]=$value;$hasKey=$hasValue=FALSE;}}else{while(isset($tokens[$n+1])&&$tokens[$n+1][0]==="\n")$n++;$newIndent=strlen($tokens[$n])-1;if($indent===NULL){$indent=$newIndent;}if($newIndent>$indent){if($hasValue||!$hasKey){$this->error();}elseif($key===NULL){$result[]=$this->_parse($newIndent);}else{$result[$key]=$this->_parse($newIndent);}$newIndent=strlen($tokens[$n])-1;$hasKey=FALSE;}else{if($hasValue&&!$hasKey){if($result===NULL)return$value;$this->error();}elseif($hasKey){$value=$hasValue?$value:NULL;if($key===NULL)$result[]=$value;else$result[$key]=$value;$hasKey=$hasValue=FALSE;}}if($newIndent<$indent||!isset($tokens[$n+1])){return$result;}}}else{if($hasValue){$this->error();}if($t[0]==='"'){$value=json_decode($t);if($value===NULL){$this->error();}}elseif($t[0]==="'"){$value=substr($t,1,-1);}elseif($t==='true'||$t==='yes'||$t==='TRUE'||$t==='YES'){$value=TRUE;}elseif($t==='false'||$t==='no'||$t==='FALSE'||$t==='NO'){$value=FALSE;}elseif($t==='null'||$t==='NULL'){$value=NULL;}elseif(is_numeric($t)){$value=$t*1;}else{$value=$t;}$hasValue=TRUE;}}throw
new
Exception('NEON parse error: unexpected end of file.');}private
function
tokenize($s){if(!self::$regexp){self::$regexp='~'.implode('|',self::$patterns).'~mA';}$s=str_replace("\r",'',$s);$s=strtr($s,"\t",' ');$s="\n".$s."\n";$this->input=$s;$this->tokens=String::split($s,self::$regexp,PREG_SPLIT_NO_EMPTY);if(end($this->tokens)!=="\n"){$this->n=key($this->tokens);$this->error();}}private
function
error(){$tokens=String::split($this->input,self::$regexp,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_OFFSET_CAPTURE);list($token,$offset)=$tokens[$this->n];$line=substr_count($this->input,"\n",0,$offset)+1;$col=$offset-strrpos(substr($this->input,0,$offset),"\n");throw
new
Exception('NEON parse error: unexpected '.str_replace("\n",'\n',substr($token,0,10))." on line $line, column $col.");}}class
Paginator
extends
Object{private$base=1;private$itemsPerPage=1;private$page;private$itemCount=0;function
setPage($page){$this->page=(int)$page;return$this;}function
getPage(){return$this->base+$this->getPageIndex();}function
getFirstPage(){return$this->base;}function
getLastPage(){return$this->base+max(0,$this->getPageCount()-1);}function
setBase($base){$this->base=(int)$base;return$this;}function
getBase(){return$this->base;}protected
function
getPageIndex(){return
min(max(0,$this->page-$this->base),max(0,$this->getPageCount()-1));}function
isFirst(){return$this->getPageIndex()===0;}function
isLast(){return$this->getPageIndex()>=$this->getPageCount()-1;}function
getPageCount(){return(int)ceil($this->itemCount/$this->itemsPerPage);}function
setItemsPerPage($itemsPerPage){$this->itemsPerPage=max(1,(int)$itemsPerPage);return$this;}function
getItemsPerPage(){return$this->itemsPerPage;}function
setItemCount($itemCount){$this->itemCount=$itemCount===FALSE?PHP_INT_MAX:max(0,(int)$itemCount);return$this;}function
getItemCount(){return$this->itemCount;}function
getOffset(){return$this->getPageIndex()*$this->itemsPerPage;}function
getCountdownOffset(){return
max(0,$this->itemCount-($this->getPageIndex()+1)*$this->itemsPerPage);}function
getLength(){return
min($this->itemsPerPage,$this->itemCount-$this->getPageIndex()*$this->itemsPerPage);}}final
class
SafeStream{const
PROTOCOL='safe';private$handle;private$filePath;private$tempFile;private$startPos=0;private$writeError=FALSE;static
function
register(){return
stream_wrapper_register(self::PROTOCOL,__CLASS__);}function
stream_open($path,$mode,$options,&$opened_path){$path=substr($path,strlen(self::PROTOCOL)+3);$flag=trim($mode,'rwax+');$mode=trim($mode,'tb');$use_path=(bool)(STREAM_USE_PATH&$options);$append=FALSE;switch($mode){case'r':case'r+':$handle=@fopen($path,$mode.$flag,$use_path);if(!$handle)return
FALSE;if(flock($handle,$mode=='r'?LOCK_SH:LOCK_EX)){$this->handle=$handle;return
TRUE;}fclose($handle);return
FALSE;case'a':case'a+':$append=TRUE;case'w':case'w+':$handle=@fopen($path,'r+'.$flag,$use_path);if($handle){if(flock($handle,LOCK_EX)){if($append){fseek($handle,0,SEEK_END);$this->startPos=ftell($handle);}else{ftruncate($handle,0);}$this->handle=$handle;return
TRUE;}fclose($handle);}$mode{0}='x';case'x':case'x+':if(file_exists($path))return
FALSE;$tmp='~~'.time().'.tmp';$handle=@fopen($path.$tmp,$mode.$flag,$use_path);if($handle){if(flock($handle,LOCK_EX)){$this->handle=$handle;if(!@rename($path.$tmp,$path)){$this->tempFile=realpath($path.$tmp);$this->filePath=substr($this->tempFile,0,-strlen($tmp));}return
TRUE;}fclose($handle);unlink($path.$tmp);}return
FALSE;default:trigger_error("Unsupported mode $mode",E_USER_WARNING);return
FALSE;}}function
stream_close(){if($this->writeError){ftruncate($this->handle,$this->startPos);}fclose($this->handle);if($this->tempFile){if(!@rename($this->tempFile,$this->filePath)){unlink($this->tempFile);}}}function
stream_read($length){return
fread($this->handle,$length);}function
stream_write($data){$len=strlen($data);$res=fwrite($this->handle,$data,$len);if($res!==$len){$this->writeError=TRUE;}return$res;}function
stream_tell(){return
ftell($this->handle);}function
stream_eof(){return
feof($this->handle);}function
stream_seek($offset,$whence){return
fseek($this->handle,$offset,$whence)===0;}function
stream_stat(){return
fstat($this->handle);}function
url_stat($path,$flags){$path=substr($path,strlen(self::PROTOCOL)+3);return($flags&STREAM_URL_STAT_LINK)?@lstat($path):@stat($path);}function
unlink($path){$path=substr($path,strlen(self::PROTOCOL)+3);return
unlink($path);}}final
class
String{final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
checkEncoding($s,$encoding='UTF-8'){return$s===self::fixEncoding($s,$encoding);}static
function
fixEncoding($s,$encoding='UTF-8'){return@iconv('UTF-16',$encoding.'//IGNORE',iconv($encoding,'UTF-16//IGNORE',$s));}static
function
chr($code,$encoding='UTF-8'){return
iconv('UTF-32BE',$encoding.'//IGNORE',pack('N',$code));}static
function
startsWith($haystack,$needle){return
strncmp($haystack,$needle,strlen($needle))===0;}static
function
endsWith($haystack,$needle){return
strlen($needle)===0||substr($haystack,-strlen($needle))===$needle;}static
function
normalize($s){$s=str_replace("\r\n","\n",$s);$s=strtr($s,"\r","\n");$s=preg_replace('#[\x00-\x08\x0B-\x1F]+#','',$s);$s=preg_replace("#[\t ]+$#m",'',$s);$s=trim($s,"\n");return$s;}static
function
webalize($s,$charlist=NULL,$lower=TRUE){$s=strtr($s,'`\'"^~','-----');if(ICONV_IMPL==='glibc'){setlocale(LC_CTYPE,'en_US.UTF-8');}$s=@iconv('UTF-8','ASCII//TRANSLIT',$s);$s=str_replace(array('`',"'",'"','^','~'),'',$s);if($lower)$s=strtolower($s);$s=preg_replace('#[^a-z0-9'.preg_quote($charlist,'#').']+#i','-',$s);$s=trim($s,'-');return$s;}static
function
truncate($s,$maxLen,$append="\xE2\x80\xA6"){if(self::length($s)>$maxLen){$maxLen=$maxLen-self::length($append);if($maxLen<1){return$append;}elseif($matches=self::match($s,'#^.{1,'.$maxLen.'}(?=[\s\x00-@\[-`{-~])#us')){return$matches[0].$append;}else{return
iconv_substr($s,0,$maxLen,'UTF-8').$append;}}return$s;}static
function
indent($s,$level=1,$chars="\t"){return$level<1?$s:self::replace($s,'#(?:^|[\r\n]+)(?=[^\r\n])#','$0'.str_repeat($chars,$level));}static
function
lower($s){return
mb_strtolower($s,'UTF-8');}static
function
upper($s){return
mb_strtoupper($s,'UTF-8');}static
function
capitalize($s){return
mb_convert_case($s,MB_CASE_TITLE,'UTF-8');}static
function
compare($left,$right,$len=NULL){if($len<0){$left=iconv_substr($left,$len,-$len,'UTF-8');$right=iconv_substr($right,$len,-$len,'UTF-8');}elseif($len!==NULL){$left=iconv_substr($left,0,$len,'UTF-8');$right=iconv_substr($right,0,$len,'UTF-8');}return
self::lower($left)===self::lower($right);}static
function
length($s){return
function_exists('mb_strlen')?mb_strlen($s,'UTF-8'):strlen(utf8_decode($s));}static
function
trim($s,$charlist=" \t\n\r\0\x0B\xC2\xA0"){$charlist=preg_quote($charlist,'#');return
self::replace($s,'#^['.$charlist.']+|['.$charlist.']+$#u','');}static
function
padLeft($s,$length,$pad=' '){$length=max(0,$length-self::length($s));$padLen=self::length($pad);return
str_repeat($pad,$length/$padLen).iconv_substr($pad,0,$length
%$padLen,'UTF-8').$s;}static
function
padRight($s,$length,$pad=' '){$length=max(0,$length-self::length($s));$padLen=self::length($pad);return$s.str_repeat($pad,$length/$padLen).iconv_substr($pad,0,$length
%$padLen,'UTF-8');}static
function
split($subject,$pattern,$flags=0){$res=preg_split($pattern,$subject,-1,$flags|PREG_SPLIT_DELIM_CAPTURE);self::checkPreg($res,$pattern);return$res;}static
function
match($subject,$pattern,$flags=0,$offset=0){$res=preg_match($pattern,$subject,$m,$flags,$offset);self::checkPreg($res,$pattern);if($res){return$m;}}static
function
matchAll($subject,$pattern,$flags=0,$offset=0){$res=preg_match_all($pattern,$subject,$m,($flags&PREG_PATTERN_ORDER)?$flags:($flags|PREG_SET_ORDER),$offset);self::checkPreg($res,$pattern);return$m;}static
function
replace($subject,$pattern,$replacement=NULL,$limit=-1){preg_match('##','');if(is_object($replacement)||is_array($replacement)){if($replacement
instanceof
Callback){$replacement=$replacement->getNative();}if(!is_callable($replacement,FALSE,$textual)){throw
new
InvalidStateException("Callback '$textual' is not callable.");}$res=preg_replace_callback($pattern,$replacement,$subject,$limit);}elseif(is_array($pattern)){$res=preg_replace(array_keys($pattern),array_values($pattern),$subject,$limit);}else{$res=preg_replace($pattern,$replacement,$subject,$limit);}if(preg_last_error()){self::checkPreg(TRUE,$pattern);}elseif($res===NULL){self::checkPreg(FALSE,$pattern);}else{return$res;}}private
static
function
checkPreg($res,$pattern){if($res===FALSE){$error=error_get_last();throw
new
RegexpException("$error[message] in pattern: $pattern");}elseif(preg_last_error()){static$messages=array(PREG_INTERNAL_ERROR=>'Internal error',PREG_BACKTRACK_LIMIT_ERROR=>'Backtrack limit was exhausted',PREG_RECURSION_LIMIT_ERROR=>'Recursion limit was exhausted',PREG_BAD_UTF8_ERROR=>'Malformed UTF-8 data',5=>'Offset didn\'t correspond to the begin of a valid UTF-8 code point');$code=preg_last_error();throw
new
RegexpException((isset($messages[$code])?$messages[$code]:'Unknown error')." (pattern: $pattern)",$code);}}}class
RegexpException
extends
Exception{}final
class
Tools{const
MINUTE=60;const
HOUR=3600;const
DAY=86400;const
WEEK=604800;const
MONTH=2629800;const
YEAR=31557600;final
function
__construct(){throw
new
LogicException("Cannot instantiate static class ".get_class($this));}static
function
createDateTime($time){if($time
instanceof
DateTime){return
clone$time;}elseif(is_numeric($time)){if($time<=self::YEAR){$time+=time();}return
new
DateTime53(date('Y-m-d H:i:s',$time));}else{return
new
DateTime53($time);}}static
function
iniFlag($var){$status=strtolower(ini_get($var));return$status==='on'||$status==='true'||$status==='yes'||(int)$status;}static
function
defaultize(&$var,$default){if($var===NULL)$var=$default;}static
function
glob($pattern,$flags=0){$files=glob($pattern,$flags);if(!is_array($files)){$files=array();}$dirs=glob(dirname($pattern).'/*',$flags|GLOB_ONLYDIR);if(is_array($dirs)){$mask=basename($pattern);foreach($dirs
as$dir){$files=array_merge($files,self::glob($dir.'/'.$mask,$flags));}}return$files;}private
static$errorMsg;static
function
tryError($level=E_ALL){set_error_handler(array(__CLASS__,'_errorHandler'),$level);self::$errorMsg=NULL;}static
function
catchError(&$message){restore_error_handler();$message=self::$errorMsg;self::$errorMsg=NULL;return$message!==NULL;}static
function
_errorHandler($severity,$message){if(($severity&error_reporting())!==$severity){return
NULL;}if(ini_get('html_errors')){$message=html_entity_decode(strip_tags($message),ENT_QUOTES,'UTF-8');}if(($a=strpos($message,': '))!==FALSE){$message=substr($message,$a+2);}self::$errorMsg=$message;}}class
Html
extends
Object
implements
ArrayAccess,Countable,IteratorAggregate{private$name;private$isEmpty;public$attrs=array();protected$children=array();public
static$xhtml=TRUE;public
static$emptyElements=array('img'=>1,'hr'=>1,'br'=>1,'input'=>1,'meta'=>1,'area'=>1,'command'=>1,'keygen'=>1,'source'=>1,'base'=>1,'col'=>1,'link'=>1,'param'=>1,'basefont'=>1,'frame'=>1,'isindex'=>1,'wbr'=>1,'embed'=>1);static
function
el($name=NULL,$attrs=NULL){$el=new
self;$parts=explode(' ',$name,2);$el->setName($parts[0]);if(is_array($attrs)){$el->attrs=$attrs;}elseif($attrs!==NULL){$el->setText($attrs);}if(isset($parts[1])){foreach(String::matchAll($parts[1].' ','#([a-z0-9:-]+)(?:=(["\'])?(.*?)(?(2)\\2|\s))?#i')as$m){$el->attrs[$m[1]]=isset($m[3])?$m[3]:TRUE;}}return$el;}final
function
setName($name,$isEmpty=NULL){if($name!==NULL&&!is_string($name)){throw
new
InvalidArgumentException("Name must be string or NULL, ".gettype($name)." given.");}$this->name=$name;$this->isEmpty=$isEmpty===NULL?isset(self::$emptyElements[$name]):(bool)$isEmpty;return$this;}final
function
getName(){return$this->name;}final
function
isEmpty(){return$this->isEmpty;}final
function
__set($name,$value){$this->attrs[$name]=$value;}final
function&__get($name){return$this->attrs[$name];}final
function
__unset($name){unset($this->attrs[$name]);}final
function
__call($m,$args){$p=substr($m,0,3);if($p==='get'||$p==='set'||$p==='add'){$m=substr($m,3);$m[0]=$m[0]|"\x20";if($p==='get'){return
isset($this->attrs[$m])?$this->attrs[$m]:NULL;}elseif($p==='add'){$args[]=TRUE;}}if(count($args)===1){$this->attrs[$m]=$args[0];}elseif($args[0]==NULL){$tmp=&$this->attrs[$m];}elseif(!isset($this->attrs[$m])||is_array($this->attrs[$m])){$this->attrs[$m][$args[0]]=$args[1];}else{$this->attrs[$m]=array($this->attrs[$m],$args[0]=>$args[1]);}return$this;}final
function
href($path,$query=NULL){if($query){$query=http_build_query($query,NULL,'&');if($query!=='')$path.='?'.$query;}$this->attrs['href']=$path;return$this;}final
function
setHtml($html){if($html===NULL){$html='';}elseif(is_array($html)){throw
new
InvalidArgumentException("Textual content must be a scalar, ".gettype($html)." given.");}else{$html=(string)$html;}$this->removeChildren();$this->children[]=$html;return$this;}final
function
getHtml(){$s='';foreach($this->children
as$child){if(is_object($child)){$s.=$child->render();}else{$s.=$child;}}return$s;}final
function
setText($text){if(!is_array($text)){$text=str_replace(array('&','<','>'),array('&amp;','&lt;','&gt;'),(string)$text);}return$this->setHtml($text);}final
function
getText(){return
html_entity_decode(strip_tags($this->getHtml()),ENT_QUOTES,'UTF-8');}final
function
add($child){return$this->insert(NULL,$child);}final
function
create($name,$attrs=NULL){$this->insert(NULL,$child=self::el($name,$attrs));return$child;}function
insert($index,$child,$replace=FALSE){if($child
instanceof
Html||is_scalar($child)){if($index===NULL){$this->children[]=$child;}else{array_splice($this->children,(int)$index,$replace?1:0,array($child));}}else{throw
new
InvalidArgumentException("Child node must be scalar or Html object, ".(is_object($child)?get_class($child):gettype($child))." given.");}return$this;}final
function
offsetSet($index,$child){$this->insert($index,$child,TRUE);}final
function
offsetGet($index){return$this->children[$index];}final
function
offsetExists($index){return
isset($this->children[$index]);}function
offsetUnset($index){if(isset($this->children[$index])){array_splice($this->children,(int)$index,1);}}final
function
count(){return
count($this->children);}function
removeChildren(){$this->children=array();}final
function
getIterator($deep=FALSE){if($deep){$deep=$deep>0?RecursiveIteratorIterator::SELF_FIRST:RecursiveIteratorIterator::CHILD_FIRST;return
new
RecursiveIteratorIterator(new
GenericRecursiveIterator(new
ArrayIterator($this->children)),$deep);}else{return
new
GenericRecursiveIterator(new
ArrayIterator($this->children));}}final
function
getChildren(){return$this->children;}final
function
render($indent=NULL){$s=$this->startTag();if(!$this->isEmpty){if($indent!==NULL){$indent++;}foreach($this->children
as$child){if(is_object($child)){$s.=$child->render($indent);}else{$s.=$child;}}$s.=$this->endTag();}if($indent!==NULL){return"\n".str_repeat("\t",$indent-1).$s."\n".str_repeat("\t",max(0,$indent-2));}return$s;}final
function
__toString(){return$this->render();}final
function
startTag(){if($this->name){return'<'.$this->name.$this->attributes().(self::$xhtml&&$this->isEmpty?' />':'>');}else{return'';}}final
function
endTag(){return$this->name&&!$this->isEmpty?'</'.$this->name.'>':'';}final
function
attributes(){if(!is_array($this->attrs)){return'';}$s='';foreach($this->attrs
as$key=>$value){if($value===NULL||$value===FALSE)continue;if($value===TRUE){if(self::$xhtml)$s.=' '.$key.'="'.$key.'"';else$s.=' '.$key;continue;}elseif(is_array($value)){$tmp=NULL;foreach($value
as$k=>$v){if($v==NULL)continue;$tmp[]=is_string($k)?($v===TRUE?$k:$k.':'.$v):$v;}if($tmp===NULL)continue;$value=implode($key==='style'||!strncmp($key,'on',2)?';':' ',$tmp);}else{$value=(string)$value;}$s.=' '.$key.'="'.str_replace(array('&','"','<','>','@'),array('&amp;','&quot;','&lt;','&gt;','&#64;'),$value).'"';}return$s;}function
__clone(){foreach($this->children
as$key=>$value){if(is_object($value)){$this->children[$key]=clone$value;}}}}class
HttpContext
extends
Object{function
isModified($lastModified=NULL,$etag=NULL){$response=$this->getResponse();$request=$this->getRequest();if($lastModified){$response->setHeader('Last-Modified',$response->date($lastModified));}if($etag){$response->setHeader('ETag','"'.addslashes($etag).'"');}$ifNoneMatch=$request->getHeader('If-None-Match');if($ifNoneMatch==='*'){$match=TRUE;}elseif($ifNoneMatch!==NULL){$etag=$response->getHeader('ETag');if($etag==NULL||strpos(' '.strtr($ifNoneMatch,",\t",'  '),' '.$etag)===FALSE){return
TRUE;}else{$match=TRUE;}}$ifModifiedSince=$request->getHeader('If-Modified-Since');if($ifModifiedSince!==NULL){$lastModified=$response->getHeader('Last-Modified');if($lastModified!=NULL&&strtotime($lastModified)<=strtotime($ifModifiedSince)){$match=TRUE;}else{return
TRUE;}}if(empty($match)){return
TRUE;}$response->setCode(IHttpResponse::S304_NOT_MODIFIED);return
FALSE;}function
getRequest(){return
Environment::getHttpRequest();}function
getResponse(){return
Environment::getHttpResponse();}}class
HttpRequest
extends
Object
implements
IHttpRequest{protected$query;protected$post;protected$files;protected$cookies;protected$uri;protected$originalUri;protected$headers;protected$uriFilter=array(PHP_URL_PATH=>array('#/{2,}#'=>'/'),0=>array());protected$encoding;final
function
getUri(){if($this->uri===NULL){$this->detectUri();}return$this->uri;}function
setUri(UriScript$uri){$this->uri=clone$uri;$this->query=NULL;$this->uri->canonicalize();$this->uri->freeze();return$this;}final
function
getOriginalUri(){if($this->originalUri===NULL){$this->detectUri();}return$this->originalUri;}function
addUriFilter($pattern,$replacement='',$component=NULL){$pattern='#'.$pattern.'#';$component=$component===PHP_URL_PATH?PHP_URL_PATH:0;if($replacement===NULL){unset($this->uriFilter[$component][$pattern]);}else{$this->uriFilter[$component][$pattern]=$replacement;}$this->uri=NULL;}final
function
getUriFilters(){return$this->uriFilter;}protected
function
detectUri(){$uri=$this->uri=new
UriScript;$uri->scheme=$this->isSecured()?'https':'http';$uri->user=isset($_SERVER['PHP_AUTH_USER'])?$_SERVER['PHP_AUTH_USER']:'';$uri->password=isset($_SERVER['PHP_AUTH_PW'])?$_SERVER['PHP_AUTH_PW']:'';if(isset($_SERVER['HTTP_HOST'])){$pair=explode(':',$_SERVER['HTTP_HOST']);}elseif(isset($_SERVER['SERVER_NAME'])){$pair=explode(':',$_SERVER['SERVER_NAME']);}else{$pair=array('');}$uri->host=$pair[0];if(isset($pair[1])){$uri->port=(int)$pair[1];}elseif(isset($_SERVER['SERVER_PORT'])){$uri->port=(int)$_SERVER['SERVER_PORT'];}if(isset($_SERVER['REQUEST_URI'])){$requestUri=$_SERVER['REQUEST_URI'];}elseif(isset($_SERVER['ORIG_PATH_INFO'])){$requestUri=$_SERVER['ORIG_PATH_INFO'];if(isset($_SERVER['QUERY_STRING'])&&$_SERVER['QUERY_STRING']!=''){$requestUri.='?'.$_SERVER['QUERY_STRING'];}}else{$requestUri='';}$tmp=explode('?',$requestUri,2);$this->originalUri=new
Uri($uri);$this->originalUri->path=$tmp[0];$this->originalUri->query=isset($tmp[1])?$tmp[1]:'';$this->originalUri->freeze();$requestUri=String::replace($requestUri,$this->uriFilter[0]);$tmp=explode('?',$requestUri,2);$uri->path=String::replace($tmp[0],$this->uriFilter[PHP_URL_PATH]);$uri->path=String::fixEncoding($uri->path);$uri->query=isset($tmp[1])?$tmp[1]:'';$uri->canonicalize();$filename=isset($_SERVER['SCRIPT_FILENAME'])?basename($_SERVER['SCRIPT_FILENAME']):NULL;$scriptPath='';if(isset($_SERVER['SCRIPT_NAME'])&&basename($_SERVER['SCRIPT_NAME'])===$filename){$scriptPath=rtrim($_SERVER['SCRIPT_NAME'],'/');}elseif(isset($_SERVER['PHP_SELF'])&&basename($_SERVER['PHP_SELF'])===$filename){$scriptPath=$_SERVER['PHP_SELF'];}elseif(isset($_SERVER['ORIG_SCRIPT_NAME'])&&basename($_SERVER['ORIG_SCRIPT_NAME'])===$filename){$scriptPath=$_SERVER['ORIG_SCRIPT_NAME'];}elseif(isset($_SERVER['PHP_SELF'],$_SERVER['SCRIPT_FILENAME'])){$path=$_SERVER['PHP_SELF'];$segs=explode('/',trim($_SERVER['SCRIPT_FILENAME'],'/'));$segs=array_reverse($segs);$index=0;$last=count($segs);do{$seg=$segs[$index];$scriptPath='/'.$seg.$scriptPath;$index++;}while(($last>$index)&&(FALSE!==($pos=strpos($path,$scriptPath)))&&(0!=$pos));}if(strncmp($uri->path,$scriptPath,strlen($scriptPath))===0){$uri->scriptPath=$scriptPath;}elseif(strncmp($uri->path,$scriptPath,strrpos($scriptPath,'/')+1)===0){$uri->scriptPath=substr($scriptPath,0,strrpos($scriptPath,'/')+1);}elseif(strpos($uri->path,basename($scriptPath))===FALSE){$uri->scriptPath='/';}elseif((strlen($uri->path)>=strlen($scriptPath))&&((FALSE!==($pos=strpos($uri->path,$scriptPath)))&&($pos!==0))){$uri->scriptPath=substr($uri->path,0,$pos+strlen($scriptPath));}else{$uri->scriptPath=$scriptPath;}$uri->freeze();}final
function
getQuery($key=NULL,$default=NULL){if($this->query===NULL){$this->initialize();}if(func_num_args()===0){return$this->query;}elseif(isset($this->query[$key])){return$this->query[$key];}else{return$default;}}final
function
getPost($key=NULL,$default=NULL){if($this->post===NULL){$this->initialize();}if(func_num_args()===0){return$this->post;}elseif(isset($this->post[$key])){return$this->post[$key];}else{return$default;}}function
getPostRaw(){return
file_get_contents('php://input');}final
function
getFile($key){if($this->files===NULL){$this->initialize();}$args=func_get_args();return
ArrayTools::get($this->files,$args);}final
function
getFiles(){if($this->files===NULL){$this->initialize();}return$this->files;}final
function
getCookie($key,$default=NULL){if($this->cookies===NULL){$this->initialize();}if(func_num_args()===0){return$this->cookies;}elseif(isset($this->cookies[$key])){return$this->cookies[$key];}else{return$default;}}final
function
getCookies(){if($this->cookies===NULL){$this->initialize();}return$this->cookies;}function
setEncoding($encoding){if(strcasecmp($encoding,$this->encoding)){$this->encoding=$encoding;$this->query=$this->post=$this->cookies=$this->files=NULL;}return$this;}function
initialize(){$filter=(!in_array(ini_get("filter.default"),array("","unsafe_raw"))||ini_get("filter.default_flags"));parse_str($this->getUri()->query,$this->query);if(!$this->query){$this->query=$filter?filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW):(empty($_GET)?array():$_GET);}$this->post=$filter?filter_input_array(INPUT_POST,FILTER_UNSAFE_RAW):(empty($_POST)?array():$_POST);$this->cookies=$filter?filter_input_array(INPUT_COOKIE,FILTER_UNSAFE_RAW):(empty($_COOKIE)?array():$_COOKIE);$gpc=(bool)get_magic_quotes_gpc();$enc=(bool)$this->encoding;$old=error_reporting(error_reporting()^E_NOTICE);$nonChars='#[^\x09\x0A\x0D\x20-\x7E\xA0-\x{10FFFF}]#u';if($gpc||$enc){$utf=strcasecmp($this->encoding,'UTF-8')===0;$list=array(&$this->query,&$this->post,&$this->cookies);while(list($key,$val)=each($list)){foreach($val
as$k=>$v){unset($list[$key][$k]);if($gpc){$k=stripslashes($k);}if($enc&&is_string($k)&&(preg_match($nonChars,$k)||preg_last_error())){}elseif(is_array($v)){$list[$key][$k]=$v;$list[]=&$list[$key][$k];}else{if($gpc&&!$filter){$v=stripSlashes($v);}if($enc){if($utf){$v=String::fixEncoding($v);}else{if(!String::checkEncoding($v)){$v=iconv($this->encoding,'UTF-8//IGNORE',$v);}$v=html_entity_decode($v,ENT_QUOTES,'UTF-8');}$v=preg_replace($nonChars,'',$v);}$list[$key][$k]=$v;}}}unset($list,$key,$val,$k,$v);}$this->files=array();$list=array();if(!empty($_FILES)){foreach($_FILES
as$k=>$v){if($enc&&is_string($k)&&(preg_match($nonChars,$k)||preg_last_error()))continue;$v['@']=&$this->files[$k];$list[]=$v;}}while(list(,$v)=each($list)){if(!isset($v['name'])){continue;}elseif(!is_array($v['name'])){if($gpc){$v['name']=stripSlashes($v['name']);}if($enc){$v['name']=preg_replace($nonChars,'',String::fixEncoding($v['name']));}$v['@']=new
HttpUploadedFile($v);continue;}foreach($v['name']as$k=>$foo){if($enc&&is_string($k)&&(preg_match($nonChars,$k)||preg_last_error()))continue;$list[]=array('name'=>$v['name'][$k],'type'=>$v['type'][$k],'size'=>$v['size'][$k],'tmp_name'=>$v['tmp_name'][$k],'error'=>$v['error'][$k],'@'=>&$v['@'][$k]);}}error_reporting($old);}function
getMethod(){return
isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:NULL;}function
isMethod($method){return
isset($_SERVER['REQUEST_METHOD'])?strcasecmp($_SERVER['REQUEST_METHOD'],$method)===0:FALSE;}function
isPost(){return$this->isMethod('POST');}final
function
getHeader($header,$default=NULL){$header=strtolower($header);$headers=$this->getHeaders();if(isset($headers[$header])){return$headers[$header];}else{return$default;}}function
getHeaders(){if($this->headers===NULL){if(function_exists('apache_request_headers')){$this->headers=array_change_key_case(apache_request_headers(),CASE_LOWER);}else{$this->headers=array();foreach($_SERVER
as$k=>$v){if(strncmp($k,'HTTP_',5)==0){$k=substr($k,5);}elseif(strncmp($k,'CONTENT_',8)){continue;}$this->headers[strtr(strtolower($k),'_','-')]=$v;}}}return$this->headers;}final
function
getReferer(){$uri=self::getHeader('referer');return$uri?new
Uri($uri):NULL;}function
isSecured(){return
isset($_SERVER['HTTPS'])&&strcasecmp($_SERVER['HTTPS'],'off');}function
isAjax(){return$this->getHeader('X-Requested-With')==='XMLHttpRequest';}function
getRemoteAddress(){return
isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:NULL;}function
getRemoteHost(){if(!isset($_SERVER['REMOTE_HOST'])){if(!isset($_SERVER['REMOTE_ADDR'])){return
NULL;}$_SERVER['REMOTE_HOST']=getHostByAddr($_SERVER['REMOTE_ADDR']);}return$_SERVER['REMOTE_HOST'];}function
detectLanguage(array$langs){$header=$this->getHeader('accept-language');if(!$header)return
NULL;$s=strtolower($header);$s=strtr($s,'_','-');rsort($langs);preg_match_all('#('.implode('|',$langs).')(?:-[^\s,;=]+)?\s*(?:;\s*q=([0-9.]+))?#',$s,$matches);if(!$matches[0]){return
NULL;}$max=0;$lang=NULL;foreach($matches[1]as$key=>$value){$q=$matches[2][$key]===''?1.0:(float)$matches[2][$key];if($q>$max){$max=$q;$lang=$value;}}return$lang;}}final
class
HttpResponse
extends
Object
implements
IHttpResponse{private
static$fixIE=TRUE;public$cookieDomain='';public$cookiePath='/';public$cookieSecure=FALSE;private$code=self::S200_OK;function
setCode($code){$code=(int)$code;static$allowed=array(200=>1,201=>1,202=>1,203=>1,204=>1,205=>1,206=>1,300=>1,301=>1,302=>1,303=>1,304=>1,307=>1,400=>1,401=>1,403=>1,404=>1,406=>1,408=>1,410=>1,412=>1,415=>1,416=>1,500=>1,501=>1,503=>1,505=>1);if(!isset($allowed[$code])){throw
new
InvalidArgumentException("Bad HTTP response '$code'.");}elseif(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot set HTTP code after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}else{$this->code=$code;$protocol=isset($_SERVER['SERVER_PROTOCOL'])?$_SERVER['SERVER_PROTOCOL']:'HTTP/1.1';header($protocol.' '.$code,TRUE,$code);}return$this;}function
getCode(){return$this->code;}function
setHeader($name,$value){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot send header after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}if($value===NULL&&function_exists('header_remove')){header_remove($name);}else{header($name.': '.$value,TRUE,$this->code);}return$this;}function
addHeader($name,$value){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot send header after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}header($name.': '.$value,FALSE,$this->code);}function
setContentType($type,$charset=NULL){$this->setHeader('Content-Type',$type.($charset?'; charset='.$charset:''));return$this;}function
redirect($url,$code=self::S302_FOUND){if(isset($_SERVER['SERVER_SOFTWARE'])&&preg_match('#^Microsoft-IIS/[1-5]#',$_SERVER['SERVER_SOFTWARE'])&&$this->getHeader('Set-Cookie')!==NULL){$this->setHeader('Refresh',"0;url=$url");return;}$this->setCode($code);$this->setHeader('Location',$url);echo"<h1>Redirect</h1>\n\n<p><a href=\"".htmlSpecialChars($url)."\">Please click here to continue</a>.</p>";}function
setExpiration($time){if(!$time){$this->setHeader('Cache-Control','s-maxage=0, max-age=0, must-revalidate');$this->setHeader('Expires','Mon, 23 Jan 1978 10:00:00 GMT');return$this;}$time=Tools::createDateTime($time);$this->setHeader('Cache-Control','max-age='.($time->format('U')-time()));$this->setHeader('Expires',self::date($time));return$this;}function
isSent(){return
headers_sent();}function
getHeader($header,$default=NULL){$header.=':';$len=strlen($header);foreach(headers_list()as$item){if(strncasecmp($item,$header,$len)===0){return
ltrim(substr($item,$len));}}return$default;}function
getHeaders(){$headers=array();foreach(headers_list()as$header){$a=strpos($header,':');$headers[substr($header,0,$a)]=(string)substr($header,$a+2);}return$headers;}static
function
date($time=NULL){$time=Tools::createDateTime($time);$time->setTimezone(new
DateTimeZone('GMT'));return$time->format('D, d M Y H:i:s \G\M\T');}function
enableCompression(){if(headers_sent()){return
FALSE;}if($this->getHeader('Content-Encoding')!==NULL){return
FALSE;}$ok=ob_gzhandler('',PHP_OUTPUT_HANDLER_START);if($ok===FALSE){return
FALSE;}if(function_exists('ini_set')){ini_set('zlib.output_compression','Off');ini_set('zlib.output_compression_level','6');}ob_start('ob_gzhandler',1);return
TRUE;}function
__destruct(){if(self::$fixIE){if(!isset($_SERVER['HTTP_USER_AGENT'])||strpos($_SERVER['HTTP_USER_AGENT'],'MSIE ')===FALSE)return;if(!in_array($this->code,array(400,403,404,405,406,408,409,410,500,501,505),TRUE))return;if($this->getHeader('Content-Type','text/html')!=='text/html')return;$s=" \t\r\n";for($i=2e3;$i;$i--)echo$s{rand(0,3)};self::$fixIE=FALSE;}}function
setCookie($name,$value,$time,$path=NULL,$domain=NULL,$secure=NULL){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot set cookie after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}setcookie($name,$value,$time?Tools::createDateTime($time)->format('U'):0,$path===NULL?$this->cookiePath:(string)$path,$domain===NULL?$this->cookieDomain:(string)$domain,$secure===NULL?$this->cookieSecure:(bool)$secure,TRUE);return$this;}function
deleteCookie($name,$path=NULL,$domain=NULL,$secure=NULL){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot delete cookie after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}setcookie($name,FALSE,254400000,$path===NULL?$this->cookiePath:(string)$path,$domain===NULL?$this->cookieDomain:(string)$domain,$secure===NULL?$this->cookieSecure:(bool)$secure,TRUE);}}class
HttpUploadedFile
extends
Object{private$name;private$type;private$size;private$tmpName;private$error;function
__construct($value){foreach(array('name','type','size','tmp_name','error')as$key){if(!isset($value[$key])||!is_scalar($value[$key])){$this->error=UPLOAD_ERR_NO_FILE;return;}}$this->name=$value['name'];$this->size=$value['size'];$this->tmpName=$value['tmp_name'];$this->error=$value['error'];}function
getName(){return$this->name;}function
getContentType(){if($this->isOk()&&$this->type===NULL){$info=getimagesize($this->tmpName);if(isset($info['mime'])){$this->type=$info['mime'];}elseif(extension_loaded('fileinfo')){$this->type=finfo_file(finfo_open(FILEINFO_MIME),$this->tmpName);}elseif(function_exists('mime_content_type')){$this->type=mime_content_type($this->tmpName);}if(!$this->type){$this->type='application/octet-stream';}else{$this->type=preg_replace('#[\s;].*$#','',$this->type);}}return$this->type;}function
getSize(){return$this->size;}function
getTemporaryFile(){return$this->tmpName;}function
__toString(){return$this->tmpName;}function
getError(){return$this->error;}function
isOk(){return$this->error===UPLOAD_ERR_OK;}function
move($dest){$dir=dirname($dest);if(@mkdir($dir,0755,TRUE)){chmod($dir,0755);}$func=is_uploaded_file($this->tmpName)?'move_uploaded_file':'rename';if(!$func($this->tmpName,$dest)){throw
new
InvalidStateException("Unable to move uploaded file '$this->tmpName' to '$dest'.");}chmod($dest,0644);$this->tmpName=$dest;return$this;}function
isImage(){return
in_array($this->getContentType(),array('image/gif','image/png','image/jpeg'),TRUE);}function
toImage(){return
Image::fromFile($this->tmpName);}function
getImage(){trigger_error(__METHOD__.'() is deprecated; use toImage() instead.',E_USER_WARNING);return$this->toImage();}function
getImageSize(){return$this->isOk()?getimagesize($this->tmpName):NULL;}}class
Session
extends
Object{const
DEFAULT_FILE_LIFETIME=10800;private$regenerationNeeded;private
static$started;private$options=array('referer_check'=>'','use_cookies'=>1,'use_only_cookies'=>1,'use_trans_sid'=>0,'cookie_lifetime'=>0,'cookie_path'=>'/','cookie_domain'=>'','cookie_secure'=>FALSE,'cookie_httponly'=>TRUE,'gc_maxlifetime'=>self::DEFAULT_FILE_LIFETIME,'cache_limiter'=>NULL,'cache_expire'=>NULL,'hash_function'=>NULL,'hash_bits_per_character'=>NULL);function
start(){if(self::$started){throw
new
InvalidStateException('Session has already been started.');}elseif(self::$started===NULL&&defined('SID')){throw
new
InvalidStateException('A session had already been started by session.auto-start or session_start().');}try{$this->configure($this->options);}catch(NotSupportedException$e){}Tools::tryError();session_start();if(Tools::catchError($msg)){@session_write_close();throw
new
InvalidStateException($msg);}self::$started=TRUE;if($this->regenerationNeeded){session_regenerate_id(TRUE);$this->regenerationNeeded=FALSE;}unset($_SESSION['__NT'],$_SESSION['__NS'],$_SESSION['__NM']);$nf=&$_SESSION['__NF'];if(empty($nf)){$nf=array('C'=>0);}else{$nf['C']++;}$browserKey=$this->getHttpRequest()->getCookie('nette-browser');if(!$browserKey){$browserKey=(string)lcg_value();}$browserClosed=!isset($nf['B'])||$nf['B']!==$browserKey;$nf['B']=$browserKey;$this->sendCookie();if(isset($nf['META'])){$now=time();foreach($nf['META']as$namespace=>$metadata){if(is_array($metadata)){foreach($metadata
as$variable=>$value){if((!empty($value['B'])&&$browserClosed)||(!empty($value['T'])&&$now>$value['T'])||($variable!==''&&is_object($nf['DATA'][$namespace][$variable])&&(isset($value['V'])?$value['V']:NULL)!==ClassReflection::from($nf['DATA'][$namespace][$variable])->getAnnotation('serializationVersion'))){if($variable===''){unset($nf['META'][$namespace],$nf['DATA'][$namespace]);continue
2;}unset($nf['META'][$namespace][$variable],$nf['DATA'][$namespace][$variable]);}}}}}register_shutdown_function(array($this,'clean'));}function
isStarted(){return(bool)self::$started;}function
close(){if(self::$started){$this->clean();session_write_close();self::$started=FALSE;}}function
destroy(){if(!self::$started){throw
new
InvalidStateException('Session is not started.');}session_destroy();$_SESSION=NULL;self::$started=FALSE;if(!$this->getHttpResponse()->isSent()){$params=session_get_cookie_params();$this->getHttpResponse()->deleteCookie(session_name(),$params['path'],$params['domain'],$params['secure']);}}function
exists(){return
self::$started||$this->getHttpRequest()->getCookie(session_name())!==NULL;}function
regenerateId(){if(self::$started){if(headers_sent($file,$line)){throw
new
InvalidStateException("Cannot regenerate session ID after HTTP headers have been sent".($file?" (output started at $file:$line).":"."));}session_regenerate_id(TRUE);}else{$this->regenerationNeeded=TRUE;}}function
getId(){return
session_id();}function
setName($name){if(!is_string($name)||!preg_match('#[^0-9.][^.]*$#A',$name)){throw
new
InvalidArgumentException('Session name must be a string and cannot contain dot.');}session_name($name);return$this->setOptions(array('name'=>$name));}function
getName(){return
session_name();}function
getNamespace($namespace,$class='SessionNamespace'){if(!is_string($namespace)||$namespace===''){throw
new
InvalidArgumentException('Session namespace must be a non-empty string.');}if(!self::$started){$this->start();}return
new$class($_SESSION['__NF']['DATA'][$namespace],$_SESSION['__NF']['META'][$namespace]);}function
hasNamespace($namespace){if($this->exists()&&!self::$started){$this->start();}return!empty($_SESSION['__NF']['DATA'][$namespace]);}function
getIterator(){if($this->exists()&&!self::$started){$this->start();}if(isset($_SESSION['__NF']['DATA'])){return
new
ArrayIterator(array_keys($_SESSION['__NF']['DATA']));}else{return
new
ArrayIterator;}}function
clean(){if(!self::$started||empty($_SESSION)){return;}$nf=&$_SESSION['__NF'];if(isset($nf['META'])&&is_array($nf['META'])){foreach($nf['META']as$name=>$foo){if(empty($nf['META'][$name])){unset($nf['META'][$name]);}}}if(empty($nf['META'])){unset($nf['META']);}if(empty($nf['DATA'])){unset($nf['DATA']);}if(empty($_SESSION)){}}function
setOptions(array$options){if(self::$started){$this->configure($options);}$this->options=$options+$this->options;return$this;}function
getOptions(){return$this->options;}private
function
configure(array$config){$special=array('cache_expire'=>1,'cache_limiter'=>1,'save_path'=>1,'name'=>1);foreach($config
as$key=>$value){if(!strncmp($key,'session.',8)){$key=substr($key,8);}if($value===NULL){continue;}elseif(isset($special[$key])){if(self::$started){throw
new
InvalidStateException("Unable to set '$key' when session has been started.");}$key="session_$key";$key($value);}elseif(strncmp($key,'cookie_',7)===0){if(!isset($cookie)){$cookie=session_get_cookie_params();}$cookie[substr($key,7)]=$value;}elseif(!function_exists('ini_set')){if(ini_get($key)!=$value){throw
new
NotSupportedException('Required function ini_set() is disabled.');}}else{if(self::$started){throw
new
InvalidStateException("Unable to set '$key' when session has been started.");}ini_set("session.$key",$value);}}if(isset($cookie)){session_set_cookie_params($cookie['lifetime'],$cookie['path'],$cookie['domain'],$cookie['secure'],$cookie['httponly']);if(self::$started){$this->sendCookie();}}}function
setExpiration($time){if(empty($time)){return$this->setOptions(array('gc_maxlifetime'=>self::DEFAULT_FILE_LIFETIME,'cookie_lifetime'=>0));}else{$time=Tools::createDateTime($time)->format('U');return$this->setOptions(array('gc_maxlifetime'=>$time,'cookie_lifetime'=>$time));}}function
setCookieParams($path,$domain=NULL,$secure=NULL){return$this->setOptions(array('cookie_path'=>$path,'cookie_domain'=>$domain,'cookie_secure'=>$secure));}function
getCookieParams(){return
session_get_cookie_params();}function
setSavePath($path){return$this->setOptions(array('save_path'=>$path));}private
function
sendCookie(){$cookie=$this->getCookieParams();$this->getHttpResponse()->setCookie(session_name(),session_id(),$cookie['lifetime'],$cookie['path'],$cookie['domain'],$cookie['secure'],$cookie['httponly']);$this->getHttpResponse()->setCookie('nette-browser',$_SESSION['__NF']['B'],HttpResponse::BROWSER,$cookie['path'],$cookie['domain'],$cookie['secure'],$cookie['httponly']);}protected
function
getHttpRequest(){return
Environment::getHttpRequest();}protected
function
getHttpResponse(){return
Environment::getHttpResponse();}}final
class
SessionNamespace
extends
Object
implements
IteratorAggregate,ArrayAccess{private$data;private$meta;public$warnOnUndefined=FALSE;function
__construct(&$data,&$meta){$this->data=&$data;$this->meta=&$meta;}function
getIterator(){if(isset($this->data)){return
new
ArrayIterator($this->data);}else{return
new
ArrayIterator;}}function
__set($name,$value){$this->data[$name]=$value;if(is_object($value)){$this->meta[$name]['V']=ClassReflection::from($value)->getAnnotation('serializationVersion');}}function&__get($name){if($this->warnOnUndefined&&!array_key_exists($name,$this->data)){trigger_error("The variable '$name' does not exist in session namespace",E_USER_NOTICE);}return$this->data[$name];}function
__isset($name){return
isset($this->data[$name]);}function
__unset($name){unset($this->data[$name],$this->meta[$name]);}function
offsetSet($name,$value){$this->__set($name,$value);}function
offsetGet($name){return$this->__get($name);}function
offsetExists($name){return$this->__isset($name);}function
offsetUnset($name){$this->__unset($name);}function
setExpiration($time,$variables=NULL){if(empty($time)){$time=NULL;$whenBrowserIsClosed=TRUE;}else{$time=Tools::createDateTime($time)->format('U');$whenBrowserIsClosed=FALSE;}if($variables===NULL){$this->meta['']['T']=$time;$this->meta['']['B']=$whenBrowserIsClosed;}elseif(is_array($variables)){foreach($variables
as$variable){$this->meta[$variable]['T']=$time;$this->meta[$variable]['B']=$whenBrowserIsClosed;}}else{$this->meta[$variables]['T']=$time;$this->meta[$variables]['B']=$whenBrowserIsClosed;}return$this;}function
removeExpiration($variables=NULL){if($variables===NULL){unset($this->meta['']['T'],$this->meta['']['B']);}elseif(is_array($variables)){foreach($variables
as$variable){unset($this->meta[$variable]['T'],$this->meta[$variable]['B']);}}else{unset($this->meta[$variables]['T'],$this->meta[$variable]['B']);}}function
remove(){$this->data=NULL;$this->meta=NULL;}}class
Uri
extends
FreezableObject{public
static$defaultPorts=array('http'=>80,'https'=>443,'ftp'=>21,'news'=>119,'nntp'=>119);private$scheme='';private$user='';private$pass='';private$host='';private$port=NULL;private$path='';private$query='';private$fragment='';function
__construct($uri=NULL){if(is_string($uri)){$parts=@parse_url($uri);if($parts===FALSE){throw
new
InvalidArgumentException("Malformed or unsupported URI '$uri'.");}foreach($parts
as$key=>$val){$this->$key=$val;}if(!$this->port&&isset(self::$defaultPorts[$this->scheme])){$this->port=self::$defaultPorts[$this->scheme];}}elseif($uri
instanceof
self){foreach($this
as$key=>$val){$this->$key=$uri->$key;}}}function
setScheme($value){$this->updating();$this->scheme=(string)$value;return$this;}function
getScheme(){return$this->scheme;}function
setUser($value){$this->updating();$this->user=(string)$value;return$this;}function
getUser(){return$this->user;}function
setPassword($value){$this->updating();$this->pass=(string)$value;return$this;}function
getPassword(){return$this->pass;}function
setHost($value){$this->updating();$this->host=(string)$value;return$this;}function
getHost(){return$this->host;}function
setPort($value){$this->updating();$this->port=(int)$value;return$this;}function
getPort(){return$this->port;}function
setPath($value){$this->updating();$this->path=(string)$value;return$this;}function
getPath(){return$this->path;}function
setQuery($value){$this->updating();$this->query=(string)(is_array($value)?http_build_query($value,'','&'):$value);return$this;}function
appendQuery($value){$this->updating();$value=(string)(is_array($value)?http_build_query($value,'','&'):$value);$this->query.=($this->query===''||$value==='')?$value:'&'.$value;}function
getQuery(){return$this->query;}function
setFragment($value){$this->updating();$this->fragment=(string)$value;return$this;}function
getFragment(){return$this->fragment;}function
getAbsoluteUri(){return$this->scheme.'://'.$this->getAuthority().$this->path.($this->query===''?'':'?'.$this->query).($this->fragment===''?'':'#'.$this->fragment);}function
getAuthority(){$authority=$this->host;if($this->port&&isset(self::$defaultPorts[$this->scheme])&&$this->port!==self::$defaultPorts[$this->scheme]){$authority.=':'.$this->port;}if($this->user!==''&&$this->scheme!=='http'&&$this->scheme!=='https'){$authority=$this->user.($this->pass===''?'':':'.$this->pass).'@'.$authority;}return$authority;}function
getHostUri(){return$this->scheme.'://'.$this->getAuthority();}function
isEqual($uri){$part=self::unescape(strtok($uri,'?#'),'%/');if(strncmp($part,'//',2)===0){if($part!=='//'.$this->getAuthority().$this->path)return
FALSE;}elseif(strncmp($part,'/',1)===0){if($part!==$this->path)return
FALSE;}else{if($part!==$this->scheme.'://'.$this->getAuthority().$this->path)return
FALSE;}$part=self::unescape(strtr((string)strtok('?#'),'+',' '),'%&;=+');return$part===$this->query;}function
canonicalize(){$this->updating();$this->path=$this->path===''?'/':self::unescape($this->path,'%/');$this->host=strtolower(rawurldecode($this->host));$this->query=self::unescape(strtr($this->query,'+',' '),'%&;=+');}function
__toString(){return$this->getAbsoluteUri();}static
function
unescape($s,$reserved='%;/?:@&=+$,'){preg_match_all('#(?<=%)[a-f0-9][a-f0-9]#i',$s,$matches,PREG_OFFSET_CAPTURE|PREG_SET_ORDER);foreach(array_reverse($matches)as$match){$ch=chr(hexdec($match[0][0]));if(strpos($reserved,$ch)===FALSE){$s=substr_replace($s,$ch,$match[0][1]-1,3);}}return$s;}}class
UriScript
extends
Uri{private$scriptPath='';function
setScriptPath($value){$this->updating();$this->scriptPath=(string)$value;return$this;}function
getScriptPath(){return$this->scriptPath;}function
getBasePath(){return(string)substr($this->scriptPath,0,strrpos($this->scriptPath,'/')+1);}function
getBaseUri(){return$this->scheme.'://'.$this->getAuthority().$this->getBasePath();}function
getRelativeUri(){return(string)substr($this->path,strrpos($this->scriptPath,'/')+1);}function
getPathInfo(){return(string)substr($this->path,strlen($this->scriptPath));}}class
User
extends
Object
implements
IUser{const
MANUAL=1;const
INACTIVITY=2;const
BROWSER_CLOSED=3;public$guestRole='guest';public$authenticatedRole='authenticated';public$onLoggedIn;public$onLoggedOut;private$authenticationHandler;private$authorizationHandler;private$namespace='';private$session;function
login($username,$password,$extra=NULL){$handler=$this->getAuthenticationHandler();if($handler===NULL){throw
new
InvalidStateException('Authentication handler has not been set.');}$this->logout(TRUE);$credentials=array(IAuthenticator::USERNAME=>$username,IAuthenticator::PASSWORD=>$password,'extra'=>$extra);$this->setIdentity($handler->authenticate($credentials));$this->setAuthenticated(TRUE);$this->onLoggedIn($this);}final
function
logout($clearIdentity=FALSE){if($this->isLoggedIn()){$this->setAuthenticated(FALSE);$this->onLoggedOut($this);}if($clearIdentity){$this->setIdentity(NULL);}}final
function
isLoggedIn(){$session=$this->getSessionNamespace(FALSE);return$session&&$session->authenticated;}final
function
getIdentity(){$session=$this->getSessionNamespace(FALSE);return$session?$session->identity:NULL;}function
getId(){$identity=$this->getIdentity();return$identity?$identity->getId():NULL;}function
setAuthenticationHandler(IAuthenticator$handler){$this->authenticationHandler=$handler;return$this;}final
function
getAuthenticationHandler(){if($this->authenticationHandler===NULL){$this->authenticationHandler=Environment::getService('Nette\\Security\\IAuthenticator');}return$this->authenticationHandler;}function
setNamespace($namespace){if($this->namespace!==$namespace){$this->namespace=(string)$namespace;$this->session=NULL;}return$this;}final
function
getNamespace(){return$this->namespace;}function
setExpiration($time,$whenBrowserIsClosed=TRUE,$clearIdentity=FALSE){$session=$this->getSessionNamespace(TRUE);if($time){$time=Tools::createDateTime($time)->format('U');$session->expireTime=$time;$session->expireDelta=$time-time();}else{unset($session->expireTime,$session->expireDelta);}$session->expireIdentity=(bool)$clearIdentity;$session->expireBrowser=(bool)$whenBrowserIsClosed;$session->browserCheck=TRUE;$session->setExpiration(0,'browserCheck');return$this;}final
function
getLogoutReason(){$session=$this->getSessionNamespace(FALSE);return$session?$session->reason:NULL;}protected
function
getSessionNamespace($need){if($this->session!==NULL){return$this->session;}$sessionHandler=$this->getSession();if(!$need&&!$sessionHandler->exists()){return
NULL;}$this->session=$session=$sessionHandler->getNamespace('Nette.Web.User/'.$this->namespace);if(!($session->identity
instanceof
IIdentity)||!is_bool($session->authenticated)){$session->remove();}if($session->authenticated&&$session->expireBrowser&&!$session->browserCheck){$session->reason=self::BROWSER_CLOSED;$session->authenticated=FALSE;$this->onLoggedOut($this);if($session->expireIdentity){unset($session->identity);}}if($session->authenticated&&$session->expireDelta>0){if($session->expireTime<time()){$session->reason=self::INACTIVITY;$session->authenticated=FALSE;$this->onLoggedOut($this);if($session->expireIdentity){unset($session->identity);}}$session->expireTime=time()+$session->expireDelta;}if(!$session->authenticated){unset($session->expireTime,$session->expireDelta,$session->expireIdentity,$session->expireBrowser,$session->browserCheck,$session->authTime);}return$this->session;}protected
function
setAuthenticated($state){$session=$this->getSessionNamespace(TRUE);$session->authenticated=(bool)$state;$this->getSession()->regenerateId();if($state){$session->reason=NULL;$session->authTime=time();}else{$session->reason=self::MANUAL;$session->authTime=NULL;}return$this;}protected
function
setIdentity(IIdentity$identity=NULL){$this->getSessionNamespace(TRUE)->identity=$identity;return$this;}function
getRoles(){if(!$this->isLoggedIn()){return
array($this->guestRole);}$identity=$this->getIdentity();return$identity?$identity->getRoles():array($this->authenticatedRole);}final
function
isInRole($role){return
in_array($role,$this->getRoles(),TRUE);}function
isAllowed($resource=NULL,$privilege=NULL){$handler=$this->getAuthorizationHandler();if(!$handler){throw
new
InvalidStateException("Authorization handler has not been set.");}foreach($this->getRoles()as$role){if($handler->isAllowed($role,$resource,$privilege))return
TRUE;}return
FALSE;}function
setAuthorizationHandler(IAuthorizator$handler){$this->authorizationHandler=$handler;return$this;}final
function
getAuthorizationHandler(){if($this->authorizationHandler===NULL){$this->authorizationHandler=Environment::getService('Nette\\Security\\IAuthorizator');}return$this->authorizationHandler;}protected
function
getSession(){return
Environment::getSession();}function
authenticate($username,$password,$extra=NULL){trigger_error(__METHOD__.'() is deprecated; use login() instead.',E_USER_WARNING);return$this->login($username,$password,$extra);}function
signOut($clearIdentity=FALSE){trigger_error(__METHOD__.'() is deprecated; use logout() instead.',E_USER_WARNING);return$this->logout($clearIdentity);}function
isAuthenticated(){trigger_error(__METHOD__.'() is deprecated; use isLoggedIn() instead.',E_USER_WARNING);return$this->isLoggedIn();}function
getSignOutReason(){trigger_error(__METHOD__.'() is deprecated; use getLogoutReason() instead.',E_USER_WARNING);return$this->getLogoutReason();}}