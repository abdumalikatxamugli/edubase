<?php

namespace App\Orchid\Screens;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use App\Models\Student;
use App\Models\StudentGroup;
use App\Models\Group;
use Orchid\Support\Facades\Toast;

class GroupStudentsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Group';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Students in this group';

    /**
     * Query data.
     *
     * @return array
     */
    public function query($group_id): array
    {
        return [
            'group_students'=>StudentGroup::where('group_id', $group_id)->get()
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
            ModalToggle::make('Add student')
                ->modal('addStudent')
                ->method('addStudent')
                ->icon('pencil')
                ->modalTitle('New Student')
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
            Layout::table('group_students', [
                TD::make('full_name')->render(function($group_student){
                    return $group_student->student->full_name;
                }),
                TD::make('added at')->render(function($group_student){
                    return date('Y-m-d', strtotime($group_student->created_at));
                }),
                TD::make('Delete')->render(function($group_student){
                    return Button::make('delete')->method('deleteStudentGroup')
                            ->confirm(__('Are you sure you want to unlink the student?'))
                            ->class('btn btn-danger')
                            ->icon('trash')
                            ->parameters([
                                'id' => $group_student->id,
                            ]); 
                })
            ]),
            Layout::modal('addStudent', [
                Layout::rows([
                    Relation::make('student_id')
                        ->fromModel(Student::class, 'first_name')
                        ->displayAppend('full_name')
                        ->title('Select Student') 
                        ->required()                                   
                ])
            ]),
            
        ];
    }
    public function addStudent(Group $group, Request $req){
        $student_group=new StudentGroup();
        $student_group->student_id=$req->input('student_id');
        $student_group->group_id=$group->id;
        $student_group->save();
        Toast::success('Hello, world! This is a toast message.');
    }
    public function deleteStudentGroup($student_group_id){
        StudentGroup::where('id', $student_group_id)->first()->delete();
    }
}
