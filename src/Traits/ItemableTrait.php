<?php

namespace Maatwebsite\Sidebar\Traits;

use Closure;
use Illuminate\Support\Collection;
use Maatwebsite\Sidebar\Item;

trait ItemableTrait
{
    /**
     * @var Collection|Item[]
     */
    protected $items;

    /**
     * Add a new Item (or edit an existing item) to the Group
     *
     * @param string        $id
     * @param \Closure|null $callback
     *
     * @return Item
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function item($id, Closure $callback = null)
    {
        if ($this->items->has($id)) {
            $item = $this->items->get($id);
        } else {
            $item = $this->container->make('Maatwebsite\Sidebar\Item');
            $item->id($id);
        }

        $this->call($callback, $item);

        $this->addItem($item);

        return $item;
    }

    /**
     * Add Item instance to Group
     *
     * @param Item $item
     *
     * @return Item
     */
    public function addItem(Item $item)
    {
        $this->items->put($item->getId(), $item);

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems()
    {
        return $this->items->sortBy(function (Item $item) {
            return $item->getWeight();
        });
    }

    /**
     * Check if we have items
     * @return bool
     */
    public function hasItems()
    {
        return count($this->items) > 0;
    }
}
