<?php

	namespace nostrict\autoloader;
	
	class AutoLoader{
		
		protected $_ns=[];//namespaces array
		
		protected $_dir;//autoloader catalog
		
		protected $_vendor;//vendor catalog
		
		protected $_class;//second index in namespace
		
		protected $_get;//first index in namespace
		
		public function __construct(){
			
			/*preparing variables*/
			$this->_dir=dirname(__DIR__);
			$this->_vendor=dirname(dirname($this->_dir)).DIRECTORY_SEPARATOR;
			
			$this->register();//start method with spl_autoloader
			
		}
		
		public function loadns($ns){
			
			if(isset($this->_ns[$ns]))return false;//checks if the namespace has been included
			
			$this->_ns[$ns]=json_decode(utf8_encode(file_get_contents($this->_dir.DIRECTORY_SEPARATOR.'namespaces'.DIRECTORY_SEPARATOR.$ns.'.json')),true);//get array from .json file
			
		}
		
		public function register(){
			
			spl_autoload_register(array($this, 'autoload'));//load spl
		}
		
		public function autoload($class){
			
			$this->_class=explode('\\',$class);//break $class into pieces
			
			/*include file width class*/
			$this->_get=$this->_class[0];
			unset($this->_class[0]);
			$this->_class=implode('\\',$this->_class);
			require_once rtrim(str_replace(['\\','/'],DIRECTORY_SEPARATOR,$this->_vendor.$this->_get.DIRECTORY_SEPARATOR.$this->_class.DIRECTORY_SEPARATOR.$this->_ns[$this->_get][$this->_class]),'\\').'.php';
		}
	}
	
	$autoloader=new AutoLoader;
	$autoloader->loadns('core');
	//new \core\router\foo();
	new \core\router\bar();
	
	