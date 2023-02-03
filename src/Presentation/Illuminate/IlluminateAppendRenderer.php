<?php

namespace Maatwebsite\Sidebar\Presentation\Illuminate;

use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Append;

class IlluminateAppendRenderer
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
     * @param Append $append
     * @param array  $views
     *
     * @return string
     */
    public function render(Append $append, array $views)
    {
        if ($append->isAuthorized()) {
            return $this->factory->make($views['append'], [
                'append' => $append
            ])->render();
        }

        return '';
    }
}
