@extends('layouts.template')

@section('title', 'Users')

@section('main')
    <h1>Users</h1>
    @include('shared.alert')
    <form method="get" action="/admin/users" id="searchForm" class="mb-3">
        <div class="row">
            <div class="col-sm-7">
                <p>Filter Name Or Email</p>
            </div>
            <div class="col-sm-5">
                <p>Sort by</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-7 mb-2">
                {{--            <div class="col-sm-6 mb-2">--}}
                <input type="text" class="form-control" name="nameOrEmail" id="nameOrEmail"
                       {{--                       value="{{ request()->nameOrEmail }}"--}}
                       value="{{ old('nameOrEmail', request()->nameOrEmail) }}"
                       placeholder="Filter Name Or Email">
            </div>
            <div class="col-sm-5 mb-2">
                {{--            <div class="col-sm-4 mb-2">--}}
                <select class="form-control" name="sortBy" id="sortBy">
                    {{--                    <option value="%">Nothing to select</option>--}}
                    @foreach($sortByElements as $sortByElement)
                        <option
                            value="{{ $sortByElement}}" {{ (request()->sortBy ==  $sortByElement ? 'selected' : '') }}>
                            {{ $sortByElement }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Active</th>
                <th>Admin</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>@if ($user->active == 1 )
                            <i class="fas fa-check"></i>
                        @endif
                    </td>
                    <td>@if ($user->admin == 1 )
                            <i class="fas fa-check"></i>
                        @endif
                    </td>
                    <td>
                        <form action="/admin/users/{{ $user->id }}" method="post" class="deleteForm"
                              id="deleteForm-{{ $user->id }}"
                              data-id="{{ $user->id }}"
                        >
                            @method('delete')
                            @csrf
                            <div class="btn-group btn-group-sm">
                                <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-outline-success"
                                   data-toggle="tooltip"
                                   data-id="{{ $user->id }}"
                                   title="Edit {{ $user->name }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button"
                                        class="btn btn-outline-danger"
                                        data-toggle="tooltip"
                                        title="Delete {{ $user->name }}"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        data-admin="{{ $user->admin }}"
                                >
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
@endsection

@section('script_after')
    <script>
        $(function () {
            // submit form when leaving text field 'artist'
            $('#nameOrEmail').blur(function () {
                $('#searchForm').submit();
            });
            // submit form when changing dropdown list
            $('#sortBy').change(function () {
                $('#searchForm').submit();
            });
            $('.deleteForm button').click(function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let admin = $(this).data('admin');
                // Set some values for Noty
                let text = `<p>Delete the user <b>${name}</b>?</p>`;
                let type = 'warning';
                let btnText = 'Delete user';
                let btnClass = 'btn-success';
                // If admin, overwrite values for Noty
                if (admin == 1) {
                    text += `<p>ATTENTION: you are going to delete an <b>administrator</b>!</p>`;
                    btnText = `Delete admin`;
                    btnClass = 'btn-danger';
                    type = 'error';
                }
                console.log(`delete user ${id}, ${name}, ${admin}`);
                // Show Noty
                let modal = new Noty({
                    timeout: false,
                    layout: 'center',
                    modal: true,
                    type: type,
                    text: text,
                    buttons: [
                        Noty.button(btnText, `btn ${btnClass}`, function () {
                            // Delete user and close modal
                            // deleteUser(id);
                            modal.close();
                            console.log("noty: ",'id', id);
                            $(`#deleteForm-${id}`).submit();
                        }),
                        Noty.button('Cancel', 'btn btn-secondary ml-2', function () {
                            modal.close();
                        })
                    ]
                }).show();
            });
            $('.deleteForm').each(function () {
                // use $(this) to reference the current div in the loop
                let id = $(this).data('id');
                let currentUserId = {{ auth()->user()->id }};
                console.log(id, currentUserId)
                if (id == currentUserId) {
                    $(this).find('div').addClass('disabled');
                    $(this).find('a').addClass('disabled');
                    // $(this).find('a').attr('disabled', true);
                    $(this).find("button").attr('disabled', true);
                    return false;
                }
            });
        });
    </script>
@endsection
