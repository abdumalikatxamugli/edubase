<?php

namespace App\Orchid\Screens;

use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;

use App\Models\Teacher;


class TeacherList extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Teachers';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'list of teachers';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $teachers=Teacher::filters()->paginate(5);
        return [
            'teachers'=>$teachers
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
            Link::make('Create')->route('teachers.edit')->class('btn btn-success')
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
            Layout::table('teachers', [
                TD::make('first_name', 'Name')->sort()->filter(TD::FILTER_TEXT),
                TD::make('last_name', 'Surname')->sort(),
                TD::make('patronym', 'Patronym')->sort(),
                TD::make('birth_date', 'Birth date')->render(function($teacher){
                    return date('d.m.Y', strtotime($teacher->birth_date));
                }),
                TD::make('edit')->render(function($teacher){
                    return Link::make('Edit')->route('teachers.edit', $teacher);
                })
            ])
        ];
    }
}
