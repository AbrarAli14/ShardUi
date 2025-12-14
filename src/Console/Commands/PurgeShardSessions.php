<?php

declare(strict_types=1);

namespace Shard\Ui\Console\Commands;

use Illuminate\Console\Command;
use Shard\Ui\Services\ShardManager;

final class PurgeShardSessions extends Command
{
    protected $signature = 'shard:purge-sessions';
    protected $description = 'Remove expired Shard UI sessions from cache';

    public function __construct(private readonly ShardManager $manager)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $purged = $this->manager->purgeExpiredSessions();

        $this->info(sprintf('Purged %d session(s).', count($purged)));

        if (count($purged) > 0) {
            $this->line(implode(PHP_EOL, $purged));
        }

        return self::SUCCESS;
    }
}
