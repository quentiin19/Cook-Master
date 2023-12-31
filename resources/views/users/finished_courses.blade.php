<x-layout title="Finished courses" datatables=1>
    <x-admin.listing>
        <!-- head -->
        <thead>
            <tr>
                <th>{{ __('Course') }}</th>
                <th>{{ __('Difficulty') }}</th>
                <th>{{ __('Finished at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach (Auth::user()->finished_courses as $item)
                @if ($item->is_finished)
                    <tr>
                        <td>
                            <a class="font-bold link"
                                href="{{ route('courses.show', $item->course->id) }}">{{ $item->course->name }}</a>
                        </td>
                        <td>{{ $item->course->difficulty }} {{ str_repeat('⭐', $item->course->difficulty) }}</td>
                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </x-admin.listing>
</x-layout>
