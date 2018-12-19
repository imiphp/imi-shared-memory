<?php
namespace Imi\SharedMemory\Process;

use Imi\Process\BaseProcess;
use Imi\Process\Annotation\Process;
use Yurun\Swoole\SharedMemory\Server;
use Imi\Config;
use Imi\Util\Imi;

/**
 * @Process(name="sharedMemory", unique=true)
 */
class SharedMemoryProcess extends BaseProcess
{

    public function run(\Swoole\Process $process)
    {
        $socketFile = Config::get('@app.swooleSharedMemory.socketFile');
        if(null === $socketFile)
        {
            $socketFile = Imi::getRuntimePath('imi-shared-memory.sock');
        }
        $storeTypes = Config::get('@app.swooleSharedMemory.storeTypes', [
            \Yurun\Swoole\SharedMemory\Store\KV::class,
            \Yurun\Swoole\SharedMemory\Store\Stack::class,
            \Yurun\Swoole\SharedMemory\Store\Queue::class,
            \Yurun\Swoole\SharedMemory\Store\PriorityQueue::class,
        ]);
        $server = new Server([
            'socketFile'    =>  $socketFile,
            'storeTypes'    =>  $storeTypes,
        ]);
        $server->run();
        fwrite(STDOUT, 'Process [sharedMemory] start' . PHP_EOL);
    }

}