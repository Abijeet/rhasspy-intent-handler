<?php
declare(strict_types=1);

return [
	'recorder' => 'arecord',
	'args' => '-f S16_LE -D pcm.rhasspy_capture',
	'timeoutSecs' => 3,
	'fileFormat' => 'wav'
];
