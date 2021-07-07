<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Student;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;

class StudentList extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Students';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'List of all students';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $students=Student::filters()->paginate(5);
        return [
            'students'=>$students
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Create')->route('student.create_or_edit')->class('btn btn-success')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('students',[
                TD::make('first_name', 'Name')->sort()->filter(TD::FILTER_TEXT),
                TD::make('last_name', 'Surname')->filter(TD::FILTER_TEXT),
                TD::make('patronym', 'patronym')->filter(TD::FILTER_TEXT),
                TD::make('phone', 'Phone')->filter(TD::FILTER_TEXT),
                TD::make('birth_date', 'Birth date')->render(function($student){
                    return date('d.m.Y', strtotime($student->birth_date));
                }),
                TD::make('address', 'Address'),
                TD::make('edit')->render(function($student){
                    return Link::make('Edit')->route('student.create_or_edit', $student);
                })
            ])
        ];
    }
}
