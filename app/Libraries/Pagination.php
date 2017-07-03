<?php 
namespace App\Libraries;
use Landish\Pagination\SemanticUI;

// Uncomment bellow line, if you like to use "Simple Pagination"
// use Landish\Pagination\Simple\SemanticUI;

class Pagination extends SemanticUI {
    
    protected $paginationWrapper = '<ul>%s %s %s</ul>';

    protected $availablePageWrapper = '<li><a href="%s">%s</a></li>';

    protected $activePageWrapper = '<li class="active"><span>%s</span></li>';

    protected $disabledPageWrapper = '<li><span>%s</span></li>';

    protected $previousButtonText = '&lt; PREVIOUS';

    protected $nextButtonText = 'NEXT &gt;';

    protected $dotsText = '...';
}