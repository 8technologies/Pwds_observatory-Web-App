<?php

namespace App\Admin\Forms\Organisation;

use Encore\Admin\Widgets\StepForm;
use Illuminate\Http\Request;
use Encore\Admin\Form\NestedForm;
use Encore\Admin\Form;

class Contact extends StepForm
{
    /**
     * The form title.
     *
     * @var  string
     */
    public $title = 'Contacts';

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
        $this->form->text('physical_address', __('Physical address'));

        $this->form->hasMany('contact_persons', 'Contact Persons', function (NestedForm $form) {
            $form->text('name', __('Name'))->required();
            $form->text('position', __('Position'))->required();
            $form->email('email', __('Email'))->required();
            $form->text('phone1', __('Phone Tel'))->required();
            $form->text('phone2', __('Other Tel') );
        });
    }
}