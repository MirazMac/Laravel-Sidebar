<?php

namespace Maatwebsite\Sidebar\Presentation\Illuminate;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Badge;

class IlluminateBadgeRenderer
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Badge $badge
     * @param array $views
     *
     * @return string
     */
    public function render(Badge $badge, array $views)
    {
        if ($badge->isAuthorized()) {
            return $this->factory->make($views['badge'], [
                'badge' => $badge
            ])->render();
        }

        return '';
    }
}
