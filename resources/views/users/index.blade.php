@extends('layouts.library')

@section('title', 'Quản lý người dùng')

@section('content')
    <section class="space-y-5">
        <div class="rounded-sm border border-slate-200 bg-white p-5 shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex min-w-max flex-nowrap items-center gap-2 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium text-slate-700">Tên hoặc email</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên hoặc email"
                            class="input input-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                        />
                    </div>

                    <div class="w-40 shrink-0">
                        <label for="role" class="block text-sm font-medium text-slate-700">Vai trò</label>
                        <select
                            id="role"
                            name="role"
                            class="select select-sm !h-10 min-h-10 w-full rounded-sm border border-slate-200 bg-white text-sm text-slate-800 shadow-none"
                        >
                            <option value="">Tất cả vai trò</option>
                            <option value="user" @selected($filters['role'] === 'user')>Người dùng</option>
                            <option value="teacher" @selected($filters['role'] === 'teacher')>Giáo viên</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Lọc
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50">
                            Xóa lọc
                        </a>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none"
                            data-open-modal="create-user-modal"
                        >
                            Thêm người dùng
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <section class="overflow-hidden rounded-sm border border-slate-200 bg-white shadow-[0_18px_44px_-36px_rgba(15,23,42,0.35)]">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th>Tên</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Đăng nhập gần nhất</th>
                            <th>Ngày tạo</th>
                            <th class="text-right">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            @php
                                $userPayload = [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'role' => $user->role,
                                    'created_at' => optional($user->created_at)->format('d/m/Y H:i'),
                                    'destroy_url' => route('admin.users.destroy', $user),
                                    'edit_url' => route('admin.users.update', $user),
                                ];
                            @endphp
                            <tr>
                                <td class="font-medium text-slate-900">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="rounded-sm px-2 py-1 text-xs font-semibold {{ $user->role === 'teacher' ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $user->role === 'teacher' ? 'Giáo viên' : 'Người dùng' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->last_login_at)
                                        {{ $user->last_login_at->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-slate-400 font-normal">Chưa đăng nhập</span>
                                    @endif
                                </td>
                                <td>{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-sm border border-slate-200 bg-white text-slate-700 shadow-none hover:bg-slate-50"
                                            data-open-modal="edit-user-modal"
                                            data-user='@json($userPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm-delete="true" data-confirm-message="Xóa người dùng này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm rounded-sm border-0 bg-rose-600 text-white shadow-none hover:bg-rose-700">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-slate-500">Chưa có người dùng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <div>
            {{ $users->withQueryString()->links() }}
        </div>
    </section>

    <dialog id="create-user-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Thêm người dùng</h2>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="px-5 py-5">
                    @include('users._form_fields', [
                        'prefix' => 'create',
                        'name' => old('name', ''),
                        'email' => old('email', ''),
                        'role' => old('role', 'user'),
                        'passwordRequired' => true,
                    ])
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="create-user-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
                        Tạo người dùng
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    <dialog id="edit-user-modal" class="modal">
        <div class="modal-box w-xl max-w-none rounded-sm bg-white p-0 shadow-2xl">
            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-xl font-semibold text-slate-900">Sửa người dùng</h2>
            </div>
            <form method="POST" action="" id="edit-user-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5">
                    @include('users._form_fields', [
                        'prefix' => 'edit',
                        'name' => '',
                        'email' => '',
                        'role' => 'user',
                        'createdAt' => '',
                        'passwordRequired' => false,
                    ])
                </div>

                <div class="modal-action mt-0 border-t border-slate-200 px-5 py-4">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-sm border border-slate-200 bg-white px-4 text-slate-700 shadow-none hover:bg-slate-50" data-close-modal="edit-user-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm !h-10 min-h-10 rounded-sm border-0 px-4 text-white shadow-none">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>
@endsection

@push('scripts')
    <script>
        const modalOpeners = document.querySelectorAll('[data-open-modal]');
        const modalClosers = document.querySelectorAll('[data-close-modal]');
        const editUserForm = document.getElementById('edit-user-form');

        modalOpeners.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.openModal);

                if (!modal) {
                    return;
                }

                const userPayload = button.dataset.user ? JSON.parse(button.dataset.user) : null;

                if (modal.id === 'edit-user-modal' && userPayload) {
                    editUserForm.action = userPayload.edit_url;
                    document.getElementById('edit_name').value = userPayload.name;
                    document.getElementById('edit_email').value = userPayload.email;
                    document.getElementById('edit_role').value = userPayload.role;
                    document.getElementById('edit_created_at').value = userPayload.created_at;
                    document.getElementById('edit_password').value = '';
                    document.getElementById('edit_password_confirmation').value = '';
                }

                modal.showModal();
            });
        });

        modalClosers.forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.closeModal);

                if (modal) {
                    modal.close();
                }
            });
        });
    </script>
@endpush
