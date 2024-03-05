<?php

namespace App\Admin\Forms\Organisation;

use Encore\Admin\Widgets\StepForm;
use Illuminate\Http\Request;
use App\Models\Organisation;
use Encore\Admin\Form;

class Membership extends StepForm
{
    /**
     * The form title.
     *
     * @var  string
     */
    public $title = 'Membership';

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
        return $this->next($request->all());
    }

    /**
     * Build a form here.
     */
    public function form()
    {
        $this->form->radio('membership_type', __('Membership type'))->options(['member' => 'Member-Based', 'pwd' => 'Individual-based'])->required();

    }
}