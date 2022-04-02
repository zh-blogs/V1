<?php

namespace support\exception;

use Throwable;
use Psr\Log\LoggerInterface;

/**
 * Class Handler
 * @package support\exception
 */
class Handler implements \Webman\Exception\ExceptionHandlerInterface
{
    /**
     * @var LoggerInterface
     */
    protected $_logger = null;

    /**
     * @var bool
     */
    protected $_debug = false;

    /**
     * @var array
     */
    public $dontReport = [];

    /**
     * ExceptionHandler constructor.
     * @param $logger
     * @param $debug
     */
    public function __construct($logger, $debug)
    {
        $this->_logger = $logger;
        $this->_debug = $debug;
    }

    /**
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }

        $this->_logger->error($exception->getMessage(), ['exception' => (string)$exception]);
    }

    /**
     * @param Request $request
     * @param Throwable $exception
     * @return Response
     */
    public function render(\webman\Http\Request $request, Throwable $exception): \Webman\Http\Response
    {
        $code = $exception->getCode();
        $json = ['code' => $code ? $code : 500, 'msg' => "Server internal error", 'data' => []];
        if ($this->_debug) $json['err'] =  nl2br((string)$exception);
        print_r(Date("Y-m-d H:i:s") . ' ERROR > ' . PHP_EOL . $exception . PHP_EOL);
        return json($json);
    }

    /**
     * @param Throwable $e
     * @return bool
     */
    protected function shouldntReport(Throwable $e)
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }
        return false;
    }
}
