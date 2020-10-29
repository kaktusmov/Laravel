<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('app.Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl">
                        {{ __('app.Welcome') }}
                    </div>
                    <div class="mt-6 text-500">
                        {{ __('app.ref_link') }}: <br/>{{ url('register?ref=').Auth::user()->ref_code}}
                    </div>
                    <div class="mt-6 text-500">
                        @if (count($referrals))
                            {{ __('app.referrals_list') }}:
                            <ul>
                                @foreach($referrals as $referral)
                                    <li style="padding-left: {{($referral->depth-1)*25}}px">{{ $referral->userModel->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            {{ __('app.no_referrals') }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
