<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

class EventStore
{

    const KERNEL_INITED = 'kernel.inited';

    const APP_INIT = 'app.init';

    const PROCESS_REQUEST = 'process_request';
    
    const DISPATCH_ROUTE = 'dispatch_route';

    const APP_RUN = 'app.run';
}