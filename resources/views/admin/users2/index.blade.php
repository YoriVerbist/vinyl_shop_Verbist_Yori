@extends('layouts.template')

@section('title', 'Users (advanced)')

@section('main')
    <h1>Users (advanced)</h1>
    @include('shared.alert')
    <form method="get" action="/admin/users2" id="searchForm" class="mb-3">
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
    @if ($users->count() == 0)
        <div class="alert alert-danger alert-dismissible fade show">
            Can't find any user with <b>'{{ request()->users }}'</b> in his name or email
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @else
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
                        <td>@if ($user->active)
                                <i class="fas fa-check"></i>
                            @endif</td>
                        <td>@if ($user->admin)
                                <i class="fas fa-check"></i>
                            @endif</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <form action="#!" method="post" class="changeForm editForm col-s2">
                                    @csrf
                                    <a href="#!" class="btn  btn-sm btn-outline-success btn-edit @if (auth()->id() == $user->id) disabled @endif"
                                       data-toggle="tooltip"
                                       data-id="{{$user->id}}"
                                       data-user="{{ $user->name }}"
                                       data-email="{{$user->email}}"
                                       data-active="{{$user->active}}"
                                       data-admin="{{$user->admin}}"
                                       title="Edit {{ $user->name }}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                </form>
                                <form action="/admin/users2/{{ $user->id }}" method="post" class="changeForm deleteForm col-s2"
                                      id="deleteForm-{{ $user->id }}"
                                      data-id="{{ $user->id }}"
                                >
                                    @method('delete')
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-toggle="tooltip"
                                            @if (auth()->id() == $user->id)
                                            disabled
                                            @endif
                                            data-user="{{ $user->name }}"
                                            title="Delete {{ $user->name }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    @endif
    @include('admin.users2.modal')

@endsection

@section('script_after')
    <script>
        $(function () {

            // Submit form on blur 'name'
            $('#users').blur(function () {
                $('#searchForm').submit();
            });
            // Submit form on change 'sort'
            $('#sortBy').change(function () {
                $('#searchForm').submit();
            });

            $('.deleteForm button').click(function (e) {
                let form = $(this).closest('form');
                let user = $(this).data('user');
                let text = `Delete the user '${user}'?`;
                //Show Notify
                let modal = new Noty({
                    timeout: false,
                    layout: 'center',
                    modal: true,
                    type: 'warning',
                    text: text,
                    buttons: [
                        Noty.button('Delete user', `btn btn-success`, function () {
                            //Delete user and close modal
                            $(form).submit();
                            modal.close();
                        }),
                        Noty.button('Cancel', `btn btn-secondary ml-2`, function () {
                            modal.close();
                        })
                    ]
                }).show();
            });
            $('.btn-edit').click(function (e) {
                // Get data attributes from td tag
                let id =  $(this).data('id')
                let name =  $(this).data('user')
                let email =  $(this).data('email')
                let active =  $(this).data('active')
                let admin =  $(this).data('admin')
                console.log($(this))
                // Update the modal
                $('.modal-title').text(`Edit ${name}`);
                $('form').attr('action', `/admin/users2/${id}`);
                $('#name').val(name);
                $('#email').val(email);
                $('#active').prop('checked',active)
                $('#admin').prop('checked',admin)
                $('input[name="_method"]').val('put');
                // Show the modal
                $('#modal-user').modal('show');
            });
            $('.changeForm').submit(function (e) {
                console.log($(e));
                // Don't submit the form
                e.preventDefault();
                // Get the action property (the URL to submit)
                let action = $(this).attr('action');
                // Serialize the form and send it as a parameter with the post
                let pars = $(this).serialize();
                console.log(pars);
                // Post the data to the URL
                $.post(action, pars, 'json')
                    .done(function (data) {
                        console.log(data);
                        // Noty success message
                        new Noty({
                            type: data.type,
                            text: data.text
                        }).show();
                        // Hide the modal
                        $('#modal-user').modal('hide');
                        // Reload the page
                        window.location.reload();
                    })
                    .fail(function (e) {
                        console.log('error', e);
                        // e.responseJSON.errors contains an array of all the validation errors
                        console.log('error message', e.responseJSON.errors);
                        // Loop over the e.responseJSON.errors array and create an ul list with all the error messages
                        let msg = '<ul>';
                        $.each(e.responseJSON.errors, function (key, value) {
                            msg += `<li>${value}</li>`;
                        });
                        msg += '</ul>';
                        // Noty the errors
                        new Noty({
                            type: 'error',
                            text: msg
                        }).show();
                    });

            });
        });
    </script>
@endsection



