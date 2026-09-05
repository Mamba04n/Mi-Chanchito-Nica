<?php

namespace App\Livewire\Education;

use Livewire\Component;

class AssessmentViewer extends Component
{
    public function render()
    {
        return view('livewire.education.assessment-viewer')->layout('layouts.app');
    }
}
