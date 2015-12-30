<?php

namespace AbuseIO\Parsers;

/**
 * Class Usgo
 * @package AbuseIO\Parsers
 */
class Usgo extends Parser
{
    /**
     * Create a new Usgo instance
     *
     * @param \PhpMimeMailParser\Parser $parsedMail phpMimeParser object
     * @param array $arfMail array with ARF detected results
     */
    public function __construct($parsedMail, $arfMail)
    {
        parent::__construct($parsedMail, $arfMail, $this);
    }

    /**
     * Parse attachments
     * @return array    Returns array with failed or success data
     *                  (See parser-common/src/Parser.php) for more info.
     */
    public function parse()
    {
        if (($this->arfMail !== false) &&
            (preg_match_all('/([\w\-]+): (.*)[ ]*\r?\n/', $this->arfMail['report'], $matches))
        ) {
            $report = array_combine($matches[1], $matches[2]);
            $this->feedName = 'default';

            // If feed is known and enabled, validate data and save report
            if ($this->isKnownFeed() && $this->isEnabledFeed()) {
                // Sanity check
                if ($this->hasRequiredFields($report) === true) {
                    // Event has all requirements met, filter and add!
                    $report = $this->applyFilters($report);

                    $report['evidence'] = $this->arfMail['evidence'];

                    $this->events[] = [
                        'source'        => config("{$this->configBase}.parser.name"),
                        'ip'            => $report['Source-IP'],
                        'domain'        => false,
                        'uri'           => false,
                        'class'         => config("{$this->configBase}.feeds.{$this->feedName}.class"),
                        'type'          => config("{$this->configBase}.feeds.{$this->feedName}.type"),
                        'timestamp'     => strtotime($report['Received-Date']),
                        'information'   => json_encode($report),
                    ];
                }
            }
        } else {
            $this->warningCount++;
        }

        return $this->success();
    }
}
