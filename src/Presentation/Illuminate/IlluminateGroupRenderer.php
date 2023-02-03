<?php

namespace Maatwebsite\Sidebar\Presentation\Illuminate;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Group;

class IlluminateGroupRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var string
     */
    protected $view = 'sidebar::group';

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Group $group
     * @param array $views
     *
     * @return string
     */
    public function render(Group $group, array $views)
    {
        if ($group->isAuthorized()) {
            $items = [];
            foreach ($group->getItems() as $item) {
                $items[] = (new IlluminateItemRenderer($this->factory))->render($item, $views);
            }

            return $this->factory->make($views['group'], [
                'group' => $group,
                'items' => $items
            ])->render();
        }

        return '';
    }
}
