<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Cropper;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use App\Models\Group;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Fields\Relation;
use App\Models\Teacher;
use Orchid\Screen\Fields\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GroupList extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Groups';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'list';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
           'groups'=>Group::paginate(10)
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
            ModalToggle::make('Create group')
                ->modal('createGroupModal')
                ->method('saveGroup')
                ->icon('pencil')
                ->modalTitle('Create new group')
        ];
    }

    public function saveGroup(Request $req){
        $group=new Group();
        $group->fill($req->except('_token'));
        $group->save();
        Toast::info('Hello, world! This is a toast message.');
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('groups', [
                TD::make('name'),
                TD::make('teacher')->render(function($group){
                    return "{$group->teacher->first_name} {$group->teacher->last_name}";
                }),
                TD::make('edit')->render(function($group){
                    return ModalToggle::make('Edit group')
                        ->modal('editGroupModal')
                        ->method('editGroup')
                        ->icon('pencil')
                        ->class('btn btn-warning')
                        ->modalTitle('Edit Group')
                        ->asyncParameters($group->id);
                }),
                TD::make('delete')->render(function($group){
                    return Button::make('delete')->method('deleteGroup')
                            ->confirm(__('Are you sure you want to delete the group?'))
                            ->class('btn btn-danger')
                            ->icon('trash')
                            ->parameters([
                                'id' => $group->id,
                            ]);
                }),
                TD::make('students')->render(function($group){
                    return Link::make('students')->route('group.students', $group)
                                            ->class('btn btn-primary');
                }),
                TD::make('attendance')->render(function($group){
                    return Link::make('attendance')->route('group.attendance', $group)
                                            ->class('btn btn-success');
                })
            ]),
            Layout::modal('createGroupModal', [
                Layout::rows([
                    Input::make('name')->title('Group Name')->required(),
                    Relation::make('teacher_id')
                        ->fromModel(Teacher::class, 'first_name')
                        ->displayAppend('full_name')
                        ->title('Select Teacher') 
                        ->required()                                   
                ])
            ]),
            Layout::modal('editGroupModal', [
                Layout::rows([
                    Input::make('group.id')->hidden(),
                    Input::make('group.name')->title('Group Name')->required(),
                    Relation::make('group.teacher_id')
                        ->fromModel(Teacher::class, 'first_name')
                        ->displayAppend('full_name')
                        ->title('Select Teacher') 
                        ->required()                                   
                ])
            ])->async('asyncGetTeacher')
        ];
    }
    public function asyncGetTeacher($id){
        return [
            'group'=>Group::where('id',$id)->first()
        ];
    }
    public function editGroup(Request $req){
        $group=$req->input('group');
        Group::where('id', $group['id'])->first()->fill(Arr::except($group, ['id']))->save();
        Toast::success('Hello, world! This is a toast message.');
    }
    public function deleteGroup($id){
        Group::where('id', $id)->first()->delete();
        Toast::success('Group deleted');
    }
}
