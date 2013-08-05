<?php
/**
 * @version     $Revision: $:
 * @author      $Author: $:
 * @date        $Date: $:
 */
namespace ManiaLivePlugins\NadeoLive\XmlRpcScript;

class Event extends \ManiaLive\Event\Event
{
	const ON_BEGIN_MATCH		= 0x1;
	const ON_SHOOT				= 0x2;
	const ON_HIT				= 0x3;
	const ON_NEAR_MISS			= 0x4;
	const ON_ELITE_BEGIN_TURN	= 0x5;
	const ON_ELITE_SHOOT		= 0x6;
	const ON_ELITE_HIT			= 0x7;
	const ON_ELITE_MATCH_START	= 0x8;
	const ON_ELITE_CAPTURE		= 0x9;
	const ON_ELITE_ARMOR_EMPTY	= 0x10;
	const ON_ELITE_NEAR_MISS	= 0x11;
	const ON_ELITE_END_TURN		= 0x12;
	
	protected $content;
	
	function __construct($onWhat, $content)
	{
		parent::__construct($onWhat);
		$this->content = $content;
	}
	
	function fireDo($listener)
	{
		switch($this->onWhat)
		{
			case self::ON_ELITE_BEGIN_TURN:		$listener->onXmlRpcEliteBeginTurn($this->content); break;
			case self::ON_ELITE_SHOOT:			$listener->onXmlRpcEliteShoot($this->content); break;
			case self::ON_ELITE_HIT:			$listener->onXmlRpcEliteHit($this->content); break;
			case self::ON_ELITE_MATCH_START:	$listener->onXmlRpcEliteMatchStart($this->content); break;
			case self::ON_ELITE_CAPTURE:		$listener->onXmlRpcEliteCapture($this->content); break;
			case self::ON_ELITE_ARMOR_EMPTY:	$listener->onXmlRpcEliteArmorEmpty($this->content); break;
			case self::ON_ELITE_NEAR_MISS:		$listener->onXmlRpcEliteNearMiss($this->content); break;
			case self::ON_ELITE_END_TURN:		$listener->onXmlRpcEliteEndTurn($this->content); break;
		}
	}
}
?>