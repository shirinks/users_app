<div class="h-full w-64 bg-gray-800 text-white p-4">
    <h1 class="text-xl font-bold mb-8">Dashboard</h1>
    <ul class="space-y-4">
        @if(auth()->user()->hasRole('System Admin'))
            <li><a href="" class="text-gray-200 hover:text-white">User Accounts</a></li>
        @endif
        @if(auth()->user()->hasRole('System Admin')||auth()->user()->hasRole('HR'))
            <li><a href="" class="text-gray-200 hover:text-white">HR</a></li>
        @endif
        <li><a href="" class="text-gray-200 hover:text-white">Employee Profile</a></li>
        <li><a href="{{ route('logout') }}" class="text-gray-200 hover:text-white">Quit</a></li>
    </ul>
</div>
