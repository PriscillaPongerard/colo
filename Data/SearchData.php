<?php

namespace App\Data;

class SearchData
{

    /**
     * @var int
     */
    public $page = 1;


    // systeme pour le mot clé
    /**
     * @var string
     */
    public $q = '';

    /**
     * @var search[]
     */
    public $search = [];
    
}
