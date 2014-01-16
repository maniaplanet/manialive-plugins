manialive-plugins
=================

##AutoTweet

This plugin automatically post server events on your twitter acocunt.

Example: https://twitter.com/SMLiveTweet

###Installation

* Copy `NadeoLive` folder to your `ManiaLive\libraries\ManiaLivePlugins` directoy
* Add this configuration to your `manialive.ini` :

```
manialive.plugins[] = 'NadeoLive\AutoTweet'
manialive.plugins[] = 'NadeoLive\XmlRpcScript'

ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterOauthAccessToken = ''
ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterOauthAccessTokenSecret = ''
ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterConsumerKey = ''
ManiaLivePlugins\NadeoLive\AutoTweet\Config.twitterConsumerSecret = ''
```

To obtain twitter oauth tokens and keys, create an application on [twitter dev website](https://dev.twitter.com/apps). 


## XmlRpcScript

It's a plumbing script which allows you to receive XmlRpc events from script as ManiaLive events. 

### Usage

In the function `onLoad` of your plugin :

```
\ManiaLive\Event\Dispatcher::register(\ManiaLivePlugins\NadeoLive\XmlRpcScript\Event::getClass(), $this);
```

Then, the functions will be called from your plugin :

```
function onXmlRpcEliteArmorEmpty($content);
function onXmlRpcEliteShoot($content);
function onXmlRpcEliteHit($content);
```