@extends('layouts.admin.layout')

@section('content')
    @include('layouts.admin.components.sidebar')

    <div class="p-4 sm:ml-64">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-lg font-semibold leading-6 text-gray-900">Blogs</h1>
                </div>
            </div>
            <div class="flex justify-end">
                <form method="GET" action="{{ route('admin.blogs') }}">
                    @csrf
                    <div class="flex gap-5">
                        <div>
                            <label for="users"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Filter op
                                auteur</label>
                            <select id="users" name="user" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                @foreach ($users as $user)
                                  
                                    @if(isset($currentUser) && $user->id == $currentUser->id)
                                        <option value="{{ $user->id }}" selected>{{ $user->name }}</option>
                                    @else
                                      <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div>
                          <label for="date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Filter op aanmaakdatum</label>
                          <input type="date" name="date" value="{{$currentDate ? $currentDate : ''}}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          {{-- <select id="date" name="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                              <option value="ASC">Oudste eerst</option>
                              <option value="DESC">Nieuwste eerst</option>
                          </select> --}}
                      </div>
                      <div>
                          <button type="submit"
                              class="text-white mt-7 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center">Filteren
                          </button>
                      </div>
                    </div>
                </form>
                {{-- <form id="userForm" method="POST" action="{{ route('admin.blog.filter') }}">
                    @csrf
                    <input type="hidden" id="userId" name="user_id" value="{{ $currentUser ? $currentUser->id : '' }}">

                    <button id="users" data-dropdown-toggle="dropdown"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center"
                        type="button">{{ $currentUser ? $currentUser->name : 'Selecteer auteur' }}
                        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 ">
                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="users">
                            @foreach ($users as $user)
                                <li>
                                    <a onclick="selectUser({{ $user->id }})"
                                        class="block px-4 py-2 hover:bg-gray-100">{{ $user->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </form> --}}

            </div>
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Titel</th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Auteur</th>
                                        <th scope="col"
                                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Geplaatst</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    @foreach ($blogs as $blog)
                                        <tr>
                                            <td
                                                class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                {{ $blog->title }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $blog->user->name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ $blog->created_at }}</td>

                                            <td
                                                class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <a href="{{ route('admin.blog.show', ['id' => $blog->id]) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">Bekijken<span
                                                        class="sr-only">, Lindsay Walton</span></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <!-- More people... -->
                                </tbody>
                            </table>

                        </div>
                        {{ $blogs->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('after_scripts')
    <script>
        function selectUser(userId) {
            document.getElementById('userId').value = userId;
            document.getElementById('userForm').submit();
        }
    </script>
@endsection
