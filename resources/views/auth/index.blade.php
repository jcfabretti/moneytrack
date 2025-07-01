@extends('adminlte::page')
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-light">
        {{ __('Users') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Created At</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="border-t">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $user->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $user->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="px-4 py-2 text-sm">
                                <a href=""
                                    class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Edit
                                </a>
                                <form action="" method="POST"
                                    class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@Section