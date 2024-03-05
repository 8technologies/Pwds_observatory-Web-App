<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\NewsPost;
use App\Models\Resource;
use App\Models\ServiceProvider;
use App\Models\Job;
use App\Models\Innovation;
use App\Models\Event;
use App\Models\Disability;

class SearchComponent extends Component
{
    public $search;

    public function render()
    {

        if (!empty($this->search)) {
            $results['news'] = NewsPost::whereLike(['title','details','description'], $this->search)->take(100)->get();
            $results['resources'] = Resource::whereLike(['title','type','other_type','date_of_publication','description','author'], $this->search);
            $results['service_providers'] = ServiceProvider::whereLike(['name','registration_number','brief_profile','physical_address','services_offered','districts_of_operation','target_group','disability_category','affiliated_organizations','mission','postal_address'], $this->search)->take(100)->get();
            $results['jobs'] = Job::whereLike(['title','description','location','type','minimum_academic_qualification','required_experience','how_to_apply','hiring_firm','deadline'], $this->search)->take(100)->get();
            $results['innovations'] = Innovation::whereLike(['title','innovation_type','innovators','innovation_status','description'], $this->search)->take(100)->get();
            $results['events'] = Event::whereLike(['title','theme','details','venue_name','address'], $this->search)->take(100)->get();
            $results['disabilities'] = Disability::whereLike(['name','description'], $this->search)->take(100)->get();

        }

        return view('livewire.search-component', [
            'results' => $results ?? [],
        ]);
    }
}
