<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Role;
use Illuminate\Support\Facades\Storage;

/**
 * Admin User Management.
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['table'] = [
            'table_url' => route("users.data"),
            'create' => [
                'url' => route("users.create"),
                'label' => 'Add User',
            ],
            'columns' => [
                [
                    'name' => 'formatted_id',
                    'label' => 'ID',
                ],
                [
                    'name' => 'name',
                    'label' => 'Name',
                ],
                [
                    'name' => 'roles',
                    'label' => 'Roles',
                ],
                [
                    'name' => 'email',
                    'label' => 'Email',
                ],
                [
                    'name' => 'action',
                    'label' => '#',
                ],
            ]
        ];

        return view('admin.user.index', $data);
    }

    /**
     * JSON Data for DataTable.
     *
     * @return DataTable
     */
    public function getData()
    {
        $query = User::select([
            'id',
            'name',
            'email',
            'created_at'
        ])->withCount('roles')->orderBy('roles_count', 'desc');

        return Datatables::of($query)->addColumn('formatted_id', function ($user) {
            return '<strong>' . $user->formatted_id . '</strong>';
        })->addColumn('roles', function ($item) {
            return $item->roles->pluck('name')->implode(', ');
        })->addColumn('action', function ($item) {
            $string = '';

            $string .= '<a href="' . route('users.edit', $item->id) . '"><button title="Edit" class="btn btn-icon btn-sm btn-success waves-effect waves-light" style="margin-right: 5px;"><i class="fa fa-eye"></i></button></a>';

            if ($item->id != request()->user()->id) {
                $string .= '<button title="Hapus" class="btn btn-icon btn-sm btn-danger waves-effect waves-light delete"><i class="fa fa-trash"></i></button>';
                $string .= '<form action="' . route('users.destroy', $item->id) . '" method="POST">' . method_field('delete') . csrf_field() . '</form>';
            }

            return $string;
        })->rawColumns(['formatted_id', 'action'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['roles'] = \Spatie\Permission\Models\Role::get(['id', 'name']);

        return view('admin.user.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserUpdateRequest $request)
    {
        $payload = request()->only(array_keys($request->rules()));
        $payload = $this->prepareData($payload);

        \DB::transaction(function () use ($payload) {
            $this->user = User::create($payload);

            $this->user->roles()->detach();
            if (isset($payload['roles']) && count($payload['roles']) > 0) {
                $this->user->roles()->detach();
                $this->user->roles()->attach($payload['roles']);
            }

            $roles = [
                'administrator',
            ];

            foreach ($roles as $role) {
                $check = Role::where('name', $role)->first();
                if (!is_null($check)) {
                    continue;
                }
                Role::create([
                    'name'    => $role
                ]);
            }

            $this->user->assignRole('administrator');
        });

        return redirect()->route('users.index')->with('status', 'User successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect(route('users.edit', $id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['roles'] = \Spatie\Permission\Models\Role::get(['id', 'name']);
        $data['object'] = User::findOrFail($id);

        return view('admin.user.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $payload = request()->only(array_keys($request->rules()));
        $payload = $this->prepareData($payload);
        $user = User::findOrFail($id);

        \DB::transaction(function () use ($request,$user, $payload) {

            $user->update($payload);

            $user->roles()->detach();
            if (isset($payload['roles']) && count($payload['roles']) > 0) {
                $user->roles()->detach();
                $user->roles()->attach($payload['roles']);
            }
            $roles = ['administrator'];
            foreach ($roles as $role) {
                $roleModel = Role::firstOrCreate(['name' => $role]);
            }
            $user->assignRole('administrator');

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/photos', $fileName);

                $user->photo = $fileName;
                $user->save();
            }
        });

        return redirect('/users')->with('status', 'User successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('status', 'User successfully deleted.');
    }

    /**
     * Prepare Data.
     *
     * @param array $payload
     *
     * @return array
     */
    public function prepareData($payload = [])
    {
        // Upload File
        if (isset($payload['photo']) && !is_null($payload['photo'])) {
            $payload['photo'] = \Storage::disk('public')->put('assets/user', request()->file('photo'));
        }

        // Hash Password
        if (isset($payload['password']) && !is_null($payload['password'])) {
            $payload['password'] = \Hash::make($payload['password']);
        }

        // Clean data
        foreach ($payload as $key => $value) {
            if (is_null($value)) {
                unset($payload[$key]);
            }
        }

        return $payload;
    }
}
