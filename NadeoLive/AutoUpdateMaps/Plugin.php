<?php
/**
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */
namespace ManiaLivePlugins\NadeoLive\AutoUpdateMaps;

class Plugin extends \ManiaLive\PluginHandler\Plugin 
{
	/**
	 * @var \DateTime 
	 */
	protected $lastCheck;
	
	/**
	 * @var string 
	 */
	protected $baseMapsDirectory;
	
	/**
	 * @var string 
	 */
	protected $currentMapsDirectory;
	
	function onLoad()
	{
		$this->baseMapsDirectory = $this->connection->getMapsDirectory();
		$this->enableTickerEvent();
		$this->check();
	}
	
	function onTick()
	{
		$now = new \DateTime();
		if ($now->sub(new \DateInterval('PT2H')) > $this->lastCheck)
		{
			$this->check();
		}
	}
	
	protected function check()
	{
		$this->lastCheck = new \DateTime();
		if ($this->updateCurrentMapsDirectory())
		{
			$this->updateMaps();
		}
	}
	
	protected function updateCurrentMapsDirectory()
	{
		if ($latest = $this->getLatestMapDirectory() != $this->currentMapsDirectory)
		{
			$this->currentMapsDirectory = $latest;
			return true;
		}
		return false;
	}
	
	protected function updateMaps()
	{
		$config = Config::getInstance();
		//Clear old maps
		$currentMaps = $this->connection->getMapList(-1, 0);
		foreach($currentMaps as $maps)
		{
			$this->connection->removeMap($maps->fileName, true);
		}
		$this->connection->executeMulticall();
		
		//Add new maps
		$newMapsDirectory = $this->getLatestMapDirectory();
		$newMaps = scandir($newMapsDirectory);
		foreach ($newMaps as $map)
		{
			if (!in_array($map,array(".","..")))
			{
				$this->connection->addMap($newMapsDirectory.DIRECTORY_SEPARATOR.$map, true);
			}
		}
		$this->connection->executeMulticall();
		\ManiaLive\Utilities\Logger::debug(sprintf('Maps updated: %s', implode(', ', $newMaps)));
	}
	
	/**
	 * @return string Full directory
	 */
	protected function getLatestMapDirectory()
	{
		$directory = $this->getBaseMapsDirectory();
		$folders = scandir($directory, 1);
		return $directory.DIRECTORY_SEPARATOR.$folders[0];
	}
	
	protected function getBaseMapsDirectory()
	{
		$config = Config::getInstance();
		return $this->baseMapsDirectory.$config->relativeAutoMapsDirectory;
	}
}
?>