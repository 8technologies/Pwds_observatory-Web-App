<?php

namespace App\Admin\Forms\Organisation;

use Encore\Admin\Widgets\StepForm;
use Illuminate\Http\Request;
use App\Models\Organisation;
use Encore\Admin\Form;
use Encore\Admin\Form\NestedForm;

class Bio extends StepForm
{
    /**
     * The form title.
     *
     * @var  string
     */
    public $title = 'Bio';

    public static $model_form = null;


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
        $this->text('name', __('Name'));
            $this->text('registration_number', __('Registration number'));
            $this->date('date_of_registration', __('Date of registration'));
            $this->radio('type', __('Type Of Organisation'))->options(['NGO'=> 'NGO', 'SACCO'=> 'SACCO'])->required();
            $this->textarea('mission', __('Mission'));
            $this->textarea('vision', __('Vision'));
            $this->textarea('core_values', __('Core values'));
            $this->quill('brief_profile', __('Brief profile'));
    }

    public static function make($form) :self
    {
        static::$model_form = $form;
        return new static();
    }

}   