<?php

namespace MakerLabs\PagerBundle\Adapter;

interface PagerAdapterInterface
{   
   function getResults($offset, $limit);
   
   function getTotalResults();
}
