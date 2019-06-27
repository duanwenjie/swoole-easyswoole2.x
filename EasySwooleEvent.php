<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Component\SysConst;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use App\ExceptionHandler;

Class EasySwooleEvent implements EventInterface {

    private $whoopsInstance;
    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');

        /* == 异常捕获 == */
        Di::getInstance()->set(SysConst::HTTP_EXCEPTION_HANDLER,ExceptionHandler::class);

    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
        $request->withAttribute('requestTime', microtime(true));
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.

        //从请求里获取之前增加的时间戳
        $reqTime = $request->getAttribute('requestTime');
        //计算一下运行时间
        $runTime = round(microtime(true) - $reqTime, 3);
        //获取用户IP地址
        $ip = ServerManager::getInstance()->getServer()->connection_info($request->getSwooleRequest()->fd);

        //拼接一个简单的日志
        $logStr = ' | '.$ip['remote_ip'] .' | '. $runTime . '|' . $request->getUri() .' | '.
            $request->getHeader('user-agent')[0];
        //判断一下当执行时间大于1秒记录到 slowlog 文件中，否则记录到 access 文件
        if($runTime > 1){
            Logger::getInstance()->log($logStr, 'slowlog');
        }else{
            logger::getInstance()->log($logStr,'access');
        }
    }
}