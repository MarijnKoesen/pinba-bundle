<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Intaro\PinbaBundle\Twig;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Intaro\PinbaBundle\Stopwatch\Stopwatch;
use Symfony\Component\Config\FileLocatorInterface;

/**
 * Times the time spent to render a template.
 *
 */
class TimedTwigEngine extends TwigEngine
{
    protected $stopwatch;

    /**
     * Constructor.
     *
     * @param \Twig_Environment           $environment A \Twig_Environment instance
     * @param TemplateNameParserInterface $parser      A TemplateNameParserInterface instance
     * @param FileLocatorInterface        $locator     A FileLocatorInterface instance
     * @param Stopwatch                   $stopwatch   A Stopwatch instance
     * @param GlobalVariables             $globals     A GlobalVariables instance
     */
    public function __construct(\Twig_Environment $environment, TemplateNameParserInterface $parser, FileLocatorInterface $locator, Stopwatch $stopwatch, GlobalVariables $globals = null)
    {
        parent::__construct($environment, $parser, $locator, $globals);

        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    public function render($name, array $parameters = array())
    {
        $e = $this->stopwatch->start(array(
            'template' => (string) $name,
            'category' => 'template',
        ));

        $ret = parent::render($name, $parameters);

        $e->stop();

        return $ret;
    }
}