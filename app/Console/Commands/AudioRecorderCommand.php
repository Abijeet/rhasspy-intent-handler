<?php
declare( strict_types = 1 );

namespace App\Console\Commands;

use App\SearchQuery\Builders\QueryBuilder;
use Illuminate\Console\Command;

class AudioRecorderCommand extends Command
{
    protected $signature = 'record-audio';
    protected $description = 'Audio recording tester';

    public function __construct() {
        parent::__construct();
    }

    public function handle(QueryBuilder $queryBuilder)
    {
        $this->info( $queryBuilder->get() );
    }
}
