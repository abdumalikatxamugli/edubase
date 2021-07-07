<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;

use App\Models\Student;

class StudentEdit extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Student';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Edit or create';

    public $exists=false;
    /**
     * Query data.
     *
     * @return array
     */
    public function query(Student $student): array
    {
        $this->exists=$student->exists;

        if($this->exists){
            $this->description='edit';
        }else{
            $this->description='create';
        }
        return [
            'student'=>$student
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
    public function save(Student $student, Request $req){
        $student->fill($req->except('_token')['student']);
        $student->phone=str_replace(" ","", $req->input('student')['phone']);
        $student->save();
        return redirect()->route('students.list');
    }
    public function cancel(){
        return redirect()->route('students.list');
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
                        Input::make('student.first_name')->title('First Name'),
                        Input::make('student.last_name')->title('Last Name'),
                        Input::make('student.patronym')->title('Patronym')
                    ]),
                    Group::make([
                        DateTimer::make('student.birth_date')->format('Y-m-d')->title('Birth Date'),
                        Input::make('student.phone')
                            ->mask('999 99 999 99 99')
                            ->title('Phone number')
                    ]),                   
                    Group::make([
                        Input::make('student.pass_sery')->title('Passport seria')->width('100px'),
                        Input::make('student.pass_number')->title('Passport number'),
                        Input::make('student.pinfl')->title('PINFL'),
                        Select::make('student.gender')->options([
                            'm'=>'male',
                            'f'=>'female'
                        ])->title('Gender')
                    ]),
                    Input::make('student.address')->title('Address')
                ])
                    
                    
        ];
    }
}
