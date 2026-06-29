@extends('layouts.library')

@section('title', 'Quản lý người dùng')

@section('content')
    <section class="space-y-6">
        {{-- ─── FILTERS & CONTROLS ─── --}}
        <div class="rounded-2xl border p-5 shadow-sm" style="background: oklch(99.8% 0.003 75); border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex min-w-max flex-nowrap items-center gap-3 [&_label]:sr-only">
                    <div class="w-80 shrink-0">
                        <label for="keyword" class="block text-sm font-medium">Tên hoặc email</label>
                        <input
                            id="keyword"
                            type="text"
                            name="keyword"
                            value="{{ $filters['keyword'] }}"
                            placeholder="Tìm theo tên hoặc email"
                            class="input input-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        />
                    </div>

                    <div class="w-40 shrink-0">
                        <label for="role" class="block text-sm font-medium">Vai trò</label>
                        <select
                            id="role"
                            name="role"
                            class="select select-sm !h-10 min-h-10 w-full rounded-xl border text-sm shadow-none focus:outline-none"
                            style="border-color: oklch(86% 0.020 72); background: oklch(97% 0.010 76); color: oklch(20% 0.022 60);"
                        >
                            <option value="">Tất cả vai trò</option>
                            <option value="user" @selected($filters['role'] === 'user')>Người dùng</option>
                            <option value="teacher" @selected($filters['role'] === 'teacher')>Giáo viên</option>
                        </select>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        <button type="submit" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                                style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Lọc
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                           style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                           onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''">
                            Xóa lọc
                        </a>
                        <button
                            type="button"
                            class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'"
                            data-open-modal="create-user-modal"
                        >
                            Thêm người dùng
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── TABLE SECTION ─── --}}
        <section class="overflow-hidden rounded-2xl border shadow-sm" style="border-color: oklch(89% 0.018 72);">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead style="background: oklch(97% 0.010 76); color: oklch(30% 0.022 60); border-bottom: 1px solid oklch(89% 0.018 72);">
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
                            <tr style="border-bottom: 1px solid oklch(92% 0.016 74);">
                                <td class="font-semibold" style="color: oklch(18% 0.020 58);">{{ $user->name }}</td>
                                <td style="color: oklch(34% 0.025 64);">{{ $user->email }}</td>
                                <td>
                                    <span class="rounded-lg px-2.5 py-0.5 text-xs font-bold whitespace-nowrap inline-block"
                                          style="{{ $user->role === 'teacher' ? 'background: oklch(62% 0.090 240 / 0.15); color: oklch(35% 0.080 240);' : 'background: oklch(80% 0.010 70 / 0.2); color: oklch(46% 0.018 58);' }}">
                                        {{ $user->role === 'teacher' ? 'Giáo viên' : 'Người dùng' }}
                                    </span>
                                </td>
                                <td style="color: oklch(34% 0.025 64);">
                                    @if ($user->last_login_at)
                                        {{ $user->last_login_at->format('d/m/Y H:i') }}
                                    @else
                                        <span style="color: oklch(64% 0.012 62);">Chưa đăng nhập</span>
                                    @endif
                                </td>
                                <td style="color: oklch(34% 0.025 64);">{{ optional($user->created_at)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="flex justify-end gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                                            data-open-modal="edit-user-modal"
                                            data-user='@json($userPayload)'
                                        >
                                            Sửa
                                        </button>
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm-delete="true" data-confirm-message="Xóa người dùng này?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-sm rounded-xl border px-3 shadow-none transition"
                                                    style="border-color: oklch(76% 0.080 42 / 0.25); background: oklch(99.4% 0.005 78); color: oklch(38% 0.080 42);"
                                                    onmouseover="this.style.background='oklch(95% 0.020 40)'" onmouseout="this.style.background=''">
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

        {{-- ─── PAGINATION ─── --}}
        @if ($users->lastPage() > 1)
            <div
                class="lib-pagi-wrap mt-6"
                data-pagination
                data-current-page="{{ $users->currentPage() }}"
                data-last-page="{{ $users->lastPage() }}"
                data-base-url="{{ url()->current() }}"
                data-param="page"
                data-window="2"
                aria-label="Phân trang"
            ></div>
        @endif
    </section>

    {{-- ─── CREATE MODAL ─── --}}
    <dialog id="create-user-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Thêm người dùng</h2>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('users._form_fields', [
                        'prefix' => 'create',
                        'name' => old('name', ''),
                        'email' => old('email', ''),
                        'role' => old('role', 'user'),
                        'passwordRequired' => true,
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="create-user-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                        Tạo người dùng
                    </button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button aria-label="close" class="sr-only">close</button>
        </form>
    </dialog>

    {{-- ─── EDIT MODAL ─── --}}
    <dialog id="edit-user-modal" class="modal">
        <div class="modal-box w-xl max-w-none p-0 shadow-2xl" style="background: oklch(99.4% 0.005 78); border: 1px solid oklch(88% 0.020 72); border-radius: 16px;">
            <div class="px-5 py-4" style="border-bottom: 1px solid oklch(90% 0.018 74);">
                <h2 class="text-lg font-bold" style="color: oklch(18% 0.020 58);">Sửa người dùng</h2>
            </div>
            <form method="POST" action="" id="edit-user-form">
                @csrf
                @method('PUT')
                <div class="px-5 py-5 max-h-[60vh] overflow-y-auto">
                    @include('users._form_fields', [
                        'prefix' => 'edit',
                        'name' => '',
                        'email' => '',
                        'role' => 'user',
                        'createdAt' => '',
                        'passwordRequired' => false,
                    ])
                </div>

                <div class="modal-action mt-0 px-5 py-4" style="border-top: 1px solid oklch(90% 0.018 74);">
                    <button type="button" class="btn btn-ghost btn-sm !h-10 min-h-10 rounded-xl border px-4 shadow-none transition"
                            style="border-color: oklch(86% 0.020 72); background: oklch(99.4% 0.005 78); color: oklch(34% 0.022 62);"
                            onmouseover="this.style.background='oklch(95% 0.012 75)'" onmouseout="this.style.background=''"
                            data-close-modal="edit-user-modal">
                        Hủy
                    </button>
                    <button type="submit" class="btn btn-sm !h-10 min-h-10 rounded-xl border px-4 text-white shadow-none transition"
                            style="border: 1px solid oklch(36% 0.056 50 / 0.35); background: var(--g-primary);"
                            onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
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

@push('scripts')
<script src="{{ asset('js/library/pagination.js') }}"></script>
@endpush
