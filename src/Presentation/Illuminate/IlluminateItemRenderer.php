<?php

namespace Maatwebsite\Sidebar\Presentation\Illuminate;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Presentation\ActiveStateChecker;

class IlluminateItemRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'sidebar::item';

    /**
     * @var bool
     */
    protected $child = false;

    /**
     * @param Factory $factory
     * @param bool    $child
     */
    public function __construct(Factory $factory, $child = false)
    {
        $this->factory = $factory;
        $this->child = $child;
    }

    /**
     * @param Item $item
     *
     * @return string
     */
    public function render(Item $item)
    {
        if ($item->isAuthorized()) {
            $items = [];
            foreach ($item->getItems() as $child) {
                $items[] = (new IlluminateItemRenderer($this->factory, true))->render($child);
            }

            $badges = [];
            foreach ($item->getBadges() as $badge) {
                $badges[] = (new IlluminateBadgeRenderer($this->factory))->render($badge);
            }

            $appends = [];
            foreach ($item->getAppends() as $append) {
                $appends[] = (new IlluminateAppendRenderer($this->factory))->render($append);
            }

            return $this->factory->make($this->view, [
                'item'    => $item,
                'items'   => $items,
                'badges'  => $badges,
                'appends' => $appends,
                'child' => $this->child,
                'active'  => (new ActiveStateChecker())->isActive($item),
            ])->render();
        }
    }
}
