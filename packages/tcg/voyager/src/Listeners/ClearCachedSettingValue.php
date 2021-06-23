<?php

namespace TCG\Voyager\Listeners;

use Cache;
use TCG\Voyager\Events\SettingUpdated;

class ClearCachedSettingValue
{
    /**
     * Creator the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * handle.
     *
     * @param SettingUpdated $event
     *
     * @return void
     */
    public function handle(SettingUpdated $event)
    {
        if (config('voyager.settings.cache', false) === true) {
            Cache::tags('settings')->forget($event->setting->key);
        }
    }
}
