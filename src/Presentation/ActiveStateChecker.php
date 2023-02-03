<?php

namespace Maatwebsite\Sidebar\Presentation;

use Illuminate\Support\Facades\Request;
use Maatwebsite\Sidebar\Item;

class ActiveStateChecker
{
    /**
     * @param Item $item
     *
     * @return bool
     */
    public function isActive(Item $item)
    {
        // Check if one of the children is active
        foreach ($item->getItems() as $child) {
            if ($this->isActive($child)) {
                return true;
            }
        }

        $activeWhen = $item->getActiveWhen();

        if (is_string($activeWhen)) {
            return Request::is(
                $activeWhen
            );
        } elseif (is_callable($activeWhen)) {
            return $activeWhen();
        }

        if($route = $item->getRoute()) {
            return request()->routeIs($route);
        }

        $path = ltrim(str_replace(url('/'), '', $item->getUrl()), '/');

        return Request::is(
            $path,
            $path . '/*'
        );
    }
}
