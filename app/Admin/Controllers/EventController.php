<?php

namespace App\Admin\Controllers;

use App\Models\Event;
use App\Models\Utils;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EventController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Event';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Event());

        $user = Admin::user();
        if ($user->inRoles(['basic', 'pwd'])) {
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->disableDelete();
            });
            $grid->disableCreateButton();
        }

        Utils::checkEventRegustration();

        $grid->disableFilter();
        $grid->disableBatchActions();
        $grid->quickSearch('title')->placeholder('Search by Event Title');
        $grid->model()->orderBy('id', 'desc');

        $grid->column('created_at', __('Regisetered'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable();
        $grid->column('title', __('Event Title'))->sortable();

        $grid->column('theme', __('Event Theme'));
        $grid->column('details', __('Details'))->hide();

        $grid->column('venue_name', __('Venue'));
        $grid->column('event_date', __('Event date'))->display(
            function ($x) {
                return Utils::my_date($x);
            }
        )->sortable();
        $grid->column('address', __('Address'));
        $grid->column('gps_latitude', __('Gps latitude'));
        $grid->column('gps_longitude', __('Gps longitude'));
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $event = Event::findOrFail($id);
        $show = new Show($event);

        return view('admin.events.show', [
            'event' => $event
        ]);

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('title', __('Title'));
        $show->field('theme', __('Theme'));
        $show->field('photo', __('Photo'));
        $show->field('details', __('Details'));
        $show->field('prev_event_title', __('Prev event title'));
        $show->field('number_of_attendants', __('Number of attendants'));
        $show->field('number_of_speakers', __('Number of speakers'));
        $show->field('number_of_experts', __('Number of experts'));
        $show->field('venue_name', __('Venue name'));
        $show->field('venue_photo', __('Venue photo'));
        $show->field('venue_map_photo', __('Venue map photo'));
        $show->field('event_date', __('Event date'));
        $show->field('address', __('Address'));
        $show->field('gps_latitude', __('Gps latitude'));
        $show->field('gps_longitude', __('Gps longitude'));
        $show->field('video', __('Video'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Event());

        $form->text('title', __('Event Title'))->rules('required');
        $form->text('theme', __('Event Theme'))->rules('required');
        $form->date('event_date', __('Event date'))->rules('required');
        $form->image('photo', __('Photo'))->rules('required');
        $form->quill('details', __('Details'))->rules('required');
        $form->text('venue_name', __('Venue name'))->rules('required');
        $form->text('address', __('Venue Address'))->rules('required');
        $form->image('venue_photo', __('Venue photo'))->rules('required');
        $form->image('venue_map_photo', __('Venue map photo'))->rules('required');

        $form->text('gps_latitude', __('Venue Gps latitude'));
        $form->text('gps_longitude', __('Venue Gps longitude'));
        $form->file('video', __('Intro Video'));

        $form->divider('Event main speakers');

        $form->morphMany('speakers', 'Click on new to add a main speaker', function (Form\NestedForm $form) {
            $form->text('name', __('Speaker\'s name'))->rules('required');
            $form->text('designation', __('Speaker\'s Designation'))->rules('required');
            $form->image('photo', __('Speaker\'s photo'));
            $form->quill('details', __('Speaker\'s Profile'))->rules('required');
        });



        $form->divider('Ticket pricing');

        $form->morphMany('tickets', 'Click on new to add a ticket', function (Form\NestedForm $form) {
            $u = Admin::user();
            $form->hidden('user_id')->default($u->id);
            $form->text('name', __('Ticket picing name'))->rules('required');
            $form->decimal('price', __('Price (in UGX)'))->rules('required');
            $form->decimal('availability', __('Number of available tickets'))->rules('required');
            $form->textarea('details', __('Description'))->rules('required');
            $form->image('icon', __('Icon/image'));
        });


        $form->divider('Previous event');
        $form->text('prev_event_title', __('Previous event title'))->rules('required');
        $form->decimal('number_of_attendants', __('Number of Previous event attendants'))->rules('required');
        $form->decimal('number_of_speakers', __('Number of speakers'))->rules('required')->rules('required');
        $form->decimal('number_of_experts', __('Number of experts'))->rules('required');




        return $form;
    }
}
