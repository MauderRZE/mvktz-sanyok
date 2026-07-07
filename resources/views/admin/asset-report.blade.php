<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Звіт: Активи (Компоненти)</title>
    @php
        $orientation = $filters['orientation'] ?? 'landscape';
        $columns = $filters['columns'] ?? ['equipment', 'component_type', 'model_sn', 'network', 'location', 'status'];
    @endphp
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 20px;
        }
        h1 {
            font-size: 16px;
            text-align: center;
            margin-bottom: 5px;
        }
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 10px;
        }
        .filters {
            margin-bottom: 20px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 5px;
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
            font-size: 11px;
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

    <h1>Перелік активів МВКТЗ</h1>
    <div class="header-info">Сформовано: {{ $generatedAt }}</div>

    @if(!empty($filters['search']))
        <div class="filters">
            <strong>Пошуковий запит:</strong> {{ $filters['search'] }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                @if(in_array('id', $columns)) <th style="width: 5%">ID</th> @endif
                @if(in_array('equipment', $columns)) <th style="width: 15%">Пристрій (Інв. №)</th> @endif
                @if(in_array('component_type', $columns)) <th style="width: 15%">Тип компонента</th> @endif
                @if(in_array('model_sn', $columns)) <th style="width: 20%">Модель / S/N</th> @endif
                @if(in_array('network', $columns)) <th style="width: 15%">Мережа</th> @endif
                @if(in_array('location', $columns)) <th style="width: 15%">Місце / Власник</th> @endif
                @if(in_array('status', $columns)) <th style="width: 10%">Статус</th> @endif
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $c)
                <tr>
                    @if(in_array('id', $columns)) <td>#{{ $c->id }}</td> @endif
                    
                    @if(in_array('equipment', $columns)) 
                        <td>
                            @if($c->equipment)
                                {{ $c->equipment->inv_number ?? '-' }}<br>
                                <small>{{ $c->equipment->account_name ?? '' }}</small>
                            @else
                                -
                            @endif
                        </td> 
                    @endif

                    @if(in_array('component_type', $columns)) 
                        <td>{{ $c->componentType->component_name ?? '-' }}</td> 
                    @endif

                    @if(in_array('model_sn', $columns)) 
                        <td>
                            @if($c->model)
                                {{ $c->model->brand->brandtz_name ?? '' }} {{ $c->model->model_name }}
                            @else
                                -
                            @endif
                            @if($c->serial_number)
                                <br><small>SN: {{ $c->serial_number }}</small>
                            @endif
                        </td> 
                    @endif

                    @if(in_array('network', $columns)) 
                        <td>
                            @if(!empty($c->ip_address) || !empty($c->mac_address) || !empty($c->hostname))
                                @if(!empty($c->hostname)) {{ $c->hostname }}<br> @endif
                                <strong>{{ $c->ip_address }}</strong>
                                @if(!empty($c->mac_address)) <br><small>{{ $c->mac_address }}</small> @endif
                            @else
                                -
                            @endif
                        </td> 
                    @endif

                    @if(in_array('location', $columns)) 
                        <td>
                            @if($c->location)
                                Каб. {{ $c->location->room_number }}<br>
                            @endif
                            @if($c->holder)
                                @php
                                    $empName = $c->holder->employee ? $c->holder->employee->last_name . ' ' . mb_substr($c->holder->employee->first_name, 0, 1) . '.' : null;
                                    $orgName = $c->holder->organization ? $c->holder->organization->org_name : null;
                                    $holderName = $empName ?? $orgName ?? 'Не вказано';
                                @endphp
                                <small>{{ $holderName }}</small>
                            @else
                                <small>На складі</small>
                            @endif
                        </td> 
                    @endif

                    @if(in_array('status', $columns)) 
                        <td>{{ $c->status }}</td> 
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" style="text-align: center; padding: 20px;">Немає даних для відображення за вибраними фільтрами</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Всього: {{ $assets->count() }} записів
    </div>

</body>
</html>
