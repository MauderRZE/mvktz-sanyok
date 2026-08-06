<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Звіт: Обладнання</title>
    @php
        $orientation = $filters['orientation'] ?? 'landscape';
        $columns = $filters['columns'] ?? ['num', 'id', 'inv', 'name', 'components', 'location', 'status', 'price'];
    @endphp
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }
        h1 {
            font-size: 18px;
            text-align: center;
            margin-bottom: 5px;
        }
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 11px;
        }
        .filters {
            margin-bottom: 20px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .print-btn {
            display: block;
            width: 150px;
            margin: 0 auto 20px;
            padding: 10px;
            text-align: center;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-size: 14px;
        }
        .footer {
            text-align: right;
            font-size: 12px;
            font-weight: bold;
        }
        
        @media print {
            body {
                margin: 0;
            }
            .print-btn {
                display: none;
            }
            @page {
                size: A4 {{ $orientation }};
                margin: 1cm;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨 Друкувати</button>

    <h1>Перелік обладнання МВКТЗ</h1>
    <div class="header-info">Сформовано: {{ $generatedAt }}</div>

    @if(!empty($filters['search']) || !empty($filters['filterCategory']) || !empty($filters['filterStatus']) || !empty($filters['filterLocation']) || !empty($filters['filterEmployee']) || !empty($filters['filterDepartment']) || !empty($filters['filterOrganization']) || !empty($filters['filterBrand']) || !empty($filters['filterType']))
        <div class="filters" style="background: #f8f9fa; padding: 8px 12px; border: 1px solid #e9ecef; border-radius: 4px; margin-bottom: 15px;">
            <strong>Активні фільтри:</strong>
            @if(!empty($filters['search']))
                <span style="display: inline-block; margin-right: 10px;">🔍 {{ $filters['search'] }}</span>
            @endif
            @if(!empty($filters['filterCategory']) && (in_array('null', (array)$filters['filterCategory']) || in_array(null, (array)$filters['filterCategory'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Категорія: [Не вказано / Null]</span>
            @endif
            @if(!empty($filters['filterStatus']) && (in_array('null', (array)$filters['filterStatus']) || in_array(null, (array)$filters['filterStatus'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Статус: [Не вказано / Null]</span>
            @endif
            @if(!empty($filters['filterLocation']) && (in_array('null', (array)$filters['filterLocation']) || in_array(null, (array)$filters['filterLocation'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Локація: [Не вказано / Null]</span>
            @endif
            @if(!empty($filters['filterEmployee']) && (in_array('null', (array)$filters['filterEmployee']) || in_array(null, (array)$filters['filterEmployee'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Співробітник: [Не вказано / Null]</span>
            @endif
            @if(!empty($filters['filterDepartment']) && (in_array('null', (array)$filters['filterDepartment']) || in_array(null, (array)$filters['filterDepartment'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Відділ: [Не вказано / Null]</span>
            @endif
            @if(!empty($filters['filterOrganization']) && (in_array('null', (array)$filters['filterOrganization']) || in_array(null, (array)$filters['filterOrganization'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Організація: [Не вказано / Null]</span>
            @endif
            @if(!empty($filters['filterBrand']) && (in_array('null', (array)$filters['filterBrand']) || in_array(null, (array)$filters['filterBrand'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Бренд: [Не вказано / Null]</span>
            @endif
            @if(!empty($filters['filterType']) && (in_array('null', (array)$filters['filterType']) || in_array(null, (array)$filters['filterType'])))
                <span style="display: inline-block; margin-right: 10px; color: #d97706;">• Модель: [Не вказано / Null]</span>
            @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                @if(in_array('num', $columns)) <th style="width: 4%; text-align: center;">№ з/п</th> @endif
                @if(in_array('id', $columns)) <th style="width: 5%">ID</th> @endif
                @if(in_array('inv', $columns)) <th style="width: 10%">Інв. №</th> @endif
                @if(in_array('name', $columns)) <th style="width: 20%">Назва</th> @endif
                @if(in_array('components', $columns)) <th style="width: 25%">Комплектуючі</th> @endif
                @if(in_array('location', $columns)) <th style="width: 15%">Локація / Відпов.</th> @endif
                @if(in_array('status', $columns)) <th style="width: 10%">Статус</th> @endif
                @if(in_array('price', $columns)) <th style="width: 15%">Ціна (грн)</th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($equipments as $index => $eq)
                <tr>
                    @if(in_array('num', $columns)) <td style="text-align: center; color: #666;">{{ $index + 1 }}</td> @endif
                    @if(in_array('id', $columns)) <td>#{{ $eq->id }}</td> @endif
                    @if(in_array('inv', $columns)) <td>{{ $eq->inv_number }}</td> @endif
                    @if(in_array('name', $columns)) <td>{{ $eq->account_name }}</td> @endif
                    @if(in_array('components', $columns)) 
                    <td>
                        @if($eq->assets->count() > 0)
                            {{ $eq->assets->map(fn($c) => $c->componentType->component_name ?? '')->unique()->implode(', ') }}
                        @else
                            —
                        @endif
                    </td> 
                    @endif
                    @if(in_array('location', $columns)) 
                    <td>
                        @php $latestMove = $eq->movements->sortByDesc('move_date')->first(); @endphp
                        @if($latestMove)
                            Каб. {{ $latestMove->asset->location->room_number ?? '—' }}<br>
                            <small>{{ $latestMove->employee ? ($latestMove->employee->last_name . ' ' . mb_substr($latestMove->employee->first_name, 0, 1) . '.') : '' }}</small>
                        @else
                            —
                        @endif
                    </td> 
                    @endif
                    @if(in_array('status', $columns)) <td>{{ $eq->status }}</td> @endif
                    @if(in_array('price', $columns)) <td>{{ $eq->buy_price ? number_format($eq->buy_price, 2) : '—' }}</td> @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" style="text-align: center; padding: 20px;">Немає даних для відображення за вибраними фільтрами</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Всього: {{ $equipments->count() }} записів
    </div>

</body>
</html>
