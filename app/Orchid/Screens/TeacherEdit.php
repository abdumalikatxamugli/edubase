<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;

use Illuminate\Http\Request;

use App\Models\Teacher;

class TeacherEdit extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Teacher';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Edit or create';

    public $exist=false;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Teacher $teacher): array
    {
        $this->exist=$teacher->exists;
        if($this->exist){
            $this->description='Edit';
            $this->name="{$teacher->first_name} {$teacher->last_name}";
        }else{
            $this->description='Create';
        }
        return [
            'teacher'=>$teacher
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
            Button::make('Cancel')->method('cancel'),
            Button::make('Save')->class('btn btn-success')
                                ->method('save')
        ];
    }

    public function save(Teacher $teacher, Request $req){
        $teacher->fill($req->except('_token')['teacher']);
        $teacher->phone=str_replace(" ","", $req->input('teacher')['phone']);
        $teacher->save();
        return redirect()->route('teachers.list');
    }
    public function cancel(){
        return redirect()->route('teachers.list');
    }
    
    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Group::make([
                    Input::make('teacher.first_name')->title('First Name'),
                    Input::make('teacher.last_name')->title('Last Name'),
                    Input::make('teacher.patronym')->title('Patronym')
                ]),
                Group::make([
                    DateTimer::make('teacher.birth_date')->format('Y-m-d')->title('Birth Date'),
                    Input::make('teacher.phone')
                        ->mask('999 99 999 99 99')
                        ->title('Phone number')
                ]),                   
                Group::make([
                    Input::make('teacher.pass_sery')->title('Passport seria')->width('100px'),
                    Input::make('teacher.pass_number')->title('Passport number'),
                    Input::make('teacher.pinfl')->title('PINFL'),
                    Select::make('teacher.gender')->options([
                        'm'=>'male',
                        'f'=>'female'
                    ])->title('Gender')
                ]),
                Input::make('teacher.address')->title('Address')
            ])
        ];
    }
}
