<?php

/**
 * @file
 * TeamSpeak 3 PHP Framework
 *
 * $Id: UDP.php 9/26/2011 7:06:29 scp@orilla $
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   TeamSpeak3
 * @version   1.1.8-beta
 * @author    Sven 'ScP' Paulsen
 * @copyright Copyright (c) 2010 by Planet TeamSpeak. All rights reserved.
 */

namespace ManiaLivePlugins\Standard\TeamSpeak\TeamSpeak3\Transport;

/**
 * @class TeamSpeak3_Transport_UDP
 * @brief Class for connecting to a remote server through UDP.
 */
class UDP extends AbstractTransport
{
  /**
   * Connects to a remote server.
   *
   * @throws Exception
   * @return void
   */
  public function connect()
  {
    if($this->stream !== null) return;

    $host = strval($this->config["host"]);
    $port = strval($this->config["port"]);

    $address = "udp://" . $host . ":" . $port;
    $timeout = intval($this->config["timeout"]);

    $this->stream = @stream_socket_client($address, $errno, $errstr, $timeout);

    if($this->stream === FALSE)
    {
      throw new Exception(utf8_encode($errstr), $errno);
    }

    @stream_set_timeout($this->stream, $timeout);
    @stream_set_blocking($this->stream, $this->config["blocking"] ? 1 : 0);
  }

  /**
   * Disconnects from a remote server.
   *
   * @return void
   */
  public function disconnect()
  {
    if($this->stream === null) return;

    $this->stream = null;

    \ManiaLivePlugins\Standard\TeamSpeak\TeamSpeak3\Helper\Signal::getInstance()->emit(strtolower($this->getAdapterType()) . "Disconnected");
  }

  /**
   * Reads data from the stream.
   *
   * @param  integer $length
   * @throws Exception
   * @return \ManiaLivePlugins\Standard\TeamSpeak\TeamSpeak3\Helper\String
   */
  public function read($length = 4096)
  {
    $this->connect();
    $this->waitForReadyRead();

    $data = @fread($this->stream, $length);

    \ManiaLivePlugins\Standard\TeamSpeak\TeamSpeak3\Helper\Signal::getInstance()->emit(strtolower($this->getAdapterType()) . "DataRead", $data);

    if($data === FALSE)
    {
      throw new Exception("connection to server '" . $this->config["host"] . ":" . $this->config["port"] . "' lost");
    }

    return new \ManiaLivePlugins\Standard\TeamSpeak\TeamSpeak3\Helper\String($data);
  }

  /**
   * Writes data to the stream.
   *
   * @param  string $data
   * @return void
   */
  public function send($data)
  {
    $this->connect();

    @stream_socket_sendto($this->stream, $data);

    \ManiaLivePlugins\Standard\TeamSpeak\TeamSpeak3\Helper\Signal::getInstance()->emit(strtolower($this->getAdapterType()) . "DataSend", $data);
  }
}
