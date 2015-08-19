<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Applicaion;

class EventStore
{

    const KERNEL_INITED = 'kernel.inited';

    const APP_INIT = 'app.init';

    const PROCESS_REQUEST = 'process.request';
    
    const APP_DISPATCH_ROUTE = 'app.dispatch_route';

    const APP_RUN = 'app.run';
}