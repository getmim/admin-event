<?php
/**
 * Filter
 * @package admin-event
 * @version 0.0.1
 */

namespace AdminEvent\Library;

use Event\Model\Event;

class Filter implements \Admin\Iface\ObjectFilter
{
    static function filter(array $cond): ?array{
        $cnd = [];
        if(isset($cond['q']) && $cond['q'])
            $cnd['q'] = (string)$cond['q'];
        $events = Event::get($cnd, 15, 1, ['title'=>true]);
        if(!$events)
            return [];

        $result = [];
        foreach($events as $event){
            $result[] = [
                'id'    => (int)$event->id,
                'label' => $event->title,
                'info'  => $event->title,
                'icon'  => NULL
            ];
        }

        return $result;
    }

    static function lastError(): ?string{
        return null;
    }
}