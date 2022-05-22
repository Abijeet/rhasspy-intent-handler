<?php
declare( strict_types = 1 );

return [
    'recorder' => 'arecord',
    'args' => '-f cd -D pcm.rhasspy_capture',
    'timeoutSecs' => 3,
    'fileFormat' => 'wav'
];
