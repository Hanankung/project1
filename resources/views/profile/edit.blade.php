<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-emerald-800 leading-tight">
                    Profile
                </h2>
                <p class="text-sm text-emerald-700/70">จัดการข้อมูลบัญชีของคุณ</p>
            </div>
            <div class="hidden sm:block text-sm text-gray-500">member.dashboard</div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto grid gap-6 px-4 sm:px-6 lg:px-8 lg:grid-cols-3">
            {{-- Sidebar --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow p-6">
                    <div class="mx-auto w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center
                                text-emerald-700 text-2xl font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="mt-4 text-center">
                        <div class="text-lg font-semibold">{{ auth()->user()->name }}</div>
                        <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                    </div>

                    <div class="mt-6 border-t pt-4 text-sm text-gray-600">
                        อัปเดตข้อมูลส่วนตัว รหัสผ่าน และความปลอดภัยของบัญชี
                    </div>
                </div>
            </aside>

            {{-- Main forms --}}
            <main class="lg:col-span-2 space-y-6">
                <section class="bg-white rounded-2xl shadow p-6">
                    @include('profile.partials.update-profile-information-form')
                </section>

                <section class="bg-white rounded-2xl shadow p-6">
                    @include('profile.partials.update-password-form')
                </section>

                <section class="bg-white rounded-2xl shadow p-6">
                    @include('profile.partials.delete-user-form')
                </section>
            </main>
        </div>
    </div>
</x-app-layout>
