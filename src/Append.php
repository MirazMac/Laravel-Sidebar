<?php

namespace Maatwebsite\Sidebar;

interface Append extends Authorizable, Routeable
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     *
     * @return Append
     */
    public function id($id);

    /**
     * @return null|string
     */
    public function getName();

    /**
     * @param null|string $name
     *
     * @return Append
     */
    public function name($name);

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @param string|object $icon
     *
     * @return Append
     */
    public function icon($icon);

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @param string $url
     *
     * @return Item
     */
    public function url($url);

    /**
     * @param       $route
     * @param array $params
     *
     * @return Item
     */
    public function route($route, $params = []);

    /**
     * @return string
     */
    public function getRoute();


}
