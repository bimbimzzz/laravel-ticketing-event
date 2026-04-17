<x-layouts.admin title="Users - Admin KarcisDigital">
    <x-slot:header>Users</x-slot:header>

    <div class="bg-white rounded-xl border border-surface-200">
        {{-- Search --}}
        <div class="px-5 py-4 border-b border-surface-100">
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="flex-1 px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                <button
                    class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Cari</button>
            </form>
            <a href="{{ route('admin.users.export', request()->query()) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors mt-3 sm:mt-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-xs text-surface-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold">User</th>
                        <th class="px-5 py-3 text-left font-semibold">Telepon</th>
                        <th class="px-5 py-3 text-left font-semibold">Role</th>
                        <th class="px-5 py-3 text-left font-semibold">Pesanan</th>
                        <th class="px-5 py-3 text-left font-semibold">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @foreach ($users as $user)
                        <tr class="hover:bg-surface-50">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center text-xs font-bold text-primary-700">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-surface-900">{{ $user->name }}</p>
                                        <p class="text-xs text-surface-500">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-surface-600">{{ $user->phone ?? '-' }}</td>
                            <td class="px-5 py-3">
                                @if (str_ends_with($user->email, '@admin.com'))
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold bg-red-50 text-red-700 rounded-md">Admin</span>
                                @elseif($user->is_vendor)
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-md">Vendor</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 text-xs font-semibold bg-surface-100 text-surface-600 rounded-md">Buyer</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-surface-600">{{ $user->orders_count }}</td>
                            <td class="px-5 py-3 text-surface-500 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="px-5 py-3 border-t border-surface-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
