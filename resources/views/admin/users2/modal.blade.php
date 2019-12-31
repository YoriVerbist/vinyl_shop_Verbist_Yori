<div class="modal" id="modal-user">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">modal-user-title</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" class='changeForm'>
                    @method('')
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Name"
                               required
                               minlength="3"
                               value="{{ old('name', $user->name) }}">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="Name"
                               required
                               value="{{ old('email', $user->email) }}">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id='active' type="checkbox" value="1"
                                   id="active" name="active">
                            <label class="form-check-label" for="active">
                                Active

                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" id='admin' type="checkbox" value="1"

                                   id="admin" name="admin">
                            <label class="form-check-label" for="admin">
                                Admin

                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Save user</button>
                </form>
            </div>
        </div>
    </div>
</div>
