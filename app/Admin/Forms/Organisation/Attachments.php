<?php

namespace App\Admin\Forms\Organisation;

use Encore\Admin\Widgets\StepForm;
use Illuminate\Http\Request;
use Encore\Admin\Form;


class Attachments extends StepForm
{
    /**
     * The form title.
     *
     * @var  string
     */
    public $title = 'Attachments';

    public  function __construct(private readonly Form $form)
    {

    }

    /**
     * Handle the form request.
     *
     * @param  Request $request
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request)
    {
        return $this->next($data = []);
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->form->multipleFile('attachments', __('Attachments'))->removable()->rules('mimes:pdf,png,jpg,jpeg')
        ->help("Upload files such as registration certificate (pdf), logo (png, jpg, jpeg)");
    }
}