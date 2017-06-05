<?php
namespace Kizi\Settings\Commands\Installers\Traits;

trait SectionMessage
{
    public function sectionMessage($title, $message)
    {
        $formatter     = $this->getHelperSet()->get('formatter');
        $formattedLine = $formatter->formatSection(
            $title,
            $message
        );
        $this->line($formattedLine);
    }
}
