@extends('layouts.admin')

@section('content')

{{-- Flash Alert --}}
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center text-green-600 flex-shrink-0">
        <i class="fas fa-check"></i>
    </div>
    <p class="text-sm font-semibold text-green-700">{{ session('success') }}</p>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-3">
    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center text-red-600 flex-shrink-0">
        <i class="fas fa-exclamation-triangle"></i>
    </div>
    <p class="text-sm font-semibold text-red-700">{{ session('error') }}</p>
</div>
@endif

{{-- Page Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Kelola User</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar pengguna terdaftar dan hak akses sistem Lacera</p>
    </div>
</div>

{{-- Top Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-lacera-light text-lacera flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total User</p>
            <h3 class="text-xl font-bold text-gray-800">{{ $totalUsers }} Pengguna</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-user-tag"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Total Customer</p>
            <h3 class="text-xl font-bold text-gray-800">{{ $customerCount }} Akun</h3>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center text-xl shrink-0">
            <i class="fas fa-user-shield"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Administrator</p>
            <h3 class="text-xl font-bold text-gray-800">{{ $adminCount }} Admin</h3>
        </div>
    </div>
</div>

{{-- User Table --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Pengguna</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Role Hak Akses</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider">Tanggal Bergabung</th>
                    <th class="px-6 py-4 font-bold text-gray-500 text-xs uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-lacera-light text-lacera font-bold flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $user->name }}</p>
                                <p class="text-[10px] text-gray-400 font-mono">ID: #{{ $user->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 font-medium">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4">
                        @if($user->role === 'admin')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-700 text-xs font-bold rounded-full border border-purple-200">
                            <i class="fas fa-shield-alt text-[10px]"></i> Administrator
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full border border-gray-200">
                            <i class="fas fa-user text-[10px]"></i> Customer
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $user->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex gap-2">
                            <form action="{{ route('admin.users.toggle-role', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-gray-50 text-gray-700 border border-gray-200 rounded-lg text-xs font-bold hover:bg-lacera hover:text-white hover:border-lacera transition" title="Ubah Role">
                                    <i class="fas fa-exchange-alt mr-1"></i> {{ $user->role === 'admin' ? 'Jadikan Customer' : 'Jadikan Admin' }}
                                </button>
                            </form>

                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 rounded-lg text-xs font-bold hover:bg-red-500 hover:text-white transition" title="Hapus User">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        Belum ada pengguna terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
