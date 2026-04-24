<?php

namespace App\Traits;

trait MetaTrait
{
    protected $meta = [
        'title' => '',  // Default title
    ];

    public function setTitle($title)
    {
        $this->meta['title'] = $title;
    }

    public function metaResponse()
    {
        return $this->meta;
    }
}
