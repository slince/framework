<?php
/**
 * slince application library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Application;

class EventStore
{

    const KERNEL_INITED = 'Kernel.inited';

    const APP_INITED = 'App.inited';

    const PROCESS_REQUEST = 'processRequest';
    
    const ERROR_OCCURRED = 'errorOccurred';
    
    const EXCEPTION_OCCURRED = 'exceptionOccurred';
}