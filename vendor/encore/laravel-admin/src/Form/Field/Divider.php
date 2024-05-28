<?php

namespace Encore\Admin\Form\Field;

use Encore\Admin\Form\Field;

class Divider extends Field
{
    protected $title;

    public function __construct($title = '')
    {
        $this->title = $title;
    }

    public function render()
    {
        if (empty($this->title)) {
            return '<hr>';
        }

        return <<<HTML
<div style="height: 26px; border-bottom: 1px solid #eee; text-align: center;margin-top: 20px;margin-bottom: 18px;  background-color: #009DD6;">
  <span style="font-size: 20px; background-color: #ffffff; padding: 0 15px; font-weight: 800;">
    {$this->title}
  </span>
</div>
HTML;
    }
}
