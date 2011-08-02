<?php

namespace MakerLabs\PagerBundle\Twig\Extension;

use MakerLabs\PagerBundle\Pager;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * FormExtension extends Twig with pager capabilities.
 *
 * @author Marcin Butlak <contact@maker-labs.com>
 */
class PagerExtension extends \Twig_Extension
{
   /**
    *
    * @var RouterInterface
    */
   protected $router;
   /**
    *
    * @var \Twig_Environment
    */
   protected $enviroment;

   public function __construct(RouterInterface $router)
   {
      $this->router = $router;
   }

   public function initRuntime(\Twig_Environment $environment)
   {
      $this->enviroment = $environment;
   }

   public function getFunctions()
   {
      return array(
          'paginate' => new \Twig_Function_Method($this, 'paginate', array('is_safe' => array('html'))),
          'paginate_path' => new \Twig_Function_Method($this, 'path', array('is_safe' => array('html'))),
          
      );
   }

   public function paginate(Pager $pager, $route, array $parameters = array(), $template = 'MakerLabsPagerBundle:Pager:paginate.html.twig')
   {
      return $this->enviroment->render($template, array('pager' => $pager, 'route' => $route, 'parameters' => $parameters));
   }
   
   public function path($route, $page, array $parameters = array())
   {
      if (isset($parameters['_page']))
      {
         $parameters[$parameters['_page']] = $page;

         unset($parameters['_page']);
      }
      else
      {
         $parameters['page'] = $page;
      }      
      
      return $this->router->generate($route, $parameters);
   }

   public function getName()
   {
      return 'pager';
   }

}