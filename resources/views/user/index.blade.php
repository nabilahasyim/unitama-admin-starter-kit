<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow p-3 mb-3">
        <h5 class="fw-bold mb-0">{{ $title }}</h5>
    </div>

    <div class="card shadow p-3">

        <div>
            <a class=="btn btn-primary mb-3" href="{{ route('user.create') }}" role="button">Create</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered border-dark table-hover w-100" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $user->name }}</td>
                            <td class="text-start">{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class='bx bx-edit-alt'></i>
                                </a>

                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

    @push('modals')
    @endpush

    @push('scripts')
    @endpush

</x-app>
