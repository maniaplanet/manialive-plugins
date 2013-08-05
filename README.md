manialive-plugins
=================

##AutoTweet

This plugin automatically post server events on your twitter acocunt.

Example: https://twitter.com/SMLiveTweet

###Installation

* Copy `NadeoLive` folder to your `ManiaLive\libraries\ManiaLivePlugins` directoy
* Add this configuration to your `manialive.ini` :

	manialive.plugins[] = 'NadeoLive\AutoTweet'
	manialive.plugins[] = 'NadeoLive\XmlRpcScript'

	ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterOauthAccessToken = ''
	ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterOauthAccessTokenSecret = ''
	ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterConsumerKey = ''
	ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterConsumerSecret = ''
	
To obtain twitter oauth tokens and keys, create an application on (twitter dev website)[https://dev.twitter.com/apps]. 