<?php

namespace Maatwebsite\Sidebar\Presentation\Illuminate;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Presentation\SidebarRenderer;
use Maatwebsite\Sidebar\Sidebar;

class IlluminateSidebarRenderer implements SidebarRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var array|string[] Views for the sidebar
     */
    protected $views = [
        'menu'   => 'sidebar::menu',
        'item'   => 'sidebar::item',
        'badge'  => 'sidebar::badge',
        'group'  => 'sidebar::group',
        'append' => 'sidebar::append',
    ];

    /**
     * @param Factory $factory
     * @param array   $views
     */
    public function __construct(Factory $factory, array $views = [])
    {
        $this->factory = $factory;
        $this->views   = array_merge($this->views, $views);
    }

    /**
     * @param Sidebar $sidebar
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render(Sidebar $sidebar)
    {
        $menu = $sidebar->getMenu();

        if ($menu->isAuthorized()) {
            $groups = [];
            foreach ($menu->getGroups() as $group) {
                $groups[] = (new IlluminateGroupRenderer($this->factory))->render(
                    $group,
                    $this->views
                );
            }

            return $this->factory->make($this->views['menu'], [
                'groups' => $groups
            ]);
        }

        return '';
    }
}
