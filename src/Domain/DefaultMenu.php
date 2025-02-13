<?php

namespace Maatwebsite\Sidebar\Domain;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Collection;
use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Traits\AuthorizableTrait;
use Maatwebsite\Sidebar\Traits\CacheableTrait;
use Maatwebsite\Sidebar\Traits\CallableTrait;
use Serializable;

class DefaultMenu implements Menu, Serializable
{
    use AuthorizableTrait;
    use CacheableTrait;
    use CallableTrait;

    /**
     * @var Collection|Group[]
     */
    protected $groups;

    /**
     * @var Container
     */
    protected $container;

    /**
     * Data that should be cached
     * @var array
     */
    protected $cacheables = [
        'groups'
    ];

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->groups = new Collection();
    }

    /**
     * Init a new group or call an existing group and add it to the menu
     *
     * @param $id
     * @param Closure|null $callback
     *
     * @return Group
     * @throws BindingResolutionException
     */
    public function group($id, Closure $callback = null)
    {
        if ($this->groups->has($id)) {
            $group = $this->groups->get($id);
        } else {
            $group = $this->container->make('Maatwebsite\Sidebar\Group');
            $group->id($id);
        }

        $this->call($callback, $group);

        $this->addGroup($group);

        return $group;
    }

    /**
     * Add a Group instance to the Menu
     *
     * @param Group $group
     *
     * @return $this
     */
    public function addGroup(Group $group)
    {
        $this->groups->put($group->getId(), $group);

        return $this;
    }

    /**
     * Add another Menu instance and combined the two
     * Groups with the same name get combined, but
     * inherit each other's items
     *
     * @param Menu $menu
     *
     * @return Menu $menu
     */
    public function add(Menu $menu)
    {
        foreach ($menu->getGroups() as $group) {
            if ($this->groups->has($group->getId())) {
                $existingGroup = $this->groups->get($group->getId());

                $group->hideHeading(!$group->shouldShowHeading());

                foreach ($group->getItems() as $item) {
                    $existingGroup->addItem($item);
                }
            } else {
                $this->addGroup($group);
            }
        }

        return $this;
    }

    /**
     * Get collection of Group instances sorted by their weight
     * @return Collection|Group[]
     */
    public function getGroups()
    {
        return $this->groups->sortBy(function (Group $group) {
            return $group->getWeight();
        });
    }
}
