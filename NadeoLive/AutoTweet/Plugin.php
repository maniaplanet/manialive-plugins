<?php
/**
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */
namespace ManiaLivePlugins\NadeoLive\AutoTweet;

use ManiaLive\DedicatedApi\Callback\Event as ServerEvent;

class Plugin extends \ManiaLive\PluginHandler\Plugin
{
	/**
	 * @var Config 
	 */
	protected $config;
	
	protected $twitter;
	
	public function onInit()
	{
		$this->config = Config::getInstance();
		
		$this->setVersion('0.0.1');
		
		$this->addDependency(new \ManiaLive\PluginHandler\Dependency('NadeoLive\XmlRpcScript'));
		
		$this->twitter = new TwitterAPIExchange(array(
			'oauth_access_token' => $this->config->twitterOauthAccessToken,
			'oauth_access_token_secret' => $this->config->twitterOauthAccessTokenSecret,
			'consumer_key' => $this->config->twitterConsumerKey,
			'consumer_secret' => $this->config->twitterConsumerSecret
		));
	}
	
	protected function postTwitterMessage($message)
	{
		 $this->twitter->buildOauth('https://api.twitter.com/1.1/statuses/update.json', 'POST')
             ->setPostfields(array('status' => $message))
             ->performRequest();
	}

	public function onLoad()
	{
		$this->enableDedicatedEvents();
		
		\ManiaLive\Event\Dispatcher::register(\ManiaLivePlugins\NadeoLive\XmlRpcScript\Event::getClass(), $this);
		
		$this->connection->setModeScriptSettings(array('S_UseScriptCallbacks' => true));
		
		$this->postTwitterMessage(sprintf('Now following %s', $this->storage->server->name));
	}
	

	public function onTick()
	{
	}

	function onUnload()
	{
	}
	
	function onBeginMap($map, $warmUp, $matchContinuation)
	{
		$map = \DedicatedApi\Structures\Map::fromArray($map);
		$this->postTwitterMessage(sprintf("Map change for %s", $this->formatForTwitter($map->name)));
	}
	
	function onPlayerConnect($login, $isSpectator)
	{
	}
	
	function onPlayerDisconnect($login, $disconnectionReason)
	{
	}
	
	function onPlayerChangeSide($player, $oldSide)
	{
		
	}
	
	//Xml RPC events
	function onXmlRpcEliteBeginTurn($content)
	{
		$this->postTwitterMessage(sprintf("Turn #%d: %s attack", $content->TurnNumber, $this->formatForTwitter($content->AttackingPlayer->Name)));
	}
	
	function onXmlRpcEliteEndTurn($content)
	{
		$this->postTwitterMessage(sprintf("Turn #%d end", $content->TurnNumber));
	}
	
	function onXmlRpcEliteArmorEmpty($content)
	{
		$message = sprintf("%s has eliminated %s",
			$this->formatForTwitter($content->Event->Shooter->Name),
			$this->formatForTwitter($content->Event->Victim->Name));
		$this->postTwitterMessage($message);
	}
	
	function onXmlRpcEliteShoot($content)
	{
	}
	
	function onXmlRpcEliteHit($content)
	{
		if ($content->Event->Victim->IsTouchingGround == false)
		{
			$message = "AIRSHOT: ";
		}
		else
		{
			$message = "";
		}
		$message .= sprintf("%s has hit %s with %s", 
				$this->formatForTwitter($content->Event->Shooter->Name),
				$this->formatForTwitter($content->Event->Victim->Name),
				$this->getWeaponName($content->Event->WeaponNum));
		
		if ($content->Event->HitDist > 50)
		{
			$message .= sprintf(" from %sm", $content->Event->HitDist);
		}
		
		$this->postTwitterMessage($message);
	}
	
	function onXmlRpcEliteMatchStart($content)
	{
		$this->postTwitterMessage("Match Started!");
		$this->postTwitterMessage(sprintf("Current map: %s", $this->formatForTwitter($this->storage->currentMap->name)));
	}
	
	function onXmlRpcEliteCapture($content)
	{
		$message = sprintf("%s has secured the pole",
				$this->formatForTwitter($content->Event->Player->Name));
		
		$this->postTwitterMessage($message);
	}
	
	function onXmlRpcEliteNearMiss($content)
	{
		$message = sprintf("%s missed %s by %01.2f cm",
				$this->formatForTwitter($content->Event->Shooter->Name),
				$this->formatForTwitter($content->Event->Victim->Name),
				$this->formatForTwitter($content->Event->MissDist));
		
		$this->postTwitterMessage($message);
	}
	
	protected function getWeaponName($num)
	{
		switch ($num)
		{
			case 1:
				return 'laser';
			case 2:
				return 'rocket';
			case 3:
				return 'nucleus';
			case 5:
				return 'arrow';
			default:
				return '';
		}
	}
	
	protected function formatForTwitter($text)
	{
		return \ManiaLib\Utils\Formatting::stripStyles($text);
	}
}