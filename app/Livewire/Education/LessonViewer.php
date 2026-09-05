<?php

namespace App\Livewire\Education;

use Livewire\Component;

class LessonViewer extends Component
{
    public function render()
    {
        return view('livewire.education.lesson-viewer')->layout('layouts.app');
    }
}
