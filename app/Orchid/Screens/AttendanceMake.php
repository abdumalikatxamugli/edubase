<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\StudentGroup;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceMake extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Attendace';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Today';

    /**
     * Query data.
     *
     * @return array
     */
    public function query($group_id): array
    {
       
        return [
            'group_students'=>StudentGroup::where('group_id', $group_id)->get(),
            
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
            Button::make('save')->method('save')->class('btn btn-success')
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
                TD::make('status')->render(function($group_student){
                    return Select::make('status['.$group_student->id.']')
                            ->options([
                                'p'=>'present',
                                'a'=>'absent',
                                'l'=>'late'
                            ])->empty(
                                $group_student->todayAttendance()->statusText(),
                                $group_student->todayAttendance()->status
                            );
                })                
            ])
        ];
    }
    public function save(Group $group, Request $req){
        foreach($req->input('status')??[] as $student=>$status){
            $attendance=Attendance::where([
                'group_student_id'=>$student,
                'date_control'=>date('Y-m-d')
            ])->first();
            if(!$attendance){
                $attendance=new Attendance();
                $attendance->group_student_id=$student;
                $attendance->date_control=date('Y-m-d');
            }           
            $attendance->status=$status;
            $attendance->save();
        }
        return redirect()->route('groups.list');
    }
}
