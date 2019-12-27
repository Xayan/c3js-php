<?php

namespace C3\Renderer;

use C3\Chart\ChartInterface;
use C3\Exception\TwigException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class ChartRenderer
{
    /**
     * @var Environment
     */
    private $environment;

    /**
     * ChartRenderer constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param ChartInterface $chart
     * @return string
     * @throws TwigException
     */
    public function render(ChartInterface $chart): string
    {
        try {
            return $this->environment->render('html/chart.html.twig', [
                'chart' => $chart
            ]);
        } catch(LoaderError | RuntimeError | SyntaxError $e) {
            throw new TwigException($e->getMessage(), $e->getCode(), $e);
        }
    }
}