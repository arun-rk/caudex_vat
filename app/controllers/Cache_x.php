<?php 
class Cache_x extends MY_Controller {

	public function index()
	{
		echo $this->load->driver('cache',  array('adapter' => 'apc', 'backup' => 'file', 'key_prefix' => 'my_'));
		echo $this->cache->apc->is_supported();
		echo 'loaded driver<br>';
		// echo $this->cache->file->save('first', 'asdasdsa', 120);
		echo $this->cache->save('first', array('asdasd'=>123), 120);
		echo 'saved cache<br>';
		// echo $this->cache->file->get('first');
		// var_dump($this->cache->get('first'));
		var_dump($this->cache->get_metadata('first'));
		echo 'get cache';
		var_dump($this->cache->cache_info());

	}

}