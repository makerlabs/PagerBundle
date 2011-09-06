<ul class="pager">
    <?php if ($pager->isFirstPage() == false): ?>    
        <li class="first"><a href="<?php echo $view['pager']->path($route, $pager->getFirstPage(), $parameters) ?>">&laquo;</a></li>
        <li class="previous"><a href="<?php echo $view['pager']->path($route, $pager->getPreviousPage(), $parameters) ?>">&lsaquo;</a></li>
    <?php endif ?>
    <?php foreach ($pager->getPages() as $page): ?>
        <?php if ($page == $pager->getPage()): ?>
            <li class="selected">
                <b><?php echo $page ?></b>
            </li>      
        <?php else: ?>
            <li>
                <a href="<?php echo $view['pager']->path($route, $page, $parameters) ?>"><?php echo $page ?></a>
            </li>
        <?php endif ?>
    <?php endforeach; ?>  
    <?php if ($pager->isLastPage() == false): ?>
        <li class="next"><a href="<?php echo $view['pager']->path($route, $pager->getNextPage(), $parameters) ?>">&rsaquo;</a></li>
        <li class="last"><a href="<?php echo $view['pager']->path($route, $pager->getLastPage(), $parameters) ?>">&raquo;</a></li>
    <?php endif ?>
</ul>