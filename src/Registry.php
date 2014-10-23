<?php
/**
 * slince view library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\View;

class Registry
{

    /**
     * 视图实例
     *
     * @var array
     */
    static $_views = [];

    /**
     * 添加一个管理的视图对象、
     *
     * @param string $name            
     * @param View $view            
     */
    static function addView($name, View $view)
    {
        self::$_views[$name] = $view;
    }

    /**
     * 移除一个视图对象
     *
     * @param string $name            
     */
    static function removeView($name)
    {
        unset(self::$_views[$name]);
    }

    /**
     * 是否存在某一个视图对象
     *
     * @param string $name            
     */
    static function hasView($name)
    {
        return isset(self::$_views[$name]);
    }

    /**
     * 清除视图对象
     */
    static function clear()
    {
        self::$_views = [];
    }

    /**
     * 获取托管的对象
     *
     * @param string $name            
     * @return null|View
     */
    static function getView($name)
    {
        return self::hasView($name) ? self::$_views[$name] : null;
    }
}