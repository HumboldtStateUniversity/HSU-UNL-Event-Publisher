<?php

interface UNL_UCBCN_Cacheable
{
    public function getCacheKey();
    public function run();
    public function preRun($cache_hit = false);
}
