<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow p-3 mb-3">
        <h5 class="fw-bold mb-0">{{ $title }}</h5>
    </div>

    <div class="card shadow p-3">

        <div class="mb-3">
            <a href="{{ route('user.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Create
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover w-100" id="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="170">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $user->name }}</td>
                            <td class="text-start">{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>

                            <td class="text-center text-nowrap">
                                <a href="{{ route('user.show', $user) }}" class="btn btn-info btn-sm">
                                    <i class="bx bx-show"></i>
                                </a>

                                <a href="{{ route('user.edit', $user) }}" class="btn btn-warning btn-sm">
                                    <i class="bx bx-edit"></i>
                                </a>

                                <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal" data-route="{{ route('user.destroy', $user) }}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

    @push('modals')
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail {{ $title }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-detail">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            $('#data-table').on('click', '.btn-delete', function() {
                $('#form-delete').attr('action', $(this).data('route'));
            });

            $('#data-table').on('click', '.btn-detail', function() {
                $('#modal-detail').load($(this).data('route'));
            });
        </script>
    @endpush

</x-app>
