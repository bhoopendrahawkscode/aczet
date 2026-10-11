@props([
    'columns' => [],
    'data' => [],
    'sortable' => false,
    'pagination' => false,
    'perPage' => 15,
    'striped' => true,
    'hoverable' => true,
    'class' => '',
    'emptyMessage' => 'No data available',
])

@php
    $sortColumn = request()->query('sort');
    $sortDirection = request()->query('direction', 'asc');
    $currentPage = request()->query('page', 1);
@endphp

<div class="overflow-x-auto {{ $class }}" {{ $attributes->merge(['class' => '']) }}>
    <table class="w-full border-collapse" role="grid">
        <thead class="bg-[var(--color-bg-tertiary)]">
            <tr>
                @foreach($columns as $column)
                    <th
                        scope="col"
                        class="px-4 py-3 text-left text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider whitespace-nowrap {{ $column['align'] ?? '' }}"
                        @if($sortable && isset($column['sortable']) && $column['sortable'])
                            @php
                                $sortUrl = request()->fullUrlWithQuery([
                                    'sort' => $column['key'],
                                    'direction' => ($sortColumn === $column['key'] && $sortDirection === 'asc') ? 'desc' : 'asc',
                                    'page' => null,
                                ]);
                            @endphp
                            <a href="{{ $sortUrl }}" class="flex items-center gap-1 hover:text-[var(--color-text-primary)]">
                                {{ $column['label'] }}
                                @if($sortColumn === $column['key'])
                                    <x-ui.icon :name="$sortDirection === 'asc' ? 'chevron-up' : 'chevron-down'" class="h-4 w-4" />
                                @else
                                    <x-ui.icon name="chevron-up-down" class="h-4 w-4 opacity-50" />
                                @endif
                            </a>
                        @else
                            {{ $column['label'] }}
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-[var(--color-border-light)]">
            @if(count($data) > 0)
                @foreach($data as $rowIndex => $row)
                    <tr
                        class="{{ $striped ? ($rowIndex % 2 === 0 ? 'bg-white' : 'bg-[var(--color-bg-primary)]') : 'bg-white' }} {{ $hoverable ? 'hover:bg-[var(--color-bg-tertiary)]' : '' }} transition-colors"
                    >
                        @foreach($columns as $column)
                            <td class="px-4 py-3 text-sm text-[var(--color-text-primary)] whitespace-nowrap {{ $column['align'] ?? '' }}">
                                @if(isset($column['render']))
                                    {{ $column['render']($row, $rowIndex) }}
                                @elseif(isset($column['key']))
                                    {{ data_get($row, $column['key']) }}
                                @else
                                    &nbsp;
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-4 py-12 text-center text-[var(--color-text-muted)]">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    @if($pagination && $data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-[var(--color-text-muted)]">
                Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} results
            </div>
            <div class="flex items-center gap-2">
                @if($data->onFirstPage())
                    <button disabled class="px-3 py-1.5 text-sm font-medium text-[var(--color-text-muted)] bg-[var(--color-bg-tertiary)] border border-[var(--color-border-light)] rounded-lg cursor-not-allowed">
                        <x-ui.icon name="chevron-left" class="h-4 w-4" />
                    </button>
                @else
                    <a href="{{ $data->previousPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-[var(--color-text-primary)] bg-white border border-[var(--color-border-light)] rounded-lg hover:bg-[var(--color-bg-tertiary)] transition-colors focus-ring">
                        <x-ui.icon name="chevron-left" class="h-4 w-4" />
                    </a>
                @endif

                <span class="px-3 py-1.5 text-sm font-medium text-[var(--color-text-primary)]">
                    Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
                </span>

                @if($data->hasMorePages())
                    <a href="{{ $data->nextPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-[var(--color-text-primary)] bg-white border border-[var(--color-border-light)] rounded-lg hover:bg-[var(--color-bg-tertiary)] transition-colors focus-ring">
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </a>
                @else
                    <button disabled class="px-3 py-1.5 text-sm font-medium text-[var(--color-text-muted)] bg-[var(--color-bg-tertiary)] border border-[var(--color-border-light)] rounded-lg cursor-not-allowed">
                        <x-ui.icon name="chevron-right" class="h-4 w-4" />
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>