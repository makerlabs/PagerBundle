<?php

/*
 * This file is part of the PagerBundle package.
 *
 * (c) Marcin Butlak <contact@maker-labs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MakerLabs\PagerBundle\Twig\Extension;

use MakerLabs\PagerBundle\Pager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MakerLabs\PagerBundle\Templating\Helper\PagerHelper;

/**
 * PagerExtension extends Twig with pagination capabilities.
 *
 * @author Marcin Butlak <contact@maker-labs.com>
 */
class PagerExtension extends \Twig_Extension
{
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            'paginate' => new \Twig_Function_Method($this, 'paginate', array('is_safe' => array('html'))),
            'paginate_path' => new \Twig_Function_Method($this, 'path', array('is_safe' => array('html'))),
        );
    }

    public function paginate(Pager $pager, $route, array $parameters = array(), $template = null)
    {
        $template = $template ?: $this->container->getParameter('maker_labs_pager.pager.template') ?: 'MakerLabsPagerBundle:Pager:paginate.html.php';

        return $this->container->get('makerlabs.templating.helper.pager')->paginate(
            $pager,
            $route,
            $parameters,
            $template
        );
    }

    public function path($route, $page, array $parameters = array())
    {
        return $this->container->get('makerlabs.templating.helper.pager')->path(
            $route,
            $page,
            $parameters
        );
    }

    public function getName()
    {
        return 'pager';
    }
}