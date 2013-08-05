<?php
/**
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */
namespace ManiaLivePlugins\NadeoLive\XmlRpcScript;

use ManiaLive\DedicatedApi\Callback\Event as ServerEvent;

class Plugin extends \ManiaLive\PluginHandler\Plugin
{
	public function onInit()
	{
	}

	public function onLoad()
	{
		$this->enableDedicatedEvents(ServerEvent::ON_MODE_SCRIPT_CALLBACK);
	}
	
	public function onModeScriptCallback($param1, $param2)
	{
		$this->param = $param2;
		switch ($param1)
		{
			case 'LibXmlRpc_BeginMatch':
				$this->dispatch(Event::ON_BEGIN_MATCH, $param2);
				break;
			case 'LibXmlRpc_OnShoot':
				$this->dispatch(Event::ON_SHOOT, $param2);
				break;
			case 'LibXmlRpc_OnHit':
				$this->dispatch(Event::ON_HIT, $param2);
				break;
			case 'LibXmlRpc_OnNearMiss':
				$this->dispatch(Event::ON_NEAR_MISS, $param2);
				break;
			case 'LibXmlRpc_OnCapture':
				$this->dispatch(Event::ON_CAPTURE, $param2);
				break;
			
			case 'BeginMatch':
				$this->dispatch(Event::ON_ELITE_MATCH_START, json_decode($param2));
				break;
			case 'BeginTurn':
				$this->dispatch(Event::ON_ELITE_BEGIN_TURN, json_decode($param2));
				break;
			case 'OnShoot':
				$this->dispatch(Event::ON_ELITE_SHOOT, json_decode($param2));
				break;
			case 'OnHit':
				$this->dispatch(Event::ON_ELITE_HIT, json_decode($param2));
				break;
			case 'OnCapture':
				$this->dispatch(Event::ON_ELITE_CAPTURE, json_decode($param2));
				break;	
			case 'OnArmorEmpty':
				$this->dispatch(Event::ON_ELITE_ARMOR_EMPTY, json_decode($param2));
				break;
			case 'OnNearMiss':
				$this->dispatch(Event::ON_ELITE_NEAR_MISS, json_decode($param2));
				break;
			case 'EndTurn':
				$this->dispatch(Event::ON_ELITE_END_TURN, json_decode($param2));
		}
	}
	
	protected function dispatch($event, $param)
	{
		\ManiaLive\Event\Dispatcher::dispatch(new Event($event, $param));
	}
}