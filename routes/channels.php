<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('updates', fn () => true);
