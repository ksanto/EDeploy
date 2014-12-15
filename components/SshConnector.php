<?php
/**
 * Компонент для выполнения bash команд на удаленной машине по ssh.
 *
 * Created by PhpStorm.
 * User: develop
 * Date: 15.12.14
 * Time: 12:36
 */
namespace app\components;

use Yii;
use yii\base;
use yii\base\Exception;

class SshConnector extends \yii\base\Component
{
    /**
     * Store of the ssh session
     * @var null
     */
    private $ssh = null;

    /**
     * Connect to server via ssh.
     *
     * @param $host
     * @param $username
     * @param string $password
     * @param int $port
     * @param int $timeout
     * @return bool
     * @throws \yii\base\Exception
     */
    public function connect($host, $username, $password = '', $port = 22, $timeout = 10)
    {
        $this->ssh = new \Net_SSH2($host, $port, $timeout);

        if ($this->ssh->login($username, $password))
            return true;

        return false;
    }

    /**
     * Run a ssh command for the current connection.
     *
     * @param string|array $commands
     * @throws Exception If the client is not connected to the server
     * @return string
     */
    public function run($commands)
    {
        if (!$this->ssh->isConnected())
            throw new Exception(
                'Yii2SshConnector',
                'Connect is failed!'
            );

        $output = '';
        $this->ssh->exec($commands, false);
        while (true) {
            if (is_null($line = $this->readLine())) break;
            $output .= $line;
        }
        return $output;
    }

    /**
     * Read the next line from the SSH session.
     *
     * @return string|null
     */
    public function readLine()
    {
        $output = $this->ssh->_get_channel_packet(NET_SSH2_CHANNEL_EXEC);
        return $output === true ? null : $output;
    }

}