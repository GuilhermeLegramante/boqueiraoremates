 @extends('reports.page')

 @section('header')
     {{-- @include('reports.header-bids') --}}
 @endsection

 @section('content')
     {{-- <style type="text/css">
         /* Mantendo o seu padrão de estilos para consistência */
         .table {
             border-collapse: collapse;
             border-spacing: 0;
             width: 100%;
         }

         .table tr:nth-child(even) {
             background-color: #f0f0f0;
         }

         .table td {
             border: 1px solid #ccc;
             font-size: 9px;
             padding: 4px;
         }

         .table th {
             font-size: 9px;
             padding: 4px;
         }

         .table .table-header {
             background-color: #333333;
             color: #ffffff;
             font-weight: bold;
             text-align: center;
         }

         .background {
             background-image: url('https://sistema.boqueiraoremates.com/img/logo.png');
             background-position: center;
             height: 100%;
             background-repeat: no-repeat;
             opacity: 0.05;
         }

         .text-white {
             color: #ffffff;
         }

         .text-center {
             text-align: center;
         }

         .upper {
             text-transform: uppercase;
         }
     </style> --}}

     <div class="background">
         <br><br><br>

         <table class="table" style="table-layout: fixed;">
             <thead>
                 <tr>
                     <th class="table-header text-white" style="width: 7%;">Código</th>
                     <th class="table-header text-white" style="width: 12%;">Data/Hora</th>
                     <th class="table-header text-white">Nome Cliente</th>
                     <th class="table-header text-white" style="width: 8%;">Lote</th>
                     <th class="table-header text-white" style="width: 15%;">Valor do Lance</th>
                 </tr>
             </thead>
             <tbody>
                 @php $totalGeral = 0; @endphp
                 @foreach ($bids as $bid)
                     <tr>
                         <td class="text-center">{{ str_pad($bid->id, 5, '0', STR_PAD_LEFT) }}</td>

                         <td class="text-center">{{ $bid->created_at->format('d/m/Y H:i') }}</td>

                         <td class="upper">{{ $bid->user->name }}</td>

                         <td class="text-center">{{ $bid->lot_number }}</td>

                         @include('reports.partials.td-money', ['money_value' => $bid->amount])
                     </tr>
                     @php $totalGeral += $bid->amount; @endphp
                 @endforeach
             </tbody>
         </table>

         <br>

         <table class="table" style="table-layout: fixed; width: 30%; margin-left: auto;">
             <tr>
                 <td class="table-header text-white">Qtd. Lances</td>
                 <td class="text-center"><strong>{{ $bids->count() }}</strong></td>
             </tr>
             <tr>
                 <td class="table-header text-white">Total</td>
                 @include('reports.partials.td-money', ['money_value' => $totalGeral])
             </tr>
         </table>
     </div>
 @endsection

 @section('footer')
     @include('reports.footer')
 @endsection
