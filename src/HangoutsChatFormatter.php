<?php

namespace Monolog\Formatter;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;

class HangoutsChatFormatter implements FormatterInterface
{
    private $lineFormatter;

    public function __construct()
    {
        $this->lineFormatter = new LineFormatter;
    }

    public function format(array $record)
    {
        $message = '';
        if ($record['message']) {
            $message = strlen($record['message']) > 4000 ? substr($record['message'], 0, 4000)."..." : $record['message'];
        }
        if ($record['extra'] && $record['extra'] !== []) {
            $message .= "\n```".$record['extrta'];
            $message = strlen($message) > 4000 ? substr($message, 0, 4000)."...```" : $message."```";
        }

        if ($record['context'] && $record['context'] !== []) {
            $context = $this->lineFormatter->stringify($record['context']);
            $message .= "\n```".$context;
            $message = strlen($message) > 4000 ? substr($message, 0, 4000)."...```" : $message."```";
        }

        $message = json_encode(['text' => "*".$record['level_name']."*\n".$message]);

        return $message;
    }

    public function formatBatch(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }
        return strlen($message) > 4000 ? substr($message, 0, 4000) : $message;
    }
}
